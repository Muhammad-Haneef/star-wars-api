<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\People;
use Illuminate\Support\Facades\Http;

class PeopleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($x = 1; $x <= 9; $x++) {
            $response = Http::get("https://swapi.dev/api/people/?page=$x");
            $rows = $response['results'];
            foreach ($rows as $row) {
                People::create([
                    'name' => $row['name'],
                    'height' => $row['height'],
                    'mass' => $row['mass'],
                    'hair_color' => $row['hair_color'],
                    'skin_color' => $row['skin_color'],
                    'eye_color' => $row['eye_color'],
                    'birth_year' => $row['birth_year'],
                    'gender' => $row['gender'],
                ]);
            }
        }
    }
}
