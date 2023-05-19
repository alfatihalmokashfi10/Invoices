<?php

namespace App\Notifications;

use auth;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use App\Http\Controllers\InvoiceController;
class add_inoice_db extends Notification
{
    use Queueable;
private $invoice;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( $invoice)
    {
        $this->invoice=$invoice;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'invoice_id' => $this->invoice->id,
            'title' => 'تم اضافة فاتوره جديده بواسطه :',
            'user'=>auth()->user()->name
        ];
    }
}
