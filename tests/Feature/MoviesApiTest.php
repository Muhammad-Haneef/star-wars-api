<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;

use Laravel\Passport\Passport;

use Tests\TestCase;

class MoviesApiTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    /** @test */
    public function it_returns_list_of_movies()
    {
        // Create a user with factory (by using Laravel Passport for authentication)
        $user = User::factory()->create();
        // Authenticate the user using Passport
        Passport::actingAs($user);

        // Create dummy movies (in this case, 3 movies)
        $movies = Movie::factory()->count(3)->create();

        // Hit the endpoint that returns the list of movies
        $response = $this->getJson('/api/movies');

        // Assert that the HTTP status code of the response is 200, indicating success
        $response->assertStatus(200);

        // Assert the structure of the JSON response returned by the endpoint
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
                ],
            ],
        ]);
    }


    /** @test */
    public function it_shows_movie_by_id()
    {
        // Create a user with factory (by using Laravel Passport for authentication)
        $user = User::factory()->create();
        // Authenticate the user using Passport
        Passport::actingAs($user);

        // Create a dummy movie
        $movie = Movie::factory()->create();

         // Hit the endpoint that returns the movie by its ID
        $response = $this->getJson('/api/movie/' . $movie->id);

        // Assert that the HTTP status code of the response is 200, indicating success
        $response->assertStatus(200);

        // Assert the JSON response returned by the endpoint
        $response->assertJson([
            'status' => true,
            'message' => 'Data found',
            'data' => [
                'id' => $movie->id,
                'title' => $movie->title,
                'opening_crawl' => $movie->opening_crawl,
                'director' => $movie->director,
                'producer' => $movie->producer,
                'release_date' => $movie->release_date
            ],
        ]);
    }

    /** @test */
    public function it_updates_movie_record()
    {
        // Create a user with factory (by using Laravel Passport for authentication)
        $user = User::factory()->create();
        // Authenticate the user using Passport
        Passport::actingAs($user);

        // Create a dummy movie
        $movie = Movie::factory()->create();

        // New data for updating the movie
        $newTitle = 'New Movie Title';
        $newDirector = 'New Movie Director';

        // Hit the endpoint that updates the movie
        $response = $this->post("/api/movie/update/{$movie->id}", [
            'title' => 'Updated Title',
            'opening_crawl' => 'Updated Opening Crawl',
            'director' => 'Updated Director',
            'producer' => 'Updated Producer',
            'release_date' => '2024-05-02',
        ]);

        // Assert HTTP status code is 200
        $response->assertStatus(200);
    }



    /** @test */
    public function it_deletes_movie_by_id()
    {

        // Create a user with factory (by using Laravel Passport for authentication)
        $user = User::factory()->create();
        // Authenticate the user using Passport
        Passport::actingAs($user);

        // Create a dummy movie
        $movie = Movie::factory()->create();

        // Hit the endpoint that deletes the movie by its ID
        $response = $this->get('/api/movie/delete/' . $movie->id);

        // Assert HTTP status code is 200, indicating success
        $response->assertStatus(200);

        // Assert the JSON response returned by the endpoint
        $response->assertJson([
            'status' => true,
            'message' => 'Record has been deleted successfully',
            'data' => [
                'id' => $movie->id,
                // Include other expected attributes
            ],
        ]);

        // Assert HTTP status code is 204 (No Content), indicating success
        $response->assertStatus(200);

        // Assert that the movie record has been deleted from the database
        $this->assertDatabaseMissing('movies', ['id' => $movie->id]);
    }


    /** @test */
    public function it_searches_movie_by_title()
    {

        // Create a user with factory (by using Laravel Passport for authentication)
        $user = User::factory()->create();
        // Authenticate the user using Passport
        Passport::actingAs($user);

        // Create dummy movies (in this case, 3 movies)
        $movies = Movie::factory()->count(3)->create();

        // Hit the endpoint that searches for movies by title
        $response = $this->postJson('/api/movie/search', [
            'keyword' => $movies->first()->title,
        ]);

         // Assert HTTP status code is 200, indicating success
        $response->assertStatus(200);

        // Assert the structure of the JSON response returned by the endpoint
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
                ],
            ],
        ]);
    }
}
