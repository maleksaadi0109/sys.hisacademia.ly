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
                                <x-input-label for="value_rec" :value="__('القيمة المدفوعة')" />
                                <x-text-input id="value_rec" value="{{old('value_rec')}}" class="block mt-1 w-full" min="0" type="number" name="value_rec" required  autofocus autocomplete="value_rec" />
                                <x-input-error :messages="$errors->get('value_rec')" class="mt-2" />
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
                var course_price = element.find(':selected').data('price');
                $.ajax({
                    url        :window.location.origin +'/public/admin/time_by_course/'+course_id,
                    type       :'GET',
                    dataType   :'JSON',
                    success    :'success',
                    success    :function(result){
                    $('#table_course').empty();
                    $('#table_course_time').hide();
                    $('#span_price').html(course_price);
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

            function SelectStudentChange(element) {
                var student_id = element.val();
                $.ajax({
                    url        :window.location.origin +'/public/admin/time_by_student/'+student_id,
                    type       :'GET',
                    dataType   :'JSON',
                    success    :'success',
                    success    :function(result){
                    $('#table_student').empty();
                    $('#table_student_time').hide();
                    var table = document.getElementById('table_student');
                    if (result['datacourses'].length != 0){
                        $('#table_student_time').show();
                        result['datacourses'].forEach((item, index) =>{
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

        });
</script>
@stop