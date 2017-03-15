<?php

namespace Tests\Unit;

use App\Actor;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ActorTest extends TestCase
{
    /**
     * Test it calculates the age automatically
     *
     * @return void
     */
    public function testItCalculatesTheAgeAutomatically()
    {
        $actor = factory(Actor::class)->make([
            'dob' => Carbon::now()->subYears(30)
        ]);

        $this->assertEquals($actor->age, 30);
    }

    /**
     * Test it handles no date of birth when calculating age
     *
     * @return void
     */
    public function testItHandlesNoDobWhenCalculatingAge()
    {
        $actor = factory(Actor::class)->make([
            'dob' => null
        ]);

        $this->assertEquals($actor->age, null);
    }
}
