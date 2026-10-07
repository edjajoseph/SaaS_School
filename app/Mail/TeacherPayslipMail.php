<?php

namespace App\Mail;

use App\Modules\School\Models\Staff;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TeacherPayslipMail extends Mailable
{
    use Queueable, SerializesModels;

    public Staff $staff;
    public array $payrollData;
    public string $startDate;
    public string $endDate;

    public function __construct(Staff $staff, array $payrollData, string $startDate, string $endDate)
    {
        $this->staff = $staff;
        $this->payrollData = $payrollData;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function build()
    {
        // Génération du PDF en mémoire
        $pdf = Pdf::loadView('School::staff_attendance.exports.teacher_payslip_pdf', [
            'staff'       => $this->staff,
            'details'     => $this->payrollData['details'],
            'totalHours'  => $this->payrollData['total_hours'],
            'totalLate'   => $this->payrollData['total_late_min'],
            'grossTotal'  => $this->payrollData['gross_total'],
            'penalties'   => $this->payrollData['penalties_total'],
            'netTotal'    => $this->payrollData['net_total'],
            'startDate'   => $this->startDate,
            'endDate'     => $this->endDate,
        ])->setPaper('a4', 'portrait');

        $nomFichier = 'fiche_de_paie_' . $this->startDate . '_au_' . $this->endDate . '.pdf';

        return $this->subject('Votre fiche de paie du ' . date('d/m/Y', strtotime($this->startDate)) . ' au ' . date('d/m/Y', strtotime($this->endDate)))
                    ->view('School::staff_attendance.emails.teacher_payslip')
                    ->attachData($pdf->output(), $nomFichier, [
                        'mime' => 'application/pdf',
                    ]);
    }
}