<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_name',
        'customer_phone',
        'customer_email',
        'reservation_date',
        'reservation_time',
        'guests_count',
        'table_id',
        'shift',
        'status',
        'notes',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'reservation_date' => 'date',
            'reservation_time' => 'datetime:H:i',
            'guests_count' => 'integer',
        ];
    }

    /**
     * Get the table assigned to this reservation
     */
    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    /**
     * Get the user who created this reservation
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope for filtering by date
     */
    public function scopeForDate(Builder $query, $date): Builder
    {
        return $query->where('reservation_date', $date);
    }

    /**
     * Scope for filtering by date range
     */
    public function scopeDateRange(Builder $query, $startDate, $endDate): Builder
    {
        return $query->whereBetween('reservation_date', [$startDate, $endDate]);
    }

    /**
     * Scope for filtering by shift
     */
    public function scopeByShift(Builder $query, $shift): Builder
    {
        return $query->where('shift', $shift);
    }

    /**
     * Scope for filtering by status
     */
    public function scopeByStatus(Builder $query, $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for search by customer name or phone
     */
    public function scopeSearch(Builder $query, $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('customer_name', 'like', "%{$search}%")
              ->orWhere('customer_phone', 'like', "%{$search}%");
        });
    }

    /**
     * Scope for today's reservations
     */
    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('reservation_date', Carbon::today());
    }

    /**
     * Scope for tomorrow's reservations
     */
    public function scopeTomorrow(Builder $query): Builder
    {
        return $query->whereDate('reservation_date', Carbon::tomorrow());
    }

    /**
     * Scope for current week reservations
     */
    public function scopeThisWeek(Builder $query): Builder
    {
        return $query->whereBetween('reservation_date', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek(),
        ]);
    }

    /**
     * Get formatted date and time
     */
    public function getFormattedDateTimeAttribute(): string
    {
        return $this->reservation_date->format('d/m/Y') . ' alle ' . $this->reservation_time->format('H:i');
    }

    /**
     * Get status badge color class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'confirmed' => 'badge-success',
            'pending' => 'badge-warning',
            'cancelled' => 'badge-error',
            'completed' => 'badge-ghost',
            default => 'badge-ghost',
        };
    }

    /**
     * Get status label in Italian
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'confirmed' => 'Confermata',
            'pending' => 'In attesa',
            'cancelled' => 'Annullata',
            'completed' => 'Completata',
            default => $this->status,
        };
    }

    /**
     * Get shift label in Italian
     */
    public function getShiftLabelAttribute(): string
    {
        return match($this->shift) {
            'lunch' => 'Pranzo',
            'dinner' => 'Cena',
            default => $this->shift,
        };
    }
}
