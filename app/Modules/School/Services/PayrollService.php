<?php

namespace App\Modules\School\Services;

use App\Modules\School\Models\Staff;
use App\Modules\School\Models\Payroll;
use App\Modules\School\Models\PayrollItem;
use App\Modules\School\Models\PayrollSetting;
use App\Modules\School\Models\PayrollTaxRule;
use App\Modules\School\Models\TeacherSubjectRate;
use App\Modules\School\Models\Journal;
use App\Modules\School\Services\TreasuryService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    protected TreasuryService $treasuryService;

    public function __construct(TreasuryService $treasuryService)
    {
        $this->treasuryService = $treasuryService;
    }

    /**
     * Calcule et génère le bulletin complet avec cotisations/impôts dynamiques et écritures comptables
     */
    public function generatePayroll(Staff $staff, string $startDate, string $endDate, int $userId, array $extraInputs = []): Payroll
    {
        return DB::transaction(function () use ($staff, $startDate, $endDate, $userId, $extraInputs) {
            $schoolId = $staff->school_id ?? 1;

            $setting = PayrollSetting::firstOrCreate(
                ['school_id' => $schoolId],
                [
                    'country_id'             => $staff->school->country_id ?? null,
                    'apply_taxes_to_vacants' => false,
                ]
            );

            $contract = $staff->contracts()
                ->where('status', 'active')
                ->where('start_date', '<=', $endDate)
                ->where(function ($q) use ($endDate) {
                    $q->whereNull('end_date')->orWhere('end_date', '>=', $endDate);
                })
                ->first();

            if (!$contract) {
                throw new \Exception("Aucun contrat actif trouvé pour cet employé.");
            }

            $isVacant = ($contract->pay_type === 'hourly');
            $totalHours = 0;
            $baseSalary = 0;
            $grossBrutImposable = 0;

            // --- A. SALAIRE DE BASE / VACATIONS ---
            if ($isVacant) {
                $rates = TeacherSubjectRate::where('staff_id', $staff->id)->get();
                foreach ($rates as $rate) {
                    $hoursForSubject = (float) $rate->total_executed_hours;
                    $subjectGross = ((float) $rate->executed_volume_cm * (float) $rate->rate_cm)
                        + ((float) $rate->executed_volume_td * (float) $rate->rate_td)
                        + ((float) $rate->executed_volume_tp * (float) $rate->rate_tp)
                        + ((float) $rate->executed_volume_examen * (float) $rate->rate_examen);

                    if ($subjectGross == 0 && $hoursForSubject > 0) {
                        $hourlyRate = max((float)$rate->rate_cm, (float)$rate->rate_td, (float)$rate->rate_tp, (float)$rate->rate_examen);
                        if ($hourlyRate == 0) $hourlyRate = (float) ($contract->base_salary_or_rate ?? 10000);
                        $subjectGross = $hoursForSubject * $hourlyRate;
                    }

                    $totalHours += $hoursForSubject;
                    $grossBrutImposable += $subjectGross;
                }

                if ($totalHours <= 0 || $grossBrutImposable <= 0) {
                    throw new \Exception("Aucune prestation exécutée enregistrée sur la période.");
                }
            } else {
                $baseSalary = (float) ($contract->base_salary_or_rate ?? $staff->base_salary ?? 0);
                $grossBrutImposable = $baseSalary;
            }

            // --- B. INITIALISATION DU BULLETIN ---
            $payrollPrefix = $isVacant ? 'PAY-VAC-' : 'PAY-PERM-';
            $payroll = Payroll::updateOrCreate(
                [
                    'staff_id'     => $staff->id,
                    'period_start' => $startDate,
                    'period_end'   => $endDate,
                ],
                [
                    'school_id'         => $schoolId,
                    'staff_contract_id' => $contract->id,
                    'payroll_number'    => $payrollPrefix . strtoupper(Str::random(6)),
                    'base_salary'       => $baseSalary,
                    'total_hours'       => $totalHours,
                    'gross_amount'      => $grossBrutImposable,
                    'status'            => 'draft',
                    'processed_by'      => $userId,
                ]
            );

            $payroll->items()->delete();

            // Ligne de Salaire de base / Honoraires
            PayrollItem::create([
                'payroll_id'    => $payroll->id,
                'label'         => $isVacant ? "Honoraires / Heures exécutées ({$totalHours}h)" : "Salaire de base",
                'type'          => 'gain',
                'base_amount'   => $grossBrutImposable,
                'rate_or_value' => 1.0,
                'amount'        => $grossBrutImposable,
            ]);

            $totalGainsNonImposables = 0;
            $totalDeductions = 0;

            // --- C. INDEMNITÉS ET PRIMES FIXES / DYNAMIQUES ---
            $transport = (float) ($extraInputs['transport_allowance'] ?? 0);
            if ($transport > 0) {
                PayrollItem::create([
                    'payroll_id'    => $payroll->id,
                    'label'         => 'Indemnité de Transport (Exonérée)',
                    'type'          => 'gain_non_taxable',
                    'base_amount'   => $transport,
                    'rate_or_value' => 1.0,
                    'amount'        => $transport,
                ]);
                $totalGainsNonImposables += $transport;
            }

            $extraBonus = (float) ($extraInputs['extra_bonuses'] ?? 0);
            if ($extraBonus > 0) {
                PayrollItem::create([
                    'payroll_id'    => $payroll->id,
                    'label'         => 'Primes et Gratifications Imposables',
                    'type'          => 'gain',
                    'base_amount'   => $extraBonus,
                    'rate_or_value' => 1.0,
                    'amount'        => $extraBonus,
                ]);
                $grossBrutImposable += $extraBonus;
            }

            // --- D. CHARGES SOCIALES, FISCALES ET MUTUELLES DYNAMIQUES ---
            $cnpsTotalAmount = 0;
            $itsTotalAmount = 0;

            // Récupération dynamique des règles actives créées par l'administrateur
            $taxRules = PayrollTaxRule::where('school_id', $schoolId)
                ->where('is_active', true)
                ->when($isVacant, function ($query) {
                    $query->where('applies_to_vacants', true);
                })
                ->get();

            foreach ($taxRules as $rule) {
                $calculationBase = $grossBrutImposable;

                // Application du plafonnement si spécifié
                if ($rule->ceiling && $rule->ceiling > 0) {
                    $calculationBase = min($calculationBase, (float) $rule->ceiling);
                }

                // Vérification du plancher éventuel
                if ($rule->min_base && $calculationBase < (float) $rule->min_base) {
                    continue;
                }

                // Calcul selon le type (pourcentage ou montant fixe)
                $deductionAmount = 0;
                $rateOrValue = 0;

                if ($rule->calculation_type === 'percentage') {
                    $rateOrValue = (float) $rule->rate * 100;
                    $deductionAmount = round($calculationBase * (float) $rule->rate, 2);
                } else {
                    $rateOrValue = (float) $rule->fixed_amount;
                    $deductionAmount = (float) $rule->fixed_amount;
                }

                if ($deductionAmount > 0) {
                    PayrollItem::create([
                        'payroll_id'    => $payroll->id,
                        'label'         => $rule->name,
                        'type'          => 'deduction',
                        'base_amount'   => $calculationBase,
                        'rate_or_value' => $rateOrValue,
                        'amount'        => $deductionAmount,
                    ]);

                    $totalDeductions += $deductionAmount;

                    // Ventilation pour les colonnes récapitulatives de la table payrolls
                    if (Str::contains(strtoupper($rule->code), ['CNPS', 'SOCIAL', 'RETRAITE'])) {
                        $cnpsTotalAmount += $deductionAmount;
                    } else {
                        $itsTotalAmount += $deductionAmount;
                    }
                }
            }

            // --- E. DÉDUCTIONS D'ACOMPTES ET AVANCES ---
            $advances = (float) ($extraInputs['advance_deduction'] ?? 0);
            if ($advances <= 0 && method_exists($staff, 'advances')) {
                $advances = (float) $staff->advances()->whereBetween('paid_at', [$startDate, $endDate])->sum('amount');
            }

            if ($advances > 0) {
                PayrollItem::create([
                    'payroll_id'    => $payroll->id,
                    'label'         => 'Acompte / Avance sur salaire',
                    'type'          => 'deduction',
                    'base_amount'   => $advances,
                    'rate_or_value' => 100.0,
                    'amount'        => $advances,
                ]);
                $totalDeductions += $advances;
            }

            // --- F. CALCUL DU NET À PAYER ET MISE À JOUR ---
            $totalBrutGénéral = $grossBrutImposable + $totalGainsNonImposables;
            $netAmount = max(0, $totalBrutGénéral - $totalDeductions);

            $payroll->update([
                'gross_amount'     => $grossBrutImposable,
                'cnps_amount'      => $cnpsTotalAmount,
                'its_amount'       => $itsTotalAmount,
                'bonuses_amount'   => $totalGainsNonImposables,
                'penalties_amount' => $totalDeductions,
                'net_amount'       => $netAmount,
            ]);

            return $payroll;
        });
    }

    /**
     * Génère l'écriture comptable liée à la paie dans le journal trésorerie/OD
     */
    public function generatePayrollAccountingEntry(Payroll $payroll): void
    {
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
            'journal_id'        => $journal->id,
            'debit_account_id'  => $expenseAccount,
            'credit_account_id' => $personnelAccount,
            'amount'            => $totalGross,
            'date'              => $payroll->period_end,
            'label'             => $label,
            'reference'         => $payroll->payroll_number,
            'sourceable_type'   => Payroll::class,
            'sourceable_id'     => $payroll->id,
        ]);

        if ($socialDeductions > 0) {
            $this->treasuryService->recordReceipt([
                'journal_id'        => $journal->id,
                'debit_account_id'  => $personnelAccount,
                'credit_account_id' => $socialTaxAccount,
                'amount'            => $socialDeductions,
                'date'              => $payroll->period_end,
                'label'             => 'Retenue CNPS / Sociales - ' . $payroll->payroll_number,
                'reference'         => $payroll->payroll_number,
                'sourceable_type'   => Payroll::class,
                'sourceable_id'     => $payroll->id,
            ]);
        }

        if ($taxDeductions > 0) {
            $this->treasuryService->recordReceipt([
                'journal_id'        => $journal->id,
                'debit_account_id'  => $personnelAccount,
                'credit_account_id' => $taxAccount,
                'amount'            => $taxDeductions,
                'date'              => $payroll->period_end,
                'label'             => 'Retenue Impôts (ITS/IGR/Mutuelles) - ' . $payroll->payroll_number,
                'reference'         => $payroll->payroll_number,
                'sourceable_type'   => Payroll::class,
                'sourceable_id'     => $payroll->id,
            ]);
        }
    }

    private function getPayrollExpenseAccount(Payroll $payroll): int
    {
        return ($payroll->contract && $payroll->contract->pay_type === 'monthly') ? 6611 : 6620;
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
}