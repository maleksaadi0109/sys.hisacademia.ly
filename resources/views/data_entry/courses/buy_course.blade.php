    @extends('data_entry.dashboard')
    @section('content')
        <section>
            <header>
                <h2 class="text-lg font-medium text-gray-900">{{__('حجز دبلوم للطالب')}}</h2>
            </header>
            @php
                use App\Enums\WeekDays;
            @endphp
            <div class="container">
                <div class="row">
                    <input type="hidden" id="original_diploma_price" value="0">
                    <input type="hidden" id="final_diploma_price" value="0">

                    <form method="POST" enctype="multipart/form-data" action="{{ route('data_entry.enroll.diploma') }}">
                        @csrf
                        <div class="row">

                            <!-- Student Selection -->
                            <div class="mt-5 col-lg-8">
                                <div class="input-group mb-3">
                                    <label class="input-group-text" for="selectstudentchange">الطالب</label>
                                    <select class="form-select" id="selectstudentchange" name="student_id" required>
                                        <option selected disabled>أختر....</option>
                                        @foreach($students as $value)
                                            @if(old('student_id') == $value->id)
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

                            <!-- Diploma Selection -->
                            <div class="mt-5 col-lg-12">
                                <div class="input-group mb-3">
                                    <label class="input-group-text" for="selectsoursechange">الدبلوم</label>
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
                                        $messages = $errors->get('diploma_id');
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

                            <!-- Diploma Courses Table -->
                            <div class="row table-responsive" id="table_diploma_time">
                                <h5>معلومات هذا الدبلوم (السعر: <span id="span_price">0</span>)</h5>
                                <table class="student_table_course table table-light table-hover text-center" id="table_diploma">
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
                                    <x-text-input id="value_rec" value="{{old('value_rec')}}" class="form-control" min="0" type="number" name="value_rec" required autofocus autocomplete="value_rec" />
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
                $('#table_diploma_time').hide();

                // Select Course Change
                function SelectCourseChange(element) {
                    var course_id = element.val();
                    if (!course_id) return;

                    var course_price = parseFloat(element.find(':selected').attr('data-price'));

                    // Store original and final price
                    $('#original_diploma_price').val(course_price);
                    $('#final_diploma_price').val(course_price);

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
                            $('#table_diploma').empty();
                            $('#table_diploma_time').hide();
                            var table = document.getElementById('table_diploma');
                            if (result['datacourses'].length != 0){
                                $('#table_diploma_time').show();
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
                    $('#span_price').html(price);
                    $('#value_rec').val(price);
                    $('#value_rec').attr('max', price);
                    $('#total_price_span').text('/ ' + price);
                    $('#final_diploma_price').val(price);
                    $('#value_rec_error').text('');
                }

                // Apply Coupon Code
                $('#apply_coupon_btn').click(function() {
                    var coupon_code = $('#coupon_code').val();
                    var diploma_id = $('#selectsoursechange').val();
                    var status_div = $('#coupon_status');

                    if (!diploma_id) {
                        status_div.html('<span class="text-danger">الرجاء اختيار الدبلوم أولاً.</span>');
                        return;
                    }
                    if (!coupon_code) {
                        status_div.html('<span class="text-danger">الرجاء إدخال كود الخصم.</span>');
                        return;
                    }

                    status_div.html('<span class="text-info">جاري التحقق...</span>');

                    $.ajax({
                        url: '{{ url("/data_entry/apply-coupon-diploma") }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            coupon_code: coupon_code,
                            diploma_id: diploma_id
                        },
                        dataType: 'JSON',
                        success: function(response) {
                            if (response.success) {
                                var new_price = response.new_price;
                                status_div.html('<span class="text-success">تم تطبيق الخصم! السعر الجديد: ' + new_price + '</span>');
                                updatePriceUI(new_price);
                            } else {
                                status_div.html('<span class="text-danger">' + response.message + '</span>');
                                var original_price = $('#original_diploma_price').val();
                                updatePriceUI(original_price);
                            }
                        },
                        error: function() {
                            status_div.html('<span class="text-danger">حدث خطأ في الخادم.</span>');
                            var original_price = $('#original_diploma_price').val();
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
