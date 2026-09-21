<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class DataExportController extends Controller
{
    public function showExportPage()
    {
        $events = DB::table('activity_events')->select('id', 'name')->get();
        return view('admin.export', compact('events'));
    }

    public function exportActivityData(Request $request)
    {
        $eventId = $request->input('event_id');
    
        $records = DB::table('activity_signups')
            ->join('users', 'activity_signups.user_id', '=', 'users.uid')
            ->where('activity_signups.event_id', $eventId)
            ->select(
                DB::raw("SUBSTRING_INDEX(users.email, '@', 1) as student_number"),
                'users.real_name',
                'activity_signups.signed_in',
                'activity_signups.signed_at',
                'activity_signups.created_at'
            )
            ->get();
    
        // 打开内存中的临时文件句柄
        $handle = fopen('php://temp', 'r+');
    
        // 写入 BOM（防止 Excel 中文乱码）
        fwrite($handle, "\xEF\xBB\xBF");
    
        // 表头
        fputcsv($handle, ['学号', '姓名', '是否签到', '签到时间', '报名时间']);
    
        foreach ($records as $row) {
            fputcsv($handle, [
                $row->student_number,
                $row->real_name,
                $row->signed_in == 1 ? '是' : '否',
                $row->signed_at,
                $row->created_at
            ]);
        }
    
        rewind($handle); // 将文件指针移到开头
        $csvContent = stream_get_contents($handle);
        fclose($handle);
    
        // 返回 response
        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', "attachment; filename=activity_export_{$eventId}.csv");
    }
}
