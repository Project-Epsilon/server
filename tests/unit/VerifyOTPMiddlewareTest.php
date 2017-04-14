<?php

namespace Tests\Unit;

use App\Http\Middleware\VerifyOTP;
use App\User;
use Illuminate\Http\Request;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tymon\JWTAuth\Facades\JWTAuth;

class VerifyOTPMiddlewareTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A middleware test example.
     *
     * @return void
     */
    public function testOTPMiddlware()
    {
        $this->seed();

        $user = User::find(1);
        $token = JWTAuth::fromUser($user);
        $this->be($user);

        $this->get('api/wallet', [
            'Authorization' => 'Bearer ' . $token
        ])->assertSee('data');

        $user->otp = -1;
        $user->save();

        $this->get('api/wallet', [
            'Authorization' => 'Bearer ' . $token
        ])->assertSee('errors');
    }
}
