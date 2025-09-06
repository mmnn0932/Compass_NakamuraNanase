<x-sidebar>
<div class="vh-100 d-flex" style="align-items:center; justify-content:center;">
  <div class="w-50 m-auto h-75">
    @php
      // 部の表示ラベル
      $partLabelMap = [1 => '1部', 2 => '2部', 3 => '3部'];
      $partLabel = $partLabelMap[(int)$part] ?? ($part . '部');
    @endphp

    <p>
      <span>{{ $date }}</span>
      <span class="ml-3">{{ $partLabel }}</span>
    </p>
    <div class="h-75 border">
      <table class="">
        <tr class="text-center">
          <th class="w-25">ID</th>
          <th class="w-25">名前</th>
          <th class="w-25">場所</th>
        </tr>
        <tr class="text-center">
          @foreach($reservePersons as $reserve)
    @foreach($reserve->users as $u)
      <tr class="text-center">
        <td class="w-25">{{ $u->id }}</td>
        <td class="w-50">{{ $u->over_name }} {{ $u->under_name }}</td>
        <td class="w-25">リモート</td>
      </tr>
            @endforeach
           @endforeach
        </tr>
      </table>
    </div>
  </div>
</div>
</x-sidebar>
