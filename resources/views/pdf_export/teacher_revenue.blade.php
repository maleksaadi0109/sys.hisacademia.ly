<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Teacher Revenue</title>
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
                <td style="width: 85%;">{{$course->user->name}}</td>
            <tr>
        </tbody>
        <tbody>
            <tr>
                <td style="width: 15%; text-align: right;">رقم الهاتف: </td>
                <td style="width: 85%;"> {{$teacher->phone}} </td>
            <tr>
        </tbody>
        <tbody>
            <tr>
                <td style="width: 15%; text-align: right;">رقم الهوية: </td>
                <td style="width: 85%;"> {{$teacher->id_number}} </td>
            <tr>
        </tbody>
    </table>
    @php
    use App\Enums\WeekDays;
    @endphp
    <table style="width: 100%; text-align: center;">
        <tr>
            <th colspan="2">بيانات الإيصال</th>
            <th colspan="4">بيانات الكورس</th>
            <th colspan="1">أبصر للترجمة القانونية</th>
        </tr>
        <tr>
            <td colspan="1">رقم المعلم التسلسلي</td>
            <td colspan="1">الموظف المختص</td>
            <td colspan="1">رقم الكورس التسلسلي</td>
            <td colspan="1">اسم الكورس</td>
            <td colspan="1">المستوى</td>
            <td colspan="1">القسم</td>
            <td colspan="1">تاريخ التسليم</td>
        </tr>
        <tr>
            <td colspan="1">{{$teacher->id}}</td>
            <td colspan="1">{{Auth::user()->name}}</td>
            <td colspan="1">{{$course->id}}</td>
            <td colspan="1">{{$course->name}}</td>
            <td colspan="1">{{$course->level}}</td>
            <td colspan="1">{{$course->section}}</td>
            <td colspan="1">{{$date}}</td>
        </tr>
        <tr >
            <td style="height: 40px" colspan="2">ايام الكورس</td>
            <td style="height: 40px" colspan="5">
                <div>
                    @foreach(json_decode($course->days) as $value)
                    <span>{{WeekDays::WeekDaysAr()[$value]}}<br/></span>
                    @endforeach
                </div>
            </td>
        </tr>
        <tr >
            <td style="height: 40px" colspan="7">الرســـــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــوم</td>
        </tr>
        <tr>
            <td colspan="2">عدد الساعات</td>
            <td colspan="2">سعر الساعة</td>
            <td colspan="2">الكلي</td>
            <td colspan="1">العملة</td>
        </tr>
        <tr>
            <td colspan="2">{{$course->total_days}}</td>
            <td colspan="2">{{40 .'D'}}</td>
            <td colspan="2">{{$course->total_days * 40}}</td>
            <td colspan="1">{{'D'}}</td>
        </tr>
        <tr>
            <td style="text-align: right; height: 50px" colspan="3">توقيع الموظف المختص:</td>
            <td style="text-align: right; height: 50px" colspan="4">توقيع الاستاذ:</td>
        </tr>
    </table>  

    <table style="width: 100%; text-align: center;">
        <tr>
            <th colspan="7"> شارع الاستقلال (المقريف سابقا) - طرابلس - ليبيا      &nbsp;&nbsp;&nbsp;     هاتف : 00218918788222   &nbsp;&nbsp;&nbsp;   بريد إلكتروني :  info@hisacademia.ly</th>
        </tr>
    </table>
</body>
</html>