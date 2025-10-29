@extends('admin.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">إضافة كوبونات جديدة</h3>
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right"></i> العودة للقائمة
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.coupons.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <!-- نوع الكوبون -->
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">نوع الكوبون <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                    <option value="">اختر نوع الكوبون</option>
                                    <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>مبلغ ثابت (دينار)</option>
                                    <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>نسبة مئوية (%)</option>
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
                                           value="{{ old('value') }}" 
                                           step="0.01" 
                                           min="0" 
                                           required>
                                    <span class="input-group-text" id="value-unit">دينار</span>
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
                                    <option value="courses" {{ old('applicable_to') == 'courses' ? 'selected' : '' }}>الكورسات فقط</option>
                                    <option value="diplomas" {{ old('applicable_to') == 'diplomas' ? 'selected' : '' }}>الدبلومات فقط</option>
                                    <option value="both" {{ old('applicable_to') == 'both' ? 'selected' : '' }}>الكورسات والدبلومات</option>
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
                                        <option value="{{ $course->id }}" {{ old('specific_course_id') == $course->id ? 'selected' : '' }}>
                                            {{ $course->name }} - {{ $course->price }} دينار
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
                                        <option value="{{ $diploma->id }}" {{ old('specific_diploma_id') == $diploma->id ? 'selected' : '' }}>
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

                            <!-- عدد الكوبونات -->
                            <div class="col-md-6 mb-3">
                                <label for="quantity" class="form-label">عدد الكوبونات <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('quantity') is-invalid @enderror" 
                                       id="quantity" 
                                       name="quantity" 
                                       value="{{ old('quantity', 10) }}" 
                                       min="1" 
                                       max="100" 
                                       required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">يمكن إنشاء من 1 إلى 100 كوبون في المرة الواحدة</div>
                            </div>

                            <!-- حد الاستخدام -->
                            <div class="col-md-6 mb-3">
                                <label for="usage_limit" class="form-label">حد الاستخدام</label>
                                <input type="number" 
                                       class="form-control @error('usage_limit') is-invalid @enderror" 
                                       id="usage_limit" 
                                       name="usage_limit" 
                                       value="{{ old('usage_limit') }}" 
                                       min="1">
                                @error('usage_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">اتركه فارغاً للاستخدام غير المحدود</div>
                            </div>

                            <!-- تاريخ البداية -->
                            <div class="col-md-6 mb-3">
                                <label for="valid_from" class="form-label">صالح من تاريخ</label>
                                <input type="date" 
                                       class="form-control @error('valid_from') is-invalid @enderror" 
                                       id="valid_from" 
                                       name="valid_from" 
                                       value="{{ old('valid_from') }}">
                                @error('valid_from')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- تاريخ النهاية -->
                            <div class="col-md-6 mb-3">
                                <label for="valid_until" class="form-label">صالح حتى تاريخ</label>
                                <input type="date" 
                                       class="form-control @error('valid_until') is-invalid @enderror" 
                                       id="valid_until" 
                                       name="valid_until" 
                                       value="{{ old('valid_until') }}">
                                @error('valid_until')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- الوصف -->
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">الوصف</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="3" 
                                          maxlength="500">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus"></i> إنشاء الكوبونات
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
