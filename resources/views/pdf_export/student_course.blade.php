<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Student bill</title>
    <style>
        td{
            border: 1px solid;
        }
        th{
            border: 1px solid;
        }
        table{
            font-weight: bold;
        }
    </style>
</head>
<body>

    <table style="width: 100%;">
        <tbody>
            <tr>
                <th style="width: 80%; text-align: right;"> <h2>{{$title}}</h2> </td>
                <th style="width: 20%;"> <img src="storage/logo/logo.png"/> </td>
            <tr>
        </tbody>
    </table>

    <table style="width: 100%;">
        <tbody>
            <tr>
                <td style="width: 15%; text-align: right;">الاسم: </td>
                <td style="width: 85%;">{{$revenue->user->name}}</td>
            <tr>
        </tbody>
        <tbody>
            <tr>
                <td style="width: 15%; text-align: right;">رقم الهاتف: </td>
                <td style="width: 85%;"> {{$student->phone}} </td>
            <tr>
        </tbody>
        <tbody>
            <tr>
                <td style="width: 15%; text-align: right;">رقم اثبات هوية: </td>
                <td style="width: 85%;"> {{$student->id_number}} </td>
            <tr>
        </tbody>
    </table>
    @php
    use App\Enums\WeekDays;
    use App\Enums\Attend;
    use App\Enums\CourseType;
    @endphp
    <table style="width: 100%; text-align: center;">
        <tr>
            <th style="width: 40%;" colspan="3">بيانات الإيصال</th>
            <th style="width: 40%;" colspan="3">بيانات المعاملات</th>
            <th style="width: 20%;" colspan="2">مواعيد الدراسة</th>
        </tr>
        <tr>
            <td>ايصال رقم</td>
            <td>التاريخ</td>
            <td>الموظف المختص</td>
            <td>طريقة الحضور</td>
            <td>القسم</td>
            <td>نوع الكورس</td>
            <td>التوقيت</td>
            <td>الايام</td>
        </tr>
        <tr>
            <td>{{$revenue->id}}</td>
            <td>{{$revenue->date_of_rec}}</td>
            <td>{{Auth::user()->name}}</td>
            <td>{{Attend::AttendAr()[$student->dataStudent->attend]}}</td>
            <td>{{$student->dataStudent->section}}</td>
            <td>{{CourseType::CourseTypeAr()[$student->dataStudent->course_type]}}</td>
            <td rowspan="2">من {{date("h:i A", strtotime($revenue->course->start_time))}} <br/> الى {{date("h:i A", strtotime($revenue->course->end_time))}}</td>
            <td rowspan="2">
                <div>
                    @foreach(json_decode($revenue->course->days) as $value)
                    <span>{{WeekDays::WeekDaysAr()[$value]}}<br/></span>
                    @endforeach
                </div>
            </td>
        </tr>
        <tr >
            <td style="height: 40px" colspan="6"></td>
        </tr>
        <tr >
            <td style="height: 40px" colspan="8">الرســـــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــوم</td>
        </tr>
        <tr>
            <td colspan="2">طريقة الدفع</td>
            <td>دبلوم</td>
            <td>كورس</td>
            <td>نشاط ثقافي</td>
            <td>الاجمالي</td>
            <td>المدفوع</td>
            <td>الباقي</td>
        </tr>
        <tr>
            <td colspan="2">{{$student->dataStudent->payment_method}}</td>
            <td>{{0}}</td>
            <td>{{$revenue->type == 'course' ? $revenue->value : ''}}</td>
            <td>{{0}}</td>
            <td rowspan="2">{{$revenue->value}}</td>
            <td rowspan="2">{{$revenue->value_rec}}</td>
            <td rowspan="2">{{$revenue->value_rem . ' ' . $revenue->currency}}</td>
        </tr>
        <tr>
            <td style="text-align: right; height: 40px" colspan="5">توقيع الموظف المختص:</td>
        </tr>
    </table>  

    <table style="width: 100%; text-align: center;">
        <tr>
            <th colspan="7"> شارع الاستقلال (المقريف سابقا) - طرابلس - ليبيا      &nbsp;&nbsp;&nbsp;     هاتف : 00218918788222   &nbsp;&nbsp;&nbsp;   بريد إلكتروني :  info@hisacademia.ly</th>
        </tr>
    </table>
</body>
</html>