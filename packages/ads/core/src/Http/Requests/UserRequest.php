<?php

namespace Ads\Core\Http\Requests;

use Ads\Core\Services\User\AuthService;
use Ads\Core\Support\Number;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    private AuthService $authService;

    public function prepareForValidation()
    {
        if ($this->request->has('phone')) {
            request()->merge([
                'phone' => Number::onlyNumbers($this->request->get('phone')),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(AuthService $authService): array
    {
        $user = $this->user();

        $rules = [
            'email' => ['nullable', 'email', Rule::unique('users', 'email')->ignore($this->user)->whereNull('deleted_at')],
            'phone' => ['nullable', 'string', Rule::unique('users', 'phone')->ignore($this->user)->whereNull('deleted_at')],
            'phone' => ['nullable', 'string', Rule::unique('users', 'login')->ignore($this->user)->whereNull('deleted_at')],
            'name' => 'required|string',
            'password' => 'required|confirmed|min:6',
        ];

        if ($authService->user()->hasPermission('user_create')) {
            $rules['parent_id'] = 'nullable|integer';
            $rules['role_id'] = 'nullable|integer';
        }

        if (request()->isMethod('PATCH') || request()->isMethod('PUT')) {
            $rules['password'] = 'nullable|confirmed|min:6';
            $rules['name'] = 'nullable|string';
        }

        return $rules;
    }
}
