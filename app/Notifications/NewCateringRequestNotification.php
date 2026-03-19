<?php

namespace App\Notifications;

use App\Models\CateringRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewCateringRequestNotification extends Notification
{
    use Queueable;

    public function __construct(public CateringRequest $catering) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'catering_id' => $this->catering->id,
            'name'        => $this->catering->name,
            'event_type'  => $this->catering->event_type_label,
            'event_date'  => $this->catering->event_date?->format('d/m/Y'),
            'guests'      => $this->catering->guests,
            'message'     => 'Nouvelle demande traiteur de ' . $this->catering->name,
        ];
    }
}
