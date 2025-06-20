<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderProcessedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Order $order;
    public string $customerName;
    public string $customerEmail;

    /**
     * Create a new message instance.
     *
     * @param Order $order
     * @param string $customerName
     * @param string $customerEmail
     * @return void
     */
    public function __construct(Order $order, string $customerName, string $customerEmail)
    {
        $this->order = $order;
        $this->customerName = $customerName;
        $this->customerEmail = $customerEmail;
    }

    /**
     * Get the message envelope.
     *
     * @return Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Order Has Been Processed - #' . $this->order->order_number,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return Content
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.processed',
            with: [
                // Public properties $order, $customerName, $customerEmail are automatically available to the view.
                // The HTML view template will construct any necessary URLs from the $order object.
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
