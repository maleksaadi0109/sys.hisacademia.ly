<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة الكوبون - {{ $coupon->code }}</title>
    <style>
        @page {
            size: A4;
            margin: 0.5cm;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: white;
        }
        
        .coupon-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            padding: 10px;
        }
        
        .coupon {
            width: 100%;
            height: 8cm;
            border: 2px solid #000;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            position: relative;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .coupon-header {
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        
        .coupon-title {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin: 0;
        }
        
        .coupon-code {
            font-size: 24px;
            font-weight: bold;
            color: #e74c3c;
            background: #fff;
            padding: 8px;
            border: 2px solid #e74c3c;
            border-radius: 5px;
            margin: 10px 0;
            letter-spacing: 2px;
        }
        
        .coupon-value {
            font-size: 20px;
            font-weight: bold;
            color: #27ae60;
            margin: 10px 0;
        }
        
        .coupon-details {
            font-size: 12px;
            color: #666;
            margin: 5px 0;
        }
        
        .coupon-footer {
            border-top: 2px dashed #000;
            padding-top: 10px;
            margin-top: 10px;
            font-size: 10px;
            color: #666;
        }
        
        .validity-info {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 3px;
            padding: 5px;
            margin: 5px 0;
            font-size: 10px;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            z-index: 1000;
        }
        
        .print-button:hover {
            background: #0056b3;
        }
        
        @media print {
            .print-button {
                display: none;
            }
            
            .coupon-container {
                grid-template-columns: repeat(4, 1fr);
                gap: 5px;
                padding: 5px;
            }
            
            .coupon {
                height: 7.5cm;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">
        <i class="fas fa-print"></i> طباعة
    </button>

    <div class="coupon-container">
        @for($i = 0; $i < 100; $i++)
            <div class="coupon">
                <div class="coupon-header">
                    <h3 class="coupon-title">كوبون خصم</h3>
                    <div class="coupon-code">{{ $coupon->code }}</div>
                </div>
                
                <div class="coupon-value">
                    @if($coupon->type === 'fixed')
                        خصم {{ $coupon->value }} دينار
                    @else
                        خصم {{ $coupon->value }}%
                    @endif
                </div>
                
                <div class="coupon-details">
                    @if($coupon->applicable_to === 'courses')
                        صالح للكورسات فقط
                    @elseif($coupon->applicable_to === 'diplomas')
                        صالح للدبلومات فقط
                    @else
                        صالح للكورسات والدبلومات
                    @endif
                </div>
                
                @if($coupon->valid_from || $coupon->valid_until)
                    <div class="validity-info">
                        @if($coupon->valid_from)
                            من: {{ \Carbon\Carbon::parse($coupon->valid_from)->format('Y-m-d') }}
                        @endif
                        @if($coupon->valid_until)
                            إلى: {{ \Carbon\Carbon::parse($coupon->valid_until)->format('Y-m-d') }}
                        @endif
                    </div>
                @endif
                
                <div class="coupon-footer">
                    <div>FutureZD Academy</div>
                    <div>تطبق الشروط والأحكام</div>
                </div>
            </div>
        @endfor
    </div>

    <script>
        // Auto print when page loads
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 1000);
        });
    </script>
</body>
</html>
