<?php

namespace App\Http\Requests\Admin;

use App\Models\Developer;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAccountRequest extends FormRequest
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
        $isDeveloper = $this->input('role') === 'developer';

        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => [
                'required',
                'string',
                'email',
                'max:150',
                Rule::unique('users', 'email'),
                Rule::when($isDeveloper, [Rule::unique('developers', 'email')]),
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
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
