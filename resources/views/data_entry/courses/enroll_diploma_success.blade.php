@extends('data_entry.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('تم التسجيل بنجاح!')}}</h2>
            <p class="mt-1 text-sm text-gray-600">{{__('تم تسجيل الدبلوم للطالب بنجاح. اختر الخطوة التالية:')}}</p>
        </header>

        <div class="container mt-6">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Success Message Card -->
                    <div class="card mb-4 border-success">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-check-circle me-2"></i>
                                تم التسجيل بنجاح
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>اسم الطالب:</strong> {{ $revenue->user->name }}
                                </div>
                                <div class="col-md-6">
                                    <strong>اسم الدبلوم:</strong> {{ $revenue->diploma->name }}
                                </div>
                                <div class="col-md-6 mt-2">
                                    <strong>المبلغ الإجمالي:</strong> {{ $revenue->value }} {{ $revenue->currency }}
                                </div>
                                <div class="col-md-6 mt-2">
                                    <strong>رقم الإيصال:</strong> {{ $revenue->id }}
                                </div>
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

                        <!-- Book Another Diploma -->
                        <div class="col-md-4">
                            <div class="card h-100 border-success">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-graduation-cap fa-3x text-success"></i>
                                    </div>
                                    <h4 class="card-title text-success">حجز دبلوم آخر</h4>
                                    <p class="card-text text-muted">
                                        تسجيل الطالب في دبلوم آخر
                                    </p>
                                    <div class="mt-4">
                                        <a href="{{ route('data_entry.buy.diploma') }}" 
                                           class="btn btn-success btn-lg w-100">
                                            <i class="fas fa-graduation-cap me-2"></i>
                                            حجز دبلوم آخر
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Print Receipt -->
                        <div class="col-md-4">
                            <div class="card h-100 border-warning">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-print fa-3x text-warning"></i>
                                    </div>
                                    <h4 class="card-title text-warning">طباعة الإيصال</h4>
                                    <p class="card-text text-muted">
                                        طباعة إيصال تسديد الرسوم
                                    </p>
                                    <div class="mt-4">
                                        <a href="{{ route('data_entry.print_student_bill', ['id' => $revenue->id, 'print' => true]) }}" 
                                           target="_blank"
                                           class="btn btn-warning btn-lg w-100">
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
        
        .border-success {
            border-color: #28a745 !important;
        }
        
        .border-warning {
            border-color: #ffc107 !important;
        }
    </style>
@endsection

