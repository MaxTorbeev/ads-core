<?php

namespace Ads\Core\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'login' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'password' => 'required|string'
        ];
    }
}
