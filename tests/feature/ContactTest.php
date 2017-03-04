<?php

namespace Tests\Feature;

use App\Contact;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ContactTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    /**
     * Storing contact test.
     *
     * @return void
     */
    public function testStore()
    {
        $this->seed(\UsersTableSeeder::class);
        $this->be(User::find(1));

        $this->post('api/user/contact', [
            'name' => 'John Smith',
            'phone_number' => 5145554444,
        ])->assertJsonFragment([
            'name' => 'John Smith',
            'phone_number' => 5145554444
        ]);
    }

    /**
     * Destroy contact test.
     */
    public function testDestroy()
    {
        $this->seed(\UsersTableSeeder::class);

        $user = User::find(1);
        $this->be($user);

        $user->contacts()->save(new Contact([
            'name' => 'Timothy Doe',
            'phone_number' => 5145554444
        ]));

        $this->delete('api/user/contact/1')->assertSee('ok');
    }
}