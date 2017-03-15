<?php

namespace Tests\Feature\Api\V1;

use App\User;
use App\Actor;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ActorsControllerTest extends TestCase
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
     * Test it returns a listing of actors
     *
     * @return void
     */
    public function testItReturnsAListingOfActors()
    {
        $actor = factory(Actor::class)->create();

        $this->get('/api/actors')
            ->assertStatus(200)
            ->assertJsonFragment([
                'first_name' => $actor->first_name,
            ]);
    }

    /**
     * Test it allows the creation of an actor
     *
     * @return void
     */
    public function testItAllowsCreationOfAnActor()
    {
        $actor = factory(Actor::class)->make();

        $response = $this->json(
            'POST',
            '/api/actors',
            [
                'first_name'  => $actor->first_name,
                'middle_name' => $actor->middle_name,
                'last_name'   => $actor->last_name,
                'dob'         => !empty($actor->dob) ? $actor->dob->format('Y-m-d') : null,
                'biography'   => $actor->biography,
            ]
        );

        $response
            ->assertStatus(201)
            ->assertJsonFragment([
                'first_name' => $actor->first_name
            ]);

        $this->assertDatabaseHas(
            'actors',
            ['first_name' => $actor->first_name]
        );
    }

    /**
     * Test it prevents creation of an actor with no name
     *
     * @return void
     */
    public function testItPreventsCreationOfAnActorWithNoName()
    {
        $response = $this->json('POST', '/api/actors', ['first_name' => ''])
            ->assertStatus(422);
    }

    /**
     * Test it allows updating of an actor
     *
     * @return void
     */
    public function testItAllowsUpdatingOfAnActor()
    {
        $actor = factory(Actor::class)->create();

        $response = $this->json(
            'PUT',
            '/api/actors/' . $actor->id,
            ['first_name' => 'Someotherfirstname', 'last_name' => 'Someotherlastname']
        );

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'first_name' => 'Someotherfirstname',
                'last_name'  => 'Someotherlastname',
            ]);

        $this->assertDatabaseHas(
            'actors',
            [
                'first_name' => 'Someotherfirstname',
                'last_name'  => 'Someotherlastname',
            ]
        );
    }

    /**
     * Test it prevents updating an actor with no name
     *
     * @return void
     */
    public function testItPreventsUpdatingAnActorWithNoName()
    {
        $actor = factory(Actor::class)->create();

        $response = $this->json(
            'PUT',
            '/api/actors/' . $actor->id,
            ['first_name' => '']
        );

        $response->assertStatus(422);
    }

    /**
     * Test it shows actor details
     *
     * @return void
     */
    public function testItShowsActorDetails()
    {
        $actor = factory(actor::class)->create();

        $this->get('/api/actors/' . $actor->id)
            ->assertstatus(200)
            ->assertJsonFragment([
                'first_name' => $actor->first_name,
            ]);
    }

    /**
     * Test it deletes an actor
     *
     * @return void
     */
    public function testItDeletesAnActor()
    {
        $actor = factory(Actor::class)->create();

        $this->delete('/api/actors/' . $actor->id)
            ->assertStatus(204);

        $this->assertDatabaseMissing('actors', ['id' => $actor->id]);
    }
}
