<?php

namespace App\Http\Controllers\Teacher;

use App\Enums\WeekDays;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function mainIndex(){
        return view('teacher.main');
    }

    public function courses(){
        $courses = Course::where('teacher_id' , '=', Auth::id())->get();
        return view('teacher.courses',['courses' => $courses]);
    }

    public function teacherCalendar(){
        return view('teacher.teacher_calendar');
    }

    public function ajaxGetCalendarTeacher(){
        $teacher = User::with('teacherCoursesNotEnd')->findOrFail(Auth::id());
        $courses = $teacher->teacherCoursesNotEnd;
        $teacher_name = $teacher->name;
        $result= [];
        foreach($courses as $course){
            $days = [];
            foreach(json_decode($course->days) as $value){
                $days[$value] = WeekDays::weekDaysAr()[$value];
            }
            $name =  $course->name;
            $startdate =  date("Y-m-d", strtotime($course->start_date));
            $enddate =  date("Y-m-d", strtotime($course->end_date));
            $starttime = $course->start_time;
            $endtime = $course->end_time;
            $result[] = ['name' => $name, 'teacher_name' => $teacher_name, 'days' => $days, 'startdate' => $startdate, 'enddate' => $enddate, 'starttime' => $starttime, 'endtime' => $endtime];
        }
        return $result;
    }
}
