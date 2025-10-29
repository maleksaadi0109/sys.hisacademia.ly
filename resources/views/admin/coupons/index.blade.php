@extends('admin.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">إدارة الكوبونات</h3>
                    <div>
                        <button id="printSelectedBtn" class="btn btn-success me-2" disabled>
                            <i class="fas fa-print"></i> طباعة المحدد
                        </button>
                        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> إضافة كوبونات جديدة
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                    </th>
                                    <th>#</th>
                                    <th>كود الكوبون</th>
                                    <th>النوع</th>
                                    <th>القيمة</th>
                                    <th>ينطبق على</th>
                                    <th>الكورس المحدد</th>
                        <th>الدبلومة المحددة</th>
                                    <th>حد الاستخدام</th>
                                    <th>المستخدم</th>
                                    <th>الحالة</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($coupons as $coupon)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="form-check-input coupon-checkbox" value="{{ $coupon->id }}">
                                        </td>
                                        <td>{{ $coupon->id }}</td>
                                        <td>
                                            <code class="bg-light p-1 rounded">{{ $coupon->code }}</code>
                                        </td>
                                        <td>
                                            @if($coupon->type === 'fixed')
                                                <span class="badge bg-info">مبلغ ثابت</span>
                                            @else
                                                <span class="badge bg-warning">نسبة مئوية</span>
                                            @endif
                                        </td>
                                        <td>{{ $coupon->formatted_value }}</td>
                                        <td>
                                            @if($coupon->applicable_to === 'courses')
                                                <span class="badge bg-primary">الكورسات</span>
                                            @elseif($coupon->applicable_to === 'diplomas')
                                                <span class="badge bg-success">الدبلومات</span>
                                            @else
                                                <span class="badge bg-secondary">كليهما</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($coupon->specificCourse)
                                                <span class="badge bg-info">{{ $coupon->specificCourse->name }}</span>
                                            @else
                                                <span class="text-muted">جميع الكورسات</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($coupon->specificDiploma)
                                                <span class="badge bg-warning">{{ $coupon->specificDiploma->name }}</span>
                                            @else
                                                <span class="text-muted">جميع الدبلومات</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($coupon->usage_limit)
                                                {{ $coupon->usage_limit }}
                                            @else
                                                <span class="text-muted">غير محدود</span>
                                            @endif
                                        </td>
                                        <td>{{ $coupon->used_count }}</td>
                                        <td>
                                            @if($coupon->is_active)
                                                <span class="badge bg-success">نشط</span>
                                            @else
                                                <span class="badge bg-danger">غير نشط</span>
                                            @endif
                                        </td>
                                        <td>{{ $coupon->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.coupons.show', $coupon) }}" 
                                                   class="btn btn-sm btn-info" title="عرض">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.coupons.edit', $coupon) }}" 
                                                   class="btn btn-sm btn-warning" title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.coupons.print', $coupon) }}" 
                                                   class="btn btn-sm btn-secondary" title="طباعة" target="_blank">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                                <form action="{{ route('admin.coupons.toggle-status', $coupon) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-{{ $coupon->is_active ? 'warning' : 'success' }}" 
                                                            title="{{ $coupon->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}">
                                                        <i class="fas fa-{{ $coupon->is_active ? 'pause' : 'play' }}"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.coupons.destroy', $coupon) }}" 
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا الكوبون؟')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">لا توجد كوبونات</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $coupons->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const couponCheckboxes = document.querySelectorAll('.coupon-checkbox');
    const printSelectedBtn = document.getElementById('printSelectedBtn');

    // Select all functionality
    selectAllCheckbox.addEventListener('change', function() {
        couponCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updatePrintButton();
    });

    // Individual checkbox functionality
    couponCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updatePrintButton();
            updateSelectAllCheckbox();
        });
    });

    // Print selected coupons
    printSelectedBtn.addEventListener('click', function() {
        const selectedIds = Array.from(couponCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);

        if (selectedIds.length === 0) {
            alert('يرجى تحديد كوبون واحد على الأقل للطباعة');
            return;
        }

        // Create form and submit
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = '{{ route("admin.coupons.print_multiple") }}';

        selectedIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'coupon_ids[]';
            input.value = id;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    });

    function updatePrintButton() {
        const selectedCount = document.querySelectorAll('.coupon-checkbox:checked').length;
        printSelectedBtn.disabled = selectedCount === 0;
        printSelectedBtn.textContent = selectedCount > 0 ? 
            `طباعة المحدد (${selectedCount})` : 'طباعة المحدد';
    }

    function updateSelectAllCheckbox() {
        const checkedCount = document.querySelectorAll('.coupon-checkbox:checked').length;
        const totalCount = couponCheckboxes.length;
        
        if (checkedCount === 0) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = false;
        } else if (checkedCount === totalCount) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = true;
        } else {
            selectAllCheckbox.indeterminate = true;
        }
    }
});
</script>
@endsection
