@extends('data_entry.main') {{-- Correct layout based on previous request --}}

@section('content')
    {{-- External CSS Dependencies --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    {{-- Font Awesome for Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    {{-- Custom Styles --}}
    <style>
        #customer_fields,
        #student_fields {
            transition: opacity 0.3s ease-in-out, max-height 0.3s ease-in-out;
            max-height: 500px; /* Adjust as needed */
            overflow: hidden;
            opacity: 1;
        }
        #customer_fields.hidden,
        #student_fields.hidden {
             max-height: 0;
             opacity: 0;
             margin-top: 0; /* Prevent margin when hidden */
             margin-bottom: 0; /* Prevent margin when hidden */
             padding-top: 0; /* Prevent padding when hidden */
             padding-bottom: 0; /* Prevent padding when hidden */
             border: none; /* Prevent border when hidden */
        }
        /* Ensure Select2 container is hidden correctly */
        #student_fields.hidden .select2-bootstrap-5-theme {
            display: none;
        }
         #customer_fields.hidden > div {
             margin-bottom: 0 !important; /* Remove margin from inner divs when hidden */
         }


        .form-check-input:checked + .form-check-label {
            color: #0d6efd;
            font-weight: 600;
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        /* Smooth transitions for form elements */
        .form-control, .form-select {
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

         /* Style for required fields */
        .form-label .text-danger {
            font-size: 0.9em;
            margin-inline-start: 2px;
        }

         /* Style price display */
        #total_price_display {
            font-size: 1.1rem;
            font-weight: bold;
        }
         #total_price_display .fas {
             font-size: 1rem;
         }
    </style>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" dir="rtl" style="text-align: right;" role="main">
        <header class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2" id="page-title">حجز مساحة عمل</h1>
        </header>

        {{-- Display validation errors --}}
        @if ($errors->any())
            <div class="alert alert-danger" role="alert" aria-live="polite">
                <h4 class="alert-heading">يرجى تصحيح الأخطاء التالية:</h4>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Success message --}}
        @if (session('success'))
            <div class="alert alert-success" role="alert" aria-live="polite">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif

        {{-- Booking Form --}}
        <form action="{{ route('data_entry.bookingstore') }}" method="POST" id="bookingForm" novalidate>
            @csrf
            <div class="row g-3">

                 {{-- Checkbox --}}
                <div class="col-12">
                    <div class="form-check mb-3">
                        <input class="form-check-input"
                               type="checkbox"
                               value="1"
                               id="is_student_pricing"
                               name="is_student_pricing"
                               {{ old('is_student_pricing') ? 'checked' : '' }}
                               aria-describedby="student_pricing_help">
                        <label class="form-check-label" for="is_student_pricing">
                            طالب في الأكاديمية؟ (تطبيق سعر الطالب)
                        </label>
                        <div id="student_pricing_help" class="form-text">عند تحديد هذا الخيار، يمكنك اختيار الطالب من القائمة وسيتم تطبيق سعر خاص إن وجد.</div>
                    </div>
                </div>

                {{-- Customer / Student Combined Section --}}
                <div class="col-md-6">
                     {{-- Student Selection (Hidden initially) --}}
                    <div id="student_fields" class="{{ old('is_student_pricing') ? '' : 'hidden' }}">
                        <label for="student_id" class="form-label">اختر الطالب <span class="text-danger">*</span></label>
                        <div class="select2-bootstrap-5-theme" id="student_select_container">
                            <select class="form-select @error('student_id') is-invalid @enderror"
                                    id="student_id"
                                    name="student_id"
                                    style="width: 100%;"
                                    aria-describedby="student_id_help">
                                <option value="">-- ابحث واختر الطالب --</option>
                                @foreach($students ?? [] as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->user->name ?? 'Student ID: ' . $student->id }} ({{ $student->user->username ?? '' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                         <div id="student_id_help" class="form-text">ابحث عن الطالب واختره من القائمة</div>
                        @error('student_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    {{-- Customer Fields (Visible initially) --}}
                    <div id="customer_fields" class="{{ old('is_student_pricing') ? 'hidden' : '' }}">
                        <div class="mb-3">
                            <label for="customer_name" class="form-label">اسم العميل <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('customer_name') is-invalid @enderror"
                                   id="customer_name"
                                   name="customer_name"
                                   placeholder="أدخل اسم العميل الكامل"
                                   value="{{ old('customer_name') }}"
                                   aria-describedby="customer_name_help"
                                   autocomplete="name">
                            <div id="customer_name_help" class="form-text">مطلوب إذا لم يكن الحجز لطالب</div>
                            @error('customer_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label for="customer_email" class="form-label">البريد الإلكتروني للعميل <span class="text-danger">*</span></label>
                            <input type="email"
                                   class="form-control @error('customer_email') is-invalid @enderror"
                                   id="customer_email"
                                   name="customer_email"
                                   placeholder="example@domain.com"
                                   value="{{ old('customer_email') }}"
                                   aria-describedby="customer_email_help"
                                   autocomplete="email">
                             <div id="customer_email_help" class="form-text">مطلوب إذا لم يكن الحجز لطالب</div>
                            @error('customer_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                {{-- Coworking Space Selection --}}
                <div class="col-md-6">
                    <label for="coworking_space_id" class="form-label">اختر مساحة العمل <span class="text-danger">*</span></label>
                    <select class="form-select @error('coworking_space_id') is-invalid @enderror"
                            id="coworking_space_id"
                            name="coworking_space_id"
                            required
                            aria-describedby="space_help">
                        {{-- Make sure data attributes use underscores consistently --}}
                        <option value="" selected disabled data-daily="0" data-weekly="0" data-monthly="0" data-three_month="0" data-student_daily="0" data-student_weekly="0" data-student_monthly="0" data-student_three_month="0">
                            -- اختر المساحة --
                        </option>
                        @foreach($spaces ?? [] as $space)
                            <option value="{{ $space->id }}"
                                    data-daily="{{ $space->daily_price ?? 0 }}"
                                    data-weekly="{{ $space->weekly_price ?? 0 }}"
                                    data-monthly="{{ $space->monthly_price ?? 0 }}"
                                    data-three_month="{{ $space->three_month_price ?? 0 }}"
                                    data-student_daily="{{ $space->student_daily_price ?? ($space->daily_price ?? 0) }}"
                                    data-student_weekly="{{ $space->student_weekly_price ?? ($space->weekly_price ?? 0) }}"
                                    data-student_monthly="{{ $space->student_monthly_price ?? ($space->monthly_price ?? 0) }}"
                                    data-student_three_month="{{ $space->student_three_month_price ?? ($space->three_month_price ?? 0) }}"
                                    {{ old('coworking_space_id') == $space->id ? 'selected' : '' }}>
                                {{ $space->name }}{{ $space->location ? ' - ' . $space->location : '' }}
                            </option>
                        @endforeach
                    </select>
                    <div id="space_help" class="form-text">اختر مساحة العمل المناسبة</div>
                    @error('coworking_space_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                 {{-- Booking Type --}}
                <div class="col-md-6"> {{-- Changed width --}}
                    <fieldset>
                        <legend class="form-label pt-0">نوع الحجز <span class="text-danger">*</span></legend> {{-- Removed col-form-label --}}
                        <div class="d-flex flex-wrap" role="radiogroup" aria-labelledby="booking_type_legend">
                            <div class="form-check me-3 mb-2">
                                <input class="form-check-input" type="radio" name="booking_type" id="type_daily" value="daily" {{ old('booking_type', 'daily') == 'daily' ? 'checked' : '' }} required aria-describedby="booking_type_help">
                                <label class="form-check-label" for="type_daily">يومي</label>
                            </div>
                            <div class="form-check me-3 mb-2">
                                <input class="form-check-input" type="radio" name="booking_type" id="type_weekly" value="weekly" {{ old('booking_type') == 'weekly' ? 'checked' : '' }} required aria-describedby="booking_type_help">
                                <label class="form-check-label" for="type_weekly">أسبوعي</label>
                            </div>
                            <div class="form-check me-3 mb-2">
                                <input class="form-check-input" type="radio" name="booking_type" id="type_monthly" value="monthly" {{ old('booking_type') == 'monthly' ? 'checked' : '' }} required aria-describedby="booking_type_help">
                                <label class="form-check-label" for="type_monthly">شهري</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="booking_type" id="type_three_month" value="three_month" {{ old('booking_type') == 'three_month' ? 'checked' : '' }} required aria-describedby="booking_type_help">
                                <label class="form-check-label" for="type_three_month">3 أشهر</label>
                            </div>
                        </div>
                         <div id="booking_type_help" class="form-text">اختر مدة الحجز المطلوبة</div>
                    </fieldset>
                    @error('booking_type') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                {{-- Start Date --}}
                <div class="col-md-3"> {{-- Changed width --}}
                    <label for="start_date" class="form-label">تاريخ البدء <span class="text-danger">*</span></label>
                    <input type="date"
                           class="form-control @error('start_date') is-invalid @enderror"
                           id="start_date"
                           name="start_date"
                           value="{{ old('start_date', now()->format('Y-m-d')) }}"
                           required
                           min="{{ now()->format('Y-m-d') }}"
                           aria-describedby="start_date_help">
                    <div id="start_date_help" class="form-text">تاريخ بداية الحجز</div>
                    @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- End Date --}}
                <div class="col-md-3"> {{-- Changed width --}}
                    <label for="end_date" class="form-label">تاريخ الانتهاء <span class="text-danger">*</span></label>
                    <input type="date"
                           class="form-control @error('end_date') is-invalid @enderror"
                           id="end_date"
                           name="end_date"
                           value="{{ old('end_date') }}"
                           readonly
                           required
                           aria-describedby="end_date_help">
                    <div id="end_date_help" class="form-text">يتم حسابها تلقائياً</div>
                    @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>


                {{-- Total Price --}}
                <div class="col-12">
                    <label class="form-label">السعر الإجمالي:</label>
                    <div id="total_price_display"
                         class="alert alert-info d-flex align-items-center @error('total_price') alert-danger @enderror"
                         role="alert"
                         aria-live="polite">
                        <i class="fas fa-calculator me-2"></i>
                        <span id="price_amount">0.00 د.ل</span>
                    </div>
                    <input type="hidden" name="total_price" id="total_price" value="{{ old('total_price', '0.00') }}">
                    {{-- Specific error messages --}}
                    @error('capacity_exceeded') <div class="alert alert-danger small p-2 mt-1">{{ $message }}</div> @enderror
                    @error('db_error') <div class="alert alert-danger small p-2 mt-1">{{ $message }}</div> @enderror
                    {{-- Generic price error --}}
                     @error('total_price') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
                </div>

                {{-- Submit Button --}}
                <div class="col-12 mt-4">
                    <button type="submit"
                            class="btn btn-primary btn-lg"
                            id="submit_btn"
                            aria-describedby="submit_help">
                        <i class="fas fa-save me-2"></i>حفظ الحجز
                    </button>
                    <a href="{{ route('data_entry.booking') }}"
                       class="btn btn-secondary btn-lg ms-2"
                       role="button"
                       aria-label="إلغاء والعودة للقائمة">
                        <i class="fas fa-times me-2"></i>إلغاء
                    </a>
                    <div id="submit_help" class="form-text mt-2">تأكد من صحة جميع البيانات قبل الحفظ</div>
                </div>
            </div>
        </form>
    </main>

    {{-- External JavaScript Dependencies --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>

    <script>
        /**
         * Coworking Booking Form Handler (Vanilla JS with Select2 Integration)
         * Handles form interactions, validation hints, and price calculations
         */
        (function() {
            'use strict';

            // DOM Elements Cache
            const elements = {
                customerNameInput: document.getElementById('customer_name'),
                customerEmailInput: document.getElementById('customer_email'), // Added Email
                customerFieldsDiv: document.getElementById('customer_fields'),
                studentSelect: document.getElementById('student_id'),
                studentFieldsDiv: document.getElementById('student_fields'),
                isStudentCheckbox: document.getElementById('is_student_pricing'),
                spaceSelect: document.getElementById('coworking_space_id'),
                bookingTypeRadios: document.querySelectorAll('input[name="booking_type"]'),
                startDateInput: document.getElementById('start_date'),
                endDateInput: document.getElementById('end_date'),
                priceDisplay: document.getElementById('price_amount'), // Target the span inside
                priceInput: document.getElementById('total_price'),
                submitBtn: document.getElementById('submit_btn'),
                form: document.getElementById('bookingForm')
            };

            // Configuration
            const config = {
                currency: 'د.ل',
                select2Config: {
                    theme: 'bootstrap-5',
                    placeholder: '-- ابحث واختر الطالب --',
                    allowClear: true,
                    dir: 'rtl',
                    language: {
                        noResults: () => "لا توجد نتائج",
                        searching: () => "جاري البحث..."
                    }
                }
            };

            // Utility Functions
            const utils = {
                formatPrice: (price) => `${parseFloat(price).toFixed(2)} ${config.currency}`,
                formatDate: (date) => {
                    if (!date || isNaN(date.getTime())) return ''; // Handle invalid date
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                },
                isValidDate: (date) => date instanceof Date && !isNaN(date.getTime()),
                // Debounce function to limit rapid calculations
                debounce: (func, wait) => {
                    let timeout;
                    return function executedFunction(...args) {
                        const later = () => {
                            clearTimeout(timeout);
                            func.apply(this, args); // Use apply to maintain context
                        };
                        clearTimeout(timeout);
                        timeout = setTimeout(later, wait);
                    };
                }
            };

            // Booking Calculation Logic
            const bookingCalculator = {
                calculateEndDate: (startDateStr, bookingType) => {
                    if (!startDateStr) return null;
                     const startDate = new Date(startDateStr + 'T00:00:00');
                     if (!utils.isValidDate(startDate)) return null;

                    const endDate = new Date(startDate);

                    try {
                        switch (bookingType) {
                            case 'daily': break;
                            case 'weekly': endDate.setDate(startDate.getDate() + 6); break;
                            case 'monthly': endDate.setMonth(startDate.getMonth() + 1); endDate.setDate(startDate.getDate() - 1); break;
                            case 'three_month': endDate.setMonth(startDate.getMonth() + 3); endDate.setDate(startDate.getDate() - 1); break;
                        }
                         return utils.isValidDate(endDate) ? endDate : null;
                    } catch (e) {
                         console.error("Error calculating end date:", e);
                         return null;
                    }
                },

                // *** REFINED calculatePrice function ***
                calculatePrice: (spaceOption, bookingType, isStudent) => {
                    if (!spaceOption || !bookingType || !spaceOption.value) return 0; // Guard clause

                    // Helper to get dataset value using direct attribute access for reliability with underscores
                    const getPriceFromAttribute = (key) => {
                        const value = spaceOption.getAttribute(`data-${key}`); // Use underscores directly as they are in HTML
                        console.log(`Checking attribute: data-${key}, Value: ${value}`); // Debugging
                        const price = parseFloat(value);
                        return isNaN(price) ? 0 : price; // Return 0 if not a valid number
                    };

                    let price = 0;
                    let regularPriceKey = '';
                    let studentPriceKey = '';

                    // Define keys based on booking type (using underscores to match HTML)
                    switch (bookingType) {
                        case 'daily':
                            regularPriceKey = 'daily';
                            studentPriceKey = 'student_daily';
                            break;
                        case 'weekly':
                            regularPriceKey = 'weekly';
                            studentPriceKey = 'student_weekly';
                            break;
                        case 'monthly':
                            regularPriceKey = 'monthly';
                            studentPriceKey = 'student_monthly';
                            break;
                        case 'three_month':
                            regularPriceKey = 'three_month';
                            studentPriceKey = 'student_three_month';
                            break;
                        default:
                            return 0; // Invalid type
                    }

                    const regularPrice = getPriceFromAttribute(regularPriceKey);
                    const studentPrice = getPriceFromAttribute(studentPriceKey);

                    if (isStudent) {
                        // Use student price if it's available and greater than 0, otherwise fallback to regular price
                        price = (studentPrice > 0) ? studentPrice : regularPrice;
                    } else {
                        price = regularPrice;
                    }

                    // console.log(`Booking Type: ${bookingType}, Is Student: ${isStudent}, Regular Price: ${regularPrice}, Student Price: ${studentPrice}, Final Price: ${price}`); // Debugging
                    return price;
                },

                 // Main update function, called by event listeners
                 updateBookingDetails: () => {
                    const selectedOption = elements.spaceSelect.selectedOptions[0];
                    const selectedType = document.querySelector('input[name="booking_type"]:checked')?.value;
                    const startDateValue = elements.startDateInput.value;
                    const applyStudentPrice = elements.isStudentCheckbox.checked;

                    console.log('updateBookingDetails called with:', {
                        selectedOption: selectedOption?.value,
                        selectedType,
                        startDateValue,
                        applyStudentPrice
                    });

                     if (!selectedOption || !selectedOption.value || !selectedType || !startDateValue) {
                         console.log('Missing required data, resetting display');
                         bookingCalculator.resetDisplay();
                         return;
                     }

                    try {
                        const price = bookingCalculator.calculatePrice(selectedOption, selectedType, applyStudentPrice);
                        const endDate = bookingCalculator.calculateEndDate(startDateValue, selectedType);

                        console.log('Calculated price:', price, 'End date:', endDate);

                        elements.endDateInput.value = utils.formatDate(endDate);
                        elements.priceDisplay.textContent = utils.formatPrice(price);
                        elements.priceInput.value = price.toFixed(2);
                    } catch (error) {
                         console.error('Booking calculation error:', error);
                         bookingCalculator.showError();
                    }
                },


                resetDisplay: () => {
                    elements.priceDisplay.textContent = utils.formatPrice(0);
                    elements.priceInput.value = '0.00';
                    elements.endDateInput.value = '';
                },

                showError: () => {
                    elements.priceDisplay.textContent = 'خطأ';
                    elements.priceInput.value = '0.00';
                    elements.endDateInput.value = '';
                }
            };

            // Form Toggling Logic
            const formToggler = {
                toggleCustomerTypeFields: () => {
                    const isStudent = elements.isStudentCheckbox.checked;
                    console.log('Toggle customer type fields - isStudent:', isStudent);

                    if (isStudent) {
                        elements.customerFieldsDiv.classList.add('hidden');
                        elements.customerNameInput.required = false;
                        elements.customerEmailInput.required = false;

                        elements.studentFieldsDiv.classList.remove('hidden');
                        elements.studentSelect.required = true;

                        elements.customerNameInput.value = '';
                        elements.customerEmailInput.value = '';
                        elements.customerNameInput.classList.remove('is-invalid');
                        elements.customerEmailInput.classList.remove('is-invalid');

                    } else {
                        elements.studentFieldsDiv.classList.add('hidden');
                        elements.studentSelect.required = false;

                        elements.customerFieldsDiv.classList.remove('hidden');
                        elements.customerNameInput.required = true;
                        elements.customerEmailInput.required = true;

                        elements.studentSelect.value = '';
                        if (typeof $ !== 'undefined' && $(elements.studentSelect).hasClass('select2-hidden-accessible')) {
                            $(elements.studentSelect).val(null).trigger('change');
                        }
                         elements.studentSelect.classList.remove('is-invalid');
                    }
                    
                    console.log('Calling updateBookingDetails after toggle');
                    bookingCalculator.updateBookingDetails(); // Recalculate price
                }
            };

            // Form Validation (Simple client-side hints)
             const formValidator = {
                 validateOnSubmit: (e) => {
                     let isValid = true;
                     const requiredFields = elements.form.querySelectorAll('[required]');

                     requiredFields.forEach(field => {
                         const fieldContainer = field.closest('#student_fields, #customer_fields') || field.closest('div, fieldset');
                         const isHidden = fieldContainer && fieldContainer.classList.contains('hidden');

                         if (!isHidden && field.hasAttribute('required')) {
                              // Special check for Select2
                              if (field.id === 'student_id' && $(field).val() === '') {
                                   isValid = false;
                                   // Style Select2 container - find the span sibling and add is-invalid
                                   $(field).siblings('span.select2-container').find('.select2-selection').addClass('is-invalid-select'); // Add custom class
                              }
                              // Regular check for other fields
                              else if (!field.value || (field.type === 'checkbox' && !field.checked) || (field.type === 'radio' && !document.querySelector(`input[name="${field.name}"]:checked`))) {
                                 field.classList.add('is-invalid');
                                 const feedback = field.closest('div').querySelector('.invalid-feedback');
                                 if (feedback) feedback.style.display = 'block';
                                 isValid = false;
                             } else {
                                 field.classList.remove('is-invalid');
                                 // Reset Select2 style
                                 if (field.id === 'student_id') {
                                      $(field).siblings('span.select2-container').find('.select2-selection').removeClass('is-invalid-select');
                                 }
                                  const feedback = field.closest('div').querySelector('.invalid-feedback');
                                 if (feedback) feedback.style.display = '';
                             }
                         } else {
                              field.classList.remove('is-invalid');
                               if (field.id === 'student_id') {
                                    $(field).siblings('span.select2-container').find('.select2-selection').removeClass('is-invalid-select');
                               }
                              const feedback = field.closest('div').querySelector('.invalid-feedback');
                              if (feedback) feedback.style.display = '';
                         }
                     });
                      // Custom CSS for invalid Select2
                      if (!document.getElementById('select2-invalid-style')) {
                          const style = document.createElement('style');
                          style.id = 'select2-invalid-style';
                          style.innerHTML = `.is-invalid-select { border-color: #dc3545 !important; }`;
                          document.head.appendChild(style);
                      }


                     if (!isValid) {
                         e.preventDefault(); // Stop submission
                         console.log("Form validation failed.");
                         // Find first invalid field and focus it
                         const firstInvalid = elements.form.querySelector('.is-invalid, .is-invalid-select');
                         if(firstInvalid) {
                              if(firstInvalid.classList.contains('select2-selection')) {
                                   $(elements.studentSelect).select2('open'); // Open select2 if invalid
                              } else {
                                   firstInvalid.focus();
                              }
                         }
                     } else {
                         elements.submitBtn.disabled = true;
                         elements.submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الحفظ...';
                     }
                 }
             };


            // Initialization Function
            const init = () => {
                if (typeof $ !== 'undefined' && typeof $.fn.select2 !== 'undefined') {
                    $(elements.studentSelect).select2(config.select2Config);
                } else {
                     console.warn("jQuery or Select2 not ready, retrying initialization...");
                     setTimeout(init, 200);
                     return;
                }

                const debouncedCalculation = utils.debounce(bookingCalculator.updateBookingDetails, 250);

                elements.isStudentCheckbox?.addEventListener('change', formToggler.toggleCustomerTypeFields);
                elements.spaceSelect?.addEventListener('change', debouncedCalculation);
                elements.startDateInput?.addEventListener('change', debouncedCalculation);
                elements.bookingTypeRadios.forEach(radio => {
                    radio.addEventListener('change', debouncedCalculation);
                });

                 elements.form?.addEventListener('submit', formValidator.validateOnSubmit);

                formToggler.toggleCustomerTypeFields();
                 console.log("Booking form initialized.");
            };

            // --- Start the application ---
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }

        })();
    </script>
@endsection

