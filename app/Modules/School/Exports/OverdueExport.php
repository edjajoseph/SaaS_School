<?php

namespace App\Modules\School\Exports;

use App\Modules\School\Models\StudentFeeAccount;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OverdueExport implements FromCollection, WithHeadings, WithMapping
{
    protected $classId;

    public function __construct($classId = null)
    {
        $this->classId = $classId;
    }

    public function collection()
    {
        return StudentFeeAccount::with(['registration.student', 'registration.schoolClass'])
            ->where('balance', '>', 0)
            ->when($this->classId, function ($q) {
                $q->whereHas('registration', fn($sub) => $sub->where('school_class_id', $this->classId));
            })
            ->get();
    }

    public function headings(): array
    {
        return ['Matricule', 'Nom', 'Prénom', 'Classe', 'Total Dû', 'Total Payé', 'Reste à Payer (Créance)', 'Statut'];
    }

    public function map($account): array
    {
        return [
            $account->registration->student->matricule ?? 'N/A',
            $account->registration->student->last_name ?? '',
            $account->registration->student->first_name ?? '',
            $account->registration->schoolClass->name ?? 'N/A',
            $account->total_due,
            $account->total_paid,
            $account->balance,
            $account->status,
        ];
    }
}