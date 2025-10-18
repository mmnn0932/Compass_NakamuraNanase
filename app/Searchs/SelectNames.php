<?php
namespace App\Searchs;

use App\Models\Users\User;

class SelectNames implements DisplayUsers{

  public function resultUsers($keyword, $category, $updown, $gender, $role, $subjects){
    $gender = empty($gender) ? ['1','2','3']     : (array)$gender;
    $role   = empty($role)   ? ['1','2','3','4'] : (array)$role;

    $dir = strtoupper($updown ?? 'ASC');
    $dir = in_array($dir, ['ASC','DESC'], true) ? $dir : 'ASC';

    return User::with('subjects')
    ->when($keyword !== null && $keyword !== '', function ($q) use ($keyword) {
    $q->where(function ($qq) use ($keyword) {
    $qq->where('over_name', 'like', "%{$keyword}%")
    ->orWhere('under_name', 'like', "%{$keyword}%")
    ->orWhere('over_name_kana', 'like', "%{$keyword}%")
    ->orWhere('under_name_kana', 'like', "%{$keyword}%");
      });
    })
    ->whereIn('sex',  $gender)
    ->whereIn('role', $role)
    ->orderBy('id', $dir)
    ->get();
    }
}
