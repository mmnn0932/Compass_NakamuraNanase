<?php

namespace App\Http\Controllers\Authenticated\Calendar\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\General\CalendarView;
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar;
use App\Models\USers\User;
use Auth;
use DB;

class CalendarController extends Controller
{
    public function show(){
        $calendar = new CalendarView(time());
        return view('authenticated.calendar.general.calendar', compact('calendar'));
    }

    public function reserve(Request $request){
        DB::beginTransaction();
        try{
            $getPart = $request->getPart;
            $getDate = $request->getData;
            $reserveDays = array_filter(array_combine($getDate, $getPart));
            foreach($reserveDays as $key => $value){
                $reserve_settings = ReserveSettings::where('setting_reserve', $key)->where('setting_part', $value)->first();
                $reserve_settings->decrement('limit_users');
                $reserve_settings->users()->attach(Auth::id());
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }

     public function delete(Request $request)
    {
        $date = $request->input('delete_date'); // 例: 2025-09-03
        $part = $request->input('delete_part'); // 1/2/3 など

        // 最低限のバリデーション
        $request->validate([
            'delete_date' => ['required','date'],
            'delete_part' => ['required','in:1,2,3'],
        ]);

        DB::beginTransaction();
        try{
            $reserve = ReserveSettings::where('setting_reserve', $date)
                        ->where('setting_part', $part)
                        ->firstOrFail();

            // 予約者(自分)をピボットから外す
            $reserve->users()->detach(Auth::id());

            // 定員を1つ戻す
            $reserve->increment('limit_users');

            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            // 必要ならエラーメッセージを付けて戻す
            return back()->with('error', 'キャンセルに失敗しました。');
        }

        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()])
               ->with('status', '予約をキャンセルしました。');
    }
}
