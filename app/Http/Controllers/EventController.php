<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        return view('calendar');
    }

    public function events()
    {
        return Event::all(); // Returns JSON for FullCalendar
    }

    public function store(Request $request)
    {
        $event = Event::create($request->all());
        return response()->json($event);
    }

    /**
     * Display the admin calendar view
     */
    public function adminCalendar()
    {
        return view('admin.calendar');
    }

    /**
     * Get all events for admin (shows all user events)
     */
    public function adminEvents()
    {
        return Event::all(); // Returns all events for admin to see
    }

    /**
     * Store a new event (admin can create events)
     */
    public function adminStore(Request $request)
    {
        $event = Event::create($request->all());
        return response()->json($event);
    }

    /**
     * Display the admin bookings management page
     */
    public function AdminBooking()
    {
        return view('admin.AdminBooking');
    }

   /**
     * Display the admin payment management page
     */
    public function AdminPayment()
    {
        return view('admin.AdminPayment');
    }

     public function AdminReports()
    {
        return view('admin.AdminReports');
    }

     public function AdminInventory()
    {
        return view('admin.AdminInventory');
    }
}
