<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Zero Auth Wall for Voters!
    }

    public function rules(): array
    {
        return [
            'candidate_id' => ['required', 'exists:candidates,id'],
            'vote_quantity' => ['required', 'integer', 'min:1', 'max:10000'],
            'voter_name' => ['required', 'string', 'max:255'],
            'voter_phone' => ['required', 'string', 'max:20'],
            'support_message' => ['nullable', 'string', 'max:500'],
            'is_anonymous' => ['nullable', 'boolean'],
            'payment_method' => ['required', 'string', 'in:QRIS,VA_BCA,VA_MANDIRI,EWALLET'],
        ];
    }
}