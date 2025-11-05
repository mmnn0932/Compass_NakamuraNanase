<x-sidebar>
  <div class="vh-100 d-flex align-items-start justify-content-center">
    <div class="w-100 pt-3 px-3">
      <div class="w-75 reserve-offset">
    <p class="detail-title mb-2">
    {{ \Carbon\Carbon::parse($date)->format('Y年n月j日') }}
    <span class="ml-3">{{ [1=>'1部',2=>'2部',3=>'3部'][(int)$part] }}</span>
    </p>
      @php
      $setting = $reservePersons->first();
      $users = $setting ? $setting->users : collect();
      @endphp
      <div class="detail-card">
      <div class="table-responsive">
      <table class="table reserve-table reserve-table--compact mb-0">
      <caption class="sr-only">
      {{ \Carbon\Carbon::parse($date)->format('Y年n月j日') }}
      {{ [1=>'1部',2=>'2部',3=>'3部'][(int)$part] }}
      </caption>




      <thead class="text-center">
        <tr>
          <th class="col-id">ID</th>
          <th class="col-name">名前</th>
          <th class="col-place">場所</th>
        </tr>
      </thead>
      <tbody class="text-center">
        @forelse($users as $user)
      <tr>
          <td>{{ $user->id }}</td>
          <td>{{ $user->over_name }} {{ $user->under_name }}</td>
          <td>リモート</td>
        </tr>
      @empty
       @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>
</x-sidebar>
