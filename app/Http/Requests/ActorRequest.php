<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActorRequest extends FormRequest
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
            'first_name'  => 'required|string',
            'middle_name' => 'string|nullable',
            'last_name'   => 'required|string',
            'dob'         => 'date_format:Y-m-d|nullable',
            'biography'   => 'string|nullable',
        ];
    }
}
