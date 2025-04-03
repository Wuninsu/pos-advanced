<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// class OrderDeletionRequest extends Notification
// {
//     use Queueable;

//     /**
//      * Create a new notification instance.
//      */
//     public function __construct()
//     {
//         //
//     }

//     /**
//      * Get the notification's delivery channels.
//      *
//      * @return array<int, string>
//      */
//     public function via(object $notifiable): array
//     {
//         return ['mail'];
//     }

//     /**
//      * Get the mail representation of the notification.
//      */
//     public function toMail(object $notifiable): MailMessage
//     {
//         return (new MailMessage)
//                     ->line('The introduction to the notification.')
//                     ->action('Notification Action', url('/'))
//                     ->line('Thank you for using our application!');
//     }

//     /**
//      * Get the array representation of the notification.
//      *
//      * @return array<string, mixed>
//      */
//     public function toArray(object $notifiable): array
//     {
//         return [
//             //
//         ];
//     }
// }

class OrderDeletionRequest extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;
    public $cashier;

    public function __construct($order)
    {
        $this->order = $order;
        $this->cashier = auth('web')->user();
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast']; // Save in database and send as a real-time event
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "Cashier {$this->cashier->name} requested deletion of Order #{$this->order->order_number}.",
            'order_id' => $this->order->id,
            'cashier_id' => $this->cashier->id,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => "Cashier {$this->cashier->name} requested deletion of Order #{$this->order->order_number}.",
            'order_id' => $this->order->id,
            'cashier_id' => $this->cashier->id,
        ]);
    }
}
