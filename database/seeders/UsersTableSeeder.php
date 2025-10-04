<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = now();

        $users = [
            [
                'over_name' => '山田', 'under_name' => '太郎',
                'over_name_kana' => 'ヤマダ', 'under_name_kana' => 'タロウ',
                'mail_address' => 'yamada_taro@gmail.com',
                'sex' => 1, 'birth_day' => '2001-01-01', 'role' => 1,
                'password' => Hash::make('yamadataro'),
                'remember_token' => null, 'created_at' => $now, 'updated_at' => $now, 'deleted_at' => null,
            ],
            [
                'over_name' => '佐藤', 'under_name' => '二郎',
                'over_name_kana' => 'サトウ', 'under_name_kana' => 'ジロウ',
                'mail_address' => 'sato_jiro@gmail.com',
                'sex' => 1, 'birth_day' => '2002-02-02', 'role' => 2,
                'password' => Hash::make('satojiro'),
                'remember_token' => null, 'created_at' => $now, 'updated_at' => $now, 'deleted_at' => null,
            ],
            [
                'over_name' => '鈴木', 'under_name' => '三郎',
                'over_name_kana' => 'スズキ', 'under_name_kana' => 'サブロウ',
                'mail_address' => 'suzuki_saburo@gmail.com',
                'sex' => 1, 'birth_day' => '2003-03-03', 'role' => 3,
                'password' => Hash::make('suzukisaburo'),
                'remember_token' => null, 'created_at' => $now, 'updated_at' => $now, 'deleted_at' => null,
            ],
            [
                'over_name' => '石田', 'under_name' => '四郎',
                'over_name_kana' => 'イシダ', 'under_name_kana' => 'シロウ',
                'mail_address' => 'ishida_shiro@gmail.com',
                'sex' => 1, 'birth_day' => '2004-04-04', 'role' => 1,
                'password' => Hash::make('ishidashiro'),
                'remember_token' => null, 'created_at' => $now, 'updated_at' => $now, 'deleted_at' => null,
            ],
            [
                'over_name' => '小田', 'under_name' => '五郎',
                'over_name_kana' => 'オダ', 'under_name_kana' => 'ゴロウ',
                'mail_address' => 'oda_goro@gmail.com',
                'sex' => 1, 'birth_day' => '2005-05-05', 'role' => 2,
                'password' => Hash::make('odagoro'),
                'remember_token' => null, 'created_at' => $now, 'updated_at' => $now, 'deleted_at' => null,
            ],
            [
                'over_name' => '林', 'under_name' => '愛子',
                'over_name_kana' => 'ハヤシ', 'under_name_kana' => 'アイコ',
                'mail_address' => 'hayashi_aiko@gmail.com',
                'sex' => 2, 'birth_day' => '2006-06-06', 'role' => 3,
                'password' => Hash::make('hayashiaiko'),
                'remember_token' => null, 'created_at' => $now, 'updated_at' => $now, 'deleted_at' => null,
            ],

            //生徒
            [
                'over_name' => '佐々木', 'under_name' => '花子',
                'over_name_kana' => 'ササキ', 'under_name_kana' => 'ハナコ',
                'mail_address' => 'sasaki_hanako@gmail.com',
                'sex' => 2, 'birth_day' => '2011-01-01', 'role' => 4,
                'password' => Hash::make('sasakihanako'),
                'remember_token' => null, 'created_at' => $now, 'updated_at' => $now, 'deleted_at' => null,
            ],
            [
                'over_name' => '高橋', 'under_name' => '太一',
                'over_name_kana' => 'タカハシ', 'under_name_kana' => 'タイチ',
                'mail_address' => 'takahashi_taichi@gmail.com',
                'sex' => 1, 'birth_day' => '2012-02-02', 'role' => 4,
                'password' => Hash::make('takahashitaichi'),
                'remember_token' => null, 'created_at' => $now, 'updated_at' => $now, 'deleted_at' => null,
            ],
            [
                'over_name' => '吉田', 'under_name' => '優子',
                'over_name_kana' => 'ヨシダ', 'under_name_kana' => 'ユウコ',
                'mail_address' => 'yoshida_yuko@gmail.com',
                'sex' => 3, 'birth_day' => '2013-03-03', 'role' => 4,
                'password' => Hash::make('yoshidayuko'),
                'remember_token' => null, 'created_at' => $now, 'updated_at' => $now, 'deleted_at' => null,
            ],
            [
                'over_name' => '野中', 'under_name' => '優介',
                'over_name_kana' => 'ノナカ', 'under_name_kana' => 'ユウスケ',
                'mail_address' => 'nonaka_yusuke@gmail.com',
                'sex' => 1, 'birth_day' => '2014-04-04', 'role' => 4,
                'password' => Hash::make('nonakayusuke'),
                'remember_token' => null, 'created_at' => $now, 'updated_at' => $now, 'deleted_at' => null,
            ],
            [
                'over_name' => '清水', 'under_name' => '紀子',
                'over_name_kana' => 'シミズ', 'under_name_kana' => 'ノリコ',
                'mail_address' => 'shimizu_noriko@gmail.com',
                'sex' => 2, 'birth_day' => '2015-05-05', 'role' => 4,
                'password' => Hash::make('shimizunoriko'),
                'remember_token' => null, 'created_at' => $now, 'updated_at' => $now, 'deleted_at' => null,
            ],
            [
                'over_name' => '岡田', 'under_name' => '康夫',
                'over_name_kana' => 'オカダ', 'under_name_kana' => 'ヤスオ',
                'mail_address' => 'okada_yasuo@gmail.com',
                'sex' => 3, 'birth_day' => '2011-11-11', 'role' => 4,
                'password' => Hash::make('okadayasuo'),
                'remember_token' => null, 'created_at' => $now, 'updated_at' => $now, 'deleted_at' => null,
            ],
        ];

        DB::table('users')->insertOrIgnore($users);
    }
}
