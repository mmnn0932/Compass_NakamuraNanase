<?php
namespace App\Calendars\General;

use Carbon\Carbon;
use Auth;

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
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table">';
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
      $startDay   = $this->carbon->format('Y-m-01');
      $toDay      = Carbon::today(config('app.timezone'))->format('Y-m-d');
      $dayDateStr = $day->everyDay();
      $isValid    = !empty($dayDateStr);
      $dayDateStr = $isValid ? Carbon::parse($dayDateStr)->format('Y-m-d') : '';
      $isPast     = $isValid && ($dayDateStr >= $startDay) && ($dayDateStr <= $toDay);
      if ($isPast) {
          $html[] = '<td class="calendar-td past-day">';
        }else{
          $html[] = '<td class="calendar-td '.$day->getClassName().'">';
        }
        $html[] = $day->render();

        $hasMyReserve = in_array($dayDateStr, $day->authReserveDay(), true);

      if ($hasMyReserve) {
        $reserve = $day->authReserveDate($dayDateStr)->first();
        $reservePart = $reserve->setting_part ?? null;
        if($reservePart === 1)      $reserveLabel = 'リモ1部';
        elseif ($reservePart === 2)  $reserveLabel = 'リモ2部';
        elseif ($reservePart === 3)  $reserveLabel = 'リモ3部';
        else                         $reserveLabel = 'リモ';

        if ($isPast) {
          $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px">'.$reserveLabel . '</p>';
          $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
        } else {
          $html[] = '<button type="submit" class="btn btn-danger p-0 w-75" name="delete_date" style="font-size:12px" ' .
          'data-date="' . $reserve->setting_reserve . '" ' .
          'data-part="' . $reserve->setting_part . '" ' .
          'style="font-size:12px">' . $reserveLabel . '</button>';
          $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
          }
        }else{
        if ($isPast) {
          $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px; color:#212529;">受付終了</p>';
          $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
        }else{
          $html[] = $day->selectPart($dayDateStr);
        }
        }
        $html[] = $day->getDate();
        $html[] = '</td>';
      }
      $html[] = '</tr>';
    }
    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';
    $html[] = '<form action="/reserve/calendar" method="post" id="reserveParts">'.csrf_field().'</form>';
    return implode('', $html);
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
