<?php

namespace Ads\Cache\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CacheRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'user' => 'nullable|number',
            'entity' => 'nullable|string',
            'prefix' => 'nullable|string',
        ];
    }
}
