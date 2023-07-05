<?php

namespace Ads\Core\Http\Requests;

use Ads\Core\Services\User\AuthService;
use Ads\Core\Support\Number;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

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
        $rules = [
            'email' => 'nullable|email|unique:users,email,NULL,id,deleted_at,NULL',
            'phone' => 'nullable|string|unique:users,phone,NULL,id,deleted_at,NULL',
            'login' => 'nullable|string|unique:users,login,NULL,id,deleted_at,NULL',
            'name' => 'required|string',
            'password' => 'required|confirmed|min:6',
        ];

        if ($authService->user()->hasPermission('can_create_main_user')) {
            $rules['parent_id'] = 'nullable|integer';
        }

        if (request()->isMethod('PATCH')) {
            $rules['password'] = 'nullable|confirmed|min:6';
            $rules['name'] = 'nullable|string';
        }

        return $rules;
    }
}
