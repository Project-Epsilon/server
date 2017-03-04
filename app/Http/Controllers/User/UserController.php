<?php

namespace App\Http\Controllers\User;

use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Displays information of current user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return fractal()
            ->item($request->user())
            ->transformWith(new UserTransformer())
            ->toArray();
    }

    /**
     * Updating a users information
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'sometimes|required',
            'email' => 'sometimes|required',
            'phone_number' => 'sometimes|required',
            'username' => 'sometimes|required|alpha',
            'password' => 'sometimes|required|confirmed'
        ]);

        $request->user()->update($request->all());

        return $this->index($request);
    }

    /**
     * Removing a user from the system.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

}
