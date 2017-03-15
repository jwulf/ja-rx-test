<?php

namespace Tests\Feature\Api\V1;

use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TestControllerTest extends TestCase
{
    /**
     * Test it is secured by OAuth
     *
     * @return void
     */
    public function testItIsSecuredByOauth()
    {
        $this->get('/api/test')->assertStatus(401);
    }

    /**
     * Test it allows an authenticated user access
     *
     * @return void
     */
    public function testItAllowsUsersAccess()
    {
        Passport::actingAs(
            User::whereName('dev')->first()
        );

        $this->get('/api/test')->assertStatus(200);
    }
}
