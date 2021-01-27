<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
{

    /**
     * The key to be used for the view error bag.
     *
     * @var string
     */
    protected $errorBag = 'updateBookingRequest';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_replace_recursive((new CreateBookingRequest())->rules(), [

            'onsite_contact' => ['array'],
            'onsite_contact.name' => ['required_with:onsite_contact.phone,onsite_contact.email','string', 'max:255'],
            'onsite_contact.phone' => ['required_with:onsite_contact.name,onsite_contact.email','string'],
            'onsite_contact.email' => ['required_with:onsite_contact.name,onsite_contact.phone','email'],

            'event' => ['array'],
        ]);
    }
}
