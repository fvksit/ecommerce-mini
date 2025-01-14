<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:1|max:1000000000000',
            'stock' => 'required|integer|min:1|max:1000000000000',
            'images' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    $totalSize = 0;

                    foreach ($value as $image) {
                        $totalSize += $image->getSize();
                    }

                    if ($totalSize > 2 * 1024 * 1024) {
                        $fail('The total size of images must not exceed 2MB.');
                    }
                }
            ],
            'images.*' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'images_size' => 'array',
            'images_size.*' => 'required|integer|min:1',
        ];
    }
}