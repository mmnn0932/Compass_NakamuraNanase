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
        try {
            $getPart = (array) $request->input('getPart', []);
            $getDate = (array) $request->input('getDate', []);

            foreach ($getPart as $i => $part) {
                if (!$part) continue;
                $date = $getDate[$i] ?? null;
                if (!$date) continue;

                $rs = ReserveSettings::where('setting_reserve', $date)
                    ->where('setting_part', $part)
                    ->first();
                if (!$rs) continue;
                if ($rs->limit_users <= 0) continue;

                $already = $rs->users()->where('users.id', \Auth::id())->exists();
                if ($already) continue;

                $rs->decrement('limit_users');
                $rs->users()->attach(\Auth::id());
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }
    public function delete(Request $request)
{
    $date = $request->input('delete_date');
    DB::beginTransaction();
        $reserve = Auth::user()
            ->reserveSettings()
            ->where('setting_reserve', $date)
            ->first();
        if ($reserve) {
        ReserveSettings::where('id', $reserve->id)->increment('limit_users');
            $reserve->users()->detach(Auth::id());
        DB::commit();
    }
    return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }

}
