<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\SocialAccountService;
use App\Transformers\UserTransformer;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Laravel\Socialite\Facades\Socialite;
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
     *  List of supported social providers.
     */
    private $socialProviders = ['google', 'facebook'];

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     *  Redirect to social provider
     *
     * @param $provider
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @api {get} auth/:provider Social login.
     * @apiVersion 0.2.0
     * @apiName Social Login
     * @apiGroup Auth
     *
     * @apiDescription Authenticates the user with credentials.
     *
     * @apiParam {String} provider          Social provider either <code>google</code> or <code>facebook</code>.
     *
     * @apiSuccess {Object} data            Data object.
     * @apiSuccess {String} data.url        The redirect url for oauth.
     */
    public function redirectToProvider($provider)
    {
        if(! in_array($provider, $this->socialProviders)){
            return response()->json(['errors' => ['message' => Lang::get('auth.social.failed')]]);
        }

        $url = Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();

        return response()->json(['data' => ['url' => $url]]);
    }

    /**
     * Handle retrieved information from provider
     *
     * @param $provider
     * @param SocialAccountService $accountService
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback($provider, SocialAccountService $accountService, Request $request)
    {
        if(! in_array($provider, $this->socialProviders)){
            return response()->json(['errors' => ['message' => Lang::get('auth.social.failed')]]);
        }

        $socialUser = Socialite::driver($provider)->stateless()->user();

        $user = $accountService->getUser($socialUser, $provider);

        if ($user) {
            $this->clearLoginAttempts($request);
            $token = JWTAuth::fromUser($user);

            $data = fractal($user, new UserTransformer())->addMeta(['token' => $token])->toArray();

            return redirect('api/app/callback?data=' . urlencode(json_encode($data)) . '&success=true');
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Handle a login request to the application.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    /**
     * @api {post} auth/login Basic login.
     * @apiVersion 0.2.0
     * @apiName BasicLogin
     * @apiGroup Auth
     *
     * @apiDescription Authenticates the user with credentials.
     *
     * @apiParam {String} email             User email.
     * @apiParam {String} password          User password.
     *
     * @apiSuccess {Object} data            User information.
     * @apiSuccess {Number} data.id         User id.
     * @apiSuccess {String} data.name       User name.
     * @apiSuccess {String} data.email      User email.
     * @apiSuccess {String} data.username User username.
     * @apiSuccess {String} data.phone_number User primary phone number.
     * @apiSuccess {Boolean}data.locked     Lock out indication for otp.
     * @apiSuccess {Object} meta            Meta data.
     * @apiSuccess {String} meta.token           JWT token.
     *
     * @apiError {Object} errors            Object containing errors to the parameters inputted.
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->credentials($request);

        if ($token = JWTAuth::attempt($credentials)) {
            return $this->sendLoginResponse($request, $token, JWTAuth::toUser($token));
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
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
     * @param Request $request
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