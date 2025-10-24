<?php

namespace App\Http\Controllers\DataEntry;

use App\Enums\EnumPermission;
use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Enums\WeekDays;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Customer;
use App\Models\DataStudent;
use App\Models\Diploma;
use App\Models\Revenue;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\TranslationDel;
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

class DataEntryController extends Controller
{
    public function mainIndex(){
        return view('data_entry.main');
    }

    ## teacher ##
    public function addTeacher(){
        return view('data_entry.teacher.add_teacher');
    }

    public function registerTeacher(Request $request){
        try{
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'status' => ['required','in:active,inactive'],
                'en_name' => ['required', 'string', 'max:255'],
                'id_number' => ['required', 'string', 'max:255'],
                'nationality' => ['required', 'string', 'max:255'],
                'date_of_birth' => ['required','date_format:Y-m-d'],
                'academic_qualification' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:255'],
                'pass_image' => ['required','mimes:png,jpeg'],
            ]);

            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_type' => UserType::teacher,
                'status' => $request->status,
            ]);

            $pass_image_name = time().rand(10,99).'.'.$request->pass_image->getClientOriginalExtension();
            $request->pass_image->storeAs('public/passport',$pass_image_name);

            Teacher::create([
                'en_name' => $request->en_name,
                'id_number' => $request->id_number,
                'nationality' => $request->nationality,
                'date_of_birth' => $request->date_of_birth,
                'academic_qualification' => $request->academic_qualification,
                'phone' => $request->phone,
                'pass_image' => $pass_image_name,
                'user_id' => $user->id,
            ]);

            return back()->with('success','تم إضافة المعلم بنجاح ');
        }catch(Exception $e){
            return back()->with('error',$e->getMessage())->withInput();
        }
    }



    public function editTeacher($id){
        if(!in_array(EnumPermission::modification,json_decode(Auth::user()->permission))){
            return back()->with('error','ليس لديك صلاحية التعديل');
        }
        $teacher = Teacher::with('user')->findOrFail($id);
        $user = $teacher->user;

        return view('data_entry.teacher.edit_teacher',['teacher' => $teacher, 'user' => $user]);
    }

    public function updateTeacher(Request $request,$id){
        if(!in_array(EnumPermission::modification,json_decode(Auth::user()->permission))){
            return back()->with('error','ليس لديك صلاحية التعديل');
        }
        $teacher = Teacher::with('user')->findOrFail($id);
        $user = $teacher->user;

        try{
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'status' => ['required','in:active,inactive'],
                'en_name' => ['required', 'string', 'max:255'],
                'id_number' => ['required', 'string', 'max:255'],
                'nationality' => ['required', 'string', 'max:255'],
                'date_of_birth' => ['required','date_format:Y-m-d'],
                'academic_qualification' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:255'],
            ]);

            if($request->password != null){
                $request->validate([
                    'password' => ['required', 'confirmed', Rules\Password::defaults()],
                ]);
                $user->password = Hash::make($request->password);
            }
            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->status = $request->status;

            $teacher->en_name = $request->en_name;
            $teacher->id_number = $request->id_number;
            $teacher->nationality = $request->nationality;
            $teacher->date_of_birth = $request->date_of_birth;
            $teacher->academic_qualification = $request->academic_qualification;
            $teacher->phone = $request->phone;
            $teacher->nationality = $request->nationality;

            if($request->pass_image){
                $image_name = time().rand(10,99).'.'.$request->pass_image->getClientOriginalExtension();
                $request->pass_image->storeAs('public/passport',$image_name);
                $teacher->pass_image = $image_name;
            }
            $user->save();
            $teacher->save();
            return back()->with('success','تم تعديل بيانات المعلم بنجاح');

        }catch(Exception $e){
            return back()->with('error',$e->getMessage())->withInput();
        }
    }
    public function students(Request $request, $orderBy = 'id', $sort = 'asc')
    {
        $query = $request->input('search');

        // تأكد من صحة sort
        $sort = strtolower($sort);
        if (!in_array($sort, ['asc', 'desc'])) {
            $sort = 'asc';
        }

        // قائمة الأعمدة المسموح ترتيبها
        $allowedColumns = ['id', 'en_name', 'id_number', 'nationality', 'phone']; // أعمدة جدول students
        // لو فيه أعمدة من جدول user ممكن تضيفها بصيغة relation: 'user.name'
        $allowedUserColumns = ['name', 'email'];

        // افحص orderBy
        if (!in_array($orderBy, $allowedColumns) && !in_array($orderBy, $allowedUserColumns)) {
            $orderBy = 'id'; // افتراضي
        }

        $students = Student::with('user')
            ->when($query, function ($q) use ($query) {
                $q->whereHas('user', function ($userQuery) use ($query) {
                    $userQuery->where('name', 'like', "%{$query}%");
                });
            });

        // ترتيب حسب العمود المناسب
        if (in_array($orderBy, $allowedUserColumns)) {
            $students = $students->join('users', 'students.user_id', '=', 'users.id')
                ->orderBy("users.$orderBy", $sort)
                ->select('students.*'); // مهم عشان يرجع عمود students فقط
        } else {
            $students = $students->orderBy($orderBy, $sort);
        }

        $students = $students->paginate(10);

        if ($request->ajax()) {
            return view('data_entry.partials.students_table', compact('students'))->render();
        }

        return view('data_entry.students.students', compact('students'));
    }

    ## students ##
    public function addStudent(){
        return view('data_entry.students.add_student');
    }
    public function courseCalendar(){
        $date = date('Y-m-d');
        $courses = Course::with('diploma')->where('end_date', '>=', $date)->get();
        return view('data_entry.calendar.calendar',['courses' => $courses]);
    }
    public function teacherCalendar(){
        $teachers = User::where('status', '=', UserStatus::active)->where('user_type', '=', UserType::teacher)->get();
        return view('data_entry.calendar.teacher_calendar',['teachers' => $teachers]);
    }
    public function ajaxGetCalendarTeacher($id){
        $teacher = User::with('teacherCoursesNotEnd')->findOrFail($id);
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


    public function ajaxGetCalendarCourse($id){
        $course = Course::with('user')->findOrFail($id);
        $teacher_name = $course->user ? $course->user->name : 'N/A';

        // نرجع الأيام كـ object (associative array)
        $days = [];
        foreach(json_decode($course->days) as $value){
            $days[$value] = WeekDays::weekDaysAr()[$value];  // object format: {"su": "الأحد", "mo": "الاثنين"}
        }

        $name = $course->name;
        $startdate = date("Y-m-d", strtotime($course->start_date));
        $enddate = date("Y-m-d", strtotime($course->end_date));
        $starttime = $course->start_time;
        $endtime = $course->end_time;

        return response()->json([
            'name' => $name,
            'teacher_name' => $teacher_name,
            'days' => $days,  // راح يرجع object
            'startdate' => $startdate,
            'enddate' => $enddate,
            'starttime' => $starttime,
            'endtime' => $endtime
        ]);
    }



    public function student_calendar()
    {
        $students = User::where('status', '=', UserStatus::active)->where('user_type', '=', UserType::student)->get();
        return view('data_entry.calendar.student_calendar',['students' => $students]);
    }

    public function ajaxGetTimeByStudent($id){
        $student = Student::find($id);
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        $courses = $student->user->studentCoursesNotEnd;
        $result = [];
        foreach ($courses as $course){
            $days = [];
            $teacher_name = $course->user ? $course->user->name : 'N/A'; // Added null check
            foreach (json_decode($course->days) as $value){
                $days[] = WeekDays::weekDaysAr()[$value];
            }
            $result[] = [
                'name' => $course->name,
                'teacher_name' => $teacher_name,
                'days' => implode(', ', $days),
                'startdate' => date('Y-m-d', strtotime($course->start_date)),
                'enddate' => date('Y-m-d', strtotime($course->end_date)),
                'starttime' => date('h:i A', strtotime($course->start_time)),
                'endtime' => date('h:i A', strtotime($course->end_time))
            ];
        }
        return response()->json(['datacourses' => $result]);
    }






    public function diplomaCalendar()
    {
        $diplomas = Diploma::all();
        return view('data_entry.calendar.diploma_calendar', ['diplomas' => $diplomas]);
    }

    public function ajaxGetCalendarDiploma($id)
    {
        $diploma = Diploma::with('diplomaCourses.user')->findOrFail($id);
        $courses = $diploma->diplomaCourses;
        $result = [];
        foreach ($courses as $course) {
            $days = [];
            $teacher_name = $course->user ? $course->user->name : 'N/A';
            foreach (json_decode($course->days) as $value) {
                $days[$value] = WeekDays::weekDaysAr()[$value];
            }
            $name = $course->name;
            $startdate = date("Y-m-d", strtotime($course->start_date));
            $enddate = date("Y-m-d", strtotime($course->end_date));
            $starttime = $course->start_time;
            $endtime = $course->end_time;
            $result[] = ['name' => $name, 'teacher_name' => $teacher_name, 'days' => $days, 'startdate' => $startdate, 'enddate' => $enddate, 'starttime' => $starttime, 'endtime' => $endtime];
        }
        return $result;
    }


    public function registerStudent(Request $request) {
        try{
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'status' => ['required','in:active,inactive'],
                'en_name' => ['required', 'string', 'max:255'],
                'id_number' => ['required', 'string', 'max:255'],
                'nationality' => ['required', 'string', 'max:255'],
                'date_of_birth' => ['required','date_format:Y-m-d'],
                'academic_qualification' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:255'],
                'pass_image' => ['required','mimes:png,jpeg'],
            ]);

            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_type' => UserType::student,
                'status' => $request->status,
            ]);

            $pass_image_name = time().rand(10,99).'.'.$request->pass_image->getClientOriginalExtension();
            $request->pass_image->storeAs('public/passport',$pass_image_name);

            Student::create([
                'en_name' => $request->en_name,
                'id_number' => $request->id_number,
                'nationality' => $request->nationality,
                'date_of_birth' => $request->date_of_birth,
                'academic_qualification' => $request->academic_qualification,
                'phone' => $request->phone,
                'pass_image' => $pass_image_name,
                'user_id' => $user->id,
            ]);

            return back()->with('success','تم إضافة الطالب بنجاح ');
        }catch(Exception $e){
            return back()->with('error',$e->getMessage())->withInput();
        }
    }



    public function updateStudent(Request $request,$id){
        if(!in_array(EnumPermission::modification,json_decode(Auth::user()->permission))){
            return back()->with('error','ليس لديك صلاحية التعديل');
        }
        $student = Student::with('user')->findOrFail($id);
        $user = $student->user;

        try{
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'status' => ['required','in:active,inactive'],
                'en_name' => ['required', 'string', 'max:255'],
                'id_number' => ['required', 'string', 'max:255'],
                'nationality' => ['required', 'string', 'max:255'],
                'date_of_birth' => ['required','date_format:Y-m-d'],
                'academic_qualification' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:255'],
            ]);

            if($request->password != null){
                $request->validate([
                    'password' => ['required', 'confirmed', Rules\Password::defaults()],
                ]);
                $user->password = Hash::make($request->password);
            }
            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->status = $request->status;

            $student->en_name = $request->en_name;
            $student->id_number = $request->id_number;
            $student->nationality = $request->nationality;
            $student->date_of_birth = $request->date_of_birth;
            $student->academic_qualification = $request->academic_qualification;
            $student->phone = $request->phone;
            $student->nationality = $request->nationality;

            if($request->pass_image){
                $image_name = time().rand(10,99).'.'.$request->pass_image->getClientOriginalExtension();
                $request->pass_image->storeAs('public/passport',$image_name);
                $student->pass_image = $image_name;
            }
            $user->save();
            $student->save();
            return back()->with('success','تم تعديل بيانات الطالب بنجاح');

        }catch(Exception $e){
            return back()->with('error',$e->getMessage())->withInput();
        }
    }

    public function editStudentData($id){
        if(!in_array(EnumPermission::modification,json_decode(Auth::user()->permission))){
            return back()->with('error','ليس لديك صلاحية التعديل');
        }
        $student = Student::with('dataStudent')->findOrFail($id);
        $dataStudent = $student->dataStudent;

        return view('data_entry.students.edit_data_student',['student' => $student, 'dataStudent' => $dataStudent]);
    }

    public function updateDataStudent(Request $request,$id){
        if(!in_array(EnumPermission::modification,json_decode(Auth::user()->permission))){
            return back()->with('error','ليس لديك صلاحية التعديل');
        }
        try{
            $student = Student::with('dataStudent')->findOrFail($id);
            $dataStudent = $student->dataStudent;

            if($dataStudent == null){
                $signature_name = null;
                if($request->signature){
                    $signature_name = time().rand(10,99).'.'.$request->signature->getClientOriginalExtension();
                    $request->signature->storeAs('public/signature',$signature_name);
                }
                DataStudent::create([
                    'section' => $request->section,
                    'level' => $request->level,
                    'attend' => $request->attend,
                    'course_type' => $request->course_type,
                    'course_days' => json_encode($request->course_days),
                    'course_start_time' => $request->course_start_time,
                    'course_end_time' => $request->course_end_time,
                    'classroom_name' => $request->classroom_name,
                    'cultural_activity' => $request->cultural_activity,
                    'payment_method' => $request->payment_method,
                    'signature' => $signature_name,
                    'student_id' => $id,
                ]);
                return back()->with('success','تم انشاء بيانات الدراسة بنجاح');
            }else{
                if($request->signature){
                    $signature_name = time().rand(10,99).'.'.$request->signature->getClientOriginalExtension();
                    $request->signature->storeAs('public/signature',$signature_name);
                    $dataStudent->signature = $signature_name;
                }
                $dataStudent->section = $request->section;
                $dataStudent->level = $request->level;
                $dataStudent->attend = $request->attend;
                $dataStudent->course_type = $request->course_type;
                $dataStudent->course_days = json_encode($request->course_days);
                $dataStudent->course_start_time = $request->course_start_time;
                $dataStudent->course_end_time = $request->course_end_time;
                $dataStudent->classroom_name = $request->classroom_name;
                $dataStudent->cultural_activity = $request->cultural_activity;
                $dataStudent->payment_method = $request->payment_method;
                $dataStudent->student_id = $id;

                $dataStudent->save();
                return back()->with('success','تم تعديل بيانات الدراسة بنجاح');

            }
        }catch(Exception $e){
            return back()->with('error',$e->getMessage())->withInput();
        }

    }

    public function studentDestroy($id){
        $student = Student::findOrFail($id);
        $user_id = $student->user_id;
        DB::delete('DELETE FROM revenue WHERE user_id = '.$user_id);
        DB::delete('DELETE FROM user_diploma WHERE user_id = '.$user_id);
        DB::delete('DELETE FROM user_course WHERE user_id = '.$user_id);
        DB::delete('DELETE FROM data_student WHERE student_id = '.$id);
        $student->delete();
        DB::delete('DELETE FROM users WHERE id = '.$user_id);
        return back();
    }

    ## Courses ##
    public function addCourse(){
        $teachers = Teacher::whereHas('user', function ($query) {
            return $query->where('status', '=', 'active');
        })->get();
        return view('data_entry.courses.add_course',['teachers' => $teachers]);
    }

    public function buyCourse(){
        $date = date('Y-m-d');
        $courses = Course::where('end_date', '>=', $date)
            ->where(function ($query) {
                $query->whereNull('diploma_id')
                    ->orWhere('diploma_id', '=', '')
                    ->orWhere('diploma_id', '=', 0);
            })
            ->get();
        $students = Student::whereHas('user', function ($query) {
            return $query->where('status', '=', 'active');
        })->get();
        return view('data_entry.courses.buy_course',['courses' => $courses, 'students' => $students]);
    }

    public function ajaxGetTimeByCourse($id){
        $course = Course::findOrFail($id);
        $datacourses = [];
        foreach(json_decode($course->days) as $value){
            $days[] = WeekDays::weekDaysAr()[$value];
        }
        $name =  $course->name;
        $startdate =  date("Y:m:d", strtotime($course->start_date));
        $enddate =  date("Y:m:d", strtotime($course->end_date));
        $starttime = date("h:i A", strtotime($course->start_time));
        $endtime = date("h:i A", strtotime($course->end_time));
        $datacourses[] = ['name' => $name, 'days' => $days, 'startdate' => $startdate, 'enddate' => $enddate, 'starttime' => $starttime, 'endtime' => $endtime];
        $result = ['datacourses' => $datacourses];
        return $result;
    }




    public function enrollCourse(Request $request){
        try{
            $request->validate([
                'course_id' => ['required'],
                'student_id' => ['required'],
            ]);

            $course = Course::findOrFail($request->course_id);
            $student = Student::findOrFail($request->student_id);
            $courses = $student->user->studentCourses;

            if($course->price >= $request->value_rec && $request->value_rec >= 0){

                $course_start_time = DateTime::createFromFormat('H:i:s', $course->start_time);
                $course_start_date = DateTime::createFromFormat('Y-m-d', $course->start_date);
                $days = json_decode($course->days);

                foreach($courses as $item){
                    $item_start_time = DateTime::createFromFormat('H:i:s', $item->start_time);
                    $item_end_time = DateTime::createFromFormat('H:i:s', $item->end_time);
                    $item_start_date = DateTime::createFromFormat('Y-m-d', $item->start_date);
                    $item_end_date = DateTime::createFromFormat('Y-m-d', $item->end_date);

                    if($item->id == $course->id){
                        return back()->with('error','مسجل في هذا الكورس سابقاً')->withInput();

                    }if($course_start_time >= $item_start_time && $course_start_time <= $item_end_time){
                        foreach(json_decode($item->days) as $value){
                            if(in_array($value,$days)){
                                if($course_start_date >= $item_start_date && $course_start_date <= $item_end_date){
                                    return back()->with('error','توقيت هذا الكورس يتعارض مع برنامج الطالب')->withInput();
                                }
                            }
                        }
                    }
                }

                $student->user->studentCourses()->syncWithoutDetaching($course->id);

                $value_rem = $course->price - $request->value_rec;
                Revenue::create([
                    'course_id' => $course->id,
                    'date_of_rec' => date("Y-m-d"),
                    'value' => $course->price,
                    'currency' => $course->currency,
                    'user_id' => $student->user->id,
                    'value_rec' => $request->value_rec,
                    'value_rem' => $value_rem,
                ]);

            }else{
                return back()->with('error','القيمة المدفوعة اكبر من سعر الكورس او خاطئة')->withInput();
            }

            return back()->with('success','تم تسجيل هذا الكورس للطالب')->withInput();

        }catch(Exception $e){
            return back()->with('error',$e->getMessage())->withInput();
        }
    }

    public function registerCourse(Request $request){
        try{
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'section' => ['required', 'string', 'max:255'],
                'start_date' => ['required','date_format:Y-m-d'],
                'end_date' => ['required','date_format:Y-m-d'],
                'level' => ['required', 'string', 'max:255'],
                'start_time'=> ['required', 'date_format:H:i'],
                'end_time' => ['required', 'date_format:H:i'],
                'total_days' => ['required', 'numeric'],
                'average_hours' => ['required', 'numeric'],
                'total_hours' => ['required', 'numeric'],
                'n_d_per_week' => ['required', 'numeric'],
                'days' => ['required'],
                'price' => ['required', 'numeric'],
                'currency' => ['required','in:USD,D'],
                'teacher_id' => ['required'],
            ]);

            Course::create([
                'name' => $request->name,
                'section' => $request->section,
                'start_date' => $request->start_date,
                'end_date' =>  $request->end_date,
                'level' => $request->level,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'total_days' => $request->total_days,
                'average_hours' => $request->average_hours,
                'total_hours' => $request->total_hours,
                'n_d_per_week' => $request->n_d_per_week,
                'days' => json_encode($request->days),
                'price' => $request->price,
                'currency' => $request->currency,
                'teacher_id' => $request->teacher_id,
            ]);
            return back()->with('success','تم إضافةالكورس بنجاح ');
        }catch(Exception $e){
            return back()->with('error',$e->getMessage())->withInput();
        }
    }

    public function courses(Request $request, $orderBy = 'id', $sort = 'asc')
    {
        $searchQuery = $request->input('search');
        $sectionFilter = $request->input('section');

        // Validate sort direction
        $sort = strtolower($sort);
        if (!in_array($sort, ['asc', 'desc'])) {
            $sort = 'asc';
        }

        // Allowed columns for ordering
        $allowedColumns = ['id', 'name', 'section', 'start_date', 'end_date', 'level', 'price'];

        if (!in_array($orderBy, $allowedColumns)) {
            $orderBy = 'id';
        }

        // Build the query
        $courses = Course::with('user')
            ->where('diploma_id', '=', null)
            ->when($searchQuery, function ($q) use ($searchQuery) {
                $q->where(function($query) use ($searchQuery) {
                    $query->where('name', 'like', "%{$searchQuery}%")
                        ->orWhere('section', 'like', "%{$searchQuery}%")
                        ->orWhere('level', 'like', "%{$searchQuery}%")
                        ->orWhereHas('user', function ($userQuery) use ($searchQuery) {
                            $userQuery->where('name', 'like', "%{$searchQuery}%");
                        });
                });
            })
            ->when($sectionFilter, function ($q) use ($sectionFilter) {
                $q->where('section', $sectionFilter);
            })
            ->orderBy($orderBy, $sort)
            ->paginate(8);

        // Get unique sections for the dropdown
        $sections = Course::where('diploma_id', '=', null)
            ->select('section')
            ->distinct()
            ->orderBy('section')
            ->pluck('section');

        // If it's an AJAX request, return only the table partial
        if ($request->ajax()) {
            return view('data_entry.partials.courses_table', compact('courses'))->render();
        }

        return view('data_entry.courses.courses', compact('courses', 'sections'));
    }

    public function editCourse($id){
        if(!in_array(EnumPermission::modification,json_decode(Auth::user()->permission))){
            return back()->with('error','ليس لديك صلاحية التعديل');
        }
        $teachers = Teacher::whereHas('user', function ($query) {
            return $query->where('status', '=', 'active');
        })->get();
        $course = Course::with('user')->findOrFail($id);
        return view('data_entry.courses.edit_course',['course' => $course, 'teachers' => $teachers]);
    }

    public function updateCourse(Request $request, $id){
        if(!in_array(EnumPermission::modification,json_decode(Auth::user()->permission))){
            return back()->with('error','ليس لديك صلاحية التعديل');
        }
        try{
            $course = Course::findOrFail($id);
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'section' => ['required', 'string', 'max:255'],
                'start_date' => ['required','date_format:Y-m-d'],
                'end_date' => ['required','date_format:Y-m-d'],
                'level' => ['required', 'string', 'max:255'],
                'start_time'=> ['required', 'date_format:H:i'],
                'end_time' => ['required', 'date_format:H:i'],
                'total_days' => ['required', 'numeric'],
                'average_hours' => ['required', 'numeric'],
                'total_hours' => ['required', 'numeric'],
                'n_d_per_week' => ['required', 'numeric'],
                'days' => ['required'],
                'price' => ['required', 'numeric'],
                'currency' => ['required','in:USD,D'],
                'teacher_id' => ['required'],
            ]);

            $course->name = $request->name;
            $course->section = $request->section;
            $course->start_date = $request->start_date;
            $course->end_date =  $request->end_date;
            $course->level = $request->level;
            $course->start_time = $request->start_time;
            $course->end_time = $request->end_time;
            $course->total_days = $request->total_days;
            $course->average_hours = $request->average_hours;
            $course->total_hours = $request->total_hours;
            $course->n_d_per_week = $request->n_d_per_week;
            $course->days = json_encode($request->days);
            $course->price = $request->price;
            $course->currency = $request->currency;
            $course->teacher_id = $request->teacher_id;

            $course->save();

            return back()->with('success','تم تعديل الكورس بنجاح ');
        }catch(Exception $e){
            return back()->with('error',$e->getMessage())->withInput();
        }
    }

    public function courseDestroy($id){
        $course = Course::findOrFail($id);
        DB::delete('DELETE FROM user_course WHERE course_id = '.$id);
        DB::delete('DELETE FROM revenue WHERE course_id = '.$id);
        $course->delete();
        return back();
    }

    ### diploma ###
    public function addDiploma(){
        $teachers = Teacher::whereHas('user', function ($query) {
            return $query->where('status', '=', 'active');
        })->get();
        return view('data_entry.courses.add_diploma',['teachers' => $teachers]);
    }

    public function registerDiploma(Request $request){
        try{
            $request->validate([
                'diploma_name' => ['required', 'string', 'max:255'],
                'number_course' => ['required', 'numeric'],
            ]);

            $diploma = Diploma::create([
                'name' => $request->diploma_name,
                'number_course' => $request->number_course,
            ]);

            $course_request_array = [];
            $price = 0;
            $currency = '';
            for($i = 1; $i <= $request->number_course; $i++){
                $course_request_array [$i] = [
                    'name' => 'name'.$i,
                    'section' => 'section'.$i,
                    'start_date' => 'start_date'.$i,
                    'end_date' => 'end_date'.$i,
                    'level' => 'level'.$i,
                    'start_time' => 'start_time'.$i,
                    'end_time' => 'end_time'.$i,
                    'total_days' => 'total_days'.$i,
                    'average_hours' => 'average_hours'.$i,
                    'total_hours' => 'total_hours'.$i,
                    'n_d_per_week' => 'n_d_per_week'.$i,
                    'days' => 'days'.$i,
                    'price' => 'price'.$i,
                    'currency' => 'currency'.$i,
                    'teacher_id' => 'teacher_id'.$i,
                ];


                $request->validate([
                    $course_request_array[$i]['name'] => ['required', 'string', 'max:255'],
                    $course_request_array[$i]['section'] => ['required', 'string', 'max:255'],
                    $course_request_array[$i]['start_date'] => ['required','date_format:Y-m-d'],
                    $course_request_array[$i]['end_date'] => ['required','date_format:Y-m-d'],
                    $course_request_array[$i]['level'] => ['required', 'string', 'max:255'],
                    $course_request_array[$i]['start_time'] => ['required', 'date_format:H:i'],
                    $course_request_array[$i]['end_time'] => ['required', 'date_format:H:i'],
                    $course_request_array[$i]['total_days'] => ['required', 'numeric'],
                    $course_request_array[$i]['average_hours'] => ['required', 'numeric'],
                    $course_request_array[$i]['total_hours'] => ['required', 'numeric'],
                    $course_request_array[$i]['n_d_per_week'] => ['required', 'numeric'],
                    $course_request_array[$i]['days'] => ['required'],
                    $course_request_array[$i]['price'] => ['required', 'numeric'],
                    $course_request_array[$i]['currency'] => ['required','in:USD,D'],
                    $course_request_array[$i]['teacher_id'] => ['required'],
                ]);

                $diploma->diplomaCourses()->create([
                    'name' => $request[$course_request_array[$i]['name']],
                    'section' => $request[$course_request_array[$i]['section']],
                    'start_date' => $request[$course_request_array[$i]['start_date']],
                    'end_date' =>  $request[$course_request_array[$i]['end_date']],
                    'level' => $request[$course_request_array[$i]['level']],
                    'start_time' => $request[$course_request_array[$i]['start_time']],
                    'end_time' => $request[$course_request_array[$i]['end_time']],
                    'total_days' => $request[$course_request_array[$i]['total_days']],
                    'average_hours' => $request[$course_request_array[$i]['average_hours']],
                    'total_hours' => $request[$course_request_array[$i]['total_hours']],
                    'n_d_per_week' => $request[$course_request_array[$i]['n_d_per_week']],
                    'days' => json_encode($request[$course_request_array[$i]['days']]),
                    'price' => $request[$course_request_array[$i]['price']],
                    'currency' => $request[$course_request_array[$i]['currency']],
                    'teacher_id' => $request[$course_request_array[$i]['teacher_id']],
                    'diploma_id' => $diploma->id,
                ]);
                $price += $request[$course_request_array[$i]['price']];
                $currency = $request[$course_request_array[$i]['currency']];
            }
            $diploma->price = $price;
            $diploma->currency = $currency;
            $diploma->save();
            return back()->with('success','تم إضافةالدبلوم بنجاح ');
        }catch(Exception $e){
            return back()->with('error',$e->getMessage())->withInput();
        }
    }

    /**
     * THIS IS THE UPDATED FUNCTION
     */
    public function diplomaCourse(Request $request)
    {
        $allDiploma = Diploma::all();
        $diplomaCourse = null;
        $sections = collect(); // Create an empty collection for sections

        $diploma_id = $request->input('diploma_id');
        $searchQuery = $request->input('search');
        $sectionFilter = $request->input('section');

        if ($diploma_id) {
            // Base query for courses of the selected diploma
            $query = Course::with('user')
                ->where('diploma_id', '=', $diploma_id)
                ->when($searchQuery, function ($q) use ($searchQuery) {
                    $q->where(function($query) use ($searchQuery) {
                        $query->where('name', 'like', "%{$searchQuery}%")
                            ->orWhere('section', 'like', "%{$searchQuery}%")
                            ->orWhere('level', 'like', "%{$searchQuery}%")
                            ->orWhereHas('user', function ($userQuery) use ($searchQuery) {
                                $userQuery->where('name', 'like', "%{$searchQuery}%");
                            });
                    });
                })
                ->when($sectionFilter, function ($q) use ($sectionFilter) {
                    $q->where('section', $sectionFilter);
                })
                ->orderBy('id', 'asc'); // You can make order/sort dynamic later

            // Get unique sections for *this* diploma's dropdown
            $sections = Course::where('diploma_id', '=', $diploma_id)
                ->select('section')
                ->distinct()
                ->orderBy('section')
                ->pluck('section');

            // Paginate the results
            $diplomaCourse = $query->paginate(10); // Using paginate instead of get()
        }

        // Handle AJAX requests for filtering/pagination
        if ($request->ajax()) {
            return view('data_entry.partials.diploma_table', compact('diplomaCourse'))->render();
        }

        // Handle initial page load
        return view('data_entry.courses.diploma_course', [
            'allDiploma' => $allDiploma,
            'diplomaCourse' => $diplomaCourse,
            'sections' => $sections,
            'selectedDiplomaId' => $diploma_id // Pass back the selected ID
        ]);
    }

    public function editDiploma($id){
        $teachers = Teacher::whereHas('user', function ($query) {
            return $query->where('status', '=', 'active');
        })->get();
        $diploma = Diploma::findOrFail($id);

        $isfindStudentindiploma = DB::table('user_diploma')->where('diploma_id', '=', $diploma->id)->first() == null ? false: true;
        return view('data_entry.courses.edit_diploma',['teachers' => $teachers,'diploma' => $diploma, 'isfindStudentindiploma' => $isfindStudentindiploma]);
    }

    public function updateDiploma(Request $request, $id){
        $diploma = Diploma::findOrFail($id);
        try{
            $request->validate([
                'diploma_name' => ['required', 'string', 'max:255'],
            ]);

            if($request->number_course != null){
                $request->validate([
                    'number_course' => ['required', 'numeric'],
                ]);
                $diploma->number_course = $request->number_course;
                $diploma_number_course = $request->number_course;
            }else{
                $diploma_number_course = $diploma->number_course;
            }

            $diploma->name = $request->diploma_name;
            $diploma->save();

            $old_revenues = DB::table('revenue')->select('diploma_id','user_id', 'value_rec')
                ->where('diploma_id','=',$diploma->id)
                ->groupBy('user_id')->get();

            Revenue::where('diploma_id','=',$diploma->id)->delete();
            $diploma->diplomaCourses()->delete();

            $course_request_array = [];
            $price = 0;
            $currency = '';
            for($i = 1; $i <= $diploma_number_course; $i++){
                $course_request_array [$i] = [
                    'name' => 'name'.$i,
                    'section' => 'section'.$i,
                    'start_date' => 'start_date'.$i,
                    'end_date' => 'end_date'.$i,
                    'level' => 'level'.$i,
                    'start_time' => 'start_time'.$i,
                    'end_time' => 'end_time'.$i,
                    'total_days' => 'total_days'.$i,
                    'average_hours' => 'average_hours'.$i,
                    'total_hours' => 'total_hours'.$i,
                    'n_d_per_week' => 'n_d_per_week'.$i,
                    'days' => 'days'.$i,
                    'price' => 'price'.$i,
                    'currency' => 'currency'.$i,
                    'teacher_id' => 'teacher_id'.$i,
                ];

                $request->validate([
                    $course_request_array[$i]['name'] => ['required', 'string', 'max:255'],
                    $course_request_array[$i]['section'] => ['required', 'string', 'max:255'],
                    $course_request_array[$i]['start_date'] => ['required','date_format:Y-m-d'],
                    $course_request_array[$i]['end_date'] => ['required','date_format:Y-m-d'],
                    $course_request_array[$i]['level'] => ['required', 'string', 'max:255'],
                    $course_request_array[$i]['start_time'] => ['required', 'date_format:H:i'],
                    $course_request_array[$i]['end_time'] => ['required', 'date_format:H:i'],
                    $course_request_array[$i]['total_days'] => ['required', 'numeric'],
                    $course_request_array[$i]['average_hours'] => ['required', 'numeric'],
                    $course_request_array[$i]['total_hours'] => ['required', 'numeric'],
                    $course_request_array[$i]['n_d_per_week'] => ['required', 'numeric'],
                    $course_request_array[$i]['days'] => ['required'],
                    $course_request_array[$i]['price'] => ['required', 'numeric'],
                    $course_request_array[$i]['currency'] => ['required','in:USD,D'],
                    $course_request_array[$i]['teacher_id'] => ['required'],
                ]);


                $diploma->diplomaCourses()->create([
                    'name' => $request[$course_request_array[$i]['name']],
                    'section' => $request[$course_request_array[$i]['section']],
                    'start_date' => $request[$course_request_array[$i]['start_date']],
                    'end_date' =>  $request[$course_request_array[$i]['end_date']],
                    'level' => $request[$course_request_array[$i]['level']],
                    'start_time' => $request[$course_request_array[$i]['start_time']],
                    'end_time' => $request[$course_request_array[$i]['end_time']],
                    'total_days' => $request[$course_request_array[$i]['total_days']],
                    'average_hours' => $request[$course_request_array[$i]['average_hours']],
                    'total_hours' => $request[$course_request_array[$i]['total_hours']],
                    'n_d_per_week' => $request[$course_request_array[$i]['n_d_per_week']],
                    'days' => json_encode($request[$course_request_array[$i]['days']]),
                    'price' => $request[$course_request_array[$i]['price']],
                    'currency' => $request[$course_request_array[$i]['currency']],
                    'teacher_id' => $request[$course_request_array[$i]['teacher_id']],
                    'diploma_id' => $diploma->id,
                ]);
                $price += $request[$course_request_array[$i]['price']];
                $currency = $request[$course_request_array[$i]['currency']];
            }
            $diploma->price = $price;
            $diploma->currency = $currency;
            $diploma->save();

            if($old_revenues != null){
                foreach( $old_revenues as $revenue){
                    foreach($diploma->diplomaCourses as $value){
                        Revenue::create([
                            'course_id' => $value->id,
                            'date_of_rec' => date("Y-m-d"),
                            'type' => 'diploma',
                            'value' => $value->price,
                            'currency' => $value->currency,
                            'user_id' => $revenue->user_id,
                            'value_rec' => $revenue->value_rec,
                            'value_rem' => $price - $revenue->value_rec,
                            'diploma_id' => $diploma->id,
                        ]);
                    }
                }
            }
            return back()->with('success','تم تعديل بيانات الدبلوم بنجاح ');
        }catch(Exception $e){
            return back()->with('error',$e->getMessage())->withInput();
        }
    }

    public function buyDiploma()
    {
        $diploma = Diploma::all();
        $students = Student::whereHas('user', function ($query) {
            return $query->where('status', '=', 'active');
        })->get();

        return view('data_entry.courses.buy_diploma', [
            'diploma' => $diploma,
            'students' => $students
        ]);
    }

    public function ajaxGetTimeByDiploma($id){
        $diploma = Diploma::find($id);
        if (!$diploma) {
            return response()->json(['error' => 'Diploma not found'], 404);
        }

        $datacourses = [];
        foreach($diploma->diplomaCourses as $course){
            $days = [];
            foreach(json_decode($course->days) as $value){
                $days[] = WeekDays::weekDaysAr()[$value];
            }
            $name =  $course->name;
            $startdate =  date("Y-m-d", strtotime($course->start_date));
            $enddate =  date("Y-m-d", strtotime($course->end_date));
            $starttime = date("h:i A", strtotime($course->start_time));
            $endtime = date("h:i A", strtotime($course->end_time));
            $datacourses[] = ['name' => $name, 'days' => implode(', ', $days), 'startdate' => $startdate, 'enddate' => $enddate, 'starttime' => $starttime, 'endtime' => $endtime];
        }
        return response()->json(['datacourses' => $datacourses]);
    }

    public function enrollDiploma(Request $request){
        try{
            $request->validate([
                'diploma_id' => ['required'],
                'student_id' => ['required'],
            ]);

            $diploma = Diploma::findOrFail($request->diploma_id);
            $student = Student::findOrFail($request->student_id);
            $courses = $student->user->studentCourses;

            foreach($student->user->studentDiploma as $ditem){
                if($ditem->id == $diploma->id){
                    return back()->with('error','مسجل في هذا الدبلوم سابقاً')->withInput();

                }
            }

            foreach($diploma->diplomaCourses as $course){
                if($diploma->price >= $request->value_rec && $request->value_rec >= 0){

                    $course_start_time = DateTime::createFromFormat('H:i:s', $course->start_time);
                    $course_start_date = DateTime::createFromFormat('Y-m-d', $course->start_date);
                    $days = json_decode($course->days);

                    foreach($courses as $item){
                        $item_start_time = DateTime::createFromFormat('H:i:s', $item->start_time);
                        $item_end_time = DateTime::createFromFormat('H:i:s', $item->end_time);
                        $item_start_date = DateTime::createFromFormat('Y-m-d', $item->start_date);
                        $item_end_date = DateTime::createFromFormat('Y-m-d', $item->end_date);

                        if($item->id == $course->id){
                            return back()->with('error','مسجل في احد الكورسات الخاصة بهذا الدبلوم سابقاً')->withInput();

                        }if($course_start_time >= $item_start_time && $course_start_time <= $item_end_time){
                            foreach(json_decode($item->days) as $value){
                                if(in_array($value,$days)){
                                    if($course_start_date >= $item_start_date && $course_start_date <= $item_end_date){
                                        return back()->with('error','توقيت احد الكورسات ضمن الدبلوم يتعارض مع برنامج الطالب')->withInput();
                                    }
                                }
                            }
                        }
                    }

                    $value_rem = $diploma->price - $request->value_rec;

                    $student->user->studentCourses()->syncWithoutDetaching($course->id);
                    Revenue::create([
                        'course_id' => $course->id,
                        'date_of_rec' => date("Y-m-d"),
                        'type' => 'diploma',
                        'value' => $course->price,
                        'currency' => $course->currency,
                        'user_id' => $student->user->id,
                        'value_rec' => $request->value_rec,
                        'value_rem' => $value_rem,
                        'diploma_id' => $diploma->id,
                    ]);
                }else{
                    return back()->with('error','القيمة المدفوعة اكبر من سعر الدبلوم او خاطئة')->withInput();
                }
            }
            $student->user->studentDiploma()->syncWithoutDetaching($diploma->id);
            return back()->with('success','تم تسجيل هذا الدبلوم للطالب')->withInput();

        }catch(Exception $e){
            return back()->with('error',$e->getMessage())->withInput();
        }
    }

    public function diplomaDestroy($id){
        $diploma = Diploma::findOrFail($id);
        DB::delete('DELETE FROM user_diploma WHERE diploma_id = '.$id);
        DB::delete('DELETE FROM revenue WHERE diploma_id = '.$id);
        foreach($diploma->diplomaCourses as $value){
            DB::delete('DELETE FROM user_course WHERE course_id = '.$value->id);
        }
        DB::delete('DELETE FROM courses WHERE diploma_id = '.$id);
        $diploma->delete();

        $allDiploma = Diploma::all();
        $diplomaCourse = null;
        return view('data_entry.courses.diploma_course', ['allDiploma' => $allDiploma, 'diplomaCourse' => $diplomaCourse]);
    }

    ### translation ###
    public function addCustomer(){
        return view('data_entry.translation.add_customer');
    }

    public function registerCustomer(Request $request){
        try{
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:255'],
                'address' => ['required', 'string', 'max:255'],
            ]);

            Customer::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
            return back()->with('success','تم اضافة الزبون بنجاح');
        }catch(Exception $e){
            return back()->with('error',$e->getMessage())->withInput();
        }
    }
    public function teachers(Request $request)
    {
        $orderBy = $request->get('orderBy', 'id');
        $sort = $request->get('sort', 'asc');
        $search = $request->get('search', '');

        // Start the query
        $query = Teacher::with('user');

        // Apply search filter if search term exists
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('en_name', 'like', "%{$search}%")
                    ->orWhere('id_number', 'like', "%{$search}%")
                    ->orWhere('nationality', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhereHas('user', function($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Apply sorting
        if ($orderBy === 'id') {
            $query->orderBy('id', $sort);
        } elseif ($orderBy === 'en_name') {
            $query->orderBy('en_name', $sort);
        }

        // Paginate results
        $teachers = $query->paginate(15);

        // Check if this is an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return view('data_entry.partials.teachers_table', compact('teachers'))->render();
        }

        return view('data_entry.teacher.teachers', compact('teachers'));
    }







    public function editCustomer($id){
        if(!in_array(EnumPermission::modification,json_decode(Auth::user()->permission))){
            return back()->with('error','ليس لديك صلاحية التعديل');
        }
        $customer = Customer::findOrFail($id);
        return view('data_entry.translation.edit_customer',['customer' => $customer]);
    }

    public function updateCustomer(Request $request,$id){
        if(!in_array(EnumPermission::modification,json_decode(Auth::user()->permission))){
            return back()->with('error','ليس لديك صلاحية التعديل');
        }
        try{
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:255'],
                'address' => ['required', 'string', 'max:255'],
            ]);

            $customer = Customer::findOrFail($id);
            $customer->name = $request->name;
            $customer->phone =  $request->phone;
            $customer->address = $request->address;
            $customer->save();

            return back()->with('success','تم تعديل البيانات بنجاح');
        }catch(Exception $e){
            return back()->with('error',$e->getMessage())->withInput();
        }
    }

    public function customers($orderBy, $sort){
        if($orderBy == 'null' || $sort == 'null'){
            $customers = Customer::paginate(8);
        }else{
            $customers = Customer::orderBy($orderBy, $sort)->paginate(8);
        }
        return view('data_entry.translation.customers',['customers' => $customers]);
    }

    public function addTranslationDeal(){
        $customers = Customer::all();
        return view('data_entry.translation.add_translation_deal',['customers' => $customers]);
    }

    public function registerTranslationDeal(Request $request){
        try{
            $request->validate([
                "number_of_sheets" => ['required', 'numeric'],
                "number_of_transaction"  => ['required', 'numeric'],
                "context"  => ['required', 'string'],
                "language"  => ['required', 'string'],
                "customer_id"  => ['required'],
                "price"  => ['required', 'numeric'],
                "currency" => ['required', 'string'],
                "translator_share"  => ['required', 'numeric'],
                "academy_share"  => ['required', 'numeric'],
                "received"  => ['required', 'numeric'],
                "remaining"  => ['required', 'numeric'],
                "payment_method"  => ['required', 'string'],
                "date_of_receipt"  => ['required','date_format:Y-m-d'],
                "due_date"  => ['required','date_format:Y-m-d'],
            ]);

            TranslationDel::create([
                "number_of_sheets" => $request->number_of_sheets,
                "number_of_transaction" => $request->number_of_transaction,
                "context" => $request->context,
                "language" => $request->language,
                "customer_id" => $request->customer_id,
                "price" => $request->price,
                "currency" => $request->currency,
                "translator_share" => $request->translator_share,
                "academy_share" => $request->academy_share,
                "received" => $request->received,
                "remaining" => $request->remaining,
                "payment_method" => $request->payment_method,
                "date_of_receipt" => $request->date_of_receipt,
                "due_date" => $request->due_date,
            ]);

            return back()->with('success','تم اضافة المعاملة بنجاح');
        }catch(Exception $e){
            return back()->with('error',$e->getMessage())->withInput();
        }
    }

    public function translationDeals($orderBy, $sort){
        if($orderBy == 'null' || $sort == 'null'){
            $translationDeals = TranslationDel::with('customer')->paginate(8);
        }else{
            $translationDeals = TranslationDel::with('customer')->orderBy($orderBy, $sort)->paginate(8);
        }
        return view('data_entry.translation.translation_deals',['translationDeals' => $translationDeals]);
    }

    public function editTranslationDeal($id){
        if(!in_array(EnumPermission::modification,json_decode(Auth::user()->permission))){
            return back()->with('error','ليس لديك صلاحية التعديل');
        }
        $translation_deal = TranslationDel::findOrFail($id);
        $customers = Customer::all();
        return view('data_entry.translation.edit_translation_deal',['translation_deal' => $translation_deal, 'customers' => $customers]);
    }

    public function updateTranslationDeal(Request $request,$id){
        if(!in_array(EnumPermission::modification,json_decode(Auth::user()->permission))){
            return back()->with('error','ليس لديك صلاحية التعديل');
        }
        try{
            $request->validate([
                "number_of_sheets" => ['required', 'numeric'],
                "number_of_transaction"  => ['required', 'numeric'],
                "context"  => ['required', 'string'],
                "language"  => ['required', 'string'],
                "customer_id"  => ['required'],
                "price"  => ['required', 'numeric'],
                "currency" => ['required', 'string'],
                "translator_share"  => ['required', 'numeric'],
                "academy_share"  => ['required', 'numeric'],
                "received"  => ['required', 'numeric'],
                "remaining"  => ['required', 'numeric'],
                "payment_method"  => ['required', 'string'],
                "date_of_receipt"  => ['required','date_format:Y-m-d'],
                "due_date"  => ['required','date_format:Y-m-d'],
            ]);

            $translation_deal = TranslationDel::findOrFail($id);

            $translation_deal->number_of_sheets = $request->number_of_sheets;
            $translation_deal->number_of_transaction = $request->number_of_transaction;
            $translation_deal->context = $request->context;
            $translation_deal->language = $request->language;
            $translation_deal->customer_id = $request->customer_id;
            $translation_deal->price = $request->price;
            $translation_deal->currency = $request->currency;
            $translation_deal->translator_share = $request->translator_share;
            $translation_deal->academy_share = $request->academy_share;
            $translation_deal->received = $request->received;
            $translation_deal->remaining = $request->remaining;
            $translation_deal->payment_method = $request->payment_method;
            $translation_deal->date_of_receipt = $request->date_of_receipt;
            $translation_deal->due_date = $request->due_date;

            $translation_deal->save();

            return back()->with('success','تم تعديل بيانات المعاملة بنجاح');
        }catch(Exception $e){
            return back()->with('error',$e->getMessage())->withInput();
        }
    }

    public function customerDestroy($id){
        $customer = Customer::findOrFail($id);
        DB::delete('DELETE FROM translation_del WHERE customer_id = '.$id);
        $customer->delete();
        return back();
    }

    public function translationDealDestroy($id){
        $translation_deal = TranslationDel::findOrFail($id);
        $translation_deal->delete();
        return back();
    }

    ### Reports ###
    public function generalMonthlyReport(){
        if(!in_array(EnumPermission::view_report,json_decode(Auth::user()->permission))){
            return back()->with('error','ليس لديك صلاحية لعرض قسم التقارير');
        }
        $data = null;
        return view('data_entry.reports.general_monthly_report',['data'=> $data]);
    }

    public function generateGeneralMonthlyReport(Request $request){
        if(!in_array(EnumPermission::view_report,json_decode(Auth::user()->permission))){
            return back()->with('error','ليس لديك صلاحية لعرض قسم التقارير');
        }
        try{
            $request->validate([
                'from_date' => ['required','date_format:Y-m-d' ],
                'to_date' => ['required','date_format:Y-m-d' ],
            ]);
            $revenues = Revenue::where('type' , '=', 'course')->where('date_of_rec','>=',$request->from_date)->where('date_of_rec','<=',$request->to_date)->get();
            $translationDeals = TranslationDel::where('date_of_receipt','>=',$request->from_date)->where('due_date','<=',$request->to_date)->get();

            $t_course_price_usd = 0;
            $t_course_rec_usd = 0;
            $t_course_rem_usd = 0;
            $t_course_price_D = 0;
            $t_course_rec_D = 0;
            $t_course_rem_D = 0;
            $count_course = 0;

            $t_translation_price_usd = 0;
            $t_translation_rec_usd = 0;
            $t_translation_rem_usd = 0;
            $t_translation_price_D = 0;
            $t_translation_rec_D = 0;
            $t_translation_rem_D = 0;
            $count_translation = 0;

            $t_price_usd = 0;
            $t_rec_usd = 0;
            $t_rem_usd = 0;

            $t_price_D = 0;
            $t_rec_D = 0;
            $t_rem_D = 0;

            foreach($revenues as $value){
                if($value->currency == 'USD'){
                    $t_course_price_usd += $value->value;
                    $t_course_rec_usd += $value->value_rec;
                    $t_course_rem_usd += $value->value_rem;
                }else{
                    $t_course_price_D += $value->value;
                    $t_course_rec_D += $value->value_rec;
                    $t_course_rem_D += $value->value_rem;
                }
                $count_course += 1;
            }

            foreach($translationDeals as $value){
                if($value->currency == 'USD'){
                    $t_translation_price_usd += $value->price;
                    $t_translation_rec_usd += $value->received;
                    $t_translation_rem_usd += $value->remaining;
                }else{
                    $t_translation_price_D += $value->price;
                    $t_translation_rec_D += $value->received;
                    $t_translation_rem_D += $value->remaining;
                }
                $count_translation += 1;
            }

            $t_price_usd = $t_course_price_usd + $t_translation_price_usd;
            $t_rec_usd = $t_course_rec_usd + $t_translation_rec_usd;
            $t_rem_usd = $t_course_rem_usd + $t_translation_rem_usd;

            $t_price_D = $t_course_price_D + $t_translation_price_D;
            $t_rec_D = $t_course_rec_D + $t_translation_rec_D;
            $t_rem_D = $t_course_rem_D + $t_translation_rem_D;

            $data = [
                't_course_price_usd' => $t_course_price_usd,
                't_course_rec_usd' => $t_course_rec_usd,
                't_course_rem_usd' => $t_course_rem_usd,
                't_course_price_D' => $t_course_price_D,
                't_course_rec_D' => $t_course_rec_D,
                't_course_rem_D' => $t_course_rem_D,
                'count_course' => $count_course,
                't_translation_price_usd' => $t_translation_price_usd,
                't_translation_rec_usd' => $t_translation_rec_usd,
                't_translation_rem_usd' => $t_translation_rem_usd,
                't_translation_price_D' => $t_translation_price_D,
                't_translation_rec_D' => $t_translation_rec_D,
                't_translation_rem_D' => $t_translation_rem_D,
                'count_translation' => $count_translation,
                't_price_usd' => $t_price_usd,
                't_rec_usd' => $t_rec_usd,
                't_rem_usd' => $t_rem_usd,
                't_price_D' => $t_price_D,
                't_rec_D' => $t_rec_D,
                't_rem_D' => $t_rem_D,
            ];
            return view('data_entry.reports.general_monthly_report', ['data'=> $data]);
        }catch(Exception $e){
            return back()->with('error',$e->getMessage())->withInput();
        }
    }

    public function courseReport(){
        if(!in_array(EnumPermission::view_report,json_decode(Auth::user()->permission))){
            return back()->with('error','ليس لديك صلاحية لعرض قسم التقارير');
        }
        $courses = null;
        return view('data_entry.reports.course_report', ['courses'=> $courses]);
    }

    public function generateCourseReport(Request $request){
        if(!in_array(EnumPermission::view_report,json_decode(Auth::user()->permission))){
            return back()->with('error','ليس لديك صلاحية لعرض قسم التقارير');
        }
        try{
            $request->validate([
                'from_date' => ['required','date_format:Y-m-d' ],
                'to_date' => ['required','date_format:Y-m-d' ],
            ]);
            $courses = Course::with('user')->where('start_date','>=',$request->from_date)->where('end_date','<=',$request->to_date)->paginate(15);
            return view('data_entry.reports.course_report', ['courses'=> $courses]);
        }catch(Exception $e){
            return back()->with('error',$e->getMessage())->withInput();
        }
    }

    public function translationReport(){
        if(!in_array(EnumPermission::view_report,json_decode(Auth::user()->permission))){
            return back()->with('error','ليس لديك صلاحية لعرض قسم التقارير');
        }
        $translationDeals = null;
        return view('data_entry.reports.translation_report',['translationDeals'=> $translationDeals]);
    }

    public function generateTranslationReport(Request $request){
        if(!in_array(EnumPermission::view_report,json_decode(Auth::user()->permission))){
            return back()->with('error','ليس لديك صلاحية لعرض قسم التقارير');
        }
        try{
            $request->validate([
                'from_date' => ['required','date_format:Y-m-d' ],
                'to_date' => ['required','date_format:Y-m-d' ],
            ]);
            $translationDeals = TranslationDel::with('customer')->where('date_of_receipt','>=',$request->from_date)->where('due_date','<=',$request->to_date)->paginate(15);
            return view('data_entry.reports.translation_report', ['translationDeals'=> $translationDeals]);
        }catch(Exception $e){
            return back()->with('error',$e->getMessage())->withInput();
        }

    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
            'course_id' => 'required|exists:courses,id',
        ]);

        $couponCode = $request->input('coupon_code');
        $courseId = $request->input('course_id');

        $course = Course::findOrFail($courseId);
        $originalPrice = $course->price;
        $newPrice = $originalPrice;
        $message = 'كود الخصم غير صالح أو انتهت صلاحيته.';
        $success = false;

        // Simple coupon logic (example: 'DISCOUNT10' for 10% off)
        if ($couponCode === 'DISCOUNT10') {
            $newPrice = $originalPrice * 0.90; // 10% discount
            $message = 'تم تطبيق خصم 10% بنجاح!';
            $success = true;
        } elseif ($couponCode === 'FREECURSE') {
            $newPrice = 0; // Free course
            $message = 'تم تطبيق خصم 100% بنجاح! الكورس مجاني.';
            $success = true;
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'new_price' => round($newPrice, 2),
        ]);
    }
}
