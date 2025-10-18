<?php
namespace App\Searchs;

use App\Models\Users\User;

class SelectIds implements DisplayUsers{

  // 改修課題：選択科目の検索機能
  public function resultUsers($keyword, $category, $updown, $gender, $role, $subjects){
    $gender = empty($gender) ? ['1','2','3'] : (array)$gender;
    $role = empty($role) ? ['1','2','3','4'] : (array)$role;
    $updown = strtoupper($updown ?? 'ASC');
    return User::with('subjects')
    ->when($keyword !== null && $keyword !== '', function ($q) use ($keyword) {
    $q->where('id', 'like', "%{$keyword}%");
    })
    ->when(!empty($gender), fn($q) => $q->whereIn('sex', (array)$gender))
    ->when(!empty($role),   fn($q) => $q->whereIn('role', (array)$role))
    ->orderByRaw('CAST(users.id AS UNSIGNED) ' . ($updown === 'DESC' ? 'DESC' : 'ASC'))
    ->get();
    return $users;
  }

}
