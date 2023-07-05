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
            'login' => 'required_without_all:email,phone|nullable|string',
            'email' => 'required_without_all:login,phone|nullable|email',
            'phone' => 'required_without_all:login,email|nullable|string',
            'password' => 'required|string'
        ];
    }
}
