@extends('admin.dashboard')
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

                <form method="POST" enctype="multipart/form-data" action="{{ route('enroll.course') }}">
                    @csrf
                    <div class="row">

                        <div class="mt-5 col-lg-8">
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="selectstudentchange">الطالب</label>
                                <select class="form-select" id="selectstudentchange" name="student_id" required>
                                    <option selected disabled>أختر....</option>
                                    @foreach($students as  $value)
                                        @if(old('student_id') == $value->user->id)
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

                        <div class="row table-responsive" id="table_student_time">
                            <h5>الكورسات الحالية للطالب</h5>
                            <table class="student_table_course table table-light table-hover text-center" id="table_student">
                            </table>
                        </div>

                        <div class="mt-5 col-lg-12">
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="selectsoursechange">الكورس</label>
                                <select class="form-select" id="selectsoursechange" name="course_id" required>
                                    <option selected disabled>أختر....</option>
                                    @foreach($courses as $course)
                                        @php $days = ''; foreach(json_decode($course->days) as $value){  $days .= WeekDays::weekDaysAr()[$value].' ';} @endphp
                                        @if(old('course_id') == $course->user->id)
                                            <option data-price="{{$course->price}}" selected value="{{$course->id}}">{{$course->name.'  (الايام:'.$days.' ) __من ('.date("h:i A", strtotime($course->start_time)).') '.'حتى ('.date("h:i A", strtotime($course->end_time)).')'}}</option>
                                        @else
                                            <option data-price="{{$course->price}}" value="{{$course->id}}">{{$course->name.'  (الايام:'.$days.' ) __من ('.date("h:i A", strtotime($course->start_time)).') '.'حتى ('.date("h:i A", strtotime($course->end_time)).')'}}</option>
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

                        <div class="row table-responsive" id="table_course_time">
                            <h5>معلومات هذا الكورس (السعر: <span id="span_price"> </span>)</h5>
                            <table class="student_table_course table table-light table-hover text-center" id="table_course">
                            </table>
                        </div>

                        <div class="mt-4 col-lg-6">
                            <x-input-label for="coupon_code" :value="__('كود الخصم (اختياري)')" />
                            <div class="input-group">
                                <x-text-input id="coupon_code" class="form-control" type="text" name="coupon_code" autocomplete="off" />
                                <button class="btn btn-outline-secondary" type="button" id="apply_coupon_btn">تطبيق</button>
                            </div>
                            <div id="coupon_status" class="mt-2 text-sm"></div> </div>

                        <div class="mt-4 col-lg-6">
                            <x-input-label for="value_rec" :value="__('القيمة المدفوعة')" />
                            <div class="input-group">
                                <x-text-input id="value_rec" value="{{old('value_rec')}}" class="form-control" min="0" type="number" name="value_rec" required  autofocus autocomplete="value_rec" />
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

            function SelectCourseChange(element) {
                var course_id = element.val();
                if (!course_id) return; // إذا لم يتم اختيار كورس، لا تفعل شيئاً

                // نستخدم parseFloat لضمان أنه رقم
                var course_price = parseFloat(element.find(':selected').data('price'));

                // تخزين السعر الأصلي والسعر النهائي (الذي قد يتغير بالكوبون)
                $('#original_course_price').val(course_price);
                $('#final_course_price').val(course_price);

                // [متطلبك الأول والثاني] تحديث واجهة السعر وتعيين الحد الأقصى
                updatePriceUI(course_price);

                // إعادة تعيين حقل الكوبون عند تغيير الكورس
                $('#coupon_code').val('');
                $('#coupon_status').empty();

                $.ajax({
                    url        :window.location.origin +'/admin/time_by_course/'+course_id,
                    type       :'GET',
                    dataType   :'JSON',
                    success    :function(result){
                        $('#table_course').empty();
                        $('#table_course_time').hide();
                        var table = document.getElementById('table_course');
                        if (result['datacourses'].length != 0){
                            $('#table_course_time').show();
                            result['datacourses'].forEach((item, index) =>{
                                createTable([['اسم الكورس:', item['name'],'الايام:',item['days']],['تاريخ البداية:',item['startdate'] ,'تاريخ النهاية:', item['enddate'] ],['من الساعة:', item['starttime'] ,'الى الساعة:',item['endtime'] ]],table);
                            });
                        }},
                    error: function () {
                        alert('فشل تحميل التوقيت');
                    }
                });
            }

            $('#selectsoursechange').change(function () {
                SelectCourseChange($(this));
            });

            // ... (دالة SelectStudentChange كما هي) ...
            function SelectStudentChange(element) {
                var student_id = element.val();
                $.ajax({
                    url        :window.location.origin +'/admin/time_by_student/'+student_id,
                    type       :'GET',
                    dataType   :'JSON',
                    success    :function(result){
                        $('#table_student').empty();
                        $('#table_student_time').hide();
                        var table = document.getElementById('table_student');
                        if (result['datacourses'].length != 0){
                            $('#table_student_time').show();
                            result['datacourses'].forEach((item, index) => {
                                createTable([['اسم الكورس:', item['name'],'الايام:',item['days']],['تاريخ البداية:',item['startdate'] ,'تاريخ النهاية:', item['enddate'] ],['من الساعة:', item['starttime'] ,'الى الساعة:',item['endtime'] ]], table);
                            });
                        }},
                    error: function () {
                        alert('فشل تحميل التوقيت');
                    }
                });
            }

            $('#selectstudentchange').change(function () {
                SelectStudentChange($(this));
            });

            // ... (دالة createTable كما هي) ...
            function createTable(tableData, table) {
                tableData.forEach(function(rowData) {
                    var row = document.createElement('tr');
                    rowData.forEach(function(cellData) {
                        var cell = document.createElement('td');
                        cell.appendChild(document.createTextNode(cellData));
                        row.appendChild(cell);
                    });
                    table.appendChild(row);
                });
            }

            // == [جديد] دالة لتحديث واجهة السعر ==
            function updatePriceUI(price) {
                $('#span_price').html(price); // سعر الكورس في الجدول
                $('#value_rec').val(price); // القيمة المدفوعة (افتراضياً السعر الكامل)
                $('#value_rec').attr('max', price); // [الأهم] تعيين الحد الأقصى للمدخل
                $('#total_price_span').text('/ ' + price); // السعر الإجمالي بجانب المدخل
                $('#final_course_price').val(price); // تحديث السعر النهائي المخزن
                $('#value_rec_error').text(''); // مسح أي أخطاء سابقة
            }

            // == [جديد] تطبيق كود الخصم (متطلبك الثالث) ==
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

                // ستحتاج إلى إنشاء هذا المسار (Route) في ملف web.php
                $.ajax({
                    url: window.location.origin + '/admin/apply-coupon',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', // مهم جداً للأمان
                        coupon_code: coupon_code,
                        course_id: course_id
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.success) {
                            var new_price = response.new_price;
                            status_div.html('<span class="text-success">تم تطبيق الخصم! السعر الجديد: ' + new_price + '</span>');

                            // تحديث السعر في الواجهة
                            updatePriceUI(new_price);
                        } else {
                            status_div.html('<span class="text-danger">' + response.message + '</span>');
                            // إذا فشل الكوبون، أعد السعر للأصلي
                            var original_price = $('#original_course_price').val();
                            updatePriceUI(original_price);
                        }
                    },
                    error: function() {
                        status_div.html('<span class="text-danger">حدث خطأ في الخادم.</span>');
                        var original_price = $('#original_course_price').val();
                        updatePriceUI(original_price);
                    }
                });
            });

            // == [جديد] التحقق الفوري من القيمة المدفوعة (متطلبك الثاني) ==
            // هذا يعطي رسالة خطأ فورية للمستخدم
            $('#value_rec').on('input', function() {
                var paid_value = parseFloat($(this).val());
                var max_value = parseFloat($(this).attr('max')); // احصل على الحد الأقصى
                var error_div = $('#value_rec_error');

                if (paid_value > max_value) {
                    error_div.text('القيمة المدفوعة لا يمكن أن تكون أكبر من ' + max_value);
                } else {
                    error_div.text(''); // مسح الخطأ
                }
            });

        });
    </script>
@stop
