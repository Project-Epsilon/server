<?php

namespace App\Http\Controllers\Auth;

use App\Providers\NexmoServiceProvider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OTPController extends Controller
{
    /**
     * Sends an otp token to the requested phone number.
     *
     * @param Request $request
     * @param NexmoServiceProvider $nexmo
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function request(Request $request, NexmoServiceProvider $nexmo)
    {
        $this->validate($request, [
            'phone_number' => 'required'
        ]);

        $user = $request->user();
        $user->update([
            'phone_number' => $request->phone_number,
            'otp' => rand(100000, 999999)
        ]);

        try {
            $nexmo->send('Your verification number is ' . $user->otp, $request->phone_number);
        } catch (\Exception $e){
            return response()->json([
                'errors' => [
                    'message' => 'There was an error with the phone number.'
                ]
            ]);
        }

        return response('ok');
    }

    /**
     * Unlocks the user with the requested otp token.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function unlock(Request $request)
    {
        $this->validate($request, [
            'token' => 'required|digits:6'
        ]);

        $user = $request->user();

        if ($user->otp != $request->token) {
            return response()->json([
                'errors' => [
                    'message' => 'Token mismatched.'
                ]
            ]);
        }

        $user->update(['otp' => null]);

        return response('ok');
    }
  
}
