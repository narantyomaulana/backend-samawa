<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingTransactionRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'proof' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'price' => 'required|integer',
            'total_amount' => 'required|integer',
            'total_tax_amount' => 'required|integer',
            'started_at' => 'required|date',
            'wedding_package_id' => 'required|integer',
        ];
    }
}
