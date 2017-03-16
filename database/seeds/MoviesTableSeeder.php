<?php

use App\Actor;
use App\Genre;
use App\Movie;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class MoviesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $actors = Actor::all();

        $genres = Genre::all();

        $faker = Faker::create();

        factory(Movie::class, 50)->create()->each(function ($movie) use ($actors, $genres, $faker) {
            // Assign a random genre
            $movie->genre()->associate($genres->random())->save();

            // Assign between 5 and 10 random actors with random character names
            foreach ($actors->random(rand(5, 10)) as $randomActor) {
                $movie->actors()->attach($randomActor->id, ['character' => $faker->name]);
            }
        });
    }
}
