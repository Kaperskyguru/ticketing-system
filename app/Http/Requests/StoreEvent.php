<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvent extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['bail', 'string', 'required'],
            'description' => ['bail', 'string', 'required'],
            'ticket_price' => ['bail', 'float', 'required'],
            'event_date' => ['bail', 'date', 'required', 'after_or_equal:today'],
        ];
    }
}
