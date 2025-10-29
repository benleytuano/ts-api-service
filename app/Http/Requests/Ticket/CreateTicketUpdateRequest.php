<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\TicketUpdate;

class CreateTicketUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled in controller/service
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'message' => 'required|string',
            'type' => 'nullable|in:comment,status_change,assignment,internal_note',
            'is_internal' => 'nullable|boolean',
            'old_value' => 'nullable|string|max:255',
            'new_value' => 'nullable|string|max:255',
        ];
    }
}

