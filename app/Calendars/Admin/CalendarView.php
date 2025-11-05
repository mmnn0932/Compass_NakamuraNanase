<?php
namespace App\Calendars\Admin;
use Carbon\Carbon;
use App\Models\Calendars\ReserveSettings;

class CalendarView{
  private $carbon;

  function __construct($date){
    $this->carbon = new Carbon($date);
  }

  public function getTitle(){
    return $this->carbon->format('Y年n月');
  }

  public function render(){
    $html = [];
    $html[] = '<div class="calendar text-center admin-cal">';
    $html[] = '<table class="table calendar-table">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th>月</th>';
    $html[] = '<th>火</th>';
    $html[] = '<th>水</th>';
    $html[] = '<th>木</th>';
    $html[] = '<th>金</th>';
    $html[] = '<th>土</th>';
    $html[] = '<th>日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';

    $weeks = $this->getWeeks();
    foreach($weeks as $week){
      $html[] = '<tr class="'.$week->getClassName().'">';
      $days = $week->getDays();
      foreach($days as $day){
        $dayDateRaw = $day->everyDay();
        $isValid    = !empty($dayDateRaw);
        $dayDateStr = $isValid ? Carbon::parse($dayDateRaw, config('app.timezone'))->format('Y-m-d') : '';
        $today      = Carbon::today(config('app.timezone'))->format('Y-m-d');
        $isPast     = $isValid && ($dayDateStr < $today);
        if ($isPast) {
          $html[] = '<td class="calendar-td past-day '.$day->getClassName().'">';
        }else{
          $html[] = '<td class="calendar-td '.$day->getClassName().'">';
        }
        $html[] = $day->render();
        if(!$isValid) {
          $html[] = '</td>';
          continue;
        }
        for ($p = 1; $p <= 3; $p++) {
          $label = ($p === 1 ? '1部' : ($p === 2 ? '2部' : '3部'));
          $rs = ReserveSettings::withCount('users')
            ->where('setting_reserve', $dayDateStr)
            ->where('setting_part', $p)
            ->first();
          if ($rs) {
          $reserved = (int)$rs->users_count;
          } else {
          $reserved = 0;
          }
          $url = route('calendar.admin.detail', [
            'date' => $dayDateStr,
            'part' => $p
          ]);
          $html[] = '<div style="font-size:12px;">'
            . '<a href="'.$url.'">'.$label.'</a>'
            . '<span class="text-body" style="margin-left:30px;">'.$reserved.'</span>'
            . '</div>';
        }
        $html[] = '</td>';
      }
      $html[] = '</tr>';
    }
    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';

    return implode("", $html);
  }

  protected function getWeeks(){
    $weeks = [];
    $firstDay = $this->carbon->copy()->firstOfMonth();
    $lastDay = $this->carbon->copy()->lastOfMonth();
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
