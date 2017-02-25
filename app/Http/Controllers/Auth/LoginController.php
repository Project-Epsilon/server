<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\SocialAccountService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Handle a login request to the application.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->credentials($request);

        if ($token = JWTAuth::attempt($credentials)) {
            return $this->sendLoginResponse($request, $token, JWTAuth::user());
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Auth0 authentication.
     *
     * @param Request $request
     * @param SocialAccountService $accountService
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function auth0(Request $request, SocialAccountService $accountService)
    {
        $token = $this->getToken($request);

        if ($token == ''){
            return $this->sendFailedLoginResponse($request);
        }

        //Parser token to object
        $token = (new Parser())->parse($token);

        if (! $token->verify(new Sha256(), env('AUTH0_APP_SECRET'))){
            return $this->sendFailedLoginResponse($request);
        }

        $user = $accountService->getUser($token);

        if ($user) {
            $token = JWTAuth::fromUser($user);

            return $this->sendLoginResponse($request, $token, $user);
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Get Token
     *
     * @param $request
     * @return string
     */
    protected function getToken($request)
    {
        // Get the encrypted user JWT
        $authorizationHeader = $request->header('Authorization');

        return trim(str_replace('Bearer ', '', $authorizationHeader));
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param Request $request
     * @param string $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request, $token, $user)
    {
        $this->clearLoginAttempts($request);

        return $this->authenticated($token, $user);
    }

    /**
     * Get the failed login response instance.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return response()->json([
            'message' => Lang::get('auth.failed'),
        ], 401);
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param $user
     * @param string $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function authenticated($token, $user)
    {
        return response()->json([
            'data' => [
                'token' => $token,
                'user' => $user
            ]
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Log the user out of the application.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        return response()->json(['message' => Lang::get('auth.logged.out')]);
    }
}
