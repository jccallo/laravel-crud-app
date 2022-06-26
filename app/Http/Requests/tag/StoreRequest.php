<?php

namespace App\Http\Requests\tag;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:45', Rule::unique('tags', 'name')],
            'description' => ['sometimes', 'nullable', 'string', 'max:90' ], // ['sometimes', 'nullable'] si se manda o no, o si se manda null
        ];
    }
}
