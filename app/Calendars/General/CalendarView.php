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

  public function render()
{
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

    foreach ($weeks as $week) {
        $html[] = '<tr class="' . $week->getClassName() . '">';

        $days = $week->getDays();
        foreach ($days as $day) {
            // その日の文字列とCarbon
            $dayDateStr = $day->everyDay();                 // 例: "2025-09-03"
            $dayDate    = \Carbon\Carbon::parse($dayDateStr);
            $today      = \Carbon\Carbon::today(config('app.timezone'));

            // 過去日かどうか（今日より前なら過去）
            $isPast = $dayDate->lt($today);

            // <td> 開始（過去日はグレー）
            if ($isPast) {
                $html[] = '<td class="calendar-td past-day">';
            } else {
                $html[] = '<td class="calendar-td ' . $day->getClassName() . '">';
            }

            // 日付数字など（既存の day->render()）
            $html[] = $day->render();

            // 自分の予約の有無（注意：配列は文字列日付で比較）
            $hasMyReserve = in_array($dayDateStr, $day->authReserveDay(), true);

            if ($hasMyReserve) {
                // 予約部を取得（こちらもキーは文字列日付）
                $reserve = $day->authReserveDate($dayDateStr)->first();
                $reservePart = $reserve->setting_part ?? null;

                if ($reservePart === 1)      $reserveLabel = 'リモ1部';
                elseif ($reservePart === 2)  $reserveLabel = 'リモ2部';
                elseif ($reservePart === 3)  $reserveLabel = 'リモ3部';
                else                         $reserveLabel = 'リモ';

                if ($isPast) {
                    // 過去日は表示のみ
                    $html[] = '<p class="m-auto p-0 w-75 text-muted" style="font-size:12px">' . $reserveLabel . '</p>';
                    $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
                } else {
                    // 当日・未来はキャンセル（モーダル起動ボタン）
                    $html[] = '<button type="button"
                                    class="btn btn-danger p-0 w-75 js-open-cancel"
                                    data-date="' . e($reserve->setting_reserve) . '"
                                    data-part="' . e($reserve->setting_part) . '"
                                    style="font-size:12px">' . e($reserveLabel) . '</button>';
                    $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
                }
            } else {
                if ($isPast) {
                    // 過去日・未予約 → 受付終了
                    $html[] = '<p class="m-auto p-0 w-75 text-muted" style="font-size:12px">受付終了</p>';
                    $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
                } else {
                    // 当日以降・未予約 → プルダウン（既存の day->selectPart を使用）
                    $html[] = $day->selectPart($dayDateStr);
                }
            }

            // 日付の小さな表示など
            $html[] = $day->getDate();
            $html[] = '</td>';
        }

        $html[] = '</tr>';
    }

    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';

    // 予約登録フォーム
    $html[] = '<form action="/reserve/calendar" method="post" id="reserveParts">'
            . csrf_field()
            . '</form>';

    // 予約取消フォーム（モーダルから値を入れて submit）
    $html[] = '<form action="/delete/calendar" method="post" id="deleteParts">'
            . csrf_field()
            . '<input type="hidden" name="delete_date" id="delete_date">'
            . '<input type="hidden" name="delete_part" id="delete_part">'
            . '</form>';

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
