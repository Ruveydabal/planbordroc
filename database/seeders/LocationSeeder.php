<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            [
                'name' => 'all',
                'display_name' => 'Algemeen'
            ],
            [
                'name' => 'PraktijkHal',
                'display_name' => 'PraktijkHal'
            ],
            [
                'name' => 'Studieplein',
                'display_name' => 'Studieplein'
            ],
            [
                'name' => 'Afwezig',
                'display_name' => 'Afwezig'
            ]
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}
