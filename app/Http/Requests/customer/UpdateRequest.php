<?php

namespace App\Http\Requests\customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:45'],
            'last_name' => ['required', 'string', 'max:45'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:30', Rule::unique('customers', 'phone')->ignore($this->customer->id, 'id')],
            'avatar' => 'sometimes|nullable|mimes:jpeg,png,svg|max:1024',
        ];
    }
}
