<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CarCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() != null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:100'],
            'brand' => ['required', 'max:100'],
            'model' => ['required', 'max:100'],
            'year' => ['required', 'min:1'],
            'color' => ['required', 'max:100'],
            'image' => ['required', 'max:100'],
            'transmision' => ['required', 'in:AUTOMATIC,MANUAL'],
            'seat' => ['required', 'min:1'],
            'cost_per_day' => ['required', 'regex:/^\d+(\.\d{1,10})?$/'], // menampung hingga 10 decimal
            'location' => ['nullable'],
            'available' => ['required'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            "errors" => $validator->getMessageBag()
        ], 400));
    }
}
