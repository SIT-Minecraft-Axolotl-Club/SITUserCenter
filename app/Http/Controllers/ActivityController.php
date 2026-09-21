<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class ActivityController extends Controller
{
    // 显示创建活动表单
    public function create()
    {
        return view('admin.activity-create');
    }

    // 处理表单提交
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'signup_start' => 'required|date',
            'signup_end' => 'required|date|after:signup_start',
            'event_start' => 'required|date',
            'event_end' => 'required|date|after:event_start',
            'content' => 'required|string',
            'max_participants' => 'required|integer|min:1',
        ]);

        if ($request->signup_end > $request->event_start) {
            return back()->withErrors(['报名时间不能晚于活动开始时间'])->withInput();
        }

        $signCode = strtoupper(substr(bin2hex(random_bytes(4)), 0, 7));

        DB::table('activity_events')->insert([
            'name' => $request->input('name'),
            'signup_start' => $request->input('signup_start'),
            'signup_end' => $request->input('signup_end'),
            'event_start' => $request->input('event_start'),
            'event_end' => $request->input('event_end'),
            'content' => $request->input('content'),
            'max_participants' => $request->input('max_participants'),
            'sign_code' => $signCode,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/admin/activity/create')->with('success', '活动创建成功！');
    }
    
    public function index()
    {
        $activities = DB::table('activity_events')->orderByDesc('id')->paginate(5);
        return view('admin.activity-list', [
            'activities' => $activities,
            'now' => now(),
            'userId' => Auth::id(),
        ]);
    }
    // 重置签到码
    public function resetCode($id)
    {
        $newCode = strtoupper(substr(bin2hex(random_bytes(4)), 0, 7));

        DB::table('activity_events')->where('id', $id)->update([
            'sign_code' => $newCode,
            'updated_at' => now(),
        ]);

        return back()->with('success', '签到码已重置');
    }
    public function checkinForm($id)
    {
        if (!Auth::check()) {
            return redirect('/login')->withErrors('请先登录以进行签到。');
        }
        
        $user = Auth::user();
    
        if (empty($user->real_name)) {
            return redirect('/profile/verify-realname');
        }
    
        $event = DB::table('activity_events')->where('id', $id)->first();
        if (!$event) {
            return abort(404);
        }
    
        return view('events.checkin', ['event' => $event]);
    }
    
    public function updateRealName(Request $request)
    {
        if (!Auth::check()) {
    
            return redirect('/login')->withErrors('请先登录以完成实名认证。');
        }
        $request->validate([
            'real_name_input' => 'required|string|max:255',
        ], [
            'real_name_input.required' => '真实姓名不能为空。',
            'real_name_input.string' => '真实姓名必须是字符串。',
            'real_name_input.max' => '真实姓名长度不能超过10个字符。',
        ]);
    
        $realName = $request->input('real_name_input');
    
        $userId = Auth::id();
    
        try {
            DB::table('users')
                ->where('uid', $userId)
                ->update(['real_name' => $realName]);
    
            return redirect('/events')->with('success', '实名信息更新成功！您现在可以进行签到了。');
    
        } catch (\Exception $e) {
            \Log::error("更新用户 ID {$userId} 的实名信息时出错：" . $e->getMessage());
    
            return back()->withInput()->withErrors('更新实名信息失败，请稍后再试。');
        }
    }

    public function checkin(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect('/login')->withErrors('请先登录以进行签到。');
        }
    
        $user = Auth::user();
    
        if (empty($user->real_name)) {
            return redirect('/profile/verify-realname')->with('info', '请先完成实名认证才能进行签到。');
        }
    
                $request->validate([
            'sign_code' => 'required|string|size:7',
        ]);

        $event = DB::table('activity_events')->where('id', $id)->first();
        if (!$event) return abort(404);

        $now = now();
        if (!($now >= $event->event_start && $now <= $event->event_end)) {
            return back()->withErrors('当前不在活动时间范围内');
        }

        if (strtoupper($request->input('sign_code')) !== strtoupper($event->sign_code)) {
            return back()->withErrors('签到码错误');
        }

        $userId = auth()->id();
        if (!$userId) return redirect('/login')->withErrors('请先登录');

        $signup = DB::table('activity_signups')
            ->where('event_id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$signup) return back()->withErrors('你未报名该活动，无法签到');
        if ($signup->signed_in) return back()->with('success', '你已经签到过了！');

        DB::table('activity_signups')->where('id', $signup->id)->update([
            'signed_in' => true,
            'signed_at' => now(),
        ]);

        return back()->with('success', '签到成功！');
    }
    
    public function userindex()
    {
        $userId = Auth::id();
        $now = Carbon::now();
    
        $events = DB::table('activity_events')
            ->orderBy('event_start', 'desc')
            ->get()
            ->map(function ($event) use ($userId) {
                $event->signup_count = DB::table('activity_signups')
                    ->where('event_id', $event->id)
                    ->count();
    
                $event->is_signed_up = DB::table('activity_signups')
                    ->where('event_id', $event->id)
                    ->where('user_id', $userId)
                    ->exists();
    
                $event->is_checked_in = DB::table('activity_signups')
                    ->where('event_id', $event->id)
                    ->where('user_id', $userId)
                    ->where('signed_in', true)
                    ->exists();
    
                return $event;
            });
    
        return view('events.user-index', [
            'events' => $events,
            'now' => $now,
        ]);
    }


    // 用户报名活动
    public function signup($id, Request $request)
    {
        $userId = Auth::id();

        // 获取活动信息
        $event = DB::table('activity_events')->where('id', $id)->first();

        if (!$event) {
            return redirect()->back()->with('error', '活动不存在');
        }

        $now = Carbon::now();
        if (!($now >= $event->signup_start && $now <= $event->signup_end)) {
            return redirect()->back()->with('error', '不在报名时间段内');
        }

        // 检查是否已报名
        $exists = DB::table('activity_signups')
            ->where('event_id', $id)
            ->where('user_id', $userId)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('info', '您已报名此活动');
        }

        // 检查人数限制
        $count = DB::table('activity_signups')
            ->where('event_id', $id)
            ->count();

        if ($count >= $event->max_participants) {
            return redirect()->back()->with('error', '活动人数已满');
        }

        // 插入报名信息
        DB::table('activity_signups')->insert([
            'event_id' => $id,
            'user_id' => $userId,
            'created_at' => now(),
        ]);

        return redirect()->back()->with('success', '报名成功');
    }
    
    public function checkinWithCode($id, $code)
    {
        if (!Auth::check()) {
            return redirect('/login')->withErrors('请先登录以进行签到。');
        }
    
        $user = Auth::user();
    
        if (empty($user->real_name)) {
            return redirect('/profile/verify-realname')->with('info', '请先完成实名认证才能进行签到。');
        }
    
        $event = DB::table('activity_events')->where('id', $id)->first();
        if (!$event) return abort(404);
    
        $now = now();
        if (!($now >= $event->event_start && $now <= $event->event_end)) {
            return redirect()->route('events.checkin', ['id' => $id])->withErrors('当前不在活动时间范围内');
        }
    
        if (strtoupper($code) !== strtoupper($event->sign_code)) {
            return redirect()->route('events.checkin', ['id' => $id])->withErrors('签到码错误');
        }
    
        $userId = auth()->id();
    
        $signup = DB::table('activity_signups')
            ->where('event_id', $id)
            ->where('user_id', $userId)
            ->first();
    
        if (!$signup) {
            return redirect()->route('events.checkin', ['id' => $id])->withErrors('你未报名该活动，无法签到');
        }
    
        if ($signup->signed_in) {
            return redirect()->route('events.checkin', ['id' => $id])->with('success', '你已经签到过了！');
        }
    
        DB::table('activity_signups')->where('id', $signup->id)->update([
            'signed_in' => true,
            'signed_at' => now(),
        ]);
    
        return redirect()->route('events.checkin', ['id' => $id])->with('success', '签到成功！');
    }
}