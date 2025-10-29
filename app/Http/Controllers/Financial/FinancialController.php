<?php

namespace App\Http\Controllers\Financial;

use App\Enums\EnumPermission;
use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Expense;
use App\Models\Revenue;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\TranslationDel;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;

class FinancialController extends Controller
{
    public function mainIndex(){
        return view('financial.main');
    }

    ### revenues ###
    public function revenues($orderBy, $sort){
        if($orderBy == 'null' || $sort == 'null'){
            $revenues = Revenue::with('user')->with('course')->where('diploma_id', '=', null)->paginate(8);
        }else{
            $revenues = Revenue::with('user')->with('course')->where('diploma_id', '=', null)->orderBy($orderBy, $sort)->paginate(8);
        }
        return view('financial.revenue',['revenues' => $revenues]);
    }

    public function editRevenue($id){
        if(!in_array(EnumPermission::modification,json_decode(Auth::user()->permission))){
            return back()->with('error','ليس لديك صلاحية التعديل');
        }
        $revenue = Revenue::with('course')->findOrFail($id);
        return view('financial.edit_revenue',['revenue' => $revenue]);
    }

    public function updateRevenue(Request $request,$id){
        if(!in_array(EnumPermission::modification,json_decode(Auth::user()->permission))){
            return back()->with('error','ليس لديك صلاحية التعديل');
        }
        $revenue = Revenue::findOrFail($id);

            if($revenue->value_rem >= $request->value_rec && $request->value_rec >= 0){
                $revenue->value_rec = $revenue->value_rec + $request->value_rec;
                $revenue->value_rem = $revenue->value_rem - $request->value_rec;
                $revenue->save();
                return back()->with('success','تم الحفظ')->withInput();
            }else{
                return back()->with('error','القيمة المدفوعة اكبر من المتبقي او خاطئة')->withInput();
            }
    }

    public function diplomaRevenues(){
        $revenues = Revenue::with('user')->with('diploma')->where('diploma_id', '!=', null)->groupBy(['diploma_id','user_id'])->get();
        
        return view('financial.diploma_revenue',['revenues' => $revenues]);
    }

    public function editDiplomaRevenue($id, $user_id){
        $revenue = Revenue::with('diploma')->where('diploma_id', '=', $id)->where('user_id', '=', $user_id)->get();
        return view('financial.edit_diploma_revenue',['revenue' => $revenue]);
    }

    public function updateDiplomaRevenue(Request $request,$id, $user_id){
        $revenue = Revenue::where('diploma_id', '=', $id)->where('user_id', '=', $user_id)->get();
        foreach($revenue as $value){
            if($value->value_rem >= $request->value_rec && $request->value_rec >= 0){
                $value->value_rec = $value->value_rec + $request->value_rec;
                $value->value_rem = $value->value_rem - $request->value_rec;
                $value->save();
            }else{
                return back()->with('error','القيمة المدفوعة اكبر من المتبقي او خاطئة')->withInput();
            }
        }
        return back()->with('success','تم الحفظ')->withInput();
    }

    public function printDiplomabill($id, $user_id){
        $revenue = Revenue::with('user')->with('course')->where('diploma_id', '=', $id)->where('user_id', '=', $user_id)->get();
        $student = Student::with('dataStudent')->where('user_id' , '=' , $revenue[0]->user->id)->first();
        $title = 'إيصال تسديد رسوم التسجيل'; 
        return PDF::loadView('pdf_export.student_diploma',[
            "title" => $title,
            "revenue" => $revenue,
            "student" => $student,
        ])->download('ايصال-تسديد-رسوم-التسجيل'.time().'.pdf');
        
    }
    
    ### translation Revenue ###
    public function translationRevenues($orderBy, $sort){
        if($orderBy == 'null' || $sort == 'null'){
            $translationDeals = TranslationDel::with('customer')->paginate(8);
        }else{
            $translationDeals = TranslationDel::with('customer')->orderBy($orderBy, $sort)->paginate(8);
        }
        return view('financial.translation_revenues',['translationDeals' => $translationDeals]);
    }

    public function printTranslationbill($id){
        if(!in_array(EnumPermission::view_report,json_decode(Auth::user()->permission))){
            return back()->with('error','ليس لديك صلاحية لعرض الفاتورة');
        }
        $translationDeal = TranslationDel::with('customer')->findOrFail($id);
        $title = 'ايصال تسديد رسوم الترجمة'; 
        if($translationDeal->delivery_date == null){
            $date = now()->format('Y-m-d');
            $translationDeal->delivery_date = $date;
            $translationDeal->save();
        }else{
            $date = $translationDeal->delivery_date;
        }

        return PDF::loadView('pdf_export.translation',[
            "title" => $title,
            "translationDeal" => $translationDeal,
            "date" => $date,
        ])->download('ايصال-تسديد-رسوم-الترجمة'.time().'.pdf');
    }

    public function editTranslationRevenue($id){
        if(!in_array(EnumPermission::modification,json_decode(Auth::user()->permission))){
            return back()->with('error','ليس لديك صلاحية التعديل');
        }
        $translationDel = TranslationDel::findOrFail($id);
        return view('financial.edit_translation_revenue',['translationDel' => $translationDel]);
    }

    public function updateTranslationRevenue(Request $request, $id){
        if(!in_array(EnumPermission::modification,json_decode(Auth::user()->permission))){
            return back()->with('error','ليس لديك صلاحية التعديل');
        }
        $translationDel = TranslationDel::findOrFail($id);

        if($translationDel->remaining >= $request->received && $request->received >= 0){
            $translationDel->received = $translationDel->received + $request->received;
            $translationDel->remaining = $translationDel->remaining - $request->received;
            $translationDel->save();
            return back()->with('success','تم الحفظ')->withInput();
        }else{
            return back()->with('error','القيمة المدفوعة اكبر من المتبقي او خاطئة')->withInput();
        }
    }
    
    ### Expenses ### 
    public function expenses($orderBy, $sort){
        if($orderBy == 'null' || $sort == 'null'){
            $expenses = Expense::paginate(8);
        }else{
            $expenses = Expense::orderBy($orderBy, $sort)->paginate(8);
        }
        return view('financial.expenses',['expenses' => $expenses]);
    }
    public function addExpense(){
        return view('financial.add_expenses');
    }
    public function registerExpense(Request $request){
        try{
            $request->validate([
                'value' => ['required', 'numeric'],
                'currency' => ['required', 'in:USD,D'],
                'date' => ['required','date_format:Y-m-d' ],
                'context' => ['required', 'string', 'max:255'],
                'section' => ['required', 'string', 'max:255'],
                'review_number' => ['required', 'string', 'max:255'],
            ]);

            Expense::create([
                'bill_number' => 'bill-'.date('dhms-').rand(10,99),
                'value' => $request->value,
                'currency' => $request->currency,
                'date' => $request->date,
                'context' => $request->context,
                'section' => $request->section,
                'review_number' => $request->review_number,
            ]);

            return back()->with('success','تم إضافة الفاتورة بنجاح ');
        }catch(Exception $e){
            return back()->with('error',$e->getMessage())->withInput();
        }
    }
    public function editExpense($id){
        if(!in_array(EnumPermission::modification,json_decode(Auth::user()->permission))){
            return back()->with('error','ليس لديك صلاحية التعديل');
        }
        $expense = Expense::findOrFail($id);
        return view('financial.edit_expenses',['expense' => $expense]);
    }
    public function updateExpense(Request $request,$id){
        if(!in_array(EnumPermission::modification,json_decode(Auth::user()->permission))){
            return back()->with('error','ليس لديك صلاحية التعديل');
        }
        try{
            $expense = Expense::findOrFail($id);

            $request->validate([
                'value' => ['required', 'numeric'],
                'currency' => ['required', 'in:USD,D'],
                'date' => ['required','date_format:Y-m-d' ],
                'context' => ['required', 'string', 'max:255'],
                'section' => ['required', 'string', 'max:255'],
                'review_number' => ['required', 'string', 'max:255'],
            ]);

                $expense->value = $request->value;
                $expense->currency = $request->currency;
                $expense->date = $request->date;
                $expense->context = $request->context;
                $expense->section = $request->section;
                $expense->review_number = $request->review_number;
                $expense->save();

            return back()->with('success','تم تعديل الفاتورة بنجاح ');
        }catch(Exception $e){
            return back()->with('error',$e->getMessage())->withInput();
        }
    }

    ### student course ###
    public function printStudentbill($id){
        if(!in_array(EnumPermission::view_report,json_decode(Auth::user()->permission))){
            return back()->with('error','ليس لديك صلاحية لعرض الفاتورة');
        }
        $revenue = Revenue::with('user')->with('course')->findOrFail($id);
        $student = Student::with('dataStudent')->where('user_id' , '=' , $revenue->user->id)->first();
        $title = 'إيصال تسديد رسوم التسجيل'; 
        return PDF::loadView('pdf_export.student_course',[
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
        ])->download('ايصال-تسديد-رسوم-التسجيل'.time().'.pdf');
    }

    ### teacher revenue ###
    public function teacherRevenue(){
        $teachers = User::where('status', '=', UserStatus::active)->where('user_type', '=', UserType::teacher)->get();
        return view('financial.teacher_revenue',['teachers' => $teachers]);
    }

    public function ajaxGetCourseTeacher($id){
        $teacher = User::with('teacherCourses')->findOrFail($id);
        $courses = $teacher->teacherCourses;
        $result= [];
        foreach($courses as $course){
            $course_id =  $course->id;
            $course_name = $course->name;
            $result[] = ['course_id' => $course_id, 'course_name' => $course_name];
        }
        return $result;
    }

    public function printTeacherbill(Request $request){
        if(!in_array(EnumPermission::view_report,json_decode(Auth::user()->permission))){
            return back()->with('error','ليس لديك صلاحية لعرض الفاتورة');
        }
        $date = now()->format('Y-m-d');
        $course = Course::with('user')->where('teacher_id', '=', $request->teacher_id)->first();
        if($course->end_date > $date){
            return back()->with('error','لم ينتهي الاستاذ من هذا الكورس بعد');
        }
        $teacher = Teacher::where('user_id', '=', $course->user->id)->first();
        $title = 'إيصال الاستاذ'; 
        return PDF::loadView('pdf_export.teacher_revenue',[
            "title" => $title,
            "date" => $date,
            "course" => $course,
            "teacher" => $teacher,
        ])->download('ايصال-الاستاذ'.time().'.pdf');
    }

    ### Reports ###
    public function generalMonthlyReport(){
        if(!in_array(EnumPermission::view_report,json_decode(Auth::user()->permission))){
            return back()->with('error','ليس لديك صلاحية لعرض قسم التقارير');
        }
        $data = null;
        return view('financial.reports.general_monthly_report',['data'=> $data]);
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
            return view('financial.reports.general_monthly_report', ['data'=> $data]);
        }catch(Exception $e){
            return back()->with('error',$e->getMessage())->withInput();
        }
    }

    public function courseReport(){
        if(!in_array(EnumPermission::view_report,json_decode(Auth::user()->permission))){
            return back()->with('error','ليس لديك صلاحية لعرض قسم التقارير');
        }
        $courses = null;
        return view('financial.reports.course_report', ['courses'=> $courses]);
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
            return view('financial.reports.course_report', ['courses'=> $courses]);
        }catch(Exception $e){
            return back()->with('error',$e->getMessage())->withInput();
        }
    }

    public function translationReport(){
        if(!in_array(EnumPermission::view_report,json_decode(Auth::user()->permission))){
            return back()->with('error','ليس لديك صلاحية لعرض قسم التقارير');
        }
        $translationDeals = null;
        return view('financial.reports.translation_report',['translationDeals'=> $translationDeals]);
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
            return view('financial.reports.translation_report', ['translationDeals'=> $translationDeals]);
        }catch(Exception $e){
            return back()->with('error',$e->getMessage())->withInput();
        }

    }
}
