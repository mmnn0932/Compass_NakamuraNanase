<?php
namespace App\Searchs;

use App\Models\Users\User;

class SelectIdDetails implements DisplayUsers{

  // 改修課題：選択科目の検索機能
  public function resultUsers($keyword, $category, $updown, $gender, $role, $subjects){
    $gender   = empty($gender) ? ['1','2','3']     : (array)$gender;
    $role     = empty($role)   ? ['1','2','3','4'] : (array)$role;
    $subjects = (array)$subjects;
    $updown   = strtoupper($updown ?? 'ASC');
    return User::with('subjects')
    ->when($keyword !== null && $keyword !== '', fn($q) => $q->where('id', 'like', "%{$keyword}%"))
    ->when(!empty($gender),   fn($q) => $q->whereIn('sex', (array)$gender))
    ->when(!empty($role),     fn($q) => $q->whereIn('role', (array)$role))
    ->when(!empty($subjects), fn($q) =>
    $q->whereHas('subjects', fn($qq) => $qq->whereIn('subjects.id', (array)$subjects))
    )
    ->orderBy('id', strtoupper($updown ?? 'ASC'))
  ->get();
  }
}
