<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [

            // I want to check the name if exists first
            // If exists, will pass the name for unique validation
            // This is efficient, rather than we creating another validation for create or update
            'name' => request()->route('category')
                ? 'required|max:255|unique:categories' . request()->route('category')
                : 'required|max:255|unique:categories',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Category name is required',
        ];
    }
}
