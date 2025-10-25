<x-sidebar>
<div class="vh-100 d-flex" style="align-items:center; justify-content:center;">
  <div class="w-50 m-auto h-75">
      <p class="mb-3">
        <span class="ml-2">{{ $date }}</span>
        <span class="ml-2">{{ [1=>'1部',2=>'2部',3=>'3部'][(int)$part]}}</span>
      </p>

    @php
      $setting = $reservePersons->first();
      $users   = $setting ? $setting->users : collect();
    @endphp

    <div class="h-75 border">
      <table class="">
      <thead class="text-center">
        <tr>
          <th class="w-25">ID</th>
          <th class="w-25">名前</th>
          <th class="w-25">場所</th>
        </tr>
      </thead>
      @if($users->isNotEmpty())
        <tbody class="text-center">
        @foreach($users as $user)
        <tr>
          <td>{{ $user->id }}</td>
          <td>{{ $user->over_name }} {{ $user->under_name }}</td>
          <td>リモート</td>
        </tr>
      @endforeach
        </tbody>
      @endif
      </table>
    </div>
  </div>
</div>
</x-sidebar>
