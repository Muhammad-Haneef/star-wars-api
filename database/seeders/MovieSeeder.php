<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Movie;
use Illuminate\Support\Facades\Http;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $response = Http::get("https://swapi.dev/api/films");
        $rows = $response['results'];
        foreach ($rows as $row) {

            $characters_ids = [];
            $planets_ids = [];
            $starships_ids = [];
            $vehicles_ids = [];
            $species_ids = [];

            foreach ($row['characters'] as $s) {
                $characters_ids[] = (int)filter_var($s, FILTER_SANITIZE_NUMBER_INT);
            }

            foreach ($row['planets'] as $s) {
                $planets_ids[] = (int)filter_var($s, FILTER_SANITIZE_NUMBER_INT);
            }

            foreach ($row['starships'] as $s) {
                $starships_ids[] = (int)filter_var($s, FILTER_SANITIZE_NUMBER_INT);
            }

            foreach ($row['vehicles'] as $s) {
                $vehicles_ids[] = (int)filter_var($s, FILTER_SANITIZE_NUMBER_INT);
            }

            foreach ($row['species'] as $s) {
                $species_ids[] = (int)filter_var($s, FILTER_SANITIZE_NUMBER_INT);
            }


            Movie::create([
                'title' => $row['title'],
                'opening_crawl' => $row['opening_crawl'],
                'director' => $row['director'],
                'producer' => $row['producer'],
                'release_date' => $row['release_date'],

                'characters' => implode(',', $characters_ids),
                'planets' => implode(',', $planets_ids),
                'starships' => implode(',', $starships_ids),
                'vehicles' => implode(',', $vehicles_ids),
                'species' => implode(',', $species_ids),
            ]);
        }
    }
}
