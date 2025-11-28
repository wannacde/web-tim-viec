<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:1024'],
        ];

        // Add employer-specific validation
        if ($this->user()->role === 'employer') {
            $rules = array_merge($rules, [
                'company_name' => ['required', 'string', 'max:255'],
                'company_website' => ['nullable', 'url', 'max:255'],
                'company_address' => ['nullable', 'string', 'max:255'],
                'company_description' => ['nullable', 'string', 'max:2000'],
                'company_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:1024'],
            ]);
        }

        // Add student-specific validation
        if ($this->user()->role === 'student') {
            $rules = array_merge($rules, [
                'headline' => ['nullable', 'string', 'max:255'],
                'bio' => ['nullable', 'string', 'max:1000'],
            ]);
        }

        return $rules;
    }
}
