<?php

namespace App\Http\Controllers\User;

use App\Contact;
use App\Transformers\ContactTransformer;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    /**
     * Display a listing of all the current users contacts.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * @api {post} user/contact Get all contacts.
     * @apiVersion 0.2.0
     * @apiName GetContacts
     * @apiGroup Contacts
     *
     * @apiDescription Gets all contacts of the authenticated user.
     *
     * @apiSuccess {Object[]} data          The updated user information.
     * @apiSuccess {Number} data.id         User id.
     * @apiSuccess {String} data.name       User name.
     * @apiSuccess {String} data.email      User email.
     * @apiSuccess {String} data.phone_number User primary phone number.
     */
    public function index(Request $request)
    {
        $contacts = $request->user()->contacts()->orderBy('name', 'asc')->get();

        return fractal()
            ->collection($contacts)
            ->transformWith(new ContactTransformer())
            ->toArray();
    }

    /**
     * Store a newly created contact in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * @api {post} user/contact Store contact.
     * @apiVersion 0.2.0
     * @apiName StoreContacts
     * @apiGroup Contacts
     *
     * @apiDescription Stores a user contact.
     *
     * @apiParam {String} name              Contact name.
     * @apiParam {String} email             Contact email.
     * @apiParam {String} phone_number      Contact phone number.
     *
     * @apiSuccess {Object} data            The updated user information.
     * @apiSuccess {Number} data.id         Contact id.
     * @apiSuccess {String} data.name       Contact name.
     * @apiSuccess {String} data.email      Contact email.
     * @apiSuccess {String} data.phone_number Contact phone number.
     *
     * @apiError {Object} errors            Object containing errors to the parameters inputted.
     */
    public function store(Request $request)
    {
        $this->validateContact($request);

        $contact = $request->user()->contacts()
            ->save(new Contact($request->all()));

        return fractal()
            ->item($contact)
            ->transformWith(new ContactTransformer())
            ->toArray();
    }

    /**
     * Update the specified contact in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $contact = Contact::find($id);

        if (! $contact || ! $this->canEditContact($contact, $request->user())) {
            return $this->buildFailedValidationResponse($request, 'Contact not found');
        }

        $this->validateContact($request);
        $contact->update($request->all());

        return fractal()
            ->item($contact)
            ->transformWith(new ContactTransformer())
            ->toArray();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    /**
     * @api {delete} user/contact/:id Destroy contact.
     * @apiVersion 0.2.0
     * @apiName DestroyContact
     * @apiGroup Contacts
     *
     * @apiDescription Destroys the contact.
     *
     * @apiParam {Number} id                Contact id.
     *
     * @apiSuccess {String} ok              Okay response.
     *
     * @apiError {Object} errors            Object containing an error message.
     */
    public function destroy($id, Request $request)
    {
        $contact = Contact::find($id);

        if(! $contact || ! $this->canEditContact($contact, $request->user())){
            return $this->buildFailedValidationResponse($request, 'Contact not found.');
        }

        $contact->delete();

        return $this->successResponse();
    }


    /**
     * Validates the inputs of the requests.
     *
     * @param Request $request
     */
    protected function validateContact(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone_number' => 'required_without:email|numeric',
            'email' => 'required_without:phone_number|email'
        ]);
    }

    /**
     * Determines if a user can modify this contact.
     *
     * @param Contact $contact
     * @param User $user
     * @return bool
     */
    protected function canEditContact(Contact $contact, User $user)
    {
        return $contact->user_id == $user->id;
    }

}
