<?php

namespace App\Mail;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $booking;
    public $notificationType;
    public $notificationMessage;
    public $data;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $notificationType, $message, $data = [], Booking $booking = null)
    {
        $this->user = $user;
        $this->booking = $booking;
        $this->notificationType = $notificationType;
        $this->notificationMessage = $message;
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->getSubject();
        
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-notification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Get subject based on notification type
     */
    private function getSubject(): string
    {
        $subjects = [
            'booking_created' => 'New Booking Submitted',
            'booking_confirmed' => 'Booking Confirmed',
            'booking_ready_for_payment' => 'Payment Required for Your Booking',
            'payment_received' => 'Payment Received',
            'payment_partial_received' => 'Partial Payment Received',
            'payment_full_received' => 'Payment Complete - Booking Approved',
            'payment_submitted' => 'Payment Submitted',
            'payment_failed' => 'Payment Failed',
            'booking_in_design' => 'Your Event is Now in Design Phase',
            'booking_completed' => 'Event Successfully Completed',
            'booking_communication_chosen' => 'Communication Method Selected',
            'booking_rejected' => 'Booking Rejected',
            'booking_cancelled' => 'Booking Cancelled',
        ];

        return $subjects[$this->notificationType] ?? 'Booking Notification';
    }
}
