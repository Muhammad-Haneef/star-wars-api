<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Specie;
use Illuminate\Support\Facades\Http;

class SpecieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        for ($x = 1; $x <= 4; $x++) {
            $response = Http::get("https://swapi.dev/api/species/?page=$x");
            $rows = $response['results'];
            foreach ($rows as $row) {
                Specie::create([
                    'name' => $row['name'],
                    'classification' => $row['classification'],
                    'designation' => $row['designation'],
                    'average_height' => $row['average_height'],
                    'skin_colors' => $row['skin_colors'],
                    'hair_colors' => $row['hair_colors'],
                    'eye_colors' => $row['eye_colors'],
                    'average_lifespan' => $row['average_lifespan'],
                    'language' => $row['language'],
                ]);
            }
        }
    }
}
