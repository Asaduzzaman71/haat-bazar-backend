<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'name' => 'required |max:255',
            'category_id' => 'required',
            'unit_price' => 'required',
            'no_of_unit_in_stock' => 'integer|required',
            'description' => 'required|max:2000',
            'images'    => 'required|sometimes|array|min:1|max:5',
            'images.*' => 'required|mimetypes:image/jpeg,image/png,image/bmp|max:2000',
        ];
    }
    public function messages()
    {
        return [
            'category_id.required' => 'Category field is required',
            'images.required' => 'Please upload one or more images',
            'images.max' => 'A maximum of five images are allowed',
            'images.*.mimetypes' => 'Only jpeg,png and bmp images are allowed',
            'images.*.max' => 'Sorry! Maximum allowed size for an image is 2MB',
        ];
    }
}
