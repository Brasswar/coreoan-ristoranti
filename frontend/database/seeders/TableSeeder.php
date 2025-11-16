<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tavoli 1-6: 2 posti
        for ($i = 1; $i <= 6; $i++) {
            Table::create([
                'number' => $i,
                'seats' => 2,
            ]);
        }

        // Tavoli 7-11: 4 posti
        for ($i = 7; $i <= 11; $i++) {
            Table::create([
                'number' => $i,
                'seats' => 4,
            ]);
        }

        // Tavoli 12-14: 6 posti
        for ($i = 12; $i <= 14; $i++) {
            Table::create([
                'number' => $i,
                'seats' => 6,
            ]);
        }

        // Tavolo 15: 8 posti
        Table::create([
            'number' => 15,
            'seats' => 8,
        ]);
    }
}
