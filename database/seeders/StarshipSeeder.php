<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


use App\Models\Starship;
use Illuminate\Support\Facades\Http;

class StarshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($x = 1; $x <= 4; $x++) {
            $response = Http::get("https://swapi.dev/api/starships/?page=$x");
            $rows = $response['results'];
            foreach ($rows as $row) {
                Starship::create([
                    'name' => $row['name'],
                    'model' => $row['model'],
                    'manufacturer' => $row['manufacturer'],
                    'cost_in_credits' => $row['cost_in_credits'],
                    'length' => $row['length'],
                    'max_atmosphering_speed' => $row['max_atmosphering_speed'],
                    'crew' => $row['crew'],
                    'passengers' => $row['passengers'],
                    'cargo_capacity' => $row['cargo_capacity'],
                    'consumables' => $row['consumables'],
                    'hyperdrive_rating' => $row['hyperdrive_rating'],
                    'MGLT' => $row['MGLT'],
                    'starship_class' => $row['starship_class'],
                ]);
            }
        }
    }
}
