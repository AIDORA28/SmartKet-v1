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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre_negocio' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required','string','min:8','regex:/^(?=.*[A-Z])(?=.*\d).{8,}$/'],
            'rubro' => 'required|string|in:polleria',
        ];
    }
}
