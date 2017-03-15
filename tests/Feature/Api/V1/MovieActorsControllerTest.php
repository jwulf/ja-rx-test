<?php

namespace Tests\Feature\Api\V1;

use App\User;
use App\Actor;
use App\Movie;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MovieActorsControllerTest extends TestCase
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
     * Test it returns a listing of actors associated with a movie
     *
     * @return void
     */
    public function testItReturnsAListingOfActorsForAMovie()
    {
        $movie = factory(Movie::class)->create();

        $movie->actors()->attach([
            factory(Actor::class)->create()->id => ['character' => 'Some Character'],
            factory(Actor::class)->create()->id => ['character' => 'Some Other Character'],
        ]);

        $this->get("/api/movies/{$movie->id}/actors")
            ->assertStatus(200)
            ->assertJsonFragment([
                'character' => 'Some Character',
            ]);
    }

    /**
     * Test it allows associating an actor to a movie
     *
     * @return void
     */
    public function testItAllowsAssociatingAnActorToAMovie()
    {
        $movie = factory(Movie::class)->create();
        $actor = factory(Actor::class)->create();

        $response = $this->json(
            'POST',
            "/api/movies/{$movie->id}/actors",
            ['actor_id' => $actor->id, 'character' => 'Some Character']
        );

        $response
            ->assertStatus(201)
            ->assertJsonFragment([
                'character' => 'Some Character'
            ]);

        $this->assertDatabaseHas(
            'actor_movie',
            ['actor_id' => $actor->id, 'movie_id' => $movie->id, 'character' => 'Some Character']
        );
    }

    /**
     * Test it prevents associating an actor to a movie without a character
     *
     * @return void
     */
    public function testItPreventsAssociatingAnActorToAMovieWithoutACharacter()
    {
        $movie = factory(Movie::class)->create();
        $actor = factory(Actor::class)->create();

        $response = $this->json(
            'POST',
            "/api/movies/{$movie->id}/actors",
            ['actor_id' => $actor->id, 'character' => '']
        );

        $response->assertStatus(422);
    }

    /**
     * Test it prevents associating an actor that is already associated
     *
     * @return void
     */
    public function testItPreventsAssociatingAnActorThatIsAlreadyAssociated()
    {
        $movie = factory(Movie::class)->create();
        $actor = factory(Actor::class)->create();

        $movie->actors()->attach($actor->id, ['character' => 'Some Character']);

        $response = $this->json(
            'POST',
            "/api/movies/{$movie->id}/actors",
            ['actor_id' => $actor->id, 'character' => 'Some Character']
        );

        $response->assertStatus(409);
    }

    /**
     * Test it unassociates an actor from a movie
     *
     * @return void
     */
    public function testItUnassociatesAnActorFromAMovie()
    {
        $movie = factory(movie::class)->create();
        $actor = factory(Actor::class)->create();

        $movie->actors()->attach($actor->id, ['character' => 'Some Character']);

        $this->delete('/api/movies/' . $movie->id . '/actors/' . $actor->id)
            ->assertstatus(204);

        $this->assertDatabaseMissing('actor_movie', ['actor_id' => $actor->id, 'movie_id' => $movie->id]);
    }
}
