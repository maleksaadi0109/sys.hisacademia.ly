<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة الكوبونات</title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
            .coupon-container { 
                page-break-inside: avoid;
                margin-bottom: 10px;
            }
        }
        
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .coupon-container {
            display: inline-block;
            width: 300px;
            margin: 10px;
            background: white;
            border: 2px dashed #ddd;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .coupon-code {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin: 10px 0;
            padding: 10px;
            background: #ecf0f1;
            border-radius: 5px;
            letter-spacing: 2px;
        }
        
        .coupon-type {
            font-size: 16px;
            color: #7f8c8d;
            margin: 5px 0;
        }
        
        .coupon-value {
            font-size: 20px;
            font-weight: bold;
            color: #27ae60;
            margin: 10px 0;
        }
        
        .coupon-applicable {
            font-size: 14px;
            color: #95a5a6;
            margin: 5px 0;
        }
        
        .coupon-validity {
            font-size: 12px;
            color: #bdc3c7;
            margin: 5px 0;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #3498db;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            z-index: 1000;
        }
        
        .print-button:hover {
            background: #2980b9;
        }
        
        .back-button {
            position: fixed;
            top: 20px;
            left: 20px;
            background: #95a5a6;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            z-index: 1000;
        }
        
        .back-button:hover {
            background: #7f8c8d;
        }
        
        .coupons-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button class="print-button" onclick="window.print()">
            <i class="fas fa-print"></i> طباعة الكوبونات
        </button>
        
        <button class="back-button" onclick="window.location.href='{{ route('admin.coupons.index') }}'">
            <i class="fas fa-arrow-right"></i> العودة للقائمة
        </button>
    </div>

    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    <div class="header">
        <h1>الكوبونات المطبوعة</h1>
        <p>عدد الكوبونات: {{ count($coupons) }}</p>
    </div>

    <div class="coupons-grid">
        @foreach($coupons as $coupon)
            <div class="coupon-container">
                <div class="coupon-code">{{ $coupon->code }}</div>
                <div class="coupon-type">
                    @if($coupon->type == 'fixed')
                        خصم ثابت
                    @else
                        خصم نسبة مئوية
                    @endif
                </div>
                <div class="coupon-value">
                    {{ $coupon->formatted_value }}
                </div>
                <div class="coupon-applicable">
                    ينطبق على: 
                    @if($coupon->applicable_to == 'courses')
                        الكورسات
                    @elseif($coupon->applicable_to == 'diplomas')
                        الدبلومات
                    @else
                        الكورسات والدبلومات
                    @endif
                </div>
                @if($coupon->valid_until)
                    <div class="coupon-validity">
                        صالح حتى: {{ $coupon->valid_until->format('Y-m-d') }}
                    </div>
                @endif
                @if($coupon->usage_limit)
                    <div class="coupon-validity">
                        حد الاستخدام: {{ $coupon->usage_limit }} مرة
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 1000);
        };
    </script>
</body>
</html>
