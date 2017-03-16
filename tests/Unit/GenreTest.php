<?php

namespace Tests\Unit;

use App\Actor;
use App\Genre;
use App\Movie;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GenreTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test it returns the actors associated with the movies in the genre
     *
     * @return void
     */
    public function testItReturnsTheActorsAssociatedWithTheMoviesInTheGenre()
    {
        $genre = factory(Genre::class)->create();

        $actor = factory(Actor::class)->create();

        $movie = factory(Movie::class)->create([
            'genre_id' => $genre->id,
        ]);

        $movie->actors()->attach($actor->id, ['character' => 'Some Character']);

        $this->assertEquals($genre->actors->count(), 1);
        $this->assertEquals($genre->actors[0]->first_name, $actor->first_name);
    }

    /**
     * Test it doesn't duplicate actors associated with multiple movies in the genre
     *
     * @return void
     */
    public function testItDoesntDuplicateActorsAssocatedWithMultipleMoviesInTheGenre()
    {
        $genre = factory(Genre::class)->create();

        $actor = factory(Actor::class)->create();

        // Create two movies in the same genre with the same actor
        $movies = factory(Movie::class, 2)->create([
            'genre_id' => $genre->id,
        ])->each(function ($movie) use ($actor) {
            $movie->actors()->attach($actor->id, ['character' => 'Some Character']);
        });

        $this->assertEquals($genre->actors->count(), 1);
    }
}
