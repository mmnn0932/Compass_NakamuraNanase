<x-sidebar>
<div class="search_content w-100 border d-flex">
  <div class="reserve_users_area">
    @foreach($users as $user)
    <div class="border one_person">
      <div>
        <span>ID : </span><span>{{ $user->id }}</span>
      </div>
      <div><span>名前 : </span>
        <a href="{{ route('user.profile', ['id' => $user->id]) }}">
          <span>{{ $user->over_name }}</span>
          <span>{{ $user->under_name }}</span>
        </a>
      </div>
      <div>
        <span>カナ : </span>
        <span>({{ $user->over_name_kana }}</span>
        <span>{{ $user->under_name_kana }})</span>
      </div>
      <div>
        @if($user->sex == 1)
        <span>性別 : </span><span>男</span>
        @elseif($user->sex == 2)
        <span>性別 : </span><span>女</span>
        @else
        <span>性別 : </span><span>その他</span>
        @endif
      </div>
      <div>
        <span>生年月日 : </span><span>{{ $user->birth_day }}</span>
      </div>
      <div>
        @if($user->role == 1)
        <span>権限 : </span><span>教師(国語)</span>
        @elseif($user->role == 2)
        <span>権限 : </span><span>教師(数学)</span>
        @elseif($user->role == 3)
        <span>権限 : </span><span>講師(英語)</span>
        @else
        <span>権限 : </span><span>生徒</span>
        @endif
      </div>
      <div>
        @if($user->role == 4)
        <span>選択科目 :</span>
        @if($user->subjects->isNotEmpty())
        <span>{{ $user->subjects->pluck('subject')->implode('、')}}</span>
        @endif
        @endif
      </div>
    </div>
    @endforeach
  </div>
  <div class="search_area w-25">
    <div class="search-title">検索</div>
    <div class="mb-2">
      <input id="keywordInput" type="text" class="form-surface" name="keyword"
       placeholder="キーワードを検索" form="userSearchRequest">
    <div>
      <div class="mb-2">
      <label class="form-label">カテゴリ</label>
        <select id="categorySelect" class="form-surface" form="userSearchRequest" name="category">
          <option value="name">名前</option>
          <option value="id">社員ID</option>
        </select>
      </div>
      <div class="mb-2">
        <label class="form-label">並び替え</label>
        <select id="orderSelect" class="form-surface" name="updown" form="userSearchRequest">
          <option value="ASC">昇順</option>
          <option value="DESC">降順</option>
        </select>
      </div>
      <button type="button" class="cond-toggle underline" id="condToggleBtn" aria-expanded="false">
        <span>検索条件の追加</span>
        <i class="fa-solid fa-chevron-down chevron" aria-hidden="true"></i>
      </button>
      <div class="search_conditions_inner" id="condPanel">
      <div class="mb-2">
            <label class="form-label">性別</label>
            <div class="radio-row">
            <label><input type="radio" name="sex" value="1" form="userSearchRequest"> 男</label>
            <label><input type="radio" name="sex" value="2" form="userSearchRequest"> 女</label>
            <label><input type="radio" name="sex" value="3" form="userSearchRequest"> その他</label>
          </div>
          </div>

          <div class="mb-2">
            <label class="form-label">権限</label>
            <select id="roleSelect" name="role" form="userSearchRequest" class="form-surface">
              <option selected disabled>----</option>
              <option value="1">教師(国語)</option>
              <option value="2">教師(数学)</option>
              <option value="3">教師(英語)</option>
              <option value="4" class="">生徒</option>
            </select>
          </div>
          <div class="mb-2">
             <label class="form-label d-block">選択科目</label>
            <div class="checkbox-row">
  @php($selectedSubjects = (array)request('subjects', []))
  @foreach($subjects as $subject)
    <label class="chk">
      {{ $subject->subject }}
      <input
        type="checkbox"
        name="subjects[]"
        value="{{ $subject->id }}"
        form="userSearchRequest"
        {{ in_array($subject->id, $selectedSubjects) ? 'checked' : '' }}>
    </label>
  @endforeach
</div>
        </div>
      </div>
      <div class="actions mt-3">
        <input type="submit" name="search_btn" value="検索"
           class="btn-search block" form="userSearchRequest">
      <a href="{{ route('user.show') }}" class="reset-link">リセット</a></div>
    </div>
    <form action="{{ route('user.show') }}" method="get" id="userSearchRequest"></form>
  </div>
</div>
</x-sidebar>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('condToggleBtn');
  const panel = document.getElementById('condPanel');
  if (!btn || !panel) return;
  btn.addEventListener('click', () => {
    const open = panel.classList.toggle('is-open');
    btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    btn.querySelector('.chevron')?.classList.toggle('is-open', open);
  });
});
</script>
