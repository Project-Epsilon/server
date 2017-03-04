<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ContactTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStore()
    {
        $this->seed();
        $this->be(User::find(1));

        $this->post('api/user/contact', [
            'name' => 'John Smith',
            'phone_number' => 5145554444,
        ])->assertJsonFragment([
            'name' => 'John Smith',
            'phone_number' => 5145554444
        ]);
    }
}
