<?php

namespace Tests\Feature\Api\V1;

use App\User;
use App\Movie;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MoviesControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Set up code to run before each test
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        Passport::actingAs(
            User::whereName('dev')->first()
        );
    }

    /**
     * Test it returns a listing of movies
     *
     * @return void
     */
    public function testItReturnsAListingOfMovies()
    {
        $movie = factory(Movie::class)->create();

        $this->get('/api/movies')
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => $movie->name,
            ]);
    }
}
