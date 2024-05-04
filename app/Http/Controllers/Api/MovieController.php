<?php

namespace App\Http\Controllers\Api;

use App\Models\Movie;
use App\Models\People;
use App\Models\Planet;
use App\Models\Starship;
use App\Models\Vehicle;
use App\Models\Specie;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     *   Method : GET
     *   Parameters : token in header
     *   Activity : Get list of all movies.
     */

    /**
     * @OA\Get(
     *     path="/api/movies",
     *     tags={"Movies"},
     *     summary="Get all movies",
     *     @OA\Response(response="200", description="List of movies")
     * )
     */
    public function index()
    {
        // Retrieve all movies
        //$movies = Movie::all();

        // Retrieve all movies with most relevant data.
        $movies = Movie::select('id', 'title', 'opening_crawl', 'director', 'producer', 'release_date')->get();

        // Check if movies exist
        if (count($movies) > 0) {

            /*
            * This section will use if we want to get complete data of every movie
            foreach ($movies as $movie) {
                $movie = $this->getMovieRelatedData($movie);
            }
            */


            // Return a JSON response with success message and data
            return response()->json([
                "status" => true,
                "message" => "Movies data retrieved successfully.",
                "data" => $movies,
            ]);
        } else {
            // Return a JSON response with failure message
            return response()->json([
                "status" => false,
                "message" => "Sorry! Movies data not found.",
                "data" => [],
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     * 
     *   Method : GET
     *   Parameters : token in header, id
     *   Activity : Get perticular movie by id.
     */
    /**
     * @OA\Get(
     *     path="/api/movie/{id}",
     *     tags={"Show movie"},
     *     summary="Get movie by id",
     *     @OA\Response(response="200", description="Get movie by id")
     * )
     */          
    public function show($id)
    {
        // Retrieve the movie by id
        $movie = Movie::find($id);

        // Check if the movie exists
        if ($movie) {

            $movie = $this->getMovieRelatedData($movie);

            // Return a JSON response with success message and data
            return response()->json([
                "status" => true,
                "message" => "Movie data retrieved successfully.",
                "data" => $movie,
            ]);
        } else {
            // Return a JSON response with failure message
            return response()->json([
                "status" => false,
                "message" => "Movie not found.",
                "data" => [],
            ]);
        }
    }



    /**
     * Update the specified resource in storage.
     * 
     *   Method : POST
     *   Parameters : token in header, title, opening_crawl, director, producer, release_date
     *   Activity : Update movie record.
     */
    /**
     * @OA\Get(
     *     path="api/movie/update/{id}",
     *     tags={"Update movie"},
     *     summary="Update movie information",
     *     @OA\Response(response="200", description="Update movie record by id")
     * )
     */      
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            "title" => "required|string|max:255",
            "opening_crawl" => "required|string|max:255",
            "director" => "required|string|max:255",
            "producer" => "required|string|max:255",
            "release_date" => "required|date"
        ]);

        // Retrieve the movie by id
        $movie = Movie::find($id);

        // Check if the movie exists
        if ($movie) {
            try {
                // Update the movie with the validated data
                $movie = Movie::where('id', $id)->update($request->all());

                // Return a JSON response indicating record updated successfully.
                return response()->json([
                    "status" => true,
                    "message" => "Movie updated successfully.",
                    "data" => $movie,
                ]);
            } catch (\Exception $e) {
                // Return a JSON response indicating updation failure
                return response()->json([
                    "status" => false,
                    "message" => "Movie record updation failed. Please try again later.",
                    "error" => $e->getMessage(),
                ], 500);
            }
        } else {
            // Return a JSON response if the movie is not found
            return response()->json([
                "status" => false,
                "message" => "Movie record updation failed. Please try again later.",
                "data" => [],
            ]);
        }
    }




    /**
     * Remove the specified resource from storage.
     * 
     *   Method : DELETE
     *   Parameters : token in header, id
     *   Activity : Delete movie record by id.
     */
    /**
     * @OA\Get(
     *     path="api/movie/delete/{id}",
     *     tags={"Delete movie"},
     *     summary="Delete movie",
     *     @OA\Response(response="200", description="Delete movie by id")
     * )
     */        
    public function destroy(string $id)
    {
        // Retrieve the movie by id
        $movie = Movie::find($id);

        // Check if the movie exists
        if ($movie) {
            // Delete the movie
            Movie::destroy($id);

            // Return a JSON response indicating record deleted successfully.
            return response()->json([
                "status" => true,
                "message" => "Movie has been deleted successfully.",
                "data" => [],
            ]);
        } else {
            // Return a JSON response if the movie is not found
            return response()->json([
                "status" => false,
                "message" => "Movie does not exist.",
                "data" => [],
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     * 
     *   Method : GEt
     *   Parameters : token in header, title
     *   Activity : Search movie by title
     */
    /**
     * @OA\Get(
     *     path="api/movies/search",
     *     tags={"Search movies"},
     *     summary="Search movies",
     *     @OA\Response(response="200", description="Search movies by title")
     * )
     */      
    public function search(Request $request)
    {

        // Validate the incoming request data
        $request->validate([
            "keyword" => "required",
        ]);


        // Retrieve all movies with complete dat
        //$movies = Movie::where('title', 'like', '%' . $keyword . '%')->get();

        // Retrieve all movies with most relevant data.
        $movies = Movie::select('id', 'title', 'opening_crawl', 'director', 'producer', 'release_date')->where('title', 'like', '%' . $request->keyword . '%')->get();

        // Check if movies exist
        if (count($movies) > 0) {

            /*
            * This section will use if we want to get complete data of every movie
            foreach ($movies as $movie) {
                $movie = $this->getMovieRelatedData($movie);
            }
            */


            // Return a JSON response with success message and data
            return response()->json([
                "status" => true,
                "message" => "Movies search result.",
                "data" => $movies,
            ]);
        } else {
            // Return a JSON response with failure message
            return response()->json([
                "status" => false,
                "message" => "No serch result found.",
                "data" => [],
            ]);
        }
    }



    /**
     *   Parameters : movie data
     *   Activity : Get all movie related data
     */
    public function getMovieRelatedData($movie)
    {
        $movie->characters = People::whereIn('id', explode(',', $movie->characters))->get();
        $movie->planets = Planet::whereIn('id', explode(',', $movie->planets))->get();
        $movie->starships = Starship::whereIn('id', explode(',', $movie->starships))->get();
        $movie->vehicles = Vehicle::whereIn('id', explode(',', $movie->vehicles))->get();
        $movie->species = Specie::whereIn('id', explode(',', $movie->species))->get();
        return $movie;
    }
}
