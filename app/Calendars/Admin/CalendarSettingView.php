<?php
namespace App\Calendars\Admin;
use Carbon\Carbon;
use App\Models\Calendars\ReserveSettings;

class CalendarSettingView{
  private $carbon;

  function __construct($date){
    $this->carbon = new Carbon($date);
  }

  public function getTitle(){
    return $this->carbon->format('Y年n月');
  }

  public function render(){
    $html = [];
    $html[] = '<div class="calendar-shell setting-compact">';
    $html[] =   '<div class="calendar text-center admin-cal">';
    $html[] = '<table class="table calendar-table">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th>月</th><th>火</th><th>水</th><th>木</th><th>金</th><th>土</th><th>日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';
    $weeks = $this->getWeeks();
    foreach($weeks as $week){
      $html[] = '<tr class="'.$week->getClassName().'">';
      $days = $week->getDays();
      foreach($days as $day){
        $raw       = $day->everyDay();
        $isValid   = !empty($raw);
        $dCarbon   = $isValid ? Carbon::parse($raw, config('app.timezone'))->startOfDay() : null;
        $isPast    = $this->isPast($dCarbon);
        $tdClass   = 'calendar-td '.$day->getClassName().($isPast ? ' past-day' : '');

        $html[] = '<td class="'.$tdClass.'">';
        $html[] =   '<div class="cell-inner">';
        $html[] =     '<div class="cell-date">'.$day->render().'</div>';
        $html[] =     '<div class="cell-body">';

        if ($isValid) {
          $disabled = $isPast ? ' disabled' : '';

          $v1 = $day->onePartFrame($raw);
          $v2 = $day->twoPartFrame($raw);
          $v3 = $day->threePartFrame($raw);

          $html[] = '<div class="adjust-area">';
          $html[] =   '<p class="d-flex m-0 p-0">1部'
                    . '<input class="" name="reserve_day['.$raw.'][1]" type="text" form="reserveSetting" value="'.$v1.'"'.$disabled.'></p>';
          $html[] =   '<p class="d-flex m-0 p-0">2部'
                    . '<input class="" name="reserve_day['.$raw.'][2]" type="text" form="reserveSetting" value="'.$v2.'"'.$disabled.'></p>';
          $html[] =   '<p class="d-flex m-0 p-0">3部'
                    . '<input class="" name="reserve_day['.$raw.'][3]" type="text" form="reserveSetting" value="'.$v3.'"'.$disabled.'></p>';
          $html[] = '</div>';
        }
        $html[] =     '</div>';
        $html[] =   '</div>';
        $html[] = '</td>';
      }
      $html[] = '</tr>';
    }
    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';
    $html[] = '</div>';
    $html[] = '<form action="'.route('calendar.admin.update').'" method="post" id="reserveSetting">'.csrf_field().'</form>';
    return implode("", $html);
  }
  protected function isPast(?Carbon $d): bool {
    if (!$d) return false;
    $today = Carbon::today(config('app.timezone'))->startOfDay();
    return $d->lt($today);
  }

  protected function getWeeks(){
    $weeks = [];
    $firstDay = $this->carbon->copy()->firstOfMonth();
    $lastDay  = $this->carbon->copy()->lastOfMonth();

    $week = new CalendarWeek($firstDay->copy());
    $weeks[] = $week;
    $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek();
    while($tmpDay->lte($lastDay)){
      $week = new CalendarWeek($tmpDay, count($weeks));
      $weeks[] = $week;
      $tmpDay->addDay(7);
    }
    return $weeks;
  }
}
