<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Planet;
use Illuminate\Support\Facades\Http;

class PlanetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($x = 1; $x <= 6; $x++) {
            $response = Http::get("https://swapi.dev/api/planets/?page=$x");
            $rows = $response['results'];
            foreach ($rows as $row) {
                Planet::create([
                    'name' => $row['name'],
                    'rotation_period' => $row['rotation_period'],
                    'orbital_period' => $row['orbital_period'],
                    'diameter' => $row['diameter'],
                    'climate' => $row['climate'],
                    'gravity' => $row['gravity'],
                    'terrain' => $row['terrain'],
                    'surface_water' => $row['surface_water'],
                    'population' => $row['population']
                ]);
            }
        }
    }
}
