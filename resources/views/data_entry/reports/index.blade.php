@extends('data_entry.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('التقارير')}}</h2>
            <p class="mt-1 text-sm text-gray-600">{{__('اختر نوع التقرير المطلوب:')}}</p>
        </header>

        <div class="container mt-6">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- Report Options -->
                    <div class="row g-4">
                        <!-- Course Reports -->
                        <div class="col-md-6">
                            <div class="card h-100 border-primary">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-book fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title text-primary">تقارير الكورس</h4>
                                    <p class="card-text text-muted">
                                        عرض تقارير الكورسات والطلاب المسجلين فيها
                                    </p>
                                    <div class="mt-4">
                                        <a href="{{ route('data_entry.reports.course') }}" 
                                           class="btn btn-primary btn-lg w-100">
                                            <i class="fas fa-book me-2"></i>
                                            تقارير الكورس
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Diploma Reports -->
                        <div class="col-md-6">
                            <div class="card h-100 border-success">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-graduation-cap fa-3x text-success"></i>
                                    </div>
                                    <h4 class="card-title text-success">تقارير الدبلوم</h4>
                                    <p class="card-text text-muted">
                                        عرض تقارير الدبلومات والطلاب المسجلين فيها
                                    </p>
                                    <div class="mt-4">
                                        <a href="{{ route('data_entry.reports.diploma') }}" 
                                           class="btn btn-success btn-lg w-100">
                                            <i class="fas fa-graduation-cap me-2"></i>
                                            تقارير الدبلوم
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
        
        .fa-3x {
            font-size: 3rem;
        }
        
        .border-primary {
            border-color: #007bff !important;
        }
        
        .border-success {
            border-color: #28a745 !important;
        }
    </style>
@endsection

