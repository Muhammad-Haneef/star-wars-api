<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'opening_crawl',
        'director',
        'producer',
        'release_date'
    ];

}
