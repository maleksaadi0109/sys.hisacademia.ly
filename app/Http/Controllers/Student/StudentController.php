<?php

namespace App\Http\Controllers\Student;

use App\Enums\WeekDays;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // ✅ أضف هذا السطر

class StudentController extends Controller
{
    public function mainIndex(){
        return view('student.main');
    }

    public function courses(){
        $student = User::find(Auth::id());
        $courses = $student->studentCourses;
        return view('student.courses',['courses' => $courses]);
    }

    public function studentCalendar(){
        return view('student.student_calendar');
    }

    public function ajaxGetCalendarStudent(Request $request)
    {

        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        try {
            // Eager load relationships to avoid N+1 queries
            $student = User::with(['studentCoursesNotEnd.user'])
                ->findOrFail(Auth::id());

            // فلترة الدورات بناءً على تداخلها مع الأسبوع المعروض
            $courses = $student->studentCoursesNotEnd->filter(function($course) use ($startDate, $endDate) {
                $courseStartDate = Carbon::parse($course->start_date);
                $courseEndDate = Carbon::parse($course->end_date);
                $weekStartDate = Carbon::parse($startDate);
                $weekEndDate = Carbon::parse($endDate);

                // منطق التداخل: الدورة تتداخل مع الأسبوع إذا
                // 1. تاريخ بداية الدورة قبل (أو يساوي) تاريخ نهاية الأسبوع
                // AND
                // 2. تاريخ نهاية الدورة بعد (أو يساوي) تاريخ بداية الأسبوع
                return $courseStartDate->lessThanOrEqualTo($weekEndDate) && $courseEndDate->greaterThanOrEqualTo($weekStartDate);
            });

            // Use map instead of foreach
            $result = $courses->map(function($course) {
                // Check if teacher exists
                $teacherName = $course->user?->name ?? 'غير محدد';

                // Process days correctly
                $days = [];
                $courseDays = is_string($course->days)
                    ? json_decode($course->days, true)
                    : $course->days;

                if (is_array($courseDays)) {
                    foreach($courseDays as $value) {
                        $days[$value] = true;
                    }
                }

                return [
                    'name' => $course->name,
                    'teacher_name' => $teacherName,
                    'days' => $days,
                    'startdate' => $course->start_date instanceof Carbon
                        ? $course->start_date->format('Y-m-d')
                        : Carbon::parse($course->start_date)->format('Y-m-d'),
                    'enddate' => $course->end_date instanceof Carbon
                        ? $course->end_date->format('Y-m-d')
                        : Carbon::parse($course->end_date)->format('Y-m-d'),
                    'starttime' => $course->start_time,
                    'endtime' => $course->end_time,
                ];
            });

            // Return explicit JSON response
            return response()->json($result);

        } catch (\Exception $e) {
            // Error handling
            Log::error('Error in ajaxGetCalendarStudent: ' . $e->getMessage());

            return response()->json([
                'error' => 'فشل تحميل البيانات',
                'message' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
