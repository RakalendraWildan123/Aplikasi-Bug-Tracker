<?php

namespace App\Http\Requests\Admin;

use App\Models\Developer;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdmin();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var User $targetUser */
        $targetUser = $this->route('user');
        $isDeveloper = $this->input('role') === 'developer';
        $developerId = $targetUser->developer?->id;

        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => [
                'required',
                'string',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($targetUser->id),
                Rule::when($isDeveloper || $developerId !== null, [
                    Rule::unique('developers', 'email')->ignore($developerId),
                ]),
            ],
            'role' => ['required', Rule::in(User::ROLES)],
            'is_active' => ['sometimes', 'boolean'],
            'role_title' => [
                Rule::requiredIf($isDeveloper),
                'nullable',
                Rule::in(Developer::ROLES),
            ],
            'avatar' => ['nullable', 'string', 'max:10'],
            'avatar_bg' => ['nullable', 'string', 'max:50'],
        ];
    }
}
