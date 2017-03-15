<?php

use App\Genre;
use Illuminate\Database\Seeder;

class GenresTableSeeder extends Seeder
{
    /**
     * A list of genres to seed
     *
     * @var array
     */
    protected $genres = [
        'Action',
        'Adventure',
        'Comedy',
        'Documentary',
        'Drama',
        'Horror',
        'Romance',
        'Sci-Fi',
        'Thriller',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->genres as $genre) {
            Genre::findOrCreate(['name' => $genre]);
        }
    }
}
