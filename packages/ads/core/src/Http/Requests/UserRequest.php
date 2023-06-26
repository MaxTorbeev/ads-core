<?php

namespace Ads\Core\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'parent_id' => 'nullable|integer',
            'email' => 'nullable|email',
            'login' => 'nullable|string',
            'password' => 'required|string',
        ];
    }
}
