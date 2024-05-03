<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\MovieController;


// Route Type : Open routes
Route::post("register", [ApiController::class, "register"]);
Route::post("login", [ApiController::class, "login"]);


// Route Type : Protected routes
Route::group([
    "middleware" => ["auth:api"]
], function () {
    Route::get("profile", [ApiController::class, "profile"]);
    
    Route::get("movies", [MovieController::class, "index"]);
    Route::get("movie/{id}", [MovieController::class, "show"]);
    Route::post("movie/update/{id}", [MovieController::class, "update"]);
    Route::delete("movie/delete/{id}", [MovieController::class, "destroy"]);
    Route::post("movies/search", [MovieController::class, "search"]);

    Route::get("logout", [ApiController::class, "logout"]);
});