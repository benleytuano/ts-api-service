<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'email' => 'nullable|email|unique:users,email,' . auth()->id() . '|max:255',
            'password' => 'nullable|string|min:8',
            'password_confirmation' => 'nullable|same:password',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'This email is already in use.',
            'email.email' => 'Please provide a valid email address.',
            'password.min' => 'Password must be at least 8 characters.',
            'password_confirmation.same' => 'Password confirmation does not match.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // At least one field (email or password) must be provided
        if (!$this->filled('email') && !$this->filled('password')) {
            $this->merge([
                'validation_error' => 'At least email or password must be provided.',
            ]);
        }
    }

    /**
     * Get the failed validation response.
     */
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        // Check if at least one field is provided
        if (!$this->filled('email') && !$this->filled('password')) {
            $validator->errors()->add('fields', 'At least email or password must be provided.');
        }

        parent::failedValidation($validator);
    }
}

