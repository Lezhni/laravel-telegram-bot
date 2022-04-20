<?php

namespace App\Http\Requests\Api\Telegram\Node;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreUpdateRequest
 * @package App\Http\Requests\Api\Telegram\Node
 */
class StoreUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'text' => ['nullable', 'string', 'max:65000'],
            'links' => ['nullable', 'array'],
            'links.*.label' => ['nullable', 'string'],
            'links.*.link' => ['required', 'url'],
        ];
    }
}
