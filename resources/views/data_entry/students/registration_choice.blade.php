@extends('data_entry.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('اختيار نوع التسجيل')}}</h2>
            <p class="mt-1 text-sm text-gray-600">{{__('تم حفظ بيانات الطالب بنجاح. اختر نوع التسجيل المطلوب:')}}</p>
        </header>

        <div class="container mt-6">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Student Info Card -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-user-graduate me-2"></i>
                                معلومات الطالب المحفوظ
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>اسم الطالب:</strong> {{ $student->user->name }}
                                </div>
                                <div class="col-md-6">
                                    <strong>البريد الإلكتروني:</strong> {{ $student->user->email }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Registration Options -->
                    <div class="row g-4">
                        <!-- Diploma Registration -->
                        <div class="col-md-4">
                            <div class="card h-100 border-success">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-graduation-cap fa-3x text-success"></i>
                                    </div>
                                    <h4 class="card-title text-success">تسجيل في دبلوم</h4>
                                    <p class="card-text text-muted">
                                        تسجيل الطالب في أحد الدبلومات المتاحة في الأكاديمية
                                    </p>
                                    <div class="mt-4">
                                        <a href="{{ route('data_entry.buy.diploma', ['student_id' => $student->id]) }}" 
                                           class="btn btn-success btn-lg w-100">
                                            <i class="fas fa-graduation-cap me-2"></i>
                                            تسجيل في دبلوم
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Course Registration -->
                        <div class="col-md-4">
                            <div class="card h-100 border-primary">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-book fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title text-primary">تسجيل في كورس</h4>
                                    <p class="card-text text-muted">
                                        تسجيل الطالب في أحد الكورسات المتاحة في الأكاديمية
                                    </p>
                                    <div class="mt-4">
                                        <a href="{{ route('data_entry.buy.course', ['student_id' => $student->id]) }}" 
                                           class="btn btn-primary btn-lg w-100">
                                            <i class="fas fa-book me-2"></i>
                                            تسجيل في كورس
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pay Fees -->
                        <div class="col-md-4">
                            <div class="card h-100 border-warning">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-credit-card fa-3x text-warning"></i>
                                    </div>
                                    <h4 class="card-title text-warning">دفع الرسوم</h4>
                                    <p class="card-text text-muted">
                                        دفع الرسوم الدراسية أو الرسوم الإضافية للطالب
                                    </p>
                                    <div class="mt-4">
                                        <a href="{{ route('data_entry.pay_fees', ['student_id' => $student->id]) }}" 
                                           class="btn btn-warning btn-lg w-100">
                                            <i class="fas fa-credit-card me-2"></i>
                                            دفع الرسوم
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Options -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5 class="card-title">خيارات إضافية</h5>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <a href="{{ route('data_entry.students',['orderBy' => 'null','sort' => 'null']) }}" 
                                               class="btn btn-outline-secondary w-100">
                                                <i class="fas fa-list me-2"></i>
                                                عرض قائمة الطلاب
                                            </a>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="{{ route('data_entry.add.student') }}" 
                                               class="btn btn-outline-info w-100">
                                                <i class="fas fa-user-plus me-2"></i>
                                                إضافة طالب جديد
                                            </a>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="{{ route('data_entry.dashboard') }}" 
                                               class="btn btn-outline-dark w-100">
                                                <i class="fas fa-home me-2"></i>
                                                العودة للرئيسية
                                            </a>
                                        </div>
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
        
        .border-success {
            border-color: #28a745 !important;
        }
        
        .border-primary {
            border-color: #007bff !important;
        }
        
        .border-warning {
            border-color: #ffc107 !important;
        }
    </style>
@endsection
