@extends('data_entry.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('دفع المبلغ المتبقي')}}</h2>
            <p class="mt-1 text-sm text-gray-600">{{__('تسجيل دفع المبلغ المتبقي للطالب')}}</p>
        </header>

        <div class="container mt-6">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-money-bill-wave me-2"></i>
                                معلومات الإيصال
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>اسم الطالب:</strong> {{ $revenue->user->name }}
                                </div>
                                <div class="col-md-6">
                                    <strong>اسم الكورس:</strong> {{ $revenue->course ? $revenue->course->name : ($revenue->diploma ? $revenue->diploma->name : 'N/A') }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>المبلغ الإجمالي:</strong> {{ $revenue->value }} {{ $revenue->currency }}
                                </div>
                                <div class="col-md-6">
                                    <strong>المدفوع:</strong> {{ $revenue->value_rec }} {{ $revenue->currency }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>الباقي:</strong> 
                                    <span class="badge bg-warning fs-6">{{ $revenue->value_rem }} {{ $revenue->currency }}</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>رقم الإيصال:</strong> {{ $revenue->id }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="fas fa-credit-card me-2"></i>
                                تسجيل الدفع
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('data_entry.update.remaining.payment', ['id' => $revenue->id]) }}">
                                @csrf
                                @method('PATCH')
                                
                                <div class="mb-4">
                                    <label class="form-label" for="amount">
                                        <i class="fas fa-money-bill-wave me-2"></i>المبلغ المدفوع
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control @error('amount') is-invalid @enderror" 
                                               id="amount" 
                                               name="amount" 
                                               min="0.01" 
                                               max="{{ $revenue->value_rem }}" 
                                               step="0.01" 
                                               value="{{ old('amount', $revenue->value_rem) }}" 
                                               required>
                                        <span class="input-group-text">{{ $revenue->currency }}</span>
                                    </div>
                                    <small class="form-text text-muted">
                                        الحد الأقصى: {{ $revenue->value_rem }} {{ $revenue->currency }}
                                    </small>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="{{ route('data_entry.reports.course') }}" 
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
                </div>
            </div>
        </div>
    </section>
@endsection

