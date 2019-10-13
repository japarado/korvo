<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Config;

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
            'name' => 'required',
            'academic_year' => 'required',
            'status' => [
                'required',
                'numeric',
                Rule::in([
                    Config::get('constants.event_status.draft'),
                    Config::get('constants.event_status.socc_approval'),
                ]),
            ],
            'read_status' => [
                'numeric',
                Rule::in([
                    Config::get('constants.read_status.unread'),
                    Config::get('constants.read_status.read'),
                ]),
            ],
            'ereserve_id' => [
                'required',
                'digits:5',
                'numeric',
            ],
            'date_start' => 'required|date'
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors()));
    }
}
