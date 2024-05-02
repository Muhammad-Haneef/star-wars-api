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
     *   Parameters : null
     *   Activity : Get list of all movies.
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
                "message" => "Data found",
                "data" => $movies,
            ]);
        } else {
            // Return a JSON response with failure message
            return response()->json([
                "status" => false,
                "message" => "Sorry! Records not found",
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
     *   Parameters : id
     *   Activity : Get perticular movie by id.
     */
    public function show(string $id)
    {
        // Retrieve the movie by id
        $movie = Movie::find($id);

        // Check if the movie exists
        if ($movie) {

            $movie = $this->getMovieRelatedData($movie);

            // Return a JSON response with success message and data
            return response()->json([
                "status" => true,
                "message" => "Data found",
                "data" => $movie,
            ]);
        } else {
            // Return a JSON response with failure message
            return response()->json([
                "status" => false,
                "message" => "Sorry! Records not found",
                "data" => [],
            ]);
        }
    }



    /**
     * Update the specified resource in storage.
     * 
     *   Method : POST
     *   Parameters : title, opening_crawl, director, producer, release_date
     *   Activity : Update movie record.
     */
    public function update(Request $request, string $id)
    {
        // Validate the incoming request data
        $request->validate([
            "title" => "required|string",
            "opening_crawl" => "required|string",
            "director" => "required|string",
            "producer" => "required|string",
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
                    "message" => "Record updated successfully",
                    "data" => $movie,
                ]);
            } catch (\Exception $e) {
                // Return a JSON response indicating updation failure
                return response()->json([
                    "status" => false,
                    "message" => "Record updation failed. Please try again later.",
                    "error" => $e->getMessage(),
                ], 500);
            }
        } else {
            // Return a JSON response if the movie is not found
            return response()->json([
                "status" => false,
                "message" => "Sorry! Records not found to update",
                "data" => [],
            ]);
        }
    }




    /**
     * Remove the specified resource from storage.
     * 
     *   Method : GET
     *   Parameters : id
     *   Activity : Delete movie record by id.
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
                "message" => "Record has been deleted successfully",
                "data" => $movie,
            ]);
        } else {
            // Return a JSON response if the movie is not found
            return response()->json([
                "status" => false,
                "message" => "Sorry! Records not found",
                "data" => [],
            ]);
        }
    }

    
    /**
     * Remove the specified resource from storage.
     * 
     *   Method : POST
     *   Parameters : title
     *   Activity : Search movie by title
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
                "message" => "Data found",
                "data" => $movies,
            ]);
        } else {
           // Return a JSON response with failure message
            return response()->json([
                "status" => false,
                "message" => "Sorry! Records not found",
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
