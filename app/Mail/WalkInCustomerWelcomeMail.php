<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WalkInCustomerWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $booking;
    public $temporaryPassword;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Booking $booking, string $temporaryPassword)
    {
        $this->user = $user;
        $this->booking = $booking;
        $this->temporaryPassword = $temporaryPassword;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome! Your Account Has Been Created - Booking Management System',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.walk-in-welcome',
            with: [
                'userName' => $this->user->name,
                'userEmail' => $this->user->email,
                'temporaryPassword' => $this->temporaryPassword,
                'eventType' => ucfirst($this->booking->event_type),
                'eventDate' => $this->booking->event_date->format('F d, Y'),
                'eventTime' => date('g:i A', strtotime($this->booking->event_time)),
                'bookingId' => $this->booking->id,
                'loginUrl' => route('login'),
            ],
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
}
