<?php

namespace App\Modules\School\Notifications;

use App\Modules\School\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveRequestStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public $leaveRequest;

    public function __construct(LeaveRequest $leaveRequest)
    {
        $this->leaveRequest = $leaveRequest;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $statusText = $this->leaveRequest->status === 'approved' ? 'Accordée' : 'Refusée';

        $mail = (new MailMessage)
            ->subject("Mise à jour de votre demande d'autorisation ({$statusText})")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Votre demande d'autorisation d'absence/congé a été traitée.")
            ->line("Détails de la demande :")
            ->line("• École : " . ($this->leaveRequest->school->name ?? 'N/A'))
            ->line("• Type : " . ucfirst(str_replace('_', ' ', $this->leaveRequest->type)))
            ->line("• Période : du {$this->leaveRequest->start_date->format('d/m/Y H:i')} au {$this->leaveRequest->end_date->format('d/m/Y H:i')}")
            ->line("• Statut : {$statusText}");

        if ($this->leaveRequest->status === 'rejected' && $this->leaveRequest->rejection_reason) {
            $mail->line("• Motif du refus : " . $this->leaveRequest->rejection_reason);
        }

        return $mail->action('Consulter mes demandes', url(route('leave-requests.index')))
                    ->line('Merci pour votre collaboration.');
    }

    public function toArray($notifiable): array
    {
        return [
            'leave_request_id' => $this->leaveRequest->id,
            'type'             => $this->leaveRequest->type,
            'status'           => $this->leaveRequest->status,
            'start_date'       => $this->leaveRequest->start_date->toDateTimeString(),
            'end_date'         => $this->leaveRequest->end_date->toDateTimeString(),
            'message'          => "Votre demande d'autorisation du {$this->leaveRequest->start_date->format('d/m/Y')} a été " . ($this->leaveRequest->status === 'approved' ? 'accordée' : 'refusée') . ".",
        ];
    }
}