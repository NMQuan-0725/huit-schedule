<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\Branch;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $branchId =$request->input('branch_id');
        $teacherId =$request->input('teacher_id');
        $tab =$request->input('tab', 'timetable'); // Tab mặc định là lưới thời khóa biểu

        $query = Schedule::with(['teacher', 'branch']);

        if ($branchId) {
            $query->where('branch_id',$branchId);
        }

        if ($teacherId) {
            $query->where('teacher_id',$teacherId);
        }

        $schedules = $query->get();$branches = Branch::all();
        
        // Lấy danh sách giảng viên kèm theo số lượng lớp/tiết trong tuần phục vụ Tab Quản lý Giảng viên
        $teachers = Teacher::withCount('schedules')->get();
        
        // Nhóm dữ liệu theo học phần phục vụ Tab Quản lý Học phần
        $subjects = Schedule::with(['teacher', 'branch'])
            ->get()
            ->groupBy('subject_name');

        return view('schedules.index', compact('schedules', 'branches', 'teachers', 'subjects', 'branchId', 'teacherId', 'tab'));
    }

    public function suggestTeachers(Request $request)
    {
        $dayOfWeek =$request->input('day_of_week');
        $shift =$request->input('shift_or_periods');
        $expertise =$request->input('expertise');

        $busyTeacherIds = Schedule::where('day_of_week',$dayOfWeek)
            ->where('shift_or_periods', $shift)
            ->pluck('teacher_id');

        $suggestedTeachers = Teacher::where('expertise', 'LIKE', "\%{$expertise}%")
            ->whereNotIn('id', $busyTeacherIds)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $suggestedTeachers
        ]);
    }
}