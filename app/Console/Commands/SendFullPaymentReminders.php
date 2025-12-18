<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Notification;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SendFullPaymentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:send-full-payment-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send full payment reminders to users 1-2 weeks before their event date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for bookings that need full payment reminders...');

        // Get bookings with partial_payment payments that are 1-2 weeks before event date
        $oneWeekFromNow = Carbon::now()->addWeek();
        $twoWeeksFromNow = Carbon::now()->addWeeks(2);

        $bookings = Booking::whereHas('payments', function($query) {
                $query->where('status', 'partial_payment');
            })
            ->whereBetween('event_date', [$oneWeekFromNow->format('Y-m-d'), $twoWeeksFromNow->format('Y-m-d')])
            ->with(['user', 'payments'])
            ->get();

        $reminderCount = 0;

        foreach ($bookings as $booking) {
            // Check if reminder was already sent (to avoid duplicates)
            $existingReminder = Notification::where('user_id', $booking->user_id)
                ->where('notifiable_type', Booking::class)
                ->where('notifiable_id', $booking->booking_id)
                ->where('type', 'full_payment_reminder')
                ->whereDate('created_at', Carbon::today())
                ->first();

            if (!$existingReminder) {
                $daysUntilEvent = Carbon::now()->diffInDays($booking->event_date);
                $totalPaid = $booking->payments()->whereIn('status', ['paid', 'partial_payment'])->sum('amount');
                $remainingAmount = $booking->total_amount - $totalPaid;

                // Only send reminder if there's still a remaining balance
                if ($remainingAmount > 0) {
                    Notification::create([
                        'user_id' => $booking->user_id,
                        'type' => 'full_payment_reminder',
                        'notifiable_type' => Booking::class,
                        'notifiable_id' => $booking->booking_id,
                        'message' => "Reminder: Your {$booking->event_type} event on {$booking->event_date->format('F d, Y')} is in {$daysUntilEvent} day(s). Please complete your full payment of ₱" . number_format($remainingAmount, 2) . ".",
                        'read' => false,
                        'data' => [
                            'booking_id' => $booking->booking_id,
                            'remaining_amount' => $remainingAmount,
                            'event_date' => $booking->event_date->format('Y-m-d'),
                        ],
                    ]);

                    $reminderCount++;
                    $this->info("Sent reminder to {$booking->user->name} for booking #{$booking->booking_id}");
                }
            }
        }

        $this->info("Sent {$reminderCount} full payment reminder(s).");
        return 0;
    }
}
