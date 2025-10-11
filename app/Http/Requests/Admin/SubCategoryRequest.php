<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SubCategoryRequest extends FormRequest
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
            'main_category_id'  => ['required', 'integer', 'exists:main_categories,id'],
            'sub_category_name' => ['required', 'string', 'max:100', 'unique:sub_categories,sub_category']
        ];
    }

    public function messages(): array
    {
        return [
            'main_category_id.required'   => 'メインカテゴリーを選択してください。',
            'main_category_id.integer'    => 'メインカテゴリーの指定が不正です。',
            'main_category_id.exists'     => '選択したメインカテゴリーが存在しません。',
            'sub_category_name.required'  => 'サブカテゴリー名は必須です。',
            'sub_category_name.string'    => 'サブカテゴリー名は文字列で入力してください。',
            'sub_category_name.max'       => 'サブカテゴリー名は100文字以内で入力してください。',
            'sub_category_name.unique'   => '同じ名前のサブカテゴリーは登録できません。',
        ];
    }

}
