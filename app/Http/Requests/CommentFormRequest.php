<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentFormRequest extends FormRequest
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
            'comment' => ['required', 'string', 'max:250'],
        ];
    }

    public function messages()
    {
        return [
            'comment.required' => 'コメントは必ず入力してください。',
            'comment.string'   => 'コメントは文字列で入力してください。',
            'comment.max'      => 'コメントは250文字以内で入力してください。',
        ];
    }
}
