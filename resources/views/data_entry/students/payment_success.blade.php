@extends('data_entry.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('تم تسجيل الدفع بنجاح!')}}</h2>
            <p class="mt-1 text-sm text-gray-600">{{__('تم تسجيل الدفع للطالب بنجاح. اختر الخطوة التالية:')}}</p>
        </header>

        <div class="container mt-6">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Success Message Card -->
                    <div class="card mb-4 border-success">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-check-circle me-2"></i>
                                تم تسجيل الدفع بنجاح
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>اسم الطالب:</strong> {{ $revenue->user->name }}
                                </div>
                                <div class="col-md-6">
                                    <strong>نوع الرسوم:</strong> 
                                    @if($revenue->type == 'tuition_fees')
                                        الرسوم الدراسية
                                    @elseif($revenue->type == 'additional_fees')
                                        الرسوم الإضافية
                                    @elseif($revenue->type == 'late_fees')
                                        رسوم التأخير
                                    @else
                                        {{ $revenue->type }}
                                    @endif
                                </div>
                                <div class="col-md-6 mt-2">
                                    <strong>المبلغ المدفوع:</strong> {{ $revenue->value }} {{ $revenue->currency }}
                                </div>
                                <div class="col-md-6 mt-2">
                                    <strong>طريقة الدفع:</strong> 
                                    @if(isset($payment_details['payment_method']))
                                        @if($payment_details['payment_method'] == 'cash')
                                            نقدي
                                        @elseif($payment_details['payment_method'] == 'bank_transfer')
                                            تحويل بنكي
                                        @elseif($payment_details['payment_method'] == 'credit_card')
                                            بطاقة ائتمان
                                        @else
                                            {{ $payment_details['payment_method'] }}
                                        @endif
                                    @endif
                                </div>
                                <div class="col-md-6 mt-2">
                                    <strong>رقم الإيصال:</strong> {{ $revenue->id }}
                                </div>
                                <div class="col-md-6 mt-2">
                                    <strong>تاريخ الدفع:</strong> {{ $revenue->date_of_rec }}
                                </div>
                                @if(isset($payment_details['notes']) && !empty($payment_details['notes']))
                                <div class="col-md-12 mt-2">
                                    <strong>ملاحظات:</strong> {{ $payment_details['notes'] }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Action Options -->
                    <div class="row g-4">
                        <!-- Go to Home -->
                        <div class="col-md-4">
                            <div class="card h-100 border-primary">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-home fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title text-primary">العودة للرئيسية</h4>
                                    <p class="card-text text-muted">
                                        العودة إلى الصفحة الرئيسية
                                    </p>
                                    <div class="mt-4">
                                        <a href="{{ route('data_entry.dashboard') }}" 
                                           class="btn btn-primary btn-lg w-100">
                                            <i class="fas fa-home me-2"></i>
                                            الرئيسية
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pay More Fees -->
                        <div class="col-md-4">
                            <div class="card h-100 border-warning">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-credit-card fa-3x text-warning"></i>
                                    </div>
                                    <h4 class="card-title text-warning">دفع رسوم أخرى</h4>
                                    <p class="card-text text-muted">
                                        تسجيل دفعة أخرى للطالب
                                    </p>
                                    <div class="mt-4">
                                        <a href="{{ route('data_entry.pay_fees', ['student_id' => $student->id]) }}" 
                                           class="btn btn-warning btn-lg w-100">
                                            <i class="fas fa-credit-card me-2"></i>
                                            دفع رسوم أخرى
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Print Receipt -->
                        <div class="col-md-4">
                            <div class="card h-100 border-info">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-print fa-3x text-info"></i>
                                    </div>
                                    <h4 class="card-title text-info">طباعة الإيصال</h4>
                                    <p class="card-text text-muted">
                                        طباعة إيصال تسديد الرسوم
                                    </p>
                                    <div class="mt-4">
                                        <a href="{{ route('data_entry.print_student_bill', ['id' => $revenue->id, 'print' => true]) }}" 
                                           target="_blank"
                                           class="btn btn-info btn-lg w-100">
                                            <i class="fas fa-print me-2"></i>
                                            طباعة الإيصال
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .btn {
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .card-header {
            border-bottom: none;
        }
        
        .fa-3x {
            font-size: 3rem;
        }
        
        .border-primary {
            border-color: #007bff !important;
        }
        
        .border-warning {
            border-color: #ffc107 !important;
        }
        
        .border-info {
            border-color: #17a2b8 !important;
        }
    </style>
@endsection

