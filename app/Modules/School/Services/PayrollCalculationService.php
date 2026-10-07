<?php

namespace App\Modules\School\Services;

use App\Modules\School\Models\Staff;
use App\Modules\School\Models\Payroll;
use App\Modules\School\Models\StaffContract;
use App\Modules\School\Models\TeacherSubjectRate;
use App\Modules\School\Models\StaffAttendance;
use App\Modules\School\Models\Journal;
use App\Modules\School\Services\TreasuryService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PayrollCalculationService
{
    protected TreasuryService $treasuryService;

    public function __construct(TreasuryService $treasuryService)
    {
        $this->treasuryService = $treasuryService;
    }
    
    public function generateMonthlyPayrollForStaff(Staff $staff, string $periodStart, string $periodEnd)
    {
        // 1. Récupérer le contrat actif
        $contract = $staff->contracts()
            ->where('status', 'active')
            ->where('start_date', '<=', $periodEnd)
            ->where(function ($q) use ($periodEnd) {--
                $q->whereNull('end_date')->orWhere('end_date', '>=', $periodEnd);
            })
            ->first();

        if (!$contract) {
            throw new \Exception("Aucun contrat actif trouvé pour l'employé : {$staff->id}");
        }

        // 2. Traitement selon le mode de rémunération
        if ($contract->pay_type === 'monthly') {
            return $this->processPermanentPayroll($staff, $contract, $periodStart, $periodEnd);
        } elseif ($contract->pay_type === 'hourly') {
            return $this->processVacatairePayroll($staff, $contract, $periodStart, $periodEnd);
        }
    }

    /**
     * Traitement de la paie pour un personnel permanent (Salaire fixe)
     */
    private function processPermanentPayroll(
        Staff $staff, 
        StaffContract $contract, 
        string $periodStart, 
        string $periodEnd
    ): Payroll {
        $start = Carbon::parse($periodStart);
        $end = Carbon::parse($periodEnd);

        // 🛑 RÈGLE 1 : Empêcher la création de deux bulletins sur le même mois
        $existingPayroll = Payroll::where('staff_id', $staff->id)
            ->where('staff_contract_id', $contract->id)
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('period_start', [$start, $end])
                      ->orWhereBetween('period_end', [$start, $end])
                      ->orWhere(function ($q) use ($start, $end) {
                          $q->where('period_start', '<=', $start)
                            ->where('period_end', '>=', $end);
                      })
                      ->orWhere(function ($q) use ($start) {
                          $q->whereMonth('period_start', $start->month)
                            ->whereYear('period_start', $start->year);
                      });
            })
            ->exists();

        if ($existingPayroll) {
            throw new \Exception("Un bulletin de paie a déjà été généré pour cet employé permanent au cours du même mois.");
        }

        // Salaire de base extrait du contrat
        $baseSalary = $contract->base_salary_or_rate;

        // Primes fixes/conventionnelles liées au staff
        $allowances = method_exists($staff, 'allowances') 
            ? $staff->allowances()->where('is_active', true)->sum('amount') 
            : 0;

        // Brut de base
        $grossSalary = $baseSalary + $allowances;

        // Retenues réglementaires (CNPS / Impôts sur salaire)
        $cnpsSalarial = $this->calculateCNPS($grossSalary);
        $taxableBase = $grossSalary - $cnpsSalarial;
        $incomeTax = $this->calculateITS($taxableBase, $staff->number_of_dependents ?? 0);

        // Déductions diverses (Acomptes ou Prêts internes)
        $advances = method_exists($staff, 'advances') 
            ? $staff->advances()->whereBetween('paid_at', [$periodStart, $periodEnd])->sum('amount') 
            : 0;

        $totalDeductions = $cnpsSalarial + $incomeTax + $advances;
        $netSalary = $grossSalary - $totalDeductions;

        // 1. Création du bulletin de paie
        $payroll = Payroll::create([
            'school_id'         => $contract->school_id,
            'staff_id'          => $staff->id,
            'staff_contract_id' => $contract->id,
            'payroll_number'    => 'PAY-PERM-' . strtoupper(Str::random(6)),
            'period_start'      => $periodStart,
            'period_end'        => $periodEnd,
            'base_salary'       => $baseSalary,
            'gross_amount'      => $grossSalary,
            'bonuses_amount'    => $allowances,
            'penalties_amount'  => $totalDeductions,
            'net_amount'        => $netSalary,
            'cnps_amount'       => $cnpsSalarial,
            'its_amount'        => $incomeTax,
            'status'            => 'draft',
        ]);

        // 2. Génération automatique de l'écriture comptable
        $this->generatePayrollAccountingEntry($payroll);

        return $payroll;
    }

    /**
     * Calcule et génère le bulletin de paie d'un enseignant vacataire
     */
    protected function processVacatairePayroll(
        Staff $staff,
        StaffContract $contract,
        string $periodStart,
        string $periodEnd
    ): Payroll {
        // 1. Récupération des pointages effectués sur la période
        $attendances = StaffAttendance::where('staff_id', $staff->id)
            ->whereBetween('date', [$periodStart, $periodEnd])
            ->get();

        $totalHours = 0;
        $totalGrossVacation = 0;

        // Taux horaire de base défini dans le contrat
        $hourlyRate = $contract->base_salary_or_rate;

        foreach ($attendances as $attendance) {
            $hours = $attendance->effective_hours ?? 0;
            $totalHours += $hours;
            $totalGrossVacation += ($hours * $hourlyRate);
        }

        // 🛑 RÈGLE 2 : Si la prestation est nulle (aucune heure ou montant brut = 0)
        if ($totalHours <= 0 || $totalGrossVacation <= 0) {
            throw new \Exception("Impossible de générer le bulletin : l'enseignant vacataire n'a effectué aucune prestation (solde de base nul) sur cette période.");
        }

        // 2. Traitement des acomptes éventuels
        $advances = method_exists($staff, 'advances') 
            ? $staff->advances()->whereBetween('paid_at', [$periodStart, $periodEnd])->sum('amount') 
            : 0;
            
        $netAmount = $totalGrossVacation - $advances;

        // 3. Création de la fiche de paie
        $payroll = Payroll::create([
            'school_id'         => $contract->school_id,
            'staff_id'          => $staff->id,
            'staff_contract_id' => $contract->id,
            'payroll_number'    => 'PAY-VAC-' . strtoupper(Str::random(6)),
            'period_start'      => $periodStart,
            'period_end'        => $periodEnd,
            'total_hours'       => $totalHours,
            'base_salary'       => 0,
            'gross_amount'      => $totalGrossVacation,
            'bonuses_amount'    => 0,
            'penalties_amount'  => $advances,
            'net_amount'        => $netAmount,
            'status'            => 'draft',
        ]);

        // 4. Génération automatique de l'écriture comptable
        $this->generatePayrollAccountingEntry($payroll);

        return $payroll;
    }

    /**
     * Génère l'écriture comptable liée à la paie
     */
    public function generatePayrollAccountingEntry(Payroll $payroll): void
    {
        DB::transaction(function () use ($payroll) {
            $journal = Journal::where('code', 'OD')
                ->orWhere('code', 'PAY')
                ->first();

            if (!$journal) {
                return;
            }

            $expenseAccount = $this->getPayrollExpenseAccount($payroll); 
            $personnelAccount = $payroll->staff->account_id ?? $this->getDefaultPersonnelAccount(); 
            $socialTaxAccount = $this->getDefaultSocialTaxAccount(); 
            $taxAccount = $this->getDefaultTaxAccount(); 

            $totalGross = $payroll->gross_amount + $payroll->bonuses_amount;
            $socialDeductions = $payroll->cnps_amount ?? 0;
            $taxDeductions = $payroll->its_amount ?? 0;

            $label = sprintf(
                'Paie %s - %s (%s)',
                Carbon::parse($payroll->period_start)->format('m/Y'),
                $payroll->staff->personne->nom_complet ?? 'Employé',
                $payroll->payroll_number
            );

            $this->treasuryService->recordReceipt([
                'journal_id'          => $journal->id,
                'debit_account_id'    => $expenseAccount,
                'credit_account_id'   => $personnelAccount,
                'amount'              => $totalGross,
                'date'                => $payroll->period_end,
                'label'               => $label,
                'reference'           => $payroll->payroll_number,
                'sourceable_type'     => Payroll::class,
                'sourceable_id'       => $payroll->id,
            ]);

            if ($socialDeductions > 0) {
                $this->treasuryService->recordReceipt([
                    'journal_id'          => $journal->id,
                    'debit_account_id'    => $personnelAccount,
                    'credit_account_id'   => $socialTaxAccount,
                    'amount'              => $socialDeductions,
                    'date'                => $payroll->period_end,
                    'label'               => 'Retenue CNPS - ' . $payroll->payroll_number,
                    'reference'           => $payroll->payroll_number,
                    'sourceable_type'     => Payroll::class,
                    'sourceable_id'       => $payroll->id,
                ]);
            }

            if ($taxDeductions > 0) {
                $this->treasuryService->recordReceipt([
                    'journal_id'          => $journal->id,
                    'debit_account_id'    => $personnelAccount,
                    'credit_account_id'   => $taxAccount,
                    'amount'              => $taxDeductions,
                    'date'                => $payroll->period_end,
                    'label'               => 'Retenue Impôts (ITS/IGR) - ' . $payroll->payroll_number,
                    'reference'           => $payroll->payroll_number,
                    'sourceable_type'     => Payroll::class,
                    'sourceable_id'       => $payroll->id,
                ]);
            }
        });
    }

    private function getPayrollExpenseAccount(Payroll $payroll): int
    {
        return $payroll->contract && $payroll->contract->pay_type === 'monthly' 
            ? 6611 
            : 6620;
    }

    private function getDefaultPersonnelAccount(): int
    {
        return 4220;
    }

    private function getDefaultSocialTaxAccount(): int
    {
        return 4310;
    }

    private function getDefaultTaxAccount(): int
    {
        return 4471;
    }

    private function calculateCNPS(float $grossSalary): float
    {
        $cap = 3000000;
        $taxable = min($grossSalary, $cap);
        return $taxable * 0.063;
    }

    private function calculateITS(float $taxableBase, int $dependents): float
    {
        return $taxableBase * 0.015;
    }
}