<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة الإيصال</title>
    <style>
        @media print {
            @page {
                size: A5;
                margin: 8mm 10mm;
            }
            body {
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 8pt;
            margin: 0;
            padding: 20px;
            background-color: #ffffff;
            color: #1f2937;
        }
        
        .no-print {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        
        .print-btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 5px;
            transition: background-color 0.3s;
        }
        
        .print-btn:hover {
            background-color: #0056b3;
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
    </style>
</head>
<body>
    <div class="no-print">
        <button class="print-btn" onclick="window.print()">طباعة</button>
        <button class="print-btn" onclick="window.close()">إغلاق</button>
    </div>
    
    @php
    use App\Enums\WeekDays;
    // Try different logo paths for web view
    $logoUrl = null;
    if(file_exists(public_path('images/logo.jpg'))) {
        $logoUrl = asset('images/logo.jpg');
    } elseif(file_exists(public_path('logo.jpg'))) {
        $logoUrl = asset('logo.jpg');
    } elseif(file_exists(public_path('storage/logo/logo.png'))) {
        $logoUrl = asset('storage/logo/logo.png');
    }
    @endphp

    <table class="header-table">
        <tr>
            <td style="width: 75%;">
                <h2>{{$title}}</h2>
            </td>
            <td style="width: 25%; text-align: center;">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="Logo" style="max-width: 70px; max-height: 70px;"/>
                @endif
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td class="label">الاسم:</td>
            <td class="value">{{$revenue->user->name}}</td>
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
        <tr>
            <td class="revenue-id">{{$revenue->id}}</td>
            <td>{{$revenue->date_of_rec}}</td>
            <td>{{Auth::user()->name}}</td>
            <td>
                @if($revenue->course)
                    {{$revenue->course->name}}
                @else
                    @php
                        $paymentTypeNames = [
                            'tuition_fees' => 'الرسوم الدراسية',
                            'additional_fees' => 'الرسوم الإضافية',
                            'late_fees' => 'رسوم التأخير'
                        ];
                        echo $paymentTypeNames[$revenue->type] ?? $revenue->type;
                    @endphp
                @endif
            </td>
            @if($revenue->course)
                <td rowspan="2" class="time-details">
                    من {{date("h:i A", strtotime($revenue->course->start_time))}}<br/>
                    الى {{date("h:i A", strtotime($revenue->course->end_time))}}
                </td>
                <td rowspan="2" class="days-details">
                    @foreach(json_decode($revenue->course->days) as $value)
                        {{WeekDays::WeekDaysAr()[$value]}}<br/>
                    @endforeach
                </td>
            @else
                <td colspan="2" rowspan="2" style="text-align: center; vertical-align: middle; background-color: #f9fafb; color: #6b7280; font-style: italic;">
                    @php
                        $paymentTypeNames = [
                            'tuition_fees' => 'الرسوم الدراسية',
                            'additional_fees' => 'الرسوم الإضافية',
                            'late_fees' => 'رسوم التأخير'
                        ];
                        echo 'دفع ' . ($paymentTypeNames[$revenue->type] ?? '');
                    @endphp
                </td>
            @endif
        </tr>
        <tr>
            <td colspan="4" class="spacer"></td>
        </tr>
        <tr class="fees-header">
            <td colspan="5">الرسوم</td>
        </tr>
        <tr class="fees-subheader">
            <td>دبلوم</td>
            <td>كورس</td>
            <td>الاجمالي</td>
            <td>المدفوع</td>
            <td>الباقي</td>
        </tr>
        <tr>
            <td>{{0}}</td>
            <td>{{$revenue->type == 'course' ? $revenue->value : ''}}</td>
            <td rowspan="2" class="total-fees">{{$revenue->value}}</td>
            <td rowspan="2" class="total-fees">{{$revenue->value_rec}}</td>
            <td rowspan="2" class="total-fees">{{$revenue->value_rem . ' ' . $revenue->currency}}</td>
        </tr>
        <tr class="signature-row">
            <td colspan="2">توقيع الموظف المختص:</td>
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

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>


