<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEvent extends FormRequest
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
            'description' => ['bail', 'string', 'nullable'],
            'ticket_price' => ['bail', 'numeric', 'required'],
            'date' => ['bail', 'date', 'required', 'after_or_equal:today'],
        ];
    }
}
