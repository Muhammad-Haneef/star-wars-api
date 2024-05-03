<?php
/*
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MoviesApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}



<?php
*/

namespace Tests\Feature;

use App\Models\Movie;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MoviesApiTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    /** @test */
    public function it_returns_list_of_movies()
    {
        $movies = Movie::factory()->count(3)->create();

        echo "<pre>";
        print_r($movies);

        $response = $this->getJson('/api/movies');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'opening_crawl',
                    'director',
                    'producer',
                    'release_date',
                    'characters',
                    'planets',
                    'starships',
                    'vehicles',
                    'species',
                ],
            ],
        ]);
    }

    /** @test */
    public function it_shows_movie_by_id()
    {
        $movie = Movie::factory()->create();

        $response = $this->getJson('/api/movies/' . $movie->id);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'Data found',
            'data' => [
                'id' => $movie->id,
                'title' => $movie->title,
                // Include other expected attributes
            ],
        ]);
    }

    /** @test */
    public function it_updates_movie_record()
    {
        $movie = Movie::factory()->create();

        $response = $this->putJson('/api/movies/' . $movie->id, [
            'title' => 'Updated Title',
            'opening_crawl' => 'Updated Opening Crawl',
            'director' => 'Updated Director',
            'producer' => 'Updated Producer',
            'release_date' => '2024-05-02',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'Record updated successfully',
            'data' => [
                'id' => $movie->id,
                'title' => 'Updated Title',
                // Include other expected attributes
            ],
        ]);
    }

    /** @test */
    public function it_deletes_movie_record_by_id()
    {
        $movie = Movie::factory()->create();

        $response = $this->deleteJson('/api/movies/' . $movie->id);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'Record has been deleted successfully',
            'data' => [
                'id' => $movie->id,
                // Include other expected attributes
            ],
        ]);
        $this->assertDeleted($movie);
    }

    /** @test */
    public function it_searches_movie_by_title()
    {
        $movies = Movie::factory()->count(3)->create();

        $response = $this->postJson('/api/movies/search', [
            'keyword' => $movies->first()->title,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'opening_crawl',
                    'director',
                    'producer',
                    'release_date',
                    'characters',
                    'planets',
                    'starships',
                    'vehicles',
                    'species',
                ],
            ],
        ]);
    }
}
