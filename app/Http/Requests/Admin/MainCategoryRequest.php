<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MainCategoryRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'main_category_name' => ['required', 'string', 'max:50'],
        ];
    }
    public function messages(): array
    {
        return [
            'main_category_name.required' => 'メインカテゴリー名は必須です。',
            'main_category_name.string'   => 'メインカテゴリー名は文字列で入力してください。',
            'main_category_name.max'      => 'メインカテゴリー名は50文字以内で入力してください。',
        ];
    }
}
