<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة الإيصال - دبلوم</title>
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
    </style>
</head>
<body>
    <div class="no-print">
        <button class="print-btn" onclick="window.print()">طباعة</button>
        <button class="print-btn" onclick="window.close()">إغلاق</button>
    </div>
    
    @include('pdf_export.student_diploma', [
        'title' => $title,
        'revenue' => $revenue,
        'student' => $student
    ])

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>

