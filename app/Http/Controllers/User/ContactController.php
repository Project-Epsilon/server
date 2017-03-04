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
    public function index(Request $request)
    {
        $contacts = $request->user()->contacts;

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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Contact $contact
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy($id, Request $request)
    {
        $contact = Contact::find(1);

        if(! $contact || ! $this->canEditContact($contact, $request->user())){
            return $this->sendErrorResponse();
        }

        $contact->delete();

        return response('ok');
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
            'phone_number' => 'sometimes',
            'email' => 'sometimes|required|email'
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

    /**
     * Sends the not found contact error response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendErrorResponse()
    {
        return response()->json([
            'errors' => [
                'message' => 'Contact not found.'
            ]
        ]);
    }

}
