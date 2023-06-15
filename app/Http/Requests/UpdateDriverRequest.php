<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDriverRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'driver_id'           => ['required' ,'integer', 'exists:drivers,id'],
            'fullname'            => ['required', 'string'],
            'phone'               => ['required', 'string'],
            'license_number'      => ['required', 'string'],
            'license_expire_date' => ['required', 'date'],
            'license_file' =>  ['nullable','mimes:jpg,jpeg,png,doc,docx,ppt,pptx,pdf']
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message' => __('messages.validation_message'),
            'errors'      => $validator->errors()
        ]));
    }
}
