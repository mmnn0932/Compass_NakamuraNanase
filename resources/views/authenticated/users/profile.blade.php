<x-sidebar>
<div class="vh-100 border">
  <div class="top_area pt-3">
    <h1 class="profile-title">
      <span>{{ $user->over_name }} {{ $user->under_name }}</span>さんのプロフィール
    </h1>
    <div class="user_status p-3 profile-card">
      <p>名前 : <span>{{ $user->over_name }}</span><span class="ml-1">{{ $user->under_name }}</span></p>
      <p>カナ : <span>{{ $user->over_name_kana }}</span><span class="ml-1">{{ $user->under_name_kana }}</span></p>
      <p>性別 : @if($user->sex == 1)<span>男</span>@else<span>女</span>@endif</p>
      <p>生年月日 : <span>{{ $user->birth_day }}</span></p>
      <div>選択科目 :
        @foreach($user->subjects as $subject)
          <span class="subject-chip">{{ $subject->subject }}</span>
        @endforeach
      </div>
        @can('admin')
        <button type="button"
          class="subject_edit_btn"
          aria-expanded="false"
          aria-controls="subjectPanel-{{ $user->id }}">
        <span class="label">選択科目の登録</span>
        <i class="fa-solid fa-chevron-down chev" aria-hidden="true"></i>
        </button>
        @endcan

        @can('admin')
            <div class="subject_inner" id="subjectPanel-{{ $user->id }}" hidden>
              <form action="{{ route('user.edit') }}" method="post" class="subject-edit-row">
                <div class="subject-checks">
            @foreach($subject_lists as $subject_list)
            <label class="edit-chk">
              <span>{{ $subject_list->subject }}</span>
              <input type="checkbox" name="subjects[]" value="{{ $subject_list->id }}">
            </label>
            @endforeach
            </div>
            <div class="subject-actions">
            <input type="submit" value="登録" class="btn btn-primary">
            </div>
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            {{ csrf_field() }}
          </form>
        </div>
        @endcan
      </div>
    </div>
  </div>
</div>

</x-sidebar>

<script>
document.addEventListener('DOMContentLoaded', () => {
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('.subject_edit_btn');
    if (!btn) return;

    e.preventDefault();
    e.stopPropagation();

    const panelId = btn.getAttribute('aria-controls');
    if (!panelId) return;
    const panel = document.getElementById(panelId);
    if (!panel) return;

    if (btn.dataset.busy === '1') return;
    btn.dataset.busy = '1';

    const chev = btn.querySelector('.chev');
    const willOpen = panel.hasAttribute('hidden');

    const openPanel = () => {
      panel.removeAttribute('hidden');
      panel.classList.add('is-open');
      panel.style.maxHeight = '0px';
      requestAnimationFrame(() => {
        panel.style.maxHeight = panel.scrollHeight + 'px';
      });
      btn.setAttribute('aria-expanded', 'true');
      if (chev) chev.classList.add('is-open');
    };

    const closePanel = () => {
      const h = panel.scrollHeight;
      panel.style.maxHeight = h + 'px';
      requestAnimationFrame(() => {
        panel.style.maxHeight = '0px';
      });
      const onEnd = (ev) => {
        if (ev.propertyName !== 'max-height') return;
        panel.classList.remove('is-open');
        panel.setAttribute('hidden', '');
        panel.removeEventListener('transitionend', onEnd);
      };
      panel.addEventListener('transitionend', onEnd, { once: true });
      btn.setAttribute('aria-expanded', 'false');
      if (chev) chev.classList.remove('is-open');
    };

    willOpen ? openPanel() : closePanel();

    setTimeout(() => { btn.dataset.busy = '0'; }, 320);
  }, { passive: false });
});
</script>
