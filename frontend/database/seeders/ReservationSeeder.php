<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::where('email', 'admin@test.com')->first();

        $demoReservations = [
            // Oggi - Pranzo
            [
                'customer_name' => 'Mario Rossi',
                'customer_phone' => '+39 333 1234567',
                'customer_email' => 'mario.rossi@example.com',
                'reservation_date' => Carbon::today(),
                'reservation_time' => '12:30',
                'guests_count' => 4,
                'table_id' => 8,
                'shift' => 'lunch',
                'status' => 'confirmed',
                'notes' => 'Preferisce tavolo vicino alla finestra',
            ],
            [
                'customer_name' => 'Giulia Bianchi',
                'customer_phone' => '+39 348 9876543',
                'customer_email' => null,
                'reservation_date' => Carbon::today(),
                'reservation_time' => '13:00',
                'guests_count' => 2,
                'table_id' => 3,
                'shift' => 'lunch',
                'status' => 'pending',
                'notes' => null,
            ],
            // Oggi - Cena
            [
                'customer_name' => 'Luca Verdi',
                'customer_phone' => '+39 333 5555555',
                'customer_email' => 'luca.verdi@example.com',
                'reservation_date' => Carbon::today(),
                'reservation_time' => '20:00',
                'guests_count' => 6,
                'table_id' => 12,
                'shift' => 'dinner',
                'status' => 'confirmed',
                'notes' => 'Compleanno - possibile dessert speciale',
            ],
            [
                'customer_name' => 'Anna Neri',
                'customer_phone' => '+39 340 1111111',
                'customer_email' => 'anna.neri@example.com',
                'reservation_date' => Carbon::today(),
                'reservation_time' => '20:30',
                'guests_count' => 2,
                'table_id' => 2,
                'shift' => 'dinner',
                'status' => 'confirmed',
                'notes' => null,
            ],
            // Domani - Pranzo
            [
                'customer_name' => 'Paolo Ferrari',
                'customer_phone' => '+39 335 2222222',
                'customer_email' => null,
                'reservation_date' => Carbon::tomorrow(),
                'reservation_time' => '12:00',
                'guests_count' => 4,
                'table_id' => 9,
                'shift' => 'lunch',
                'status' => 'confirmed',
                'notes' => 'Business lunch',
            ],
            [
                'customer_name' => 'Francesca Russo',
                'customer_phone' => '+39 347 3333333',
                'customer_email' => 'francesca.russo@example.com',
                'reservation_date' => Carbon::tomorrow(),
                'reservation_time' => '13:30',
                'guests_count' => 3,
                'table_id' => 7,
                'shift' => 'lunch',
                'status' => 'pending',
                'notes' => 'Intolleranza al glutine',
            ],
            // Domani - Cena
            [
                'customer_name' => 'Marco Ricci',
                'customer_phone' => '+39 339 4444444',
                'customer_email' => 'marco.ricci@example.com',
                'reservation_date' => Carbon::tomorrow(),
                'reservation_time' => '19:30',
                'guests_count' => 8,
                'table_id' => 15,
                'shift' => 'dinner',
                'status' => 'confirmed',
                'notes' => 'Cena aziendale',
            ],
            [
                'customer_name' => 'Elena Costa',
                'customer_phone' => '+39 338 5555556',
                'customer_email' => null,
                'reservation_date' => Carbon::tomorrow(),
                'reservation_time' => '21:00',
                'guests_count' => 2,
                'table_id' => 4,
                'shift' => 'dinner',
                'status' => 'confirmed',
                'notes' => null,
            ],
            // Dopodomani
            [
                'customer_name' => 'Roberto Galli',
                'customer_phone' => '+39 345 6666666',
                'customer_email' => 'roberto.galli@example.com',
                'reservation_date' => Carbon::today()->addDays(2),
                'reservation_time' => '20:00',
                'guests_count' => 4,
                'table_id' => 10,
                'shift' => 'dinner',
                'status' => 'confirmed',
                'notes' => 'Anniversario matrimonio',
            ],
            // Una vecchia prenotazione cancellata
            [
                'customer_name' => 'Simona Martini',
                'customer_phone' => '+39 342 7777777',
                'customer_email' => null,
                'reservation_date' => Carbon::yesterday(),
                'reservation_time' => '20:00',
                'guests_count' => 2,
                'table_id' => null,
                'shift' => 'dinner',
                'status' => 'cancelled',
                'notes' => 'Cliente ha cancellato',
            ],
        ];

        foreach ($demoReservations as $reservation) {
            Reservation::create(array_merge($reservation, [
                'created_by' => $adminUser->id,
            ]));
        }
    }
}
