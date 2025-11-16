<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'number',
        'seats',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'number' => 'integer',
            'seats' => 'integer',
        ];
    }

    /**
     * Get reservations for this table
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get table display name (e.g., "Tavolo 5 (4 posti)")
     */
    public function getDisplayNameAttribute(): string
    {
        return "Tavolo {$this->number} ({$this->seats} posti)";
    }
}
