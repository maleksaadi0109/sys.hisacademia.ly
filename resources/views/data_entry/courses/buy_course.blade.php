    @extends('data_entry.dashboard')
    @section('content')
        <section>
            <header>
                <h2 class="text-lg font-medium text-gray-900">{{__('حجز كورس للطالب')}}</h2>
            </header>
            @php
                use App\Enums\WeekDays;
            @endphp
            <div class="container">
                <div class="row">
                    <input type="hidden" id="original_course_price" value="0">
                    <input type="hidden" id="final_course_price" value="0">
                    <input type="hidden" id="applied_coupon_code" name="coupon_code" value="">
                    <input type="hidden" id="applied_coupon_type" name="coupon_type" value="">

                    <form method="POST" enctype="multipart/form-data" action="{{ route('data_entry.enroll.course') }}">
                        @csrf
                        <div class="row">

                            <!-- Student Selection -->
                            <div class="mt-5 col-lg-8">
                                <div class="input-group mb-3">
                                    <label class="input-group-text" for="selectstudentchange">الطالب</label>
                                    <select class="form-select" id="selectstudentchange" name="student_id" required>
                                        <option selected disabled>أختر....</option>
                                        @foreach($students as $value)
                                            @if(old('student_id') == $value->id || (isset($selectedStudent) && $selectedStudent && $selectedStudent->id == $value->id))
                                                <option selected value="{{$value->id}}">{{$value->user->name}}</option>
                                            @else
                                                <option value="{{$value->id}}">{{$value->user->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @php
                                        $messages = $errors->get('student_id');
                                    @endphp
                                    @if ($messages)
                                        <ul class="text-sm text-red-600 space-y-1 mt-2">
                                            @foreach ((array) $messages as $message)
                                                <li>{{ $message }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>

                            <!-- Student's Current Courses Table -->
                            <div class="row table-responsive" id="table_student_time">
                                <h5>الكورسات الحالية للطالب</h5>
                                <table class="student_table_course table table-light table-hover text-center" id="table_student">
                                </table>
                            </div>

                            <!-- Course Selection -->
                            <div class="mt-5 col-lg-12">
                                <div class="input-group mb-3">
                                    <label class="input-group-text" for="selectsoursechange">الكورس</label>
                                    <select class="form-select" id="selectsoursechange" name="course_id" required>
                                        <option selected disabled>أختر....</option>
                                        @foreach($courses as $value)
                                            @if(old('course_id') == $value->id)
                                                <option selected value="{{$value->id}}" data-price="{{$value->price}}">{{$value->name}}</option>
                                            @else
                                                <option value="{{$value->id}}" data-price="{{$value->price}}">{{$value->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @php
                                        $messages = $errors->get('course_id');
                                    @endphp
                                    @if ($messages)
                                        <ul class="text-sm text-red-600 space-y-1 mt-2">
                                            @foreach ((array) $messages as $message)
                                                <li>{{ $message }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>

                            <!-- Course Details Table -->
                            <div class="row table-responsive" id="table_course_time">
                                <h5>معلومات هذا الكورس (السعر: <span id="span_price">0 دينار</span>)</h5>
                                <table class="student_table_course table table-light table-hover text-center" id="table_course">
                                </table>
                            </div>

                            <!-- Coupon Code Input -->
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="coupon_code" :value="__('كود الخصم (اختياري)')" />
                                <div class="input-group">
                                    <x-text-input id="coupon_code" class="form-control" type="text" name="coupon_code" autocomplete="off" />
                                    <button class="btn btn-outline-secondary" type="button" id="apply_coupon_btn">تطبيق</button>
                                </div>
                                <div id="coupon_status" class="mt-2 text-sm"></div>
                            </div>

                            <!-- Value Received Input -->
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="value_rec" :value="__('القيمة المدفوعة')" />
                                <div class="input-group">
                                    <x-text-input id="value_rec" value="{{old('value_rec', '0')}}" class="form-control" min="0" type="number" name="value_rec" required autofocus autocomplete="value_rec" />
                                    <span class="input-group-text" id="total_price_span">/ 0</span>
                                </div>
                                <x-input-error :messages="$errors->get('value_rec')" class="mt-2" />
                                <div id="value_rec_error" class="text-sm text-red-600 space-y-1 mt-2"></div>
                            </div>

                        </div>

                        <div class="row">
                            <x-primary-button class="mt-4 col-lg-2 justify-content-center">
                                {{ __('حجز') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <script>
            $(document).ready(function () {

                $('#table_student_time').hide();
                $('#table_course_time').hide();

                // Select Course Change
                function SelectCourseChange(element) {
                    var course_id = element.val();
                    if (!course_id) return;

                    var course_price = parseFloat(element.find(':selected').attr('data-price'));

                    // Store original and final price
                    $('#original_course_price').val(course_price);
                    $('#final_course_price').val(course_price);

                    // Update price UI
                    updatePriceUI(course_price);

                    // Reset coupon field
                    $('#coupon_code').val('');
                    $('#coupon_status').empty();

                    $.ajax({
                        url: "{{ url('/data_entry/time_by_course/') }}/" + course_id,
                        type: 'GET',
                        dataType: 'JSON',
                        success: function(result){
                            $('#table_course').empty();
                            $('#table_course_time').hide();
                            var table = document.getElementById('table_course');
                            if (result['datacourses'].length != 0){
                                $('#table_course_time').show();
                                createTable(result['datacourses'], table);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error(xhr.responseText);
                            alert('فشل تحميل التوقيت');
                        }
                    });
                }

                $('#selectsoursechange').change(function () {
                    SelectCourseChange($(this));
                });

                // Select Student Change
                function SelectStudentChange(element) {
                    var student_id = element.val();
                    $.ajax({
                        url: "{{ url('/data_entry/time_by_student/') }}/" + student_id,
                        type: 'GET',
                        dataType: 'JSON',
                        success: function(result){
                            $('#table_student').empty();
                            $('#table_student_time').hide();
                            var table = document.getElementById('table_student');
                            if (result['datacourses'].length != 0){
                                $('#table_student_time').show();
                                createTable(result['datacourses'], table);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error(xhr.responseText);
                            alert('فشل تحميل التوقيت');
                        }
                    });
                }

                $('#selectstudentchange').change(function () {
                    SelectStudentChange($(this));
                });

                // Create Table Helper
                function createTable(tableData, table) {
                    var header = table.createTHead();
                    var row = header.insertRow(0);
                    var cell1 = row.insertCell(0);
                    var cell2 = row.insertCell(1);
                    var cell3 = row.insertCell(2);
                    var cell4 = row.insertCell(3);
                    var cell5 = row.insertCell(4);
                    var cell6 = row.insertCell(5);
                    cell1.innerHTML = "<b>Name</b>";
                    cell2.innerHTML = "<b>Days</b>";
                    cell3.innerHTML = "<b>Start Date</b>";
                    cell4.innerHTML = "<b>End Date</b>";
                    cell5.innerHTML = "<b>Start Time</b>";
                    cell6.innerHTML = "<b>End Time</b>";

                    tableData.forEach(function(rowData) {
                        var row = table.insertRow(-1);
                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);
                        var cell3 = row.insertCell(2);
                        var cell4 = row.insertCell(3);
                        var cell5 = row.insertCell(4);
                        var cell6 = row.insertCell(5);
                        cell1.innerHTML = rowData.name;
                        cell2.innerHTML = rowData.days;
                        cell3.innerHTML = rowData.startdate;
                        cell4.innerHTML = rowData.enddate;
                        cell5.innerHTML = rowData.starttime;
                        cell6.innerHTML = rowData.endtime;
                    });
                }

                // Update Price UI
                function updatePriceUI(price) {
                    // Ensure price is a number and format it properly
                    var formattedPrice = parseFloat(price).toFixed(2);
                    $('#span_price').html(formattedPrice + ' دينار');
                    $('#value_rec').val(formattedPrice);
                    $('#value_rec').attr('max', formattedPrice);
                    $('#total_price_span').text('/ ' + formattedPrice);
                    $('#final_course_price').val(formattedPrice);
                    $('#value_rec_error').text('');
                }

                // Apply Coupon Code
                $('#apply_coupon_btn').click(function() {
                    var coupon_code = $('#coupon_code').val();
                    var course_id = $('#selectsoursechange').val();
                    var status_div = $('#coupon_status');

                    if (!course_id) {
                        status_div.html('<span class="text-danger">الرجاء اختيار الكورس أولاً.</span>');
                        return;
                    }
                    if (!coupon_code) {
                        status_div.html('<span class="text-danger">الرجاء إدخال كود الخصم.</span>');
                        return;
                    }

                    status_div.html('<span class="text-info">جاري التحقق...</span>');

                    $.ajax({
                        url: '{{ url("/data_entry/apply-coupon") }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            coupon_code: coupon_code,
                            course_id: course_id,
                            type: 'course'
                        },
                        dataType: 'JSON',
                        success: function(response) {
                            if (response.success) {
                                var new_price = response.new_price;
                                status_div.html('<span class="text-success">تم تطبيق الخصم! السعر الجديد: ' + parseFloat(new_price).toFixed(2) + ' دينار</span>');
                                updatePriceUI(new_price);
                                
                                // Store coupon information
                                $('#applied_coupon_code').val(coupon_code);
                                $('#applied_coupon_type').val(response.coupon_type);
                            } else {
                                status_div.html('<span class="text-danger">' + response.message + '</span>');
                                var original_price = $('#original_course_price').val();
                                updatePriceUI(original_price);
                                
                                // Clear coupon information
                                $('#applied_coupon_code').val('');
                                $('#applied_coupon_type').val('');
                            }
                        },
                        error: function() {
                            status_div.html('<span class="text-danger">حدث خطأ في الخادم.</span>');
                            var original_price = $('#original_course_price').val();
                            updatePriceUI(original_price);
                        }
                    });
                });

                // Real-time Validation for Value Received
                $('#value_rec').on('input', function() {
                    var paid_value = parseFloat($(this).val());
                    var max_value = parseFloat($(this).attr('max'));
                    var error_div = $('#value_rec_error');

                    if (paid_value > max_value) {
                        error_div.text('القيمة المدفوعة لا يمكن أن تكون أكبر من ' + max_value);
                    } else {
                        error_div.text('');
                    }
                });

            });
        </script>
    @stop
