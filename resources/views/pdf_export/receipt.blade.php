<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إيصال دفع - {{ $receiptNumber }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @media print {
            @page {
                size: A5;
                margin: 1cm;
            }
            body {
                margin: 0;
                padding: 0;
                font-size: 14px;
                background: white !important;
            }
            .no-print {
                display: none !important;
            }
            .receipt-container {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
                max-width: 100%;
                margin: 0;
                border-radius: 0 !important;
            }
            .receipt-header {
                padding: 20px;
                background: #f8f9fa !important;
                color: #333 !important;
            }
            .receipt-body {
                padding: 25px 20px;
                background: white !important;
            }
            .receipt-footer {
                padding: 20px;
                background: #f8f9fa !important;
            }
            .company-name {
                font-size: 24px;
                color: #333 !important;
            }
            .amount-value {
                font-size: 28px;
            }
            .detail-row {
                padding: 10px 0;
            }
            .amount-section {
                background: #28a745 !important;
                color: white !important;
            }
        }
        
        body {
            font-family: 'Segoe UI', 'Cairo', 'Tajawal', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 10px;
        }
        
        .receipt-container {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            border-radius: 6px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid #e1e5e9;
        }
        
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .receipt-header {
            background: #2c3e50;
            color: white;
            padding: 15px;
            text-align: center;
            position: relative;
        }
        
        .receipt-header::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, #3498db, #2ecc71, #f39c12, #e74c3c);
        }
        
        .logo-container {
            position: relative;
            z-index: 2;
            margin-bottom: 10px;
        }
        
        .company-logo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }
        
        .company-logo img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .logo-placeholder {
            font-size: 24px;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .company-name {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 5px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            letter-spacing: 0.5px;
        }
        
        .company-tagline {
            font-size: 12px;
            opacity: 0.9;
            font-weight: 300;
        }
        
        .receipt-body {
            padding: 15px 15px;
            background: white;
        }
        
        .receipt-title {
            text-align: center;
            margin-bottom: 15px;
            color: #2c3e50;
            border-bottom: 1px solid #3498db;
            padding-bottom: 8px;
        }
        
        .receipt-title h2 {
            font-size: 16px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 3px;
            letter-spacing: 0.5px;
        }
        
        .receipt-title p {
            color: #7f8c8d;
            font-size: 12px;
            margin: 0;
        }
        
        .receipt-details {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #e9ecef;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 0;
            border-bottom: 1px solid #dee2e6;
            transition: background-color 0.3s ease;
        }
        
        .detail-row:hover {
            background-color: #e9ecef;
            border-radius: 3px;
            padding: 5px 3px;
            margin: 0 -3px;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 600;
            color: #495057;
            font-size: 12px;
        }
        
        .detail-value {
            color: #212529;
            font-weight: 500;
            font-size: 12px;
        }
        
        .amount-section {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border-radius: 6px;
            padding: 10px;
            text-align: center;
            margin-bottom: 10px;
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
        }
        
        .amount-label {
            font-size: 12px;
            opacity: 0.9;
            margin-bottom: 3px;
            font-weight: 500;
        }
        
        .amount-value {
            font-size: 22px;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .receipt-footer {
            background: #f8f9fa;
            padding: 10px 15px;
            text-align: center;
            border-top: 1px solid #dee2e6;
        }
        
        .receipt-number {
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .receipt-date {
            color: #6c757d;
            font-size: 12px;
            margin-bottom: 8px;
        }
        
        .thank-you {
            color: #495057;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 10px;
        }
        
        .print-actions {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin-top: 10px;
        }
        
        .btn-print {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border: none;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 6px rgba(0, 123, 255, 0.3);
            font-size: 12px;
        }
        
        .btn-print:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(0, 123, 255, 0.4);
            color: white;
        }
        
        .btn-download {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            border: none;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 6px rgba(40, 167, 69, 0.3);
            font-size: 12px;
        }
        
        .btn-download:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(40, 167, 69, 0.4);
            color: white;
        }
        
        .btn-back {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            border: none;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 6px rgba(108, 117, 125, 0.3);
            font-size: 12px;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-back:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(108, 117, 125, 0.4);
            color: white;
            text-decoration: none;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-confirmed {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        
        .status-pending {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }
        
        .icon {
            margin-left: 8px;
            font-size: 16px;
            transition: transform 0.3s ease;
        }
        
        .detail-row:hover .icon {
            transform: scale(1.1);
        }
        
        @media (max-width: 768px) {
            body {
                padding: 5px;
            }
            
            .receipt-container {
                margin: 0;
                border-radius: 5px;
                max-width: 100%;
            }
            
            .receipt-header {
                padding: 15px;
            }
            
            .receipt-body {
                padding: 15px;
            }
            
            .receipt-footer {
                padding: 15px;
            }
            
            .print-actions {
                flex-direction: column;
            }
            
            .company-name {
                font-size: 18px;
            }
            
            .amount-value {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="receipt-header">
            <div class="logo-container">
                <div class="company-logo">
                    @if(isset($companyLogo) && $companyLogo)
                        <img src="{{ $companyLogo }}" alt="Company Logo">
                    @else
                        <div class="logo-placeholder">
                            <i class="fas fa-building"></i>
                        </div>
                    @endif
                </div>
                <div class="company-name">الأكاديمية الأسبانية الليبية</div>
                <div class="company-tagline">مساحات العمل المشتركة</div>
            </div>
        </div>
        
        <!-- Body -->
        <div class="receipt-body">
            <div class="receipt-title">
                <h2><i class="fas fa-receipt icon"></i>إيصال دفع</h2>
                <p>تم تأكيد الدفع بنجاح</p>
            </div>
            
            <!-- Payment Details -->
            <div class="receipt-details">
                <div class="detail-row">
                    <span class="detail-label">
                        <i class="fas fa-user icon"></i>اسم العميل
                    </span>
                    <span class="detail-value">{{ $customerName }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">
                        <i class="fas fa-calendar icon"></i>نوع الحجز
                    </span>
                    <span class="detail-value">{{ $bookingType }}</span>
                </div>
                
                
                <div class="detail-row">
                    <span class="detail-label">
                        <i class="fas fa-calendar-alt icon"></i>فترة الحجز
                    </span>
                    <span class="detail-value">{{ $startDate }} - {{ $endDate }}</span>
                </div>
                
                
                <div class="detail-row">
                    <span class="detail-label">
                        <i class="fas fa-check-circle icon"></i>حالة الدفع
                    </span>
                    <span class="status-badge status-confirmed">{{ $paymentStatus }}</span>
                </div>
            </div>
            
            <!-- Amount Section -->
            <div class="amount-section">
                <div class="amount-label">المبلغ المدفوع</div>
                <div class="amount-value">{{ number_format($amount, 2) }} د.ل</div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="receipt-footer">
            <div class="receipt-number">
                <i class="fas fa-hashtag icon"></i>رقم الإيصال: {{ $receiptNumber }}
            </div>
            <div class="receipt-date">
                <i class="fas fa-clock icon"></i>{{ $paymentDateTime }}
            </div>
            <div class="thank-you">
                شكراً لاختيارك خدماتنا
            </div>
            
            <!-- Print Actions -->
            <div class="print-actions no-print">
                <a href="{{ route('data_entry.bookinglist') }}" class="btn-back">
                    <i class="fas fa-arrow-right me-2"></i>العودة لقائمة الحجوزات
                </a>
                <button onclick="window.print()" class="btn-print">
                    <i class="fas fa-print me-2"></i>طباعة الإيصال
                </button>
                <button onclick="downloadReceipt()" class="btn-download">
                    <i class="fas fa-download me-2"></i>تحميل PDF
                </button>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-print when page loads
        window.addEventListener('load', function() {
            // Small delay to ensure everything is loaded
            setTimeout(function() {
                window.print();
            }, 1000);
        });
        
        // Download receipt as PDF
        function downloadReceipt() {
            // Create a new window for PDF generation
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>إيصال دفع - {{ $receiptNumber }}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
                        .receipt { max-width: 600px; margin: 0 auto; }
                        .header { text-align: center; margin-bottom: 30px; }
                        .details { margin-bottom: 20px; }
                        .detail-row { display: flex; justify-content: space-between; margin-bottom: 10px; }
                        .amount { text-align: center; font-size: 24px; font-weight: bold; margin: 20px 0; }
                        .footer { text-align: center; margin-top: 30px; }
                    </style>
                </head>
                <body>
                    <div class="receipt">
                        <div class="header">
                            <h1>الأكاديمية الأسبانية الليبية</h1>
                            <h2>إيصال دفع</h2>
                        </div>
                        <div class="details">
                            <div class="detail-row"><span>اسم العميل:</span><span>{{ $customerName }}</span></div>
                            <div class="detail-row"><span>نوع الحجز:</span><span>{{ $bookingType }}</span></div>
                            <div class="detail-row"><span>مساحة العمل:</span><span>{{ $coworkingSpaceName }}</span></div>
                            <div class="detail-row"><span>فترة الحجز:</span><span>{{ $startDate }} - {{ $endDate }}</span></div>
                            <div class="detail-row"><span>طريقة الدفع:</span><span>{{ $paymentMethod }}</span></div>
                            <div class="detail-row"><span>حالة الدفع:</span><span>{{ $paymentStatus }}</span></div>
                        </div>
                        <div class="amount">المبلغ: {{ number_format($amount, 2) }} د.ل</div>
                        <div class="footer">
                            <p>رقم الإيصال: {{ $receiptNumber }}</p>
                            <p>{{ $paymentDateTime }}</p>
                        </div>
                    </div>
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        }
        
        // Close window after printing (optional)
        window.addEventListener('afterprint', function() {
            // Uncomment the line below if you want to close the window after printing
            // window.close();
        });
    </script>
</body>
</html>
