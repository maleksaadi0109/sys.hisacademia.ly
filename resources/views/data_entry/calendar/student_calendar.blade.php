@extends('data_entry.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('روزنامة الطالب')}}</h2>
        </header>
        <div class="container">
            <div class="row table-responsive">
                @php
                    use App\Enums\WeekDays;
                @endphp

                <div class="mt-5 col-lg-12">
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="selectstudentchange">اسم الطالب</label>
                        <select class="form-select" id="selectstudentchange" name="student_id" required>
                            <option selected disabled>أختر....</option>
                            @foreach($students as $student)
                                @if(old('student_id') == $student->id)
                                    <option selected value="{{$student->id}}">{{$student->name}}</option>
                                @else
                                    <option value="{{$student->id}}">{{$student->name}}</option>
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

                <div class="col-12 d-flex justify-content-between align-items-center">
                    <button class="mt-3 mb-4 btn btn-info" data-last="0" id="last_week">الأسبوع السابق</button>
                    <button class="mt-3 mb-4 btn btn-info" id="this_week">هذا الأسبوع</button>
                    <button class="mt-3 mb-4 btn btn-info" data-next="0" id="next_week">الأسبوع التالي</button>
                    <h6 class="d-flex">من التاريخ: <span id="start_date"></span> - الى التاريخ: <span id="end_date"></span></h6>
                </div>

                <table class="table table-bordered table-primary text-center">
                    <thead>
                    <tr>
                        <th class="table-primary" style="width:12%" scope="col">التاريخ</th>
                        @foreach(WeekDays::WeekDaysAr() as $key=>$value)
                            <th id="{{$key}}" class="table-success" style="width:12%" scope="col"></th>
                        @endforeach
                    </tr>
                    <tr>
                        <th class="table-primary" style="width:12%" scope="col">التوقيت / اليوم</th>
                        @foreach(WeekDays::WeekDaysAr() as $key=>$value)
                            <th class="table-success" style="width:12%" scope="col">{{$value}}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    @for($hour = 7; $hour <= 23; $hour++)
                        <tr>
                            <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered">
                                {{ $hour <= 12 ? ($hour == 12 ? '12:00 PM' : $hour . ':00 AM') : ($hour - 12) . ':00 PM' }}
                            </td>
                            @foreach(['su', 'mo', 'tu', 'we', 'th', 'fr', 'sa'] as $day)
                                <td class="td_course" id="{{$day}}-{{sprintf('%02d', $hour)}}" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                            @endforeach
                        </tr>
                    @endfor
                    @for($hour = 0; $hour <= 6; $hour++)
                        <tr>
                            <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered">
                                {{ $hour == 0 ? '12:00 AM' : $hour . ':00 AM' }}
                            </td>
                            @foreach(['su', 'mo', 'tu', 'we', 'th', 'fr', 'sa'] as $day)
                                <td class="td_course" id="{{$day}}-{{sprintf('%02d', $hour)}}" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                            @endforeach
                        </tr>
                    @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function () {

            // Function to get week dates based on offset
            function getWeekDates(weekOffset = 0) {
                const today = new Date();
                const currentDay = today.getDay(); // 0 = Sunday, 6 = Saturday

                // Calculate start of week (Sunday) without mutating original date
                const startOfWeek = new Date(today);
                startOfWeek.setDate(today.getDate() - currentDay + (weekOffset * 7));

                // Calculate end of week (Saturday)
                const endOfWeek = new Date(startOfWeek);
                endOfWeek.setDate(startOfWeek.getDate() + 6);

                return {
                    start: startOfWeek,
                    end: endOfWeek
                };
            }

            // Function to update week display
            function updateWeekDisplay(weekDates) {
                $('#start_date').html(weekDates.start.toLocaleDateString("en-CA"));
                $('#end_date').html(weekDates.end.toLocaleDateString("en-CA"));

                // Calculate individual day dates without mutation
                const dates = [];
                for (let i = 0; i < 7; i++) {
                    const date = new Date(weekDates.start);
                    date.setDate(weekDates.start.getDate() + i);
                    dates.push(date);
                }

                $('#su').html(dates[0].toLocaleDateString("en-CA"));
                $('#mo').html(dates[1].toLocaleDateString("en-CA"));
                $('#tu').html(dates[2].toLocaleDateString("en-CA"));
                $('#we').html(dates[3].toLocaleDateString("en-CA"));
                $('#th').html(dates[4].toLocaleDateString("en-CA"));
                $('#fr').html(dates[5].toLocaleDateString("en-CA"));
                $('#sa').html(dates[6].toLocaleDateString("en-CA"));
            }

            // Initialize with current week
            var thisWeek = getWeekDates(0);
            updateWeekDisplay(thisWeek);

            // Hide navigation buttons initially
            $("#this_week").hide();
            $("#next_week").hide();
            $("#last_week").hide();

            // This Week button handler
            $("#this_week").button().click(function(){
                var thisWeek = getWeekDates(0);
                updateWeekDisplay(thisWeek);

                $('#next_week').data('next', 0);
                $('#last_week').data('last', 0);

                var student_id = $('#selectstudentchange').val();
                if (student_id) {
                    callAjaxForStudent(student_id);
                }
            });

            // Next Week button handler
            $("#next_week").button().click(function(){
                var nextOffset = $('#next_week').data('next') || 0;
                var lastOffset = $('#last_week').data('last') || 0;
                var weekDates;

                if (lastOffset == 0) {
                    // Moving forward from current or future weeks
                    nextOffset = nextOffset + 1;
                    weekDates = getWeekDates(nextOffset);
                    $('#next_week').data('next', nextOffset);
                } else {
                    // Moving forward from past weeks
                    lastOffset = lastOffset - 1;
                    $('#last_week').data('last', lastOffset);

                    weekDates = (lastOffset == 0) ? getWeekDates(0) : getWeekDates(-lastOffset);
                }

                updateWeekDisplay(weekDates);

                var student_id = $('#selectstudentchange').val();
                if (student_id) {
                    callAjaxForStudent(student_id);
                }
            });

            // Last Week button handler
            $("#last_week").button().click(function(){
                var nextOffset = $('#next_week').data('next') || 0;
                var lastOffset = $('#last_week').data('last') || 0;
                var weekDates;

                if (nextOffset == 0) {
                    // Moving backward from current or past weeks
                    lastOffset = lastOffset + 1;
                    weekDates = getWeekDates(-lastOffset);
                    $('#last_week').data('last', lastOffset);
                } else {
                    // Moving backward from future weeks
                    nextOffset = nextOffset - 1;
                    $('#next_week').data('next', nextOffset);

                    weekDates = (nextOffset == 0) ? getWeekDates(0) : getWeekDates(nextOffset);
                }

                updateWeekDisplay(weekDates);

                var student_id = $('#selectstudentchange').val();
                if (student_id) {
                    callAjaxForStudent(student_id);
                }
            });

            // Student selection change handler
            $('#selectstudentchange').change(function (){
                var student_id = $(this).val();
                if (student_id) {
                    callAjaxForStudent(student_id);
                    $("#this_week").show();
                    $("#next_week").show();
                    $("#last_week").show();
                }
            });

            // AJAX function to fetch student courses
            function callAjaxForStudent(student_id) {
                $.ajax({
                    url: window.location.origin + '/data_entry/calendar_bystudent/' + student_id,
                    type: 'GET',
                    dataType: 'JSON',
                    success: function(result){
                        // Clear all course cells
                        $('.td_course').html('');

                        // Loop through each course
                        result.forEach(function(item){
                            var data_startdate = item['startdate'];
                            var data_enddate = item['enddate'];
                            var data_starttime = item['starttime'].slice(0, 2);
                            var data_startmi = item['starttime'].slice(3, 5);
                            var data_endtime = item['endtime'].slice(0, 2);
                            var data_endmi = item['endtime'].slice(3, 5);
                            var data_name = item['name'];
                            var data_teacher_name = item['teacher_name'];

                            // Get course days
                            var days = Object.keys(item['days']).map((key) => [key, item['days'][key]]);

                            // Loop through each day
                            days.forEach(function(currentValue) {
                                var cellDate = new Date($("#" + currentValue[0]).html());
                                var courseStartDate = new Date(data_startdate);
                                var courseEndDate = new Date(data_enddate);

                                // Check if course is active on this date
                                if (cellDate >= courseStartDate && cellDate <= courseEndDate) {
                                    var endhour = parseInt(data_endtime) - parseInt(data_starttime);
                                    var top = parseInt((parseInt(data_startmi) * 100) / 60);
                                    var height = (endhour * 100) + parseInt((parseInt(data_endmi) * 100) / 60) - top;

                                    var div = `
                            <div style="
                                position: absolute;
                                width: 100%;
                                background-color: #28a745;
                                color: white;
                                font-weight: bolder;
                                text-align: right;
                                height: ${height}%;
                                top: ${top}%;
                                right: 0;
                                z-index: 2;
                                opacity: 100%;
                                border: 1px solid aliceblue;
                                border-radius: 8px;
                                padding: 5px;
                                font-size: 13px;
                                ">
                                <span style="color: black">اسم الكورس: </span>${data_name}
                                <br><span style="color: black">اسم المدرس: </span>${data_teacher_name}
                            </div>
                            `;

                                    // Insert course div into appropriate time slot
                                    $('#' + currentValue[0] + '-' + data_starttime).html(div);
                                }
                            });
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        alert('فشل تحميل التوقيت');
                    }
                });
            }

        });
    </script>
@stop
