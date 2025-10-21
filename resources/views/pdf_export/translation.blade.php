<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>translation</title>
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
                <td style="width: 85%;">{{$translationDeal->customer->name}}</td>
            <tr>
        </tbody>
        <tbody>
            <tr>
                <td style="width: 15%; text-align: right;">رقم الهاتف: </td>
                <td style="width: 85%;"> {{$translationDeal->customer->phone}} </td>
            <tr>
        </tbody>
        <tbody>
            <tr>
                <td style="width: 15%; text-align: right;">العنوان: </td>
                <td style="width: 85%;"> {{$translationDeal->customer->address}} </td>
            <tr>
        </tbody>
    </table>

    <table style="width: 100%; text-align: center;">
        <tr>
            <th colspan="3">بيانات الإيصال</th>
            <th colspan="3">بيانات المعاملات</th>
            <th colspan="1">أبصر للترجمة القانونية</th>
        </tr>
        <tr>
            <td>ايصال رقم</td>
            <td>تاريخ الاستلام</td>
            <td>الموظف المختص</td>
            <td>عدد المعاملات</td>
            <td>عدد الأوراق</td>
            <td>اللغة</td>
            <td>تاريخ التسليم</td>
        </tr>
        <tr>
            <td>{{$translationDeal->id}}</td>
            <td>{{$translationDeal->date_of_receipt}}</td>
            <td>{{Auth::user()->name}}</td>
            <td>{{$translationDeal->number_of_transaction}}</td>
            <td>{{$translationDeal->number_of_sheets}}</td>
            <td>{{$translationDeal->language}}</td>
            <td>{{$date}}</td>
        </tr>
        <tr >
            <td style="height: 40px" colspan="1">نوع المعاملات</td>
            <td style="height: 40px" colspan="6">{{$translationDeal->context}}</td>
        </tr>
        <tr >
            <td style="height: 40px" colspan="7">الرســـــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــوم</td>
        </tr>
        <tr>
            <td colspan="4">طريقة الدفع</td>
            <td>الاجمالي</td>
            <td>المدفوع</td>
            <td>الباقي</td>
        </tr>
        <tr>
            <td colspan="4">{{$translationDeal->payment_method}}</td>
            <td rowspan="2">{{$translationDeal->price}}</td>
            <td rowspan="2">{{$translationDeal->received}}</td>
            <td rowspan="2">{{$translationDeal->remaining . ' ' . $translationDeal->currency}}</td>
        </tr>
        <tr>
            <td style="text-align: right; height: 40px" colspan="4">توقيع الموظف المختص:</td>
        </tr>
    </table>  

    <table style="width: 100%; text-align: center;">
        <tr>
            <th colspan="7"> شارع الاستقلال (المقريف سابقا) - طرابلس - ليبيا      &nbsp;&nbsp;&nbsp;     هاتف : 00218918788222   &nbsp;&nbsp;&nbsp;   بريد إلكتروني :  info@hisacademia.ly</th>
        </tr>
    </table>
</body>
</html>