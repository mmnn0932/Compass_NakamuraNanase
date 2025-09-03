<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use DB;

use App\Models\Users\Subjects;
use App\Models\Users\User;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $subjects = Subjects::all();
        return view('auth.register.register', compact('subjects'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
    $rules = [
        'over_name'        => ['required','string','max:10'],
        'under_name'       => ['required','string','max:10'],
        'over_name_kana'   => ['required','string','regex:/\A[ァ-ヶー]+\z/u','max:30'],
        'under_name_kana'  => ['required','string','regex:/\A[ァ-ヶー]+\z/u','max:30'],
        'mail_address'     => ['required','email','max:100','unique:users,mail_address'],
        'sex'              => ['required','in:1,2,3'],

        // 0埋め対応のため month/day は integer を外す
        'old_year'         => ['required','not_in:none','integer','between:2000,'.date('Y')],
        'old_month'        => ['required','not_in:none'],
        'old_day'          => ['required','not_in:none'],

        'role'             => ['required','in:1,2,3,4'],
        'password'         => ['required','min:8','max:30','confirmed'],
    ];

    $messages = [
        'over_name.required'       => '姓が未入力です。',
        'over_name.max'            => '姓は10文字以下で入力してください。',
        'under_name.required'      => '名が未入力です。',
        'under_name.max'           => '名は10文字以下で入力してください。',
        'over_name_kana.required'  => 'セイが未入力です。',
        'over_name_kana.regex'     => 'セイはカタカナで入力してください。',
        'over_name_kana.max'       => 'セイは30文字以下で入力してください。',
        'under_name_kana.required' => 'メイが未入力です。',
        'under_name_kana.regex'    => 'メイはカタカナで入力してください。',
        'under_name_kana.max'      => 'メイは30文字以下で入力してください。',
        'mail_address.required'    => 'メールアドレスが未入力です。',
        'mail_address.email'       => 'メールアドレスの形式が正しくありません。',
        'mail_address.unique'      => 'このメールアドレスは既に登録されています。',
        'mail_address.max'         => 'メールアドレスは100文字以下で入力してください。',
        'sex.required'             => '性別が未選択です。',
        'role.required'            => '役職が未選択です。',
        'old_year.required'        => '生年月日が未入力です。',
        'old_month.required'       => '生年月日が未入力です。',
        'old_day.required'         => '生年月日が未入力です。',
        'old_year.not_in'          => '生年月日が未入力です。',
        'old_month.not_in'         => '生年月日が未入力です。',
        'old_day.not_in'           => '生年月日が未入力です。',
        'old_year.between'         => '生年は2000年から現在の年までを選択してください。',
        'password.required'        => 'パスワードが未入力です。',
        'password.min'             => 'パスワードは8文字以上で入力してください。',
        'password.max'             => 'パスワードは30文字以下で入力してください。',
        'password.confirmed'       => 'パスワード（確認）が一致しません。',
    ];

    $attributes = [
        'over_name' => '姓', 'under_name' => '名',
        'over_name_kana' => 'セイ', 'under_name_kana' => 'メイ',
        'mail_address' => 'メールアドレス', 'sex' => '性別',
        'old_year' => '生年', 'old_month' => '生月', 'old_day' => '生日',
        'role' => '権限', 'password' => 'パスワード',
        'password_confirmation' => 'パスワード（確認）',
    ];

    // まず通常のバリデーション
    $validator = Validator::make($request->all(), $rules, $messages, $attributes);

    // 日付の整合性チェックを「birthday」キーで追加
    $validator->after(function ($v) use ($request) {
        // 3つの必須エラーが出ている場合は重ねて出さない
        if ($v->errors()->has('old_year') || $v->errors()->has('old_month') || $v->errors()->has('old_day')) {
            return;
        }

        $y = (int)$request->old_year;
        $m = (int)$request->old_month;
        $d = (int)$request->old_day;

        // 実在しない日付
        if (!checkdate($m, $d, $y)) {
            $v->errors()->add('birthday', '存在しない日付です。');
            return;
        }

    });

    // 失敗時は自動で back() + $errors + old() にリダイレクト
    $validator->validate();

    // ここまで来たら日付は正しい
    $y = (int)$request->old_year;
    $m = (int)$request->old_month;
    $d = (int)$request->old_day;
    $birth_day = sprintf('%04d-%02d-%02d', $y, $m, $d);

    DB::beginTransaction();
    try {
        $user_get = User::create([
            'over_name'       => $request->over_name,
            'under_name'      => $request->under_name,
            'over_name_kana'  => $request->over_name_kana,
            'under_name_kana' => $request->under_name_kana,
            'mail_address'    => $request->mail_address,
            'sex'             => $request->sex,
            'birth_day'       => $birth_day,
            'role'            => $request->role,
            'password'        => bcrypt($request->password),
        ]);

        if ((int)$request->role === 4 && !empty($request->subject)) {
            $user_get->subjects()->attach($request->subject);
        }

        DB::commit();
        return view('auth.login.login');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('loginView');
    }
}
}
