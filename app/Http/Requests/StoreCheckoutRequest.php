<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'candidate_id'    => ['required', 'integer', 'exists:candidates,id'],
            'vote_quantity'   => ['required', 'integer', 'min:1'],
            'voter_name'      => ['nullable', 'string', 'max:255'],
            'voter_phone'     => ['nullable', 'string', 'max:50'],
            'support_message' => ['nullable', 'string', 'max:500'],
            'payment_method'  => ['required', 'string', 'in:QRIS,VA_BCA,VA_MANDIRI'],
        ];
    }

    /**
     * Dapatkan data yang telah divalidasi lengkap dengan fallback default value.
     */
    public function validatedWithDefaults(): array
    {
        $validated = $this->validated();

        $voterName = !empty($validated['voter_name']) ? trim($validated['voter_name']) : 'Anonymous';
        $voterPhone = !empty($validated['voter_phone']) ? trim($validated['voter_phone']) : '-';

        return array_merge($validated, [
            'voter_name'   => $voterName,
            'voter_phone'  => $voterPhone,
            'is_anonymous' => empty($validated['voter_name']),
        ]);
    }
}