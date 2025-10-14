<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterUserRequest extends FormRequest
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

    protected function prepareForValidation(): void
{
    $y = $this->input('old_year');
    $m = $this->input('old_month');
    $d = $this->input('old_day');

    if (ctype_digit((string)$y) && ctype_digit((string)$m) && ctype_digit((string)$d)) {
        $this->merge([
            'birth_day' => sprintf('%04d-%02d-%02d', (int)$y, (int)$m, (int)$d),
        ]);
    }
}

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'over_name'       => ['required','string','max:10'],
            'under_name'      => ['required','string','max:10'],
            'over_name_kana'  => ['required','string','regex:/\A[ァ-ヶー]+\z/u','max:30'],
            'under_name_kana' => ['required','string','regex:/\A[ァ-ヶー]+\z/u','max:30'],

            'mail_address'    => ['required','email','max:100','unique:users,mail_address'],
            'sex'             => ['required', Rule::in([1,2,3])],

             'old_year'  => ['required','not_in:none','integer','between:2000,'.date('Y')],
        'old_month' => ['required','not_in:none'],
        'old_day'   => ['required','not_in:none'],

        'birth_day' => ['nullable','date_format:Y-m-d','before:today'],

            'role'            => ['required', Rule::in([1,2,3,4])],
            'password'        => ['required','min:8','max:30','confirmed'],
        ];
    }
     public function messages(): array
    {
        return [
            'over_name.required'       => '姓は必ず入力してください。',
            'over_name.max'            => '姓は10文字以下で入力してください。',
            'under_name.required'      => '名は必ず入力してください。',
            'under_name.max'           => '名は10文字以下で入力してください。',
            'over_name_kana.required'  => 'セイは必ず入力してください。',
            'over_name_kana.regex'     => 'セイはカタカナで入力してください。',
            'over_name_kana.max'       => 'セイは30文字以下で入力してください。',
            'under_name_kana.required' => 'メイは必ず入力してください。',
            'under_name_kana.regex'    => 'メイはカタカナで入力してください。',
            'under_name_kana.max'      => 'メイは30文字以下で入力してください。',
            'mail_address.required'    => 'メールアドレスは必ず入力してください。',
            'mail_address.email'       => 'メールアドレスの形式が正しくありません。',
            'mail_address.unique'      => 'このメールアドレスは既に登録されています。',
            'mail_address.max'         => 'メールアドレスは100文字以下で入力してください。',
            'sex.required'             => '性別が未選択です。',
            'role.required'            => '役職が未選択です。',
            'old_year.required'        => '生年月日が未選択です。',
            'old_month.required'       => '生年月日が未選択です。',
            'old_day.required'         => '生年月日が未選択です。',
            'old_year.not_in'          => '生年月日が未選択です。',
            'old_month.not_in'         => '生年月日が未選択です。',
            'old_day.not_in'           => '生年月日が未選択です。',
            'old_year.between'         => '生年は2000年から現在の年までを選択してください。',
             'birth_day.date_format' => '存在しない日付です。',
            'birth_day.before'      => '生年月日は本日より前の日付を指定してください。',
            'password.required'        => 'パスワードは必ず入力してください。',
            'password.min'             => 'パスワードは8文字以上で入力してください。',
            'password.max'             => 'パスワードは30文字以下で入力してください。',
            'password.confirmed'       => 'パスワード（確認）が一致しません。',
        ];
    }
}
