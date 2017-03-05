<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OTPTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;
    /**
     * Requesting an otp token.
     *
     * @return void
     */
    public function testRequest()
    {
        $this->seed();

        $user = User::find(1);
        $this->be($user);

        $this->post('api/auth/otp', [
            'phone_number' => 15149275582
        ])->assertSee('ok');

        $this->post('api/auth/otp', [
            'phone_number' => 151492
        ])->assertSee('errors');

        $this->post('api/auth/otp', [
            'phone_number' => ''
        ])->assertSee('errors');
    }

    /**
     * Validates token.
     *
     * @return void
     */
    public function testValidation()
    {
        $this->seed();

        $user = User::find(1);
        $this->be($user);

        $this->post('api/auth/otp', [
            'phone_number' => 15149275582
        ])->assertSee('ok');

        $user->fresh();

        $this->post('api/auth/otp/unlock', [
            'token' => $user->otp - 1
        ])->assertSee('errors');

        $this->post('api/auth/otp/unlock', [
            'token' => $user->otp
        ])->assertSee('ok');

        $user = User::find(1);

        $this->assertNull($user->otp);
    }
}
