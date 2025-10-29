@extends('data_entry.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('دفع الرسوم')}}</h2>
            <p class="mt-1 text-sm text-gray-600">{{__('تسجيل دفع الرسوم الدراسية أو الرسوم الإضافية للطالب')}}</p>
        </header>

        <div class="container mt-6">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="fas fa-credit-card me-2"></i>
                                نموذج دفع الرسوم
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('data_entry.process_payment') }}">
                                @csrf
                                
                                <!-- Student Selection -->
                                <div class="mb-4">
                                    <label class="form-label" for="student_id">
                                        <i class="fas fa-user-graduate me-2"></i>الطالب
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('student_id') is-invalid @enderror" 
                                            id="student_id" name="student_id" required>
                                        <option selected disabled>أختر الطالب...</option>
                                        @foreach($students as $student)
                                            @if(old('student_id') == $student->id || (isset($selectedStudent) && $selectedStudent && $selectedStudent->id == $student->id))
                                                <option selected value="{{$student->id}}">{{$student->user->name}}</option>
                                            @else
                                                <option value="{{$student->id}}">{{$student->user->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('student_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Payment Type -->
                                <div class="mb-4">
                                    <label class="form-label" for="payment_type">
                                        <i class="fas fa-tag me-2"></i>نوع الرسوم
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('payment_type') is-invalid @enderror" 
                                            id="payment_type" name="payment_type" required>
                                        <option selected disabled>أختر نوع الرسوم...</option>
                                        <option value="tuition_fees" {{ old('payment_type') == 'tuition_fees' ? 'selected' : '' }}>
                                            الرسوم الدراسية
                                        </option>
                                        <option value="additional_fees" {{ old('payment_type') == 'additional_fees' ? 'selected' : '' }}>
                                            الرسوم الإضافية
                                        </option>
                                        <option value="late_fees" {{ old('payment_type') == 'late_fees' ? 'selected' : '' }}>
                                            رسوم التأخير
                                        </option>
                                    </select>
                                    @error('payment_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Amount -->
                                <div class="mb-4">
                                    <label class="form-label" for="amount">
                                        <i class="fas fa-money-bill-wave me-2"></i>المبلغ
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control @error('amount') is-invalid @enderror" 
                                               id="amount" name="amount" 
                                               value="{{ old('amount') }}" 
                                               min="0.01" step="0.01" 
                                               placeholder="0.00" required>
                                        <span class="input-group-text">د.ل</span>
                                    </div>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Payment Method -->
                                <div class="mb-4">
                                    <label class="form-label" for="payment_method">
                                        <i class="fas fa-credit-card me-2"></i>طريقة الدفع
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('payment_method') is-invalid @enderror" 
                                            id="payment_method" name="payment_method" required>
                                        <option selected disabled>أختر طريقة الدفع...</option>
                                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>
                                            نقدي
                                        </option>
                                        <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>
                                            تحويل بنكي
                                        </option>
                                        <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>
                                            بطاقة ائتمان
                                        </option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Notes -->
                                <div class="mb-4">
                                    <label class="form-label" for="notes">
                                        <i class="fas fa-sticky-note me-2"></i>ملاحظات (اختياري)
                                    </label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" name="notes" rows="3" 
                                              placeholder="أي ملاحظات إضافية حول الدفع...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="{{ route('data_entry.registration.choice', ['student_id' => isset($selectedStudent) ? $selectedStudent->id : '']) }}" 
                                       class="btn btn-outline-secondary me-md-2">
                                        <i class="fas fa-arrow-right me-2"></i>العودة
                                    </a>
                                    <button type="submit" class="btn btn-warning btn-lg">
                                        <i class="fas fa-credit-card me-2"></i>تسجيل الدفع
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Payment History Card -->
                    <div class="card mt-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-history me-2"></i>
                                تاريخ المدفوعات
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center text-muted">
                                <i class="fas fa-receipt fa-3x mb-3"></i>
                                <p>لا توجد مدفوعات مسجلة بعد</p>
                                <small>سيتم عرض تاريخ المدفوعات هنا بعد تسجيل الدفعات</small>
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
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .btn {
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }
        
        .form-label {
            font-weight: 600;
            color: #495057;
        }
        
        .input-group-text {
            background-color: #f8f9fa;
            border-color: #ced4da;
            font-weight: 600;
        }
        
        .fa-3x {
            font-size: 3rem;
            opacity: 0.3;
        }
    </style>
@endsection

