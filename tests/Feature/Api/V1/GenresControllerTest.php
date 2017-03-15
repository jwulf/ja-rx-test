<?php

namespace Tests\Feature\Api\V1;

use App\User;
use App\Genre;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GenresControllerTest extends TestCase
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
     * Test it returns a listing of genres
     *
     * @return void
     */
    public function testItReturnsAListingOfGenres()
    {
        $genre = factory(Genre::class)->create();

        $this->get('/api/genres')
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => $genre->name,
            ]);
    }

    /**
     * Test it allows the creation of a genre
     *
     * @return void
     */
    public function testItAllowsCreationOfAGenre()
    {
        $genre = factory(Genre::class)->make();

        $response = $this->json(
            'POST',
            '/api/genres',
            ['name' => $genre->name]
        );

        $response
            ->assertStatus(201)
            ->assertJsonFragment([
                'name' => $genre->name
            ]);

        $this->assertDatabaseHas(
            'genres',
            ['name' => $genre->name]
        );
    }

    /**
     * Test it prevents creation of a genre with no name
     *
     * @return void
     */
    public function testItPreventsCreationOfAGenreWithNoName()
    {
        $response = $this->json('POST', '/api/genres', ['name' => ''])
            ->assertStatus(422);
    }

    /**
     * Test it allows updating of a genre
     *
     * @return void
     */
    public function testItAllowsUpdatingOfAGenre()
    {
        $genre = factory(Genre::class)->create();

        $response = $this->json(
            'PUT',
            '/api/genres/' . $genre->id,
            ['name' => 'Some other name']
        );

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Some other name',
            ]);

        $this->assertDatabaseHas(
            'genres',
            ['name' => 'Some other name']
        );
    }

    /**
     * Test it prevents updating a genre with no name
     *
     * @return void
     */
    public function testItPreventsUpdatingAGenreWithNoName()
    {
        $genre = factory(Genre::class)->create();

        $response = $this->json(
            'PUT',
            '/api/genres/' . $genre->id,
            ['name' => '']
        );

        $response->assertStatus(422);
    }

    /**
     * Test it shows genre details
     *
     * @return void
     */
    public function testItShowsGenreDetails()
    {
        $genre = factory(genre::class)->create();

        $this->get('/api/genres/' . $genre->id)
            ->assertstatus(200)
            ->assertJsonFragment([
                'name' => $genre->name,
            ]);
    }

    /**
     * Test it deletes a genre
     *
     * @return void
     */
    public function testItDeletesAGenre()
    {
        $genre = factory(genre::class)->create();

        $this->delete('/api/genres/' . $genre->id)
            ->assertstatus(204);

        $this->assertDatabaseMissing('genres', ['id' => $genre->id]);
    }
}
