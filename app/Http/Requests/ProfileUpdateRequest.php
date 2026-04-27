<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'contact_no' => ['nullable', 'string', 'max:20'],
            'relationship_id' => ['nullable', 'integer', 'exists:relationships,id'],
            'occupation_id' => ['nullable', 'integer', 'exists:occupations,id'],
            'tech_id' => ['nullable', 'integer', 'exists:technologies,id'],
            'gender' => ['nullable', 'string', 'max:10'],
            'dob' => ['nullable', 'string', 'max:20'],

            'present_address_detail' => ['nullable', 'string', 'max:500'],
            'permanent_address_details' => ['nullable', 'string', 'max:500'],
            'union_name' => ['nullable', 'string', 'max:100'],

            'employer_name' => ['nullable', 'string', 'max:300'],
            'designation' => ['nullable', 'string', 'max:200'],
            'office_address_details' => ['nullable', 'string', 'max:500'],

            'latest_degree_name' => ['nullable', 'string', 'max:100'],
            'latest_institute_name' => ['nullable', 'string', 'max:300'],

            'emergency_contact_name' => ['nullable', 'string', 'max:200'],
            'emergency_contact_no' => ['nullable', 'string', 'max:20'],
            'profile_image' => ['nullable', 'image', 'max:2048'],
            'signature' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
