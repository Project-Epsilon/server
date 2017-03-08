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
     *
     * @api {get} user Get user info.
     * @apiVersion 0.2.0
     * @apiName GetUser
     * @apiGroup User
     *
     * @apiDescription Retrieves current authenticated user information.
     *
     * @apiSuccess {Object} data        User information.
     * @apiSuccess {Number} data.id     User id.
     * @apiSuccess {String} data.name   User name.
     * @apiSuccess {String} data.email  User email.
     * @apiSuccess {String} data.username User username.
     * @apiSuccess {String} data.phone_number User primary phone number.
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
     *
     * @api {post} user Update user info.
     * @apiVersion 0.2.0
     * @apiName UpdateUser
     * @apiGroup User
     *
     * @apiDescription Updates the current authenticated user information.
     *
     * @apiParam {String} name User name. (optional)
     * @apiParam {String} email Email. (optional)
     * @apiParam {String} phone_number Phone number. (optional)
     * @apiParam {String} username Username. (optional)
     * @apiParam {String} password Password. (optional)
     * @apiParam {String} password_confirmation Password confirmation. (optional)
     *
     * @apiSuccess {Object} data        The updated user information.
     * @apiSuccess {Number} data.id     User id.
     * @apiSuccess {String} data.name   User name.
     * @apiSuccess {String} data.email  User email.
     * @apiSuccess {String} data.username User username.
     * @apiSuccess {String} data.phone_number User primary phone number.
     *
     * @apiError {Object} errors    Object containing errors to the parameters inputted.
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
