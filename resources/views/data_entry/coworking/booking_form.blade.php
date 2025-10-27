@extends('data_entry.dashboard')

@section('content')
    {{-- Select2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" dir="rtl" style="text-align: right;">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">حجز مساحة عمل</h1>
        </div>

        {{-- Display validation errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Success message --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Booking Form --}}
        <form action="{{route('data_entry.bookingstore')}}" method="POST" id="bookingForm">
            @csrf
            <div class="row g-3">

                {{-- Customer Name / Student Selection --}}
                <div class="col-md-6">
                    <label for="customer_name" class="form-label">اسم العميل</label>

                    {{-- Text Input for regular customer --}}
                    <input type="text"
                           class="form-control @error('customer_name') is-invalid @enderror"
                           id="customer_name"
                           name="customer_name"
                           placeholder="أدخل اسم العميل"
                           value="{{ old('customer_name') }}">
                    @error('customer_name') <div class="invalid-feedback">{{ $message }}</div> @enderror

                    {{-- Searchable Dropdown for students (hidden by default) --}}
                    <div style="display: none;" id="student_select_container">
                        <select class="form-select @error('student_id') is-invalid @enderror"
                                id="student_id"
                                name="student_id"
                                style="width: 100%;">
                            <option value="">-- ابحث واختر الطالب --</option>
                            @foreach($students ?? [] as $student)
                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->user->name ?? 'Student ID: ' . $student->id }}
                                </option>
                            @endforeach
                        </select>
                        @error('student_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    {{-- Checkbox --}}
                    <div class="form-check mt-2 ps-0">
                        <input class="form-check-input ms-0 me-2"
                               type="checkbox"
                               value="1"
                               id="is_student_pricing"
                               name="is_student_pricing"
                            {{ old('is_student_pricing') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_student_pricing" style="font-size: 0.95rem;">
                            طالب في الأكاديمية؟ (تطبيق سعر الطالب)
                        </label>
                    </div>
                </div>

                {{-- Email --}}
                <div class="col-md-6">
                    <label for="email" class="form-label">البريد الإلكتروني (اختياري)</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="أدخل البريد الإلكتروني" value="{{ old('email') }}">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Coworking Space Selection --}}
                <div class="col-md-6">
                    <label for="coworking_space_id" class="form-label">اختر مساحة العمل</label>
                    <select class="form-select @error('coworking_space_id') is-invalid @enderror" id="coworking_space_id" name="coworking_space_id" required>
                        <option value="" selected disabled>-- اختر المساحة --</option>
                        @foreach($spaces ?? [] as $space)
                            <option value="{{ $space->id }}"
                                    data-daily="{{ $space->daily_price ?? 0 }}"
                                    data-weekly="{{ $space->weekly_price ?? 0 }}"
                                    data-monthly="{{ $space->monthly_price ?? 0 }}"
                                    data-three-month="{{ $space->three_month_price ?? 0 }}"
                                    data-student-daily="{{ $space->student_daily_price ?? 0 }}"
                                    data-student-weekly="{{ $space->student_weekly_price ?? 0 }}"
                                    data-student-monthly="{{ $space->student_monthly_price ?? 0 }}"
                                    data-student-three-month="{{ $space->student_three_month_price ?? 0 }}"
                                {{ old('coworking_space_id') == $space->id ? 'selected' : '' }}>
                                {{ $space->name }}{{ $space->location ? ' - ' . $space->location : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('coworking_space_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Booking Type --}}
                <div class="col-md-6">
                    <label class="form-label">نوع الحجز</label>
                    <div class="d-flex flex-wrap">
                        <div class="form-check me-3 mb-2">
                            <input class="form-check-input" type="radio" name="booking_type" id="type_daily" value="daily" {{ old('booking_type', 'daily') == 'daily' ? 'checked' : '' }} required>
                            <label class="form-check-label" for="type_daily">يومي</label>
                        </div>
                        <div class="form-check me-3 mb-2">
                            <input class="form-check-input" type="radio" name="booking_type" id="type_weekly" value="weekly" {{ old('booking_type') == 'weekly' ? 'checked' : '' }} required>
                            <label class="form-check-label" for="type_weekly">أسبوعي</label>
                        </div>
                        <div class="form-check me-3 mb-2">
                            <input class="form-check-input" type="radio" name="booking_type" id="type_monthly" value="monthly" {{ old('booking_type') == 'monthly' ? 'checked' : '' }} required>
                            <label class="form-check-label" for="type_monthly">شهري</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="booking_type" id="type_three_month" value="three_month" {{ old('booking_type') == 'three_month' ? 'checked' : '' }} required>
                            <label class="form-check-label" for="type_three_month">3 أشهر</label>
                        </div>
                    </div>
                    @error('booking_type') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- Start Date --}}
                <div class="col-md-6">
                    <label for="start_date" class="form-label">تاريخ البدء</label>
                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date', now()->format('Y-m-d')) }}" required>
                    @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- End Date --}}
                <div class="col-md-6">
                    <label for="end_date" class="form-label">تاريخ الانتهاء</label>
                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date') }}" readonly required>
                    @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Total Price --}}
                <div class="col-12">
                    <label class="form-label">السعر الإجمالي:</label>
                    <div id="total_price_display" class="alert alert-info" role="alert">
                        0.00 د.ل
                    </div>
                    <input type="hidden" name="total_price" id="total_price" value="{{ old('total_price', '0.00') }}">
                    @error('capacity_exceeded') <div class="alert alert-danger">{{ $message }}</div> @enderror
                    @error('db_error') <div class="alert alert-danger">{{ $message }}</div> @enderror
                </div>

                {{-- Submit Button --}}
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary">حفظ الحجز</button>
                    <a href="/" class="btn btn-secondary">إلغاء</a>
                </div>
            </div>
        </form>
    </main>

    {{-- jQuery & Select2 --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            const customerNameInput = $('#customer_name');
            const studentSelect = $('#student_id');
            const studentSelectContainer = $('#student_select_container');
            const isStudentCheckbox = $('#is_student_pricing');

            // Initialize Select2
            studentSelect.select2({
                theme: 'bootstrap-5',
                placeholder: '-- ابحث واختر الطالب --',
                allowClear: true,
                dir: 'rtl',
                language: {
                    noResults: function() { return "لا توجد نتائج"; },
                    searching: function() { return "جاري البحث..."; }
                }
            });

            // Toggle between text input and dropdown
            function toggleInputType() {
                if (isStudentCheckbox.is(':checked')) {
                    customerNameInput.hide().prop('required', false);
                    studentSelectContainer.show();
                    studentSelect.prop('required', true);
                } else {
                    studentSelectContainer.hide();
                    studentSelect.prop('required', false).val(null).trigger('change');
                    customerNameInput.show().prop('required', true);
                }
                calculateBooking();
            }

            // Price calculation
            const spaceSelect = $('#coworking_space_id');
            const bookingTypeRadios = $('input[name="booking_type"]');
            const startDateInput = $('#start_date');
            const endDateInput = $('#end_date');
            const priceDisplay = $('#total_price_display');
            const priceInput = $('#total_price');

            function calculateBooking() {
                const selectedOption = spaceSelect.find('option:selected');
                const selectedType = $('input[name="booking_type"]:checked').val();
                const startDateValue = startDateInput.val();
                const applyStudentPrice = isStudentCheckbox.is(':checked');

                if (!selectedOption.val() || !selectedType || !startDateValue) {
                    priceDisplay.text('0.00 د.ل');
                    priceInput.val('0.00');
                    endDateInput.val('');
                    return;
                }

                let price = 0;
                let endDate = new Date(startDateValue);
                const prefix = applyStudentPrice ? 'student' : '';

                try {
                    switch (selectedType) {
                        case 'daily':
                            price = parseFloat(selectedOption.data(prefix ? prefix + 'Daily' : 'daily')) || 0;
                            break;
                        case 'weekly':
                            price = parseFloat(selectedOption.data(prefix ? prefix + 'Weekly' : 'weekly')) || 0;
                            endDate.setDate(endDate.getDate() + 6);
                            break;
                        case 'monthly':
                            price = parseFloat(selectedOption.data(prefix ? prefix + 'Monthly' : 'monthly')) || 0;
                            endDate.setMonth(endDate.getMonth() + 1);
                            endDate.setDate(endDate.getDate() - 1);
                            break;
                        case 'three_month':
                            price = parseFloat(selectedOption.data(prefix ? prefix + 'ThreeMonth' : 'threeMonth')) || 0;
                            endDate.setMonth(endDate.getMonth() + 3);
                            endDate.setDate(endDate.getDate() - 1);
                            break;
                    }

                    if (!isNaN(endDate.getTime())) {
                        const year = endDate.getFullYear();
                        const month = String(endDate.getMonth() + 1).padStart(2, '0');
                        const day = String(endDate.getDate()).padStart(2, '0');
                        endDateInput.val(`${year}-${month}-${day}`);
                    } else {
                        endDateInput.val('');
                    }

                    priceDisplay.text(price.toFixed(2) + ' د.ل');
                    priceInput.val(price.toFixed(2));

                } catch (e) {
                    console.error("Error:", e);
                    priceDisplay.text('خطأ في الحساب');
                    priceInput.val('0.00');
                }
            }

            // Event listeners
            isStudentCheckbox.on('change', toggleInputType);
            spaceSelect.on('change', calculateBooking);
            bookingTypeRadios.on('change', calculateBooking);
            startDateInput.on('change', calculateBooking);

            // Initial setup
            toggleInputType();
        });
    </script>
@endsection
