<?php

namespace Tests\Unit;

use App\Http\Controllers\Auth\LoginController;
use App\SocialAccount;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SocialAccountTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test user relationship to user.
     *
     * @return void
     */
    public function testUser()
    {
        $this->seed();

        SocialAccount::unguard();

        $social = SocialAccount::create([
            'social_provider' => 'test',
            'social_id' => 777,
            'user_id' => 1
        ]);

        $email = $social->user()->first()->email;

        $this->assertEquals('user@user.com', $email);
    }

    /**
     * Test the get user test in the login controller.
     *
     * @return void
     */
    public function testGetUser()
    {
        $this->seed();

        SocialAccount::unguard();

        SocialAccount::create([
            'social_provider' => 'test',
            'social_id' => 777,
            'user_id' => 1
        ]);

        $login = new LoginController();

        $user = $login->getUser(new User('John Doe', 'user@user.com', 777), 'test');

        $this->assertEquals('user@user.com', $user->email);

        $user = $login->getUser(new User('Bob Miller', 'someone@user.com', 878), 'wwweba');

        $this->assertEquals($user->email, 'someone@user.com');
    }
}

/**
 * Class User for testing purposes.
 *
 * @package Tests\Unit
 */
class User implements \Laravel\Socialite\Contracts\User
{
    private $name;
    private $email;
    private $id;

    public function __construct($name, $email, $id)
    {
        $this->name = $name;
        $this->email = $email;
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getNickname()
    {
        return '';
    }

    public function getAvatar()
    {
        return '';
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getId()
    {
        return $this->id;
    }
}