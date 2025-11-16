<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with reservation statistics and calendar
     *
     * @return Response
     */
    public function index(): Response
    {
        // Get statistics
        $todayCount = Reservation::today()
            ->count();

        $tomorrowCount = Reservation::tomorrow()
            ->count();

        $thisWeekCount = Reservation::thisWeek()
            ->count();

        // Get calendar data for the current month
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $reservations = Reservation::with(['table', 'creator'])
            ->whereBetween('reservation_date', [$startOfMonth, $endOfMonth])
            ->orderBy('reservation_date')
            ->orderBy('reservation_time')
            ->get();

        // Group reservations by date
        $calendarData = $reservations->groupBy(function ($reservation) {
            return $reservation->reservation_date->format('Y-m-d');
        })->map(function ($dayReservations) {
            return [
                'total' => $dayReservations->count(),
                'confirmed' => $dayReservations->where('status', 'confirmed')->count(),
                'pending' => $dayReservations->where('status', 'pending')->count(),
                'cancelled' => $dayReservations->where('status', 'cancelled')->count(),
                'reservations' => $dayReservations->map(function ($reservation) {
                    return [
                        'id' => $reservation->id,
                        'customer_name' => $reservation->customer_name,
                        'time' => $reservation->reservation_time->format('H:i'),
                        'guests_count' => $reservation->guests_count,
                        'table_number' => $reservation->table?->number,
                        'status' => $reservation->status,
                        'status_label' => $reservation->status_label,
                        'shift' => $reservation->shift,
                        'shift_label' => $reservation->shift_label,
                    ];
                })->values(),
            ];
        });

        return Inertia::render('Dashboard', [
            'stats' => [
                'today' => $todayCount,
                'tomorrow' => $tomorrowCount,
                'thisWeek' => $thisWeekCount,
            ],
            'calendarData' => $calendarData,
            'currentMonth' => Carbon::now()->format('Y-m'),
        ]);
    }
}
