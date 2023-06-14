<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCarRequest extends FormRequest
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
            'car_id'               => ['required' ,'integer', 'exists:cars,id'],
            'description'            => ['required', 'string'],
            'model'                 => ['required', 'string'],
            'year'                  => ['required', 'string'],
            'color'                 => ['required', 'string'],
            'lat'                   => ['nullable', ' numeric'],
            'long'                  => ['nullable', ' numeric'],
            'day_price'             => ['required', ' numeric'],
            'km'                    => ['nullable', 'numeric'],
            'registration'          => ['nullable', 'string'],
            'address'               => ['required', 'string'],
            'is_manuel'             => ['required', 'boolean'],
            
            'mark_id'               => ['required' ,'integer', 'exists:mark_cars,id'],
            'category_id'           => ['required' ,'integer', 'exists:category_cars,id'],
            'type_id'               => ['required' ,'integer', 'exists:type_cars,id'],
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
