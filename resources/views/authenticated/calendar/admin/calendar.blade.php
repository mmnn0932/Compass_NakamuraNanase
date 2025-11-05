<x-sidebar>
<div class="vh-100 pt-5" style="background:#ECF1F6;">
    <div class="profile-card calendar-shell calendar-fit w-75 m-auto">
      <p class="text-center mb-2">{{ $calendar->getTitle() }}</p>

      <div class="calendar-wrap admin-cal">
        {!! $calendar->render() !!}
      </div>
      </div>
</div>
</x-sidebar>
