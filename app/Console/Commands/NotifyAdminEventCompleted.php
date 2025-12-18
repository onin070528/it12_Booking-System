<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;

class NotifyAdminEventCompleted extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:notify-admin-event-completed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify admins about completed events that can be marked as successful';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for completed events that need admin notification...');

        // Get bookings where event date has passed and status is approved or in_design
        $today = Carbon::today();
        
        $bookings = Booking::whereIn('status', ['approved', 'in_design'])
            ->whereDate('event_date', '<=', $today)
            ->where('status', '!=', 'completed')
            ->with('user')
            ->get();

        $admins = User::where('role', 'admin')->get();
        $notificationCount = 0;

        foreach ($bookings as $booking) {
            // Check if notification was already sent today (to avoid duplicates)
            $existingNotification = Notification::whereIn('user_id', $admins->pluck('id'))
                ->where('notifiable_type', Booking::class)
                ->where('notifiable_id', $booking->booking_id)
                ->where('type', 'event_completed_ready')
                ->whereDate('created_at', $today)
                ->first();

            if (!$existingNotification) {
                // Notify all admins
                foreach ($admins as $admin) {
                    Notification::create([
                        'user_id' => $admin->user_id,
                        'type' => 'event_completed_ready',
                        'notifiable_type' => Booking::class,
                        'notifiable_id' => $booking->booking_id,
                        'message' => "Event for booking #{$booking->booking_id} ({$booking->event_type}) by {$booking->user->name} on {$booking->event_date->format('F d, Y')} has passed. You can now mark it as successful.",
                        'read' => false,
                        'data' => [
                            'booking_id' => $booking->booking_id,
                            'customer_name' => $booking->user->name,
                            'event_type' => $booking->event_type,
                            'event_date' => $booking->event_date->format('Y-m-d'),
                        ],
                    ]);
                }
                $notificationCount++;
                $this->info("Sent notification for booking #{$booking->booking_id} - {$booking->event_type} event on {$booking->event_date->format('F d, Y')}");
            }
        }

        $this->info("Sent {$notificationCount} notification(s) to admins about completed events.");
        return 0;
    }
}
