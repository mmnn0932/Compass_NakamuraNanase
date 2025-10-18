<?php
namespace App\Searchs;

use App\Models\Users\User;

class SelectNameDetails implements DisplayUsers{

  // 改修課題：選択科目の検索機能
  public function resultUsers($keyword, $category, $updown, $gender, $role, $subjects){
    $gender = empty($gender) ? ['1','2','3'] : (array)$gender;
    $role = empty($role) ? ['1','2','3','4'] : (array)$role;
    $subjects = (array)$subjects;
    $updown = strtoupper($updown ?? 'ASC');
    $updown = in_array($updown, ['ASC','DESC'], true) ? $updown : 'ASC';

    return User::with('subjects')
      ->when($keyword !== null && $keyword !== '', function ($q) use ($keyword){
      $q->where(function ($qq) use ($keyword) {
      $qq->where('over_name', 'like', "%{$keyword}%")
      ->orWhere('under_name', 'like', "%{$keyword}%")
      ->orWhere('over_name_kana', 'like', "%{$keyword}%")
      ->orWhere('under_name_kana', 'like', "%{$keyword}%");
        });
      })
      ->whereIn('sex',  $gender)
      ->whereIn('role', $role)
      ->when(!empty($subjects), function ($q) use ($subjects) {
      $q->whereHas('subjects', function ($qq) use ($subjects) {
      $qq->whereIn('subjects.id', $subjects);
        });
      })
      ->orderBy('id', $updown)
      ->get();
    }
  }
