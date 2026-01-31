<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Permitir todas las solicitudes, ya que es una ruta pública
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            response()->json(['errors' => $validator->errors()], 422)
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'business_name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required','string','min:8','regex:/^(?=.*[A-Z])(?=.*\d).{8,}$/'],
            'business_type' => 'required|string|in:polleria,minimarket,restaurante,farmacia,horeca,retail,service',
        ];
    }
}
