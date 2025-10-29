@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">تعديل الكوبون</h3>
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right"></i> العودة للقائمة
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="row">
                            <!-- نوع الكوبون -->
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">نوع الكوبون <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                    <option value="">اختر النوع</option>
                                    <option value="fixed" {{ old('type', $coupon->type) == 'fixed' ? 'selected' : '' }}>مبلغ ثابت</option>
                                    <option value="percentage" {{ old('type', $coupon->type) == 'percentage' ? 'selected' : '' }}>نسبة مئوية</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- قيمة الكوبون -->
                            <div class="col-md-6 mb-3">
                                <label for="value" class="form-label">قيمة الكوبون <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control @error('value') is-invalid @enderror" 
                                           id="value" 
                                           name="value" 
                                           value="{{ old('value', $coupon->value) }}" 
                                           step="0.01" 
                                           min="0" 
                                           required>
                                    <span class="input-group-text" id="value-unit">
                                        @if($coupon->type == 'percentage')
                                            %
                                        @else
                                            دينار
                                        @endif
                                    </span>
                                </div>
                                @error('value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ينطبق على -->
                            <div class="col-md-6 mb-3">
                                <label for="applicable_to" class="form-label">ينطبق على <span class="text-danger">*</span></label>
                                <select class="form-select @error('applicable_to') is-invalid @enderror" id="applicable_to" name="applicable_to" required>
                                    <option value="">اختر النوع</option>
                                    <option value="courses" {{ old('applicable_to', $coupon->applicable_to) == 'courses' ? 'selected' : '' }}>الكورسات فقط</option>
                                    <option value="diplomas" {{ old('applicable_to', $coupon->applicable_to) == 'diplomas' ? 'selected' : '' }}>الدبلومات فقط</option>
                                    <option value="both" {{ old('applicable_to', $coupon->applicable_to) == 'both' ? 'selected' : '' }}>الكورسات والدبلومات</option>
                                </select>
                                @error('applicable_to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- الكورس المحدد -->
                            <div class="col-md-6 mb-3" id="specific_course_field" style="display: none;">
                                <label for="specific_course_id" class="form-label">الكورس المحدد</label>
                                <select class="form-select @error('specific_course_id') is-invalid @enderror" id="specific_course_id" name="specific_course_id">
                                    <option value="">اختر الكورس (اختياري)</option>
                                    @forelse($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('specific_course_id', $coupon->specific_course_id) == $course->id ? 'selected' : '' }}>
                                            {{ $course->name }} - {{ $course->price }} دينار
                                            @if($course->end_date <= now())
                                                (منتهي الصلاحية)
                                            @endif
                                        </option>
                                    @empty
                                        <option value="" disabled>لا توجد كورسات نشطة</option>
                                    @endforelse
                                </select>
                                @error('specific_course_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">إذا تم تحديد كورس، سيصبح الكوبون مخصص لهذا الكورس فقط (يتم عرض الكورسات النشطة فقط)</div>
                            </div>

                            <!-- الدبلومة المحددة -->
                            <div class="col-md-6 mb-3" id="specific_diploma_field" style="display: none;">
                                <label for="specific_diploma_id" class="form-label">الدبلومة المحددة</label>
                                <select class="form-select @error('specific_diploma_id') is-invalid @enderror" id="specific_diploma_id" name="specific_diploma_id">
                                    <option value="">اختر الدبلومة (اختياري)</option>
                                    @forelse($diplomas as $diploma)
                                        <option value="{{ $diploma->id }}" {{ old('specific_diploma_id', $coupon->specific_diploma_id) == $diploma->id ? 'selected' : '' }}>
                                            {{ $diploma->name }} - {{ $diploma->price }} دينار
                                        </option>
                                    @empty
                                        <option value="" disabled>لا توجد دبلومات</option>
                                    @endforelse
                                </select>
                                @error('specific_diploma_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">إذا تم تحديد دبلومة، سيصبح الكوبون مخصص لهذه الدبلومة فقط</div>
                            </div>

                            <!-- حد الاستخدام -->
                            <div class="col-md-6 mb-3">
                                <label for="usage_limit" class="form-label">حد الاستخدام</label>
                                <input type="number" 
                                       class="form-control @error('usage_limit') is-invalid @enderror" 
                                       id="usage_limit" 
                                       name="usage_limit" 
                                       value="{{ old('usage_limit', $coupon->usage_limit) }}" 
                                       min="1">
                                @error('usage_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">اتركه فارغاً للاستخدام غير المحدود</div>
                            </div>

                            <!-- صالح من -->
                            <div class="col-md-6 mb-3">
                                <label for="valid_from" class="form-label">صالح من</label>
                                <input type="date" 
                                       class="form-control @error('valid_from') is-invalid @enderror" 
                                       id="valid_from" 
                                       name="valid_from" 
                                       value="{{ old('valid_from', $coupon->valid_from?->format('Y-m-d')) }}">
                                @error('valid_from')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- صالح حتى -->
                            <div class="col-md-6 mb-3">
                                <label for="valid_until" class="form-label">صالح حتى</label>
                                <input type="date" 
                                       class="form-control @error('valid_until') is-invalid @enderror" 
                                       id="valid_until" 
                                       name="valid_until" 
                                       value="{{ old('valid_until', $coupon->valid_until?->format('Y-m-d')) }}">
                                @error('valid_until')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- الحالة -->
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        الكوبون نشط
                                    </label>
                                </div>
                            </div>

                            <!-- الوصف -->
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">الوصف</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="3" 
                                          placeholder="وصف اختياري للكوبون">{{ old('description', $coupon->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const valueUnit = document.getElementById('value-unit');
    const applicableToSelect = document.getElementById('applicable_to');
    const specificCourseField = document.getElementById('specific_course_field');
    const specificDiplomaField = document.getElementById('specific_diploma_field');
    
    typeSelect.addEventListener('change', function() {
        if (this.value === 'percentage') {
            valueUnit.textContent = '%';
        } else {
            valueUnit.textContent = 'دينار';
        }
    });
    
    function toggleSpecificFields() {
        // Show/hide course field
        if (applicableToSelect.value === 'courses' || applicableToSelect.value === 'both') {
            specificCourseField.style.display = 'block';
        } else {
            specificCourseField.style.display = 'none';
            document.getElementById('specific_course_id').value = '';
        }
        
        // Show/hide diploma field
        if (applicableToSelect.value === 'diplomas' || applicableToSelect.value === 'both') {
            specificDiplomaField.style.display = 'block';
        } else {
            specificDiplomaField.style.display = 'none';
            document.getElementById('specific_diploma_id').value = '';
        }
    }
    
    // Show/hide fields based on initial value
    toggleSpecificFields();
    
    // Show/hide fields when selection changes
    applicableToSelect.addEventListener('change', toggleSpecificFields);
});
</script>

@endsection
