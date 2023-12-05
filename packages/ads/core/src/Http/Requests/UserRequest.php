<?php

namespace Ads\Core\Http\Requests;

use Ads\Core\Models\Role;
use Ads\Core\Services\User\AuthService;
use Ads\Core\Support\Number;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
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
            'email' => ['nullable', 'email', Rule::unique('users', 'email')->ignore($this->user)->whereNull('deleted_at')],
            'phone' => ['nullable', 'string', Rule::unique('users', 'phone')->ignore($this->user)->whereNull('deleted_at')],
            'login' => ['nullable', 'string', Rule::unique('users', 'login')->ignore($this->user)->whereNull('deleted_at')],
            'name' => 'required|string',
            'is_active' => 'nullable|boolean',
            'password' => 'required|confirmed|min:6',
        ];

        // Пользователь с permission user_create может указывать parent_id и role_id
        if ($authService->user()->hasPermission('user_create')) {
            $rules['parent_id'] = ['nullable', 'integer'];

            // Если не передан parent_id (создается главный пользователь), то ему обязательно указать роль.
            if (!request()->input('parent_id')) {
                $rules = $this->rolesRule($rules);
            }
        }

        if (request()->isMethod('PATCH') || request()->isMethod('PUT')) {
            $rules['password'] = 'nullable|confirmed|min:6';
            $rules['name'] = 'nullable|string';
        }

        return $rules;
    }

    /**
     * Метод указывает что поле roles обязательно и массив.
     * В себе может содержать идентификаторы и имена ролей.
     *
     * @param array $rules
     * @return array
     */
    private function rolesRule(array $rules): array
    {
        $roles = Role::query()->select('id', 'name')->get();

        $rules['roles'] = 'required|array';
        $rules['roles.*'] = Rule::in(
            array_merge(
                $roles->pluck('id')->toArray(),
                $roles->pluck('name')->toArray(),
            )
        );

        return $rules;
    }
}
