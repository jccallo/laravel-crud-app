<?php

namespace App\Http\Requests\product;

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
            'name' => ['required', 'string', 'max:45'],
            'description' => ['sometimes', 'nullable', 'string', 'max:90'], // ['sometimes', 'nullable'] si se manda o no, o si se manda null
            'price' => ['required', 'numeric'],
            'stock' => ['required', 'integer'],
            'image' => 'sometimes|nullable|mimes:jpeg,png,svg|max:1024', // validacion en una sola linea
            'brand_id' => ['required', 'integer', Rule::exists('brands', 'id')],
        ];
    }
}
