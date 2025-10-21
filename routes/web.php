<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DataEntry\DataEntryController;
use App\Http\Controllers\Financial\FinancialController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Teacher\TeacherController;
use Illuminate\Support\Facades\Route;


Route::get('/', [AdminController::class, 'index'])->middleware(['auth', 'verified']);
Route::get('/dashboard', [AdminController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';



######## admin #########
Route::middleware(['auth', 'verified', 'role:admin','log.requests'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'mainIndex'])->name('admin.dashboard');

    #### users requests ####
    Route::get('/admin/user-requests', [AdminController::class, 'userRequsets'])->name('admin.user_requests');

    ### users ####
    Route::get('/admin/add_user', [RegisteredUserController::class, 'addUser'])->name('add.user');
    Route::post('/admin/register', [RegisteredUserController::class, 'store'])->name('register');
    Route::get('/admin/users/{orderBy}/{sort}', [ProfileController::class, 'users'])->name('users');
    Route::get('/admin/profile_user/{id}', [ProfileController::class, 'profileUser'])->name('profile.user');
    Route::patch('/admin/update_user/{id}', [ProfileController::class, 'update'])->name('update.user');

    ### teacher ###
    Route::get('/admin/add_teacher', [AdminController::class, 'addTeacher'])->name('add.teacher');
    Route::post('/admin/register_teacher', [AdminController::class, 'registerTeacher'])->name('register.teacher');
    Route::get('/admin/teachers/{orderBy}/{sort}', [AdminController::class, 'teachers'])->name('teachers');
    Route::get('/admin/edit_teacher/{id}', [AdminController::class, 'editTeacher'])->name('edit.teacher');
    Route::patch('/admin/update_teacher/{id}', [AdminController::class, 'updateTeacher'])->name('update.teacher');

    ### student ####
    Route::get('/admin/add_student', [AdminController::class, 'addStudent'])->name('add.student');
    Route::post('/admin/register_student', [AdminController::class, 'registerStudent'])->name('register.student');
    Route::get('/admin/students/{orderBy}/{sort}', [AdminController::class, 'students'])->name('students');
    Route::get('/admin/edit_student/{id}', [AdminController::class, 'editStudent'])->name('edit.student');
    Route::patch('/admin/update_student/{id}', [AdminController::class, 'updateStudent'])->name('update.student');
    Route::get('/admin/edit_student_data/{id}', [AdminController::class, 'editStudentData'])->name('edit.studentData');
    Route::patch('/admin/update_student_data/{id}', [AdminController::class, 'updateDataStudent'])->name('update.dataStudent');
    Route::delete('/admin/student/{id}', [AdminController::class, 'studentDestroy'])->name('student.destroy');

    ### courses ###
    Route::get('/admin/add_course', [AdminController::class, 'addCourse'])->name('add.course');
    Route::get('/admin/buy_course', [AdminController::class, 'buyCourse'])->name('buy.course');
    Route::post('/admin/enroll_course', [AdminController::class, 'enrollCourse'])->name('enroll.course');
    Route::post('/admin/register_course', [AdminController::class, 'registerCourse'])->name('register.course');
    Route::get('/admin/courses/{orderBy}/{sort}', [AdminController::class, 'courses'])->name('courses');
    Route::get('/admin/edit_course/{id}', [AdminController::class, 'editCourse'])->name('edit.course');
    Route::patch('/admin/update_course/{id}', [AdminController::class, 'updateCourse'])->name('update.course');
    Route::get('/admin/time_by_course/{id}', [AdminController::class, 'ajaxGetTimeByCourse'])->name('ajax.ajaxGetTimeByCourse');
    Route::get('/admin/time_by_student/{id}', [AdminController::class, 'ajaxGetTimeByStudent'])->name('ajax.ajaxGetTimeByStudent');
    Route::delete('/admin/course/{id}', [AdminController::class, 'courseDestroy'])->name('course.destroy');

    ### diploma ###
    Route::get('/admin/add_diploma', [AdminController::class, 'addDiploma'])->name('add.diploma');
    Route::post('/admin/register_diploma', [AdminController::class, 'registerDiploma'])->name('register.diploma');
    Route::get('/admin/diploma_course/', [AdminController::class, 'diplomaCourse'])->name('diploma.course');
    Route::get('/admin/edit_diploma/{id}', [AdminController::class, 'editDiploma'])->name('edit.diploma');
    Route::patch('/admin/update_diploma/{id}', [AdminController::class, 'updateDiploma'])->name('update.diploma');
    Route::get('/admin/buy_diploma', [AdminController::class, 'buyDiploma'])->name('buy.diploma');
    Route::post('/admin/enroll_diploma', [AdminController::class, 'enrollDiploma'])->name('enroll.diploma');
    Route::get('/admin/time_by_diploma/{id}', [AdminController::class, 'ajaxGetTimeByDiploma'])->name('ajax.ajaxGetTimeByDiploma');
    Route::delete('/admin/diploma/{id}', [AdminController::class, 'diplomaDestroy'])->name('diploma.destroy');



    ### translation ###
    Route::get('/admin/customer', [AdminController::class, 'addCustomer'])->name('add.customer');
    Route::get('/admin/edit_customer/{id}', [AdminController::class, 'editCustomer'])->name('edit.customer');
    Route::patch('/admin/update_customer/{id}', [AdminController::class, 'updateCustomer'])->name('update.customer');
    Route::post('/admin/register_customer', [AdminController::class, 'registerCustomer'])->name('register.customer');
    Route::get('/admin/customers/{orderBy}/{sort}', [AdminController::class, 'customers'])->name('customers');
    Route::delete('/admin/customer/{id}', [AdminController::class, 'customerDestroy'])->name('customer.destroy');

    Route::get('/admin/add_translation_deal', [AdminController::class, 'addTranslationDeal'])->name('add.translation_deal');
    Route::post('/admin/register_translation_deal', [AdminController::class, 'registerTranslationDeal'])->name('register.translation_deal');
    Route::get('/admin/translation_deals/{orderBy}/{sort}', [AdminController::class, 'translationDeals'])->name('translation_deals');
    Route::get('/admin/edit_translation_deal/{id}', [AdminController::class, 'editTranslationDeal'])->name('edit.translation_deal');
    Route::patch('/admin/update_translation_deal/{id}', [AdminController::class, 'updateTranslationDeal'])->name('update.translation_deal');
    Route::delete('/admin/translation_deal/{id}', [AdminController::class, 'translationDealDestroy'])->name('translation_deal.destroy');

    ### Revenue ###
    Route::get('/admin/revenues/{orderBy}/{sort}', [AdminController::class, 'revenues'])->name('revenues');
    Route::get('/admin/edit_revenue/{id}', [AdminController::class, 'editRevenue'])->name('edit.revenue');
    Route::patch('/admin/update_revenue/{id}', [AdminController::class, 'updateRevenue'])->name('update.revenue');
    Route::get('/admin/print_student_bill/{id}', [AdminController::class, 'printStudentbill'])->name('print_student_bill');
    Route::get('/admin/diploma_revenues', [AdminController::class, 'diplomaRevenues'])->name('diploma.revenues');
    Route::get('/admin/diploma_revenue/{id}/{user_id}', [AdminController::class, 'editDiplomaRevenue'])->name('edit.diploma_revenue');
    Route::patch('/admin/update_diploma_revenue/{id}/{user_id}', [AdminController::class, 'updateDiplomaRevenue'])->name('update.diploma_revenue');
    Route::get('/admin/print_diploma_bill/{id}/{user_id}', [AdminController::class, 'printDiplomabill'])->name('print_diploma_bill');

    ### translation Revenue ###
    Route::get('/admin/translation_revenues/{orderBy}/{sort}', [AdminController::class, 'translationRevenues'])->name('translation_revenues');
    Route::get('/admin/print_translation_bill/{id}', [AdminController::class, 'printTranslationbill'])->name('print_translation_bill');

    ### teacher revenue ###
    Route::get('/admin/teacher_revenue', [AdminController::class, 'teacherRevenue'])->name('teacher_revenue');
    Route::get('/admin/course_byteacher/{id}', [AdminController::class, 'ajaxGetCourseTeacher'])->name('ajax.ajaxGetCourseByTeacher');
    Route::post('/admin/print_teacher_bill', [AdminController::class, 'printTeacherbill'])->name('print_teacher_bill');


    ### Expenses ###
    Route::get('/admin/expenses/{orderBy}/{sort}', [AdminController::class, 'expenses'])->name('expenses');
    Route::get('/admin/add_expense', [AdminController::class, 'addExpense'])->name('add.expense');
    Route::post('/admin/register_expense', [AdminController::class, 'registerExpense'])->name('register.expense');
    Route::get('/admin/edit_expense/{id}', [AdminController::class, 'editExpense'])->name('edit.expense');
    Route::patch('/admin/update_expense/{id}', [AdminController::class, 'updateExpense'])->name('update.expense');
    Route::delete('/admin/expenses/{id}', [AdminController::class, 'expensesDealDestroy'])->name('expenses.destroy');

    ### Calendar ###
    Route::get('/admin/course_calendar', [AdminController::class, 'courseCalendar'])->name('course.calendar');
    Route::get('/admin/teacher_calendar', [AdminController::class, 'teacherCalendar'])->name('teacher.calendar');
    Route::get('/admin/student_calendar', [AdminController::class, 'studentCalendar'])->name('student.calendar');
    Route::get('/admin/calendar_bycourse/{id}', [AdminController::class, 'ajaxGetCalendarCourse'])->name('ajax.ajaxGetCalendarByCourse');
    Route::get('/admin/calendar_byteacher/{id}', [AdminController::class, 'ajaxGetCalendarTeacher'])->name('ajax.ajaxGetCalendarByTeacher');
    Route::get('/admin/calendar_bystudent/{id}', [AdminController::class, 'ajaxGetCalendarStudent'])->name('ajax.ajaxGetCalendarByStudent');

    ### reports ###
    Route::get('/admin/generate_general_monthly_report', [AdminController::class, 'generalMonthlyReport'])->name('general.monthly.report');
    Route::post('/admin/general_monthly_report', [AdminController::class, 'generateGeneralMonthlyReport'])->name('generate.general.monthly.report');
    Route::get('/admin/course_report', [AdminController::class, 'courseReport'])->name('course.report');
    Route::post('/admin/generate_course_report', [AdminController::class, 'generateCourseReport'])->name('generate.course.report');
    Route::get('/admin/translation_report', [AdminController::class, 'translationReport'])->name('translation.report');
    Route::post('/admin/generate_translation_report', [AdminController::class, 'generateTranslationReport'])->name('generate.translation.report');

    Route::post('/admin/apply-coupon', [AdminController::class, 'applyCoupon'])->name('apply.coupon');
    Route::post('/admin/apply-diploma-coupon', [AdminController::class, 'applyDiplomaCoupon'])->name('apply.diploma.coupon');


});

######## data_entry #########
Route::middleware(['auth', 'verified', 'role:data_entry','log.requests'])->group(function () {
    Route::get('/data_entry/dashboard', [DataEntryController::class, 'mainIndex'])->name('data_entry.dashboard');


    ### teacher ###
    Route::get('/data_entry/add_teacher', [DataEntryController::class, 'addTeacher'])->name('data_entry.add.teacher');
    Route::post('/data_entry/register_teacher', [DataEntryController::class, 'registerTeacher'])->name('data_entry.register.teacher');
    Route::get('/data_entry/teachers/{orderBy}/{sort}', [DataEntryController::class, 'teachers'])->name('data_entry.teachers');
    Route::get('/data_entry/edit_teacher/{id}', [DataEntryController::class, 'editTeacher'])->name('data_entry.edit.teacher');
    Route::patch('/data_entry/update_teacher/{id}', [DataEntryController::class, 'updateTeacher'])->name('data_entry.update.teacher');

    ### student ####
    Route::get('/data_entry/add_student', [DataEntryController::class, 'addStudent'])->name('data_entry.add.student');
    Route::post('/data_entry/register_student', [DataEntryController::class, 'registerStudent'])->name('data_entry.register.student');
    Route::get('/data_entry/students/{orderBy}/{sort}', [DataEntryController::class, 'students'])->name('data_entry.students');
    Route::get('/data_entry/edit_student/{id}', [DataEntryController::class, 'editStudent'])->name('data_entry.edit.student');
    Route::patch('/data_entry/update_student/{id}', [DataEntryController::class, 'updateStudent'])->name('data_entry.update.student');
    Route::get('/data_entry/edit_student_data/{id}', [DataEntryController::class, 'editStudentData'])->name('data_entry.edit.studentData');
    Route::patch('/data_entry/update_student_data/{id}', [DataEntryController::class, 'updateDataStudent'])->name('data_entry.update.dataStudent');
    Route::delete('/data_entry/student/{id}', [DataEntryController::class, 'studentDestroy'])->name('data_entry.student.destroy');

    ### courses ###
    Route::get('/data_entry/add_course', [DataEntryController::class, 'addCourse'])->name('data_entry.add.course');
    Route::get('/data_entry/buy_course', [DataEntryController::class, 'buyCourse'])->name('data_entry.buy.course');
    Route::post('/data_entry/enroll_course', [DataEntryController::class, 'enrollCourse'])->name('data_entry.enroll.course');
    Route::post('/data_entry/register_course', [DataEntryController::class, 'registerCourse'])->name('data_entry.register.course');
    Route::get('/data_entry/courses/{orderBy}/{sort}', [DataEntryController::class, 'courses'])->name('data_entry.courses');
    Route::get('/data_entry/edit_course/{id}', [DataEntryController::class, 'editCourse'])->name('data_entry.edit.course');
    Route::patch('/data_entry/update_course/{id}', [DataEntryController::class, 'updateCourse'])->name('data_entry.update.course');
    Route::get('/data_entry/time_by_course/{id}', [DataEntryController::class, 'ajaxGetTimeByCourse'])->name('data_entry.ajax.ajaxGetTimeByCourse');
    Route::get('/data_entry/time_by_student/{id}', [DataEntryController::class, 'ajaxGetTimeByStudent'])->name('data_entry.ajax.ajaxGetTimeByStudent');
    Route::delete('/data_entry/course/{id}', [DataEntryController::class, 'courseDestroy'])->name('data_entry.course.destroy');

    ### diploma ###
    Route::get('/data_entry/add_diploma', [DataEntryController::class, 'addDiploma'])->name('data_entry.add.diploma');
    Route::post('/data_entry/register_diploma', [DataEntryController::class, 'registerDiploma'])->name('data_entry.register.diploma');
    Route::get('/data_entry/diploma_course/', [DataEntryController::class, 'diplomaCourse'])->name('data_entry.diploma.course');
    Route::get('/data_entry/edit_diploma/{id}', [DataEntryController::class, 'editDiploma'])->name('data_entry.edit.diploma');
    Route::patch('/data_entry/update_diploma/{id}', [DataEntryController::class, 'updateDiploma'])->name('data_entry.update.diploma');
    Route::get('/data_entry/buy_diploma', [DataEntryController::class, 'buyDiploma'])->name('data_entry.buy.diploma');
    Route::post('/data_entry/enroll_diploma', [DataEntryController::class, 'enrollDiploma'])->name('data_entry.enroll.diploma');
    Route::get('/data_entry/time_by_diploma/{id}', [DataEntryController::class, 'ajaxGetTimeByDiploma'])->name('data_entry.ajax.ajaxGetTimeByDiploma');
    Route::delete('/data_entry/diploma/{id}', [DataEntryController::class, 'diplomaDestroy'])->name('data_entry.diploma.destroy');

    ### translation ###
    Route::get('/data_entry/customer', [DataEntryController::class, 'addCustomer'])->name('data_entry.add.customer');
    Route::get('/data_entry/edit_customer/{id}', [DataEntryController::class, 'editCustomer'])->name('data_entry.edit.customer');
    Route::patch('/data_entry/update_customer/{id}', [DataEntryController::class, 'updateCustomer'])->name('data_entry.update.customer');
    Route::post('/data_entry/register_customer', [DataEntryController::class, 'registerCustomer'])->name('data_entry.register.customer');
    Route::get('/data_entry/customers/{orderBy}/{sort}', [DataEntryController::class, 'customers'])->name('data_entry.customers');
    Route::delete('/data_entry/customer/{id}', [DataEntryController::class, 'customerDestroy'])->name('data_entry.customer.destroy');

    Route::get('/data_entry/add_translation_deal', [DataEntryController::class, 'addTranslationDeal'])->name('data_entry.add.translation_deal');
    Route::post('/data_entry/register_translation_deal', [DataEntryController::class, 'registerTranslationDeal'])->name('data_entry.register.translation_deal');
    Route::get('/data_entry/translation_deals/{orderBy}/{sort}', [DataEntryController::class, 'translationDeals'])->name('data_entry.translation_deals');
    Route::get('/data_entry/edit_translation_deal/{id}', [DataEntryController::class, 'editTranslationDeal'])->name('data_entry.edit.translation_deal');
    Route::patch('/data_entry/update_translation_deal/{id}', [DataEntryController::class, 'updateTranslationDeal'])->name('data_entry.update.translation_deal');
    Route::delete('/data_entry/translation_deal/{id}', [DataEntryController::class, 'translationDealDestroy'])->name('data_entry.translation_deal.destroy');


    ### reports ###
    Route::get('/data_entry/generate_general_monthly_report', [DataEntryController::class, 'generalMonthlyReport'])->name('data_entry.general.monthly.report');
    Route::post('/data_entry/general_monthly_report', [DataEntryController::class, 'generateGeneralMonthlyReport'])->name('data_entry.generate.general.monthly.report');
    Route::get('/data_entry/course_report', [DataEntryController::class, 'courseReport'])->name('data_entry.course.report');
    Route::post('/data_entry/generate_course_report', [DataEntryController::class, 'generateCourseReport'])->name('data_entry.generate.course.report');
    Route::get('/data_entry/translation_report', [DataEntryController::class, 'translationReport'])->name('data_entry.translation.report');
    Route::post('/data_entry/generate_translation_report', [DataEntryController::class, 'generateTranslationReport'])->name('data_entry.generate.translation.report');
});

######## financial_employee #########
Route::middleware(['auth', 'verified', 'role:financial_employee','log.requests'])->group(function () {
    Route::get('/financial/dashboard', [FinancialController::class, 'mainIndex'])->name('financial_employee.dashboard');

    ### Revenue ###
    Route::get('/financial/revenues/{orderBy}/{sort}', [FinancialController::class, 'revenues'])->name('financial.revenues');
    Route::get('/financial/print_student_bill/{id}', [FinancialController::class, 'printStudentbill'])->name('financial.print_student_bill');
    Route::get('/financial/edit_revenue/{id}', [FinancialController::class, 'editRevenue'])->name('financial.edit.revenue');
    Route::patch('/financial/update_revenue/{id}', [FinancialController::class, 'updateRevenue'])->name('financial.update.revenue');

    Route::get('/financial/diploma_revenues', [FinancialController::class, 'diplomaRevenues'])->name('financial.diploma.revenues');
    Route::get('/financial/diploma_revenue/{id}/{user_id}', [FinancialController::class, 'editDiplomaRevenue'])->name('financial.edit.diploma_revenue');
    Route::patch('/financial/update_diploma_revenue/{id}/{user_id}', [FinancialController::class, 'updateDiplomaRevenue'])->name('financial.update.diploma_revenue');
    Route::get('/financial/print_diploma_bill/{id}/{user_id}', [FinancialController::class, 'printDiplomabill'])->name('financial.print_diploma_bill');

    ### translation Revenue ###
    Route::get('/financial/translation_revenues/{orderBy}/{sort}', [FinancialController::class, 'translationRevenues'])->name('financial.translation_revenues');
    Route::get('/financial/print_translation_bill/{id}', [FinancialController::class, 'printTranslationbill'])->name('financial.print_translation_bill');
    Route::get('/financial/edit_translation_revenue/{id}', [FinancialController::class, 'editTranslationRevenue'])->name('financial.edit.translation_revenue');
    Route::patch('/financial/update_translation_revenue/{id}', [FinancialController::class, 'updateTranslationRevenue'])->name('financial.update.translation_revenue');

    ### Expenses ###
    Route::get('/financial/expenses/{orderBy}/{sort}', [FinancialController::class, 'expenses'])->name('financial.expenses');
    Route::get('/financial/add_expense', [FinancialController::class, 'addExpense'])->name('financial.add.expense');
    Route::post('/financial/register_expense', [FinancialController::class, 'registerExpense'])->name('financial.register.expense');
    Route::get('/financial/edit_expense/{id}', [FinancialController::class, 'editExpense'])->name('financial.edit.expense');
    Route::patch('/financial/update_expense/{id}', [FinancialController::class, 'updateExpense'])->name('financial.update.expense');

    ### teacher revenue ###
    Route::get('/financial/teacher_revenue', [FinancialController::class, 'teacherRevenue'])->name('financial.teacher_revenue');
    Route::get('/financial/course_byteacher/{id}', [FinancialController::class, 'ajaxGetCourseTeacher'])->name('financial.ajax.ajaxGetCourseByTeacher');
    Route::post('/financial/print_teacher_bill', [FinancialController::class, 'printTeacherbill'])->name('financial.print_teacher_bill');

    ### reports ###
    Route::get('/financial/generate_general_monthly_report', [FinancialController::class, 'generalMonthlyReport'])->name('financial.general.monthly.report');
    Route::post('/financial/general_monthly_report', [FinancialController::class, 'generateGeneralMonthlyReport'])->name('financial.generate.general.monthly.report');
    Route::get('/financial/course_report', [FinancialController::class, 'courseReport'])->name('financial.course.report');
    Route::post('/financial/generate_course_report', [FinancialController::class, 'generateCourseReport'])->name('financial.generate.course.report');
    Route::get('/financial/translation_report', [FinancialController::class, 'translationReport'])->name('financial.translation.report');
    Route::post('/financial/generate_translation_report', [FinancialController::class, 'generateTranslationReport'])->name('financial.generate.translation.report');
});

######## student #########
Route::middleware(['auth', 'verified', 'role:student','log.requests'])->group(function () {
    Route::get('/student/dashboard', [StudentController::class, 'mainIndex'])->name('student.dashboard');

    Route::get('/student/courses', [StudentController::class, 'courses'])->name('student.courses');

    Route::get('/student/student_calendar', [StudentController::class, 'studentCalendar'])->name('student.student.calendar');
    Route::get('/student/calendar_bystudent', [StudentController::class, 'ajaxGetCalendarStudent']) ->name('student.calendar.data');
});

######## teacher #########
Route::middleware(['auth', 'verified', 'role:teacher','log.requests'])->group(function () {
    Route::get('/teacher/dashboard', [TeacherController::class, 'mainIndex'])->name('teacher.dashboard');

    Route::get('/teacher/courses', [TeacherController::class, 'courses'])->name('teacher.courses');

    Route::get('/teacher/teacher_calendar', [TeacherController::class, 'teacherCalendar'])->name('teacher.teacher.calendar');
    Route::get('/teacher/calendar_byteacher', [TeacherController::class, 'ajaxGetCalendarTeacher'])->name('teacher.ajax.ajaxGetCalendarByTeacher');
});
