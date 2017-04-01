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

    public function index()
    {
        $this->seed();
        $this->be(User::find(1));

        $this->get('api/user/contact')
            ->assertJsonStructure([
                'data' => ['*' => [
                    'id', 'name', 'phone_number', 'email'
                ]]
            ]);
    }
    /**
     * Storing contact test.
     *
     * @return void
     */
    public function testStore()
    {
        $this->seed(\UserSeeder::class);
        $this->be(User::find(1));

        $this->post('api/user/contact', [
            'name' => 'John Smith',
            'phone_number' => 5145554444,
            'email' => ''
        ])->assertJsonFragment([
            'name' => 'John Smith',
            'phone_number' => 5145554444
        ]);

        $this->post('api/user/contact', [
            'name' => 'John Smith'
        ])->assertSee('errors');
    }

    /**
     * Destroy contact test.
     *
     * @return void
     */
    public function testDestroy()
    {
        $this->seed(\UserSeeder::class);

        $user = User::find(1);
        $this->be($user);

        $user->contacts()->save(new Contact([
            'name' => 'Timothy Doe',
            'phone_number' => 5145554444
        ]));

        $this->delete('api/user/contact/1')->assertSee('ok');
    }

    /**
     * Update contact.
     *
     * @return void
     */
    public function testUpdate()
    {
        $this->seed();
        $this->be(User::find(1));

        $this->put('api/user/contact/1', [
            'name' => 'John Smith',
            'phone_number' => 15145325432,
            'email' => 'user@user.com',
        ])->assertSee('data');

        $contact = Contact::find(1);

        $this->assertEquals(15145325432, $contact->phone_number);
    }
    
}
