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

    /**
     * Test it allows the creation of a movie
     *
     * @return void
     */
    public function testItAllowsCreationOfAMovie()
    {
        $movie = factory(Movie::class)->make();

        $response = $this->json(
            'POST',
            '/api/movies',
            ['name' => $movie->name, 'rating' => $movie->rating, 'description' => $movie->description]
        );

        $response
            ->assertStatus(201)
            ->assertJsonFragment([
                'name' => $movie->name
            ]);

        $this->assertDatabaseHas(
            'movies',
            ['name' => $movie->name]
        );
    }

    /**
     * Test it prevents creation of a movie with no name
     *
     * @return void
     */
    public function testItPreventsCreationOfAMovieWithNoName()
    {
        $response = $this->json('POST', '/api/movies', ['name' => ''])
            ->assertStatus(422);
    }

    /**
     * Test it allows updating of a movie
     *
     * @return void
     */
    public function testItAllowsUpdatingOfAMovie()
    {
        $movie = factory(Movie::class)->create();

        $response = $this->json(
            'PUT',
            '/api/movies/' . $movie->id,
            ['name' => 'Some other name']
        );

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Some other name',
            ]);

        $this->assertDatabaseHas(
            'movies',
            ['name' => 'Some other name']
        );
    }

    /**
     * Test it prevents updating a movie with no name
     *
     * @return void
     */
    public function testItPreventsUpdatingAMovieWithNoName()
    {
        $movie = factory(Movie::class)->create();

        $response = $this->json(
            'PUT',
            '/api/movies/' . $movie->id,
            ['name' => '']
        );

        $response->assertStatus(422);
    }

    /**
     * Test it shows movie details
     *
     * @return void
     */
    public function testItShowsMovieDetails()
    {
        $movie = factory(movie::class)->create();

        $this->get('/api/movies/' . $movie->id)
            ->assertstatus(200)
            ->assertJsonFragment([
                'name' => $movie->name,
            ]);
    }

    /**
     * Test it deletes a movie
     *
     * @return void
     */
    public function testItDeletesAMovie()
    {
        $movie = factory(movie::class)->create();

        $this->delete('/api/movies/' . $movie->id)
            ->assertstatus(204);

        $this->assertDatabaseMissing('movies', ['id' => $movie->id]);
    }
}
