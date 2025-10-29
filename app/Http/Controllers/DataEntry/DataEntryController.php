<?php

namespace App\Http\Controllers\DataEntry;

use App\Enums\EnumPermission;
use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Enums\WeekDays;
use App\Http\Controllers\Controller;
use App\Models\Booking;
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
use App\Models\CoworkingSpace;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class DataEntryController extends Controller
{
    public function mainIndex(){
        return view('data_entry.main');
    }

    ## teacher ##
    public function addTeacher(){
        return view('data_entry.teacher.add_teacher');
    }
      public function booking(){
          // جلب كل الطلاب
          $students = Student::with('user')->get();

          // جلب كل المساحات (كل مساحة عندها أسعار عادية وأسعار طلاب)
          $spaces = CoworkingSpace::all();

          return view('data_entry.coworking.booking_form', compact('students', 'spaces'));
      }
      public function bookinglist()
      {
          $bookings = Booking::all();
          return view('data_entry.coworking.booking', compact('bookings'));
      }
    public function savebooking(Request $request)
    {
        // Validation rules
        $rules = [
            'coworking_space_id' => 'required|exists:coworking_spaces,id',
            'booking_type' => ['required', Rule::in(['daily', 'weekly', 'monthly', 'three_month'])],
            'start_date' => 'required|date|after_or_equal:today',
            'email' => 'nullable|email|max:255',
        ];

        // Conditional validation based on checkbox
        if ($request->has('is_student_pricing') && $request->input('is_student_pricing') == '1') {
            $rules['student_id'] = 'required|exists:students,id';
            $rules['customer_name'] = 'nullable|string|max:255';
        } else {
            $rules['student_id'] = 'nullable|exists:students,id';
            $rules['customer_name'] = 'required|string|max:255';
        }

        $messages = [
            'student_id.required' => 'يرجى اختيار الطالب من القائمة.',
            'customer_name.required' => 'يرجى إدخال اسم العميل.',
            'start_date.after_or_equal' => 'تاريخ البدء يجب أن يكون اليوم أو بعده.',
            'coworking_space_id.required' => 'يرجى اختيار مساحة العمل.',
            'booking_type.required' => 'يرجى اختيار نوع الحجز.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Fetch coworking space
        $coworkingSpace = CoworkingSpace::find($request->input('coworking_space_id'));
        if (!$coworkingSpace) {
            return back()->withErrors(['coworking_space_id' => 'مساحة العمل المحددة غير صالحة.'])->withInput();
        }

        // Determine if student pricing applies
        $isStudent = $request->has('is_student_pricing') && $request->input('is_student_pricing') == '1';
        $studentId = $isStudent ? $request->input('student_id') : null;
        $customerName = $request->input('customer_name');
        $email = $request->input('email');
        $userId = auth()->id(); // The data entry person creating the booking

        // If student is selected, get student name and user_id
        if ($studentId) {
            $student = Student::with('user')->find($studentId);
            if ($student && $student->user) {
                $customerName = $student->user->name;
                $userId = $student->user->id;
                $email = $email ?? $student->user->email;
            }
        }

        // Validate customer name exists
        if (empty($customerName)) {
            return back()->withErrors(['customer_name' => 'يرجى إدخال اسم العميل أو اختيار طالب.'])->withInput();
        }

        // Server-side calculation
        try {
            $startDate = Carbon::parse($request->input('start_date'));
            $bookingType = $request->input('booking_type');
            $endDate = clone $startDate;
            $calculatedPrice = 0;

            switch ($bookingType) {
                case 'daily':
                    $calculatedPrice = $isStudent
                        ? ($coworkingSpace->student_daily_price ?? $coworkingSpace->daily_price ?? 0)
                        : ($coworkingSpace->daily_price ?? 0);
                    // End date same as start date for daily
                    break;
                case 'weekly':
                    $calculatedPrice = $isStudent
                        ? ($coworkingSpace->student_weekly_price ?? $coworkingSpace->weekly_price ?? 0)
                        : ($coworkingSpace->weekly_price ?? 0);
                    $endDate->addDays(6);
                    break;
                case 'monthly':
                    $calculatedPrice = $isStudent
                        ? ($coworkingSpace->student_monthly_price ?? $coworkingSpace->monthly_price ?? 0)
                        : ($coworkingSpace->monthly_price ?? 0);
                    $endDate->addMonth()->subDay();
                    break;
                case 'three_month':
                    $calculatedPrice = $isStudent
                        ? ($coworkingSpace->student_three_month_price ?? $coworkingSpace->three_month_price ?? 0)
                        : ($coworkingSpace->three_month_price ?? 0);
                    $endDate->addMonths(3)->subDay();
                    break;
            }

            $startDateString = $startDate->toDateString();
            $endDateString = $endDate->toDateString();

        } catch (Exception $e) {
            Log::error('Date calculation error: ' . $e->getMessage());
            return back()->withErrors(['date_error' => 'حدث خطأ أثناء حساب التواريخ.'])->withInput();
        }

        // Check capacity
        $capacity = $coworkingSpace->capacity ?? 1;

        $overlappingBookingsCount = Booking::where('coworking_space_id', $coworkingSpace->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startDateString, $endDateString) {
                $query->where('start_date', '<=', $endDateString)
                    ->where('end_date', '>=', $startDateString);
            })
            ->count();

        // Temporarily comment out capacity check for testing receipt system
        // if ($overlappingBookingsCount >= $capacity) {
        //     return back()->withErrors(['capacity_exceeded' => 'عذراً، المساحة محجوزة بالكامل في هذه الفترة.'])->withInput();
        // }
        
        // Log capacity info for debugging
        \Log::info("Capacity check: Space capacity: {$capacity}, Overlapping bookings: {$overlappingBookingsCount}");

        // Create booking
        try {
            $booking = Booking::create([
                'user_id' => $userId,
                'student_id' => $studentId,
                'name' => $customerName,
                'email' => $email ?? 'no-email@example.com',
                'coworking_space_id' => $coworkingSpace->id,
                'booking_type' => $bookingType,
                'start_date' => $startDateString,
                'end_date' => $endDateString,
                'total_price' => $calculatedPrice,
                'status' => 'confirmed',
            ]);

            // Generate receipt data
            $receiptData = [
                'receiptNumber' => 'RCP-' . str_pad($booking->id, 6, '0', STR_PAD_LEFT),
                'customerName' => $customerName,
                'bookingType' => $this->translateBookingType($bookingType),
                'coworkingSpaceName' => $coworkingSpace->name,
                'startDate' => $startDateString,
                'endDate' => $endDateString,
                'amount' => $calculatedPrice,
                'paymentMethod' => 'نقدي', // Default payment method
                'paymentStatus' => 'مؤكد',
                'paymentDateTime' => now()->format('Y-m-d H:i:s'),
                'companyLogo' => asset('images/logo.jpg'), // Company logo path
            ];

            // Store receipt data in session for the receipt page
            session(['receiptData' => $receiptData]);

            return redirect()->route('data_entry.receipt')
                ->with('success', 'تم إنشاء الحجز بنجاح!');

        } catch (Exception $e) {
            Log::error('Error saving booking: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->withErrors(['db_error' => 'حدث خطأ أثناء حفظ الحجز: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the receipt after successful booking
     */
    public function showReceipt()
    {
        $receiptData = session('receiptData');
        
        if (!$receiptData) {
            return redirect()->route('data_entry.bookinglist')
                ->with('error', 'لا يوجد إيصال لعرضه.');
        }
        
        return view('pdf_export.receipt', $receiptData);
    }

    /**
     * Translate booking type to Arabic
     */
    private function translateBookingType($type)
    {
        switch ($type) {
            case 'daily': return 'يومي';
            case 'weekly': return 'أسبوعي';
            case 'monthly': return 'شهري';
            case 'three_month': return '3 أشهر';
            default: return $type;
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
                'starttime' => date('H:i:s', strtotime($course->start_time)),
                'endtime' => date('H:i:s', strtotime($course->end_time))
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

            $student = Student::create([
                'en_name' => $request->en_name,
                'id_number' => $request->id_number,
                'nationality' => $request->nationality,
                'date_of_birth' => $request->date_of_birth,
                'academic_qualification' => $request->academic_qualification,
                'phone' => $request->phone,
                'pass_image' => $pass_image_name,
                'user_id' => $user->id,
            ]);

            return redirect()->route('data_entry.registration.choice', ['student_id' => $student->id])
                ->with('success','تم إضافة الطالب بنجاح. اختر نوع التسجيل المطلوب.');
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
    public function coworkingspaceindex(){
        // Fetch paginated data from the 'coworking_spaces' table
        $spaces = CoworkingSpace::latest()->paginate(15); // Get latest first, 15 per page

        return view('data_entry.coworking.coworking_space', compact('spaces'));
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

    public function buyCourse(Request $request){
        $date = date('Y-m-d');
        $courses = Course::where('end_date', '>=', $date)
            ->whereNull('diploma_id')
            ->get();
        $students = Student::whereHas('user', function ($query) {
            return $query->where('status', '=', 'active');
        })->get();
        
        // If student_id is provided, pre-select that student
        $selectedStudent = null;
        if ($request->has('student_id')) {
            $selectedStudent = Student::with('user')->find($request->student_id);
        }
        
        return view('data_entry.courses.buy_course', [
            'courses' => $courses, 
            'students' => $students,
            'selectedStudent' => $selectedStudent
        ]);
    }

    public function ajaxGetTimeByCourse($id){
        $course = Course::findOrFail($id);
        $datacourses = [];
        $days = [];
        foreach(json_decode($course->days) as $value){
            $days[] = WeekDays::weekDaysAr()[$value];
        }
        $name =  $course->name;
        $startdate =  date("Y-m-d", strtotime($course->start_date));
        $enddate =  date("Y-m-d", strtotime($course->end_date));
        $starttime = date("H:i:s", strtotime($course->start_time));
        $endtime = date("H:i:s", strtotime($course->end_time));
        $datacourses[] = ['name' => $name, 'days' => implode(', ', $days), 'startdate' => $startdate, 'enddate' => $enddate, 'starttime' => $starttime, 'endtime' => $endtime];
        $result = ['datacourses' => $datacourses];
        return response()->json($result);
    }




    public function enrollCourse(Request $request){
        try{
            $request->validate([
                'course_id' => ['required'],
                'student_id' => ['required'],
                'value_rec' => ['required', 'numeric', 'min:0'],
            ]);

            $course = Course::findOrFail($request->course_id);
            $student = Student::findOrFail($request->student_id);
            $courses = $student->user->studentCourses;

            // Validate that the payment amount is positive
            if($request->value_rec > 0){

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

                // If user pays the full discounted amount, remaining is 0
                $value_rem = 0;
                $revenue = Revenue::create([
                    'course_id' => $course->id,
                    'date_of_rec' => date("Y-m-d"),
                    'value' => $course->price,
                    'currency' => $course->currency,
                    'user_id' => $student->user->id,
                    'value_rec' => $request->value_rec,
                    'value_rem' => $value_rem,
                    'type' => 'course',
                    'coupon_code' => $request->coupon_code ?? null,
                    'original_price' => $course->price,
                    'discount_amount' => $course->price - $request->value_rec,
                    'coupon_type' => $request->coupon_type ?? null,
                ]);

                return redirect()->route('data_entry.enroll.success', ['revenue_id' => $revenue->id]);

            }else{
                return back()->with('error','القيمة المدفوعة يجب أن تكون أكبر من صفر')->withInput();
            }

        }catch(Exception $e){
            return back()->with('error',$e->getMessage())->withInput();
        }
    }

    public function enrollSuccess($revenue_id){
        $revenue = Revenue::with('user')->with('course')->findOrFail($revenue_id);
        $student = Student::with('dataStudent')->where('user_id', '=', $revenue->user->id)->first();
        return view('data_entry.courses.enroll_success', [
            'revenue' => $revenue,
            'student' => $student
        ]);
    }

    public function enrollDiplomaSuccess($revenue_id){
        $revenue = Revenue::with('user')->with('diploma')->findOrFail($revenue_id);
        $student = Student::with('dataStudent')->where('user_id', '=', $revenue->user->id)->first();
        return view('data_entry.courses.enroll_diploma_success', [
            'revenue' => $revenue,
            'student' => $student
        ]);
    }

    public function printStudentBill($id){
        $revenue = Revenue::with('user')->with('course')->with('diploma')->findOrFail($id);
        $student = Student::with('dataStudent')->where('user_id' , '=' , $revenue->user->id)->first();
        
        // Initialize default values
        $title = 'إيصال تسديد رسوم التسجيل';
        $pdfView = 'pdf_export.student_course';
        $htmlView = 'data_entry.courses.print_receipt';
        
        // Determine title and template based on revenue type
        if($revenue->diploma_id) {
            $title = 'إيصال تسديد رسوم التسجيل - دبلوم';
            $pdfView = 'pdf_export.student_diploma';
            $htmlView = 'data_entry.courses.print_diploma_receipt';
        } elseif($revenue->course_id) {
            $title = 'إيصال تسديد رسوم التسجيل';
            $pdfView = 'pdf_export.student_course';
            $htmlView = 'data_entry.courses.print_receipt';
        } else {
            $paymentTypeNames = [
                'tuition_fees' => 'الرسوم الدراسية',
                'additional_fees' => 'الرسوم الإضافية',
                'late_fees' => 'رسوم التأخير'
            ];
            $typeName = $paymentTypeNames[$revenue->type] ?? 'الرسوم';
            $title = 'إيصال تسديد ' . $typeName;
            $pdfView = 'pdf_export.student_course';
            $htmlView = 'data_entry.courses.print_receipt';
        }
        
        // Check if user wants direct print (from URL parameter)
        if(request()->has('print')) {
            // For diploma, use dedicated diploma print view
            if($revenue->diploma_id) {
                // For diploma, we need all courses in the diploma, not just revenues
                // Get the diploma and its courses
                $diploma = $revenue->diploma;
                if($diploma && $diploma->diplomaCourses) {
                    // Create revenue records for each course to match template expectation
                    $revenues = collect();
                    foreach($diploma->diplomaCourses as $course) {
                        $revenues->push((object)[
                            'id' => $revenue->id,
                            'date_of_rec' => $revenue->date_of_rec,
                            'course' => $course,
                            'user' => $revenue->user,
                            'diploma' => $diploma,
                            'value' => $revenue->value,
                            'value_rec' => $revenue->value_rec,
                            'value_rem' => $revenue->value_rem,
                            'currency' => $revenue->currency,
                            'coupon_code' => $revenue->coupon_code,
                            'original_price' => $revenue->original_price,
                            'discount_amount' => $revenue->discount_amount,
                            'coupon_type' => $revenue->coupon_type,
                        ]);
                    }
                } else {
                    // Fallback: use the revenue with its course if available
                    $revenues = collect([$revenue]);
                }
                return view('data_entry.courses.print_diploma_receipt', [
                    "title" => $title,
                    "revenue" => $revenues->all(),
                    "student" => $student,
                ]);
            } else {
                return view('data_entry.courses.print_receipt', [
                    "title" => $title,
                    "revenue" => $revenue,
                    "student" => $student,
                ]);
            }
        }
        
        // For PDF, use appropriate template
        if($revenue->diploma_id) {
            // For diploma, we need all courses in the diploma, not just revenues
            // Get the diploma and its courses
            $diploma = $revenue->diploma;
            if($diploma && $diploma->diplomaCourses) {
                // Create revenue records for each course to match template expectation
                $revenues = collect();
                foreach($diploma->diplomaCourses as $course) {
                    $revenues->push((object)[
                        'id' => $revenue->id,
                        'date_of_rec' => $revenue->date_of_rec,
                        'course' => $course,
                        'user' => $revenue->user,
                        'diploma' => $diploma,
                        'value' => $revenue->value,
                        'value_rec' => $revenue->value_rec,
                        'value_rem' => $revenue->value_rem,
                        'currency' => $revenue->currency,
                        'coupon_code' => $revenue->coupon_code,
                        'original_price' => $revenue->original_price,
                        'discount_amount' => $revenue->discount_amount,
                        'coupon_type' => $revenue->coupon_type,
                    ]);
                }
            } else {
                // Fallback: use the revenue with its course if available
                $revenues = collect([$revenue]);
            }
            return PDF::loadView($pdfView, [
                "title" => $title,
                "revenue" => $revenues->all(),
                "student" => $student,
            ],[
                'format' => 'A5',
                'orientation' => 'P',
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 8,
                'margin_bottom' => 8,
            ])->stream('ايصال-تسديد-رسوم-التسجيل-'.$id.'.pdf');
        } else {
            return PDF::loadView($pdfView, [
                "title" => $title,
                "revenue" => $revenue,
                "student" => $student,
            ],[
                'format' => 'A5',
                'orientation' => 'P',
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 8,
                'margin_bottom' => 8,
            ])->stream('ايصال-تسديد-رسوم-التسجيل-'.$id.'.pdf');
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
            ->whereNull('diploma_id')
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
                ->whereNotNull('diploma_id')
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

    public function buyDiploma(Request $request)
    {
        $diplomas = Diploma::all();
        $students = Student::whereHas('user', function ($query) {
            return $query->where('status', '=', 'active');
        })->get();

        // If student_id is provided, pre-select that student
        $selectedStudent = null;
        if ($request->has('student_id')) {
            $selectedStudent = Student::with('user')->find($request->student_id);
        }

        return view('data_entry.courses.buy_diploma', [
            'diplomas' => $diplomas,
            'students' => $students,
            'selectedStudent' => $selectedStudent
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
                'value_rec' => ['required', 'numeric', 'min:0'],
            ]);

            $diploma = Diploma::findOrFail($request->diploma_id);
            $student = Student::findOrFail($request->student_id);
            $courses = $student->user->studentCourses;

            foreach($student->user->studentDiploma as $ditem){
                if($ditem->id == $diploma->id){
                    return back()->with('error','مسجل في هذا الدبلوم سابقاً')->withInput();

                }
            }

            // Validate that the payment amount is positive
            if ($request->value_rec <= 0) {
                return back()->with('error', 'القيمة المدفوعة يجب أن تكون أكبر من صفر')->withInput();
            }

            // Collect all course IDs from the diploma
            $diplomaCourseIds = $diploma->diplomaCourses->pluck('id')->toArray();

            // Check for conflicts and prior enrollment before making any changes
            foreach ($diploma->diplomaCourses as $course) {
                $course_start_time = DateTime::createFromFormat('H:i:s', $course->start_time);
                $course_start_date = DateTime::createFromFormat('Y-m-d', $course->start_date);
                $days = json_decode($course->days);

                foreach ($courses as $item) {
                    $item_start_time = DateTime::createFromFormat('H:i:s', $item->start_time);
                    $item_end_time = DateTime::createFromFormat('H:i:s', $item->end_time);
                    $item_start_date = DateTime::createFromFormat('Y-m-d', $item->start_date);
                    $item_end_date = DateTime::createFromFormat('Y-m-d', $item->end_date);

                    if ($item->id == $course->id) {
                        return back()->with('error', 'مسجل في احد الكورسات الخاصة بهذا الدبلوم سابقاً')->withInput();
                    }

                    if ($course_start_time >= $item_start_time && $course_start_time <= $item_end_time) {
                        foreach (json_decode($item->days) as $value) {
                            if (in_array($value, $days)) {
                                if ($course_start_date >= $item_start_date && $course_start_date <= $item_end_date) {
                                    return back()->with('error', 'توقيت احد الكورسات ضمن الدبلوم يتعارض مع برنامج الطالب')->withInput();
                                }
                            }
                        }
                    }
                }
            }

            // Sync all diploma courses for the student
            $student->user->studentCourses()->syncWithoutDetaching($diplomaCourseIds);

            // Sync the diploma for the student
            $student->user->studentDiploma()->syncWithoutDetaching($diploma->id);

            // Create a single revenue record for the entire diploma
            // If user pays the full discounted amount, remaining is 0
            $value_rem = 0;
            $revenue = Revenue::create([
                'date_of_rec' => date("Y-m-d"),
                'type' => 'diploma',
                'value' => $diploma->price,
                'currency' => $diploma->currency,
                'user_id' => $student->user->id,
                'value_rec' => $request->value_rec,
                'value_rem' => $value_rem,
                'diploma_id' => $diploma->id,
                'coupon_code' => $request->coupon_code ?? null,
                'original_price' => $diploma->price,
                'discount_amount' => $diploma->price - $request->value_rec,
                'coupon_type' => $request->coupon_type ?? null,
            ]);
            
            return redirect()->route('data_entry.enroll_diploma.success', ['revenue_id' => $revenue->id]);

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

            $customer = Customer::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
            
            // Redirect to add translation deal page with the new customer ID
            return redirect()->route('data_entry.add.translation_deal', ['customer_id' => $customer->id])
                ->with('success', 'تم إضافة الزبون بنجاح. يمكنك الآن إضافة معاملة جديدة.');
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
        // Get all customers with sorting
        if($orderBy == 'null' || $sort == 'null'){
            $customers = Customer::paginate(8);
        }else{
            $customers = Customer::orderBy($orderBy, $sort)->paginate(8);
        }

        // Get unique addresses for filter
        $addresses = Customer::distinct()->pluck('address')->filter()->values();

        return view('data_entry.translation.customers', [
            'customers' => $customers,
            'addresses' => $addresses
        ]);
    }

    public function customersAjax(Request $request){
        try {
            // Get all customers with search and filter
            $query = Customer::query();

            // Apply search filter
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            // Apply address filter
            if ($request->has('address') && $request->address) {
                $query->where('address', $request->address);
            }

            $customers = $query->paginate(8);

            return view('data_entry.translation.partials.customers_table', compact('customers'))->render();
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function addTranslationDeal(Request $request){
        $customers = Customer::all();
        $selectedCustomerId = $request->get('customer_id', old('customer_id', null));
        
        return view('data_entry.translation.add_translation_deal', [
            'customers' => $customers,
            'selectedCustomerId' => $selectedCustomerId
        ]);
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
        // Get all translation deals with sorting
        if($orderBy == 'null' || $sort == 'null'){
            $translationDeals = TranslationDel::with('customer')->paginate(8);
        }else{
            $translationDeals = TranslationDel::with('customer')->orderBy($orderBy, $sort)->paginate(8);
        }

        // Get unique values for filters
        $languages = TranslationDel::distinct()->pluck('language')->filter()->values();
        $paymentMethods = TranslationDel::distinct()->pluck('payment_method')->filter()->values();
        $currencies = TranslationDel::distinct()->pluck('currency')->filter()->values();

        return view('data_entry.translation.translation_deals',[
            'translationDeals' => $translationDeals,
            'languages' => $languages,
            'paymentMethods' => $paymentMethods,
            'currencies' => $currencies
        ]);
    }

    public function translationDealsAjax(Request $request){
        try {
            // Get all translation deals with search and filter
            $query = TranslationDel::with('customer');

            // Apply search filter
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('context', 'like', "%{$search}%")
                      ->orWhere('language', 'like', "%{$search}%")
                      ->orWhere('payment_method', 'like', "%{$search}%")
                      ->orWhereHas('customer', function($customerQuery) use ($search) {
                          $customerQuery->where('name', 'like', "%{$search}%");
                      });
                });
            }

            // Apply language filter
            if ($request->has('language') && $request->language) {
                $query->where('language', $request->language);
            }

            // Apply payment method filter
            if ($request->has('payment_method') && $request->payment_method) {
                $query->where('payment_method', $request->payment_method);
            }

            // Apply currency filter
            if ($request->has('currency') && $request->currency) {
                $query->where('currency', $request->currency);
            }

            $translationDeals = $query->paginate(8);

            return view('data_entry.translation.partials.translation_deals_table', compact('translationDeals'))->render();
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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


    /**
     * Show registration choice page after saving student
     */
    public function registrationChoice($student_id)
    {
        $student = Student::with('user')->findOrFail($student_id);
        
        return view('data_entry.students.registration_choice', compact('student'));
    }

    /**
     * Show pay fees page
     */
    public function payFees(Request $request)
    {
        $students = Student::whereHas('user', function ($query) {
            return $query->where('status', '=', 'active');
        })->get();
        
        // If student_id is provided, pre-select that student
        $selectedStudent = null;
        if ($request->has('student_id')) {
            $selectedStudent = Student::with('user')->find($request->student_id);
        }
        
        return view('data_entry.students.pay_fees', [
            'students' => $students,
            'selectedStudent' => $selectedStudent
        ]);
    }

    /**
     * Process payment for fees
     */
    public function processPayment(Request $request)
    {
        try {
            $request->validate([
                'student_id' => ['required', 'exists:students,id'],
                'amount' => ['required', 'numeric', 'min:0.01'],
                'payment_type' => ['required', 'in:tuition_fees,additional_fees,late_fees'],
                'payment_method' => ['required', 'in:cash,bank_transfer,credit_card'],
                'notes' => ['nullable', 'string', 'max:500'],
            ]);

            $student = Student::findOrFail($request->student_id);

            // Create revenue record for payment
            $revenue = Revenue::create([
                'course_id' => null,
                'diploma_id' => null,
                'date_of_rec' => date("Y-m-d"),
                'type' => $request->payment_type, // tuition_fees, additional_fees, or late_fees
                'value' => $request->amount,
                'currency' => 'D', // Default currency
                'user_id' => $student->user->id,
                'value_rec' => $request->amount,
                'value_rem' => 0, // Full payment
            ]);

            // Store additional payment details in session to pass to success page
            session([
                'payment_details' => [
                    'payment_method' => $request->payment_method,
                    'payment_type' => $request->payment_type,
                    'notes' => $request->notes,
                ]
            ]);

            return redirect()->route('data_entry.payment.success', ['revenue_id' => $revenue->id]);
                
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function paymentSuccess($revenue_id){
        $revenue = Revenue::with('user')->findOrFail($revenue_id);
        $student = Student::with('dataStudent')->where('user_id', '=', $revenue->user->id)->first();
        $paymentDetails = session('payment_details', []);
        session()->forget('payment_details');
        
        return view('data_entry.students.payment_success', [
            'revenue' => $revenue,
            'student' => $student,
            'payment_details' => $paymentDetails
        ]);
    }

    ### Reports ###
    public function reports()
    {
        return view('data_entry.reports.index');
    }

    public function courseReports(Request $request)
    {
        // Get all course revenues with relationships
        $query = Revenue::with(['user', 'course'])
            ->whereNotNull('course_id')
            ->whereNull('diploma_id');

        // Apply filters
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('course', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->has('section') && $request->section) {
            $query->whereHas('course', function($q) use ($request) {
                $q->where('section', $request->section);
            });
        }

        if ($request->has('payment_status') && $request->payment_status) {
            if ($request->payment_status == 'paid') {
                $query->where('value_rem', '<=', 0);
            } elseif ($request->payment_status == 'pending') {
                $query->where('value_rem', '>', 0);
            }
        }

        $revenues = $query->orderBy('id', 'desc')->paginate(15);

        // Get unique sections for filter
        $sections = Revenue::with('course')
            ->whereNotNull('course_id')
            ->whereNull('diploma_id')
            ->get()
            ->pluck('course.section')
            ->filter()
            ->unique()
            ->values();

        if ($request->ajax() || $request->wantsJson()) {
            return view('data_entry.reports.partials.course_reports_table', compact('revenues'))->render();
        }

        return view('data_entry.reports.course_reports', [
            'revenues' => $revenues,
            'sections' => $sections
        ]);
    }

    public function diplomaReports(Request $request)
    {
        // Get all diploma revenues with relationships
        $query = Revenue::with(['user', 'diploma'])
            ->whereNotNull('diploma_id');

        // Apply filters
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('diploma', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->has('payment_status') && $request->payment_status) {
            if ($request->payment_status == 'paid') {
                $query->where('value_rem', '<=', 0);
            } elseif ($request->payment_status == 'pending') {
                $query->where('value_rem', '>', 0);
            }
        }

        $revenues = $query->orderBy('id', 'desc')->paginate(15);

        if ($request->ajax() || $request->wantsJson()) {
            return view('data_entry.reports.partials.diploma_reports_table', compact('revenues'))->render();
        }

        return view('data_entry.reports.diploma_reports', [
            'revenues' => $revenues
        ]);
    }

    public function payRemaining($id)
    {
        $revenue = Revenue::with(['user', 'course', 'diploma'])->findOrFail($id);
        $student = Student::with('dataStudent')->where('user_id', '=', $revenue->user->id)->first();
        
        return view('data_entry.reports.pay_remaining', [
            'revenue' => $revenue,
            'student' => $student
        ]);
    }

    public function updateRemainingPayment(Request $request, $id)
    {
        try {
            $revenue = Revenue::findOrFail($id);
            
            $request->validate([
                'amount' => ['required', 'numeric', 'min:0.01', 'max:' . $revenue->value_rem],
            ]);

            $amountPaid = $request->amount;
            $newValueRec = $revenue->value_rec + $amountPaid;
            $newValueRem = $revenue->value_rem - $amountPaid;

            // Update revenue
            $revenue->update([
                'value_rec' => $newValueRec,
                'value_rem' => max(0, $newValueRem),
            ]);

            return redirect()->route('data_entry.reports.course')
                ->with('success', 'تم تسجيل الدفع بنجاح!');
                
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Apply coupon code
     */
    public function applyCoupon(Request $request)
    {
        try {
            $request->validate([
                'coupon_code' => 'required|string',
                'type' => 'required|in:course,diploma',
                'course_id' => 'required_if:type,course|nullable|exists:courses,id',
                'diploma_id' => 'required_if:type,diploma|nullable|exists:diploma,id'
            ]);

            $coupon = \App\Models\Coupon::where('code', $request->coupon_code)->first();

            if (!$coupon) {
                return response()->json([
                    'success' => false,
                    'message' => 'كود الخصم غير صحيح'
                ]);
            }

            if (!$coupon->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'كود الخصم غير صالح أو منتهي الصلاحية'
                ]);
            }

            // Check if coupon applies to the selected type
            $typeForCheck = $request->type === 'course' ? 'courses' : 'diplomas';
            if (!$coupon->appliesTo($typeForCheck)) {
                return response()->json([
                    'success' => false,
                    'message' => 'كود الخصم لا ينطبق على ' . ($request->type === 'course' ? 'الكورسات' : 'الدبلومات')
                ]);
            }

            // Get original price
            $originalPrice = 0;
            if ($request->type === 'course') {
                $course = \App\Models\Course::find($request->course_id);
                $originalPrice = $course ? $course->price : 0;
                
                // Check if coupon is specific to a course
                if ($coupon->specific_course_id && $coupon->specific_course_id != $request->course_id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'كود الخصم مخصص لكورس آخر'
                    ]);
                }
            } else {
                $diploma = \App\Models\Diploma::find($request->diploma_id);
                $originalPrice = $diploma ? $diploma->price : 0;
                
                // Check if coupon is specific to a diploma
                if ($coupon->specific_diploma_id && $coupon->specific_diploma_id != $request->diploma_id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'كود الخصم مخصص لدبلومة أخرى'
                    ]);
                }
            }

            if ($originalPrice <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن تطبيق الخصم على هذا العنصر'
                ]);
            }

            // Calculate discount
            $discountAmount = $coupon->calculateDiscount($originalPrice);
            $newPrice = $originalPrice - $discountAmount;

            return response()->json([
                'success' => true,
                'new_price' => (float) $newPrice,
                'discount_amount' => (float) $discountAmount,
                'original_price' => (float) $originalPrice,
                'coupon_type' => $coupon->type,
                'coupon_value' => (float) $coupon->value,
                'message' => 'تم تطبيق الخصم بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في الخادم: ' . $e->getMessage()
            ], 500);
        }
    }
}
