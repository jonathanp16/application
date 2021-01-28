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
        return [

            'onsite_contact' => ['array'],
            'onsite_contact.name' => ['required_with:onsite_contact.phone,onsite_contact.email','string', 'max:255'],
            'onsite_contact.phone' => ['required_with:onsite_contact.name,onsite_contact.email','string'],
            'onsite_contact.email' => ['required_with:onsite_contact.name,onsite_contact.phone','email'],

            'event' => ['array'],
            'event.start_time' => [
                'required',
                'date_format:H:i',
            ],

            'event.end_time' => [
                'required',
                'date_format:H:i',
                'after:event.start_time',
            ],
            'event.title' => ['required','string', 'max:100'],
            'event.type' => ['required','string', 'max:50'],
            'event.description' => ['required','string', 'max:500'],
            'event.guest_speakers' => ['required','string', 'max:255'],
            'event.attendees' => ['required','numeric'],

            'event.fee' => ['string', 'max:50'],
            'event.music' => ['string', 'max:75'],
            'event.food.low_risk' => ['boolean'],
            'event.food.high_risk' => ['boolean'],
            'event.food.self_catered' => ['required_if:event.food.high_risk,true','boolean'],
            'event.food.caterer' => ['required_if:event.food.self_catered,false','string', 'max:100'],
            'event.alcohol' => ['boolean'],
            // remember open states of questions
            'event.show.contact' => ['boolean'],
            'event.show.fee' => ['boolean'],
            'event.show.music' => ['boolean'],

            'event.children' => ['boolean'],
            'event.appliances' => ['boolean'],
            'event.av' => ['boolean'],
            'event.furniture' => ['boolean'],
            'event.bake_sale' => ['boolean'],
            'event.internal_meeting' => ['boolean'],

            'notes' => ['nullable','string', 'max:500'],
            'files' => [
                'required_if:event.food.high_risk,true',
                'required_if:event.alcohol,true',
                'required_if:event.children,true',
                'required_if:event.appliances,true',
                'required_if:event.bake_sale,true',
                //'mimes:pdf,doc,docx',
                'max:50000', // 50Mb
            ],
        ];
    }
}
