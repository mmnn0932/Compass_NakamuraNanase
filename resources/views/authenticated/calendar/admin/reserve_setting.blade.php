<x-sidebar>
<div class="vh-100 pt-5" style="background:#ECF1F6;">
  <div class="profile-card calendar-shell calendar-fit setting-mode w-75 m-auto">
    <p class="text-center mb-2">{{ $calendar->getTitle() }}</p>
    <div class="calendar-wrap admin-cal">
      {!! $calendar->render() !!}
    </div>

    <div class="text-right">
       <input
          type="submit"
          class="btn btn-primary"
          value="登録"
          form="reserveSetting"
          onclick="return confirm('登録してよろしいですか？')">
    </div>
  </div>
</div>
</x-sidebar>
