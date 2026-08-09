<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCandidateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->guard('admin')->check();
    }

    public function rules(): array
    {
        $eventId = $this->input('event_id');

        return [
            'event_id' => ['required', 'exists:events,id'],
            'category_id' => [
                'nullable',
                // Critical Constraint Rule: Validasi kategori berasal dari event_id yang sama
                Rule::exists('event_categories', 'id')->where(function ($query) use ($eventId) {
                    $query->where('event_id', $eventId);
                }),
            ],
            'candidate_number' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'profile_photo' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'region' => ['nullable', 'string', 'max:100'],
            'biography' => ['nullable', 'string'],
            'social_media_url' => ['nullable', 'url', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['boolean'],
        ];
    }
}