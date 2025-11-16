<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationRequest;
use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReservationController extends Controller
{
    /**
     * Display a listing of reservations with filters and pagination
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $query = Reservation::query()
            ->with(['table', 'creator']);

        // Apply filters
        if ($request->filled('date')) {
            $query->forDate($request->date);
        }

        if ($request->filled('shift')) {
            $query->byShift($request->shift);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Order by date and time
        $query->orderBy('reservation_date', 'desc')
              ->orderBy('reservation_time', 'desc');

        // Paginate results
        $reservations = $query->paginate(20)->withQueryString();

        // Transform the data to include computed attributes
        $reservations->through(function ($reservation) {
            return [
                'id' => $reservation->id,
                'customer_name' => $reservation->customer_name,
                'customer_phone' => $reservation->customer_phone,
                'customer_email' => $reservation->customer_email,
                'reservation_date' => $reservation->reservation_date->format('Y-m-d'),
                'reservation_date_formatted' => $reservation->reservation_date->format('d/m/Y'),
                'reservation_time' => $reservation->reservation_time->format('H:i'),
                'guests_count' => $reservation->guests_count,
                'table_id' => $reservation->table_id,
                'table_number' => $reservation->table?->number,
                'table_display_name' => $reservation->table?->display_name,
                'shift' => $reservation->shift,
                'shift_label' => $reservation->shift_label,
                'status' => $reservation->status,
                'status_label' => $reservation->status_label,
                'notes' => $reservation->notes,
                'created_by' => $reservation->created_by,
                'creator_name' => $reservation->creator?->name,
                'created_at' => $reservation->created_at->format('d/m/Y H:i'),
            ];
        });

        // Get available tables for the create/edit forms
        $tables = Table::orderBy('number')->get()->map(function ($table) {
            return [
                'id' => $table->id,
                'number' => $table->number,
                'seats' => $table->seats,
                'display_name' => $table->display_name,
            ];
        });

        return Inertia::render('Reservations/Index', [
            'reservations' => $reservations,
            'tables' => $tables,
            'filters' => [
                'date' => $request->date,
                'shift' => $request->shift,
                'status' => $request->status,
                'search' => $request->search,
            ],
        ]);
    }

    /**
     * Store a newly created reservation
     *
     * @param ReservationRequest $request
     * @return RedirectResponse
     */
    public function store(ReservationRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['created_by'] = auth()->id();

        Reservation::create($validated);

        return redirect()->route('reservations.index')
            ->with('success', 'Prenotazione creata con successo.');
    }

    /**
     * Display the specified reservation
     *
     * @param Reservation $reservation
     * @return Response
     */
    public function show(Reservation $reservation): Response
    {
        $reservation->load(['table', 'creator']);

        $reservationData = [
            'id' => $reservation->id,
            'customer_name' => $reservation->customer_name,
            'customer_phone' => $reservation->customer_phone,
            'customer_email' => $reservation->customer_email,
            'reservation_date' => $reservation->reservation_date->format('Y-m-d'),
            'reservation_date_formatted' => $reservation->reservation_date->format('d/m/Y'),
            'reservation_time' => $reservation->reservation_time->format('H:i'),
            'guests_count' => $reservation->guests_count,
            'table_id' => $reservation->table_id,
            'table_number' => $reservation->table?->number,
            'table_display_name' => $reservation->table?->display_name,
            'shift' => $reservation->shift,
            'shift_label' => $reservation->shift_label,
            'status' => $reservation->status,
            'status_label' => $reservation->status_label,
            'notes' => $reservation->notes,
            'created_by' => $reservation->created_by,
            'creator_name' => $reservation->creator?->name,
            'creator_email' => $reservation->creator?->email,
            'created_at' => $reservation->created_at->format('d/m/Y H:i'),
            'updated_at' => $reservation->updated_at->format('d/m/Y H:i'),
        ];

        // Get available tables for editing
        $tables = Table::orderBy('number')->get()->map(function ($table) {
            return [
                'id' => $table->id,
                'number' => $table->number,
                'seats' => $table->seats,
                'display_name' => $table->display_name,
            ];
        });

        return Inertia::render('Reservations/Show', [
            'reservation' => $reservationData,
            'tables' => $tables,
        ]);
    }

    /**
     * Update the specified reservation
     *
     * @param ReservationRequest $request
     * @param Reservation $reservation
     * @return RedirectResponse
     */
    public function update(ReservationRequest $request, Reservation $reservation): RedirectResponse
    {
        $validated = $request->validated();

        $reservation->update($validated);

        return redirect()->route('reservations.show', $reservation)
            ->with('success', 'Prenotazione aggiornata con successo.');
    }

    /**
     * Remove the specified reservation
     *
     * @param Reservation $reservation
     * @return RedirectResponse
     */
    public function destroy(Reservation $reservation): RedirectResponse
    {
        // Soft check: prevent deletion of completed reservations
        if ($reservation->status === 'completed') {
            return redirect()->back()
                ->with('error', 'Non è possibile eliminare una prenotazione completata.');
        }

        $reservation->delete();

        return redirect()->route('reservations.index')
            ->with('success', 'Prenotazione eliminata con successo.');
    }
}
