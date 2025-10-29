<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>إيصال تسديد رسوم التسجيل - دبلوم</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 8pt;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: #1f2937;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        
        th, td {
            border: 1px solid #cbd5e1;
            padding: 5px 4px;
            text-align: center;
            font-size: 7.5pt;
            vertical-align: middle;
        }
        
        th {
            background-color: #3b82f6;
            color: #ffffff;
            font-weight: bold;
            border-color: #1e40af;
        }
        
        /* Header Section */
        .header-table {
            border: none;
            margin-bottom: 8px;
        }
        
        .header-table td {
            border: none;
            background-color: transparent;
            padding: 5px;
            vertical-align: middle;
        }
        
        .header-table h2 {
            margin: 0;
            padding: 0;
            font-size: 16pt;
            font-weight: bold;
            color: #1e40af;
            text-align: right;
        }
        
        .header-table img {
            max-width: 70px;
            max-height: 70px;
            width: auto;
            height: auto;
            display: block;
            margin: 0 auto;
        }
        
        /* Student Info Section */
        .info-table {
            margin-bottom: 8px;
        }
        
        .info-table td {
            text-align: right;
            padding: 6px 8px;
            background-color: #f8fafc;
            border: 1px solid #e5e7eb;
        }
        
        .info-table .label {
            background-color: #eff6ff !important;
            font-weight: bold;
            color: #1e40af;
            width: 25%;
            padding-right: 12px;
        }
        
        .info-table .value {
            color: #1f2937;
            padding-right: 15px;
        }
        
        /* Main Data Table */
        .main-table {
            margin-bottom: 8px;
        }
        
        .main-table th {
            background-color: #2563eb;
            color: #ffffff;
            font-size: 8pt;
            padding: 6px 4px;
        }
        
        .main-table .sub-header td {
            background-color: #f1f5f9;
            font-weight: bold;
            font-size: 7pt;
            padding: 4px 3px;
        }
        
        .main-table .revenue-id {
            background-color: #eff6ff !important;
            font-weight: bold;
            color: #1e40af;
            font-size: 8pt;
        }
        
        .main-table .time-details {
            background-color: #f0f9ff !important;
            color: #0369a1;
            font-weight: 500;
            font-size: 7pt;
        }
        
        .main-table .days-details {
            background-color: #ecfdf5 !important;
            color: #065f46;
            font-weight: 500;
            font-size: 7pt;
        }
        
        .main-table .spacer {
            height: 25px;
            border: none;
            background-color: transparent;
        }
        
        /* Fees Section */
        .fees-header td {
            background-color: #f59e0b !important;
            color: #ffffff !important;
            font-weight: bold;
            font-size: 10pt;
            padding: 6px;
            border: 2px solid #d97706 !important;
        }
        
        .fees-subheader td {
            background-color: #fef3c7 !important;
            font-weight: bold;
            font-size: 7pt;
            padding: 4px 3px;
            border-color: #fbbf24;
        }
        
        .total-fees {
            background-color: #d1fae5 !important;
            font-weight: bold;
            color: #059669;
            font-size: 8.5pt;
        }
        
        .signature-row td {
            text-align: right;
            padding: 18px 10px;
            background-color: #f9fafb !important;
            color: #6b7280;
            font-style: italic;
            font-size: 7pt;
        }
        
        .coupon-row td {
            border: 1px solid #1e40af;
            font-size: 7pt;
            padding: 4px;
        }
        
        /* Footer */
        .footer-table {
            margin-top: 8px;
        }
        
        .footer-table th {
            background-color: #1e293b;
            color: #ffffff;
            padding: 6px;
            font-size: 6.5pt;
            font-weight: normal;
            border: none;
            text-align: center;
        }
        
        .course-row {
            border-bottom: 1px solid #e5e7eb;
        }
        
        .course-row:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>

    @php
        use App\Enums\WeekDays;
        use App\Enums\Attend;
        use App\Enums\CourseType;
        
        // Try different logo paths
        $logoPath = null;
        $possiblePaths = [
            public_path('images/logo.jpg'),
            public_path('logo.jpg'),
            storage_path('app/public/logo/logo.png'),
            storage_path('logo/logo.png'),
        ];
        foreach($possiblePaths as $path) {
            if(file_exists($path)) {
                $logoPath = $path;
                break;
            }
        }
        
        // Get first revenue for calculations
        $firstRevenue = $revenue[0];
    @endphp

    <table class="header-table">
        <tr>
            <td style="width: 75%;">
                <h2>{{$title ?? 'إيصال تسديد رسوم التسجيل - دبلوم'}}</h2>
            </td>
            <td style="width: 25%; text-align: center; vertical-align: middle;">
                @if($logoPath)
                    <img src="{{ $logoPath }}" alt="Logo" style="max-width: 70px; max-height: 70px;"/>
                @else
                    <!-- Fallback if logo not found -->
                    <div style="width: 70px; height: 70px; border: 2px dashed #ccc; display: inline-block;"></div>
                @endif
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td class="label">الاسم:</td>
            <td class="value">{{$firstRevenue->user->name}}</td>
        </tr>
        <tr>
            <td class="label">رقم الهاتف:</td>
            <td class="value">{{$student->phone}}</td>
        </tr>
        <tr>
            <td class="label">رقم إثبات الهوية:</td>
            <td class="value">{{$student->id_number}}</td>
        </tr>
    </table>

    <table class="main-table">
        <tr>
            <th colspan="3">بيانات الإيصال</th>
            <th>بيانات المعاملات</th>
            <th colspan="2">مواعيد الدراسة</th>
        </tr>
        <tr class="sub-header">
            <td>ايصال رقم</td>
            <td>التاريخ</td>
            <td>الموظف المختص</td>
            <td>اسم الكورس</td>
            <td>التوقيت</td>
            <td>الايام</td>
        </tr>
        @foreach($revenue as $rev)
        <tr class="course-row">
            <td class="revenue-id">{{$rev->id}}</td>
            <td>{{$rev->date_of_rec}}</td>
            <td>{{Auth::user()->name}}</td>
            <td>{{$rev->course ? $rev->course->name : 'N/A'}}</td>
            @if($rev->course)
                <td class="time-details">
                    من {{date("h:i A", strtotime($rev->course->start_time))}}<br/>
                    الى {{date("h:i A", strtotime($rev->course->end_time))}}
                </td>
                <td class="days-details">
                    @if($rev->course->days)
                        @foreach(json_decode($rev->course->days) as $dayValue)
                            {{WeekDays::WeekDaysAr()[$dayValue]}}<br/>
                        @endforeach
                    @endif
                </td>
            @else
                <td colspan="2" style="text-align: center; background-color: #f9fafb; color: #6b7280;">-</td>
            @endif
        </tr>
        @endforeach
        <tr>
            <td colspan="6" class="spacer"></td>
        </tr>
        <tr class="fees-header">
            <td colspan="6">الرسوم</td>
        </tr>
        <tr class="fees-subheader">
            <td>دبلوم</td>
            <td>كورس</td>
            <td>نشاط ثقافي</td>
            <td>الاجمالي</td>
            <td>المدفوع</td>
            <td>الباقي</td>
        </tr>
        <tr>
            <td>{{isset($firstRevenue->diploma) && $firstRevenue->diploma ? $firstRevenue->diploma->price : $firstRevenue->value}}</td>
            <td>{{0}}</td>
            <td>{{0}}</td>
            <td rowspan="2" class="total-fees">{{$firstRevenue->value}}</td>
            <td rowspan="2" class="total-fees">{{$firstRevenue->value_rec}}</td>
            <td rowspan="2" class="total-fees">{{$firstRevenue->value_rem . ' ' . $firstRevenue->currency}}</td>
        </tr>
        @if($firstRevenue->coupon_code)
        <tr class="coupon-row">
            <td colspan="6" style="background-color: #f0f9ff; color: #1e40af; font-weight: bold; text-align: center;">
                خصم الكوبون: {{$firstRevenue->coupon_code}} - مبلغ الخصم: {{$firstRevenue->discount_amount}} {{$firstRevenue->currency}}
            </td>
        </tr>
        @endif
        <tr class="signature-row">
            <td colspan="3">توقيع الموظف المختص:</td>
        </tr>
    </table>

    <table class="footer-table">
        <tr>
            <th>
                شارع الاستقلال (المقريف سابقا) - طرابلس - ليبيا &nbsp;&nbsp;&nbsp;
                هاتف: 00218918788222 &nbsp;&nbsp;&nbsp;
                بريد إلكتروني: info@hisacademia.ly
            </th>
        </tr>
    </table>
</body>
</html>