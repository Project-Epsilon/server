<?php

namespace App\Transformers;

use App\Contact;
use League\Fractal\TransformerAbstract;

class ContactTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Contact $contact)
    {
        return [
            'id' => $contact->id,
            'user_id' => $contact->user_id,
            'name' => $contact->name,
            'phone_number' => $contact->phone_number,
            'email' => $contact->email
        ];
    }
}
