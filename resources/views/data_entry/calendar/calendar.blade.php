@extends('data_entry.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('روزنامة الكورس')}}</h2>
        </header>
        <div class="container">
            <div class="row table-responsive ">
                @php
                    use App\Enums\WeekDays;
                @endphp

                <div class="mt-5 col-lg-12">
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="selectsoursechange">الكورس</label>
                        <select class="form-select" id="selectsoursechange" name="course_id" required>
                            <option selected disabled>أختر....</option>
                            @foreach($courses as $course)
                                @php $days = ''; foreach(json_decode($course->days) as $value){  $days .= WeekDays::weekDaysAr()[$value].' ';} @endphp
                                @if(old('course_id') == $course->user->id)
                                    <option selected value="{{$course->id}}">{{$course->diploma_id != null? "دبلوم: (".$course->diploma->name.") ":""}}{{$course->name.'  (الايام:'.$days.' ) __من ('.date("h:i A", strtotime($course->start_time)).') '.'حتى ('.date("h:i A", strtotime($course->end_time)).')'}}</option>
                                @else
                                    <option value="{{$course->id}}">{{$course->diploma_id != null? "دبلوم: (".$course->diploma->name.") ":""}}{{$course->name.'  (الايام:'.$days.' ) __من ('.date("h:i A", strtotime($course->start_time)).') '.'حتى ('.date("h:i A", strtotime($course->end_time)).')'}}</option>
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

                <div class="col-12 d-flex justify-content-between align-items-center">
                    <button class="mt-3 mb-4 btn btn-info" data-last="0" id="last_week">الأسبوع السابق</button>
                    <button class="mt-3 mb-4 btn btn-info" id="this_week">هذا الأسبوع</button>
                    <button class="mt-3 mb-4 btn btn-info" data-next="0" id="next_week">الأسبوع التالي</button>
                    <h6 class="d-flex">من التاريخ: <span id="start_date"></span> - الى التاريخ: <span id="end_date"></span></h6>
                </div>

                <table class="table table-bordered table-primary  text-center">
                    <thead >
                    <tr>
                        <th class="table-primary" style="width:12%" scope="col">التاريخ</th>
                        @foreach(WeekDays::WeekDaysAr() as $key=>$value)
                            <th id="{{$key}}" class="table-success" style="width:12%" scope="col"></th>
                        @endforeach
                    </tr>
                    <tr>
                        <th class="table-primary" style="width:12%" scope="col">التوقيت / اليوم</th>
                        @foreach(WeekDays::WeekDaysAr() as $key=>$value)
                            <th id="{{$key}}" class="table-success" style="width:12%" scope="col">{{$value}}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered ">7:00 AM</td>
                        <td class="td_course" id="su-07" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-07" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-07" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-07" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-07" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-07" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-07" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered ">8:00 AM</td>
                        <td class="td_course" id="su-08" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-08" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-08" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-08" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-08" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-08" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-08" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered ">9:00 AM</td>
                        <td class="td_course" id="su-09" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-09" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-09" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-09" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-09" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-09" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-09" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered ">10:00 AM</td>
                        <td class="td_course" id="su-10" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-10" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-10" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-10" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-10" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-10" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-10" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered ">11:00 AM</td>
                        <td class="td_course" id="su-11" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-11" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-11" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-11" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-11" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-11" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-11" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered ">12:00 PM</td>
                        <td class="td_course" id="su-12" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-12" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-12" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-12" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-12" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-12" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-12" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered ">1:00 PM</td>
                        <td class="td_course" id="su-13" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-13" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-13" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-13" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-13" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-13" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-13" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered ">2:00 PM</td>
                        <td class="td_course" id="su-14" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-14" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-14" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-14" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-14" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-14" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-14" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered ">3:00 PM</td>
                        <td class="td_course" id="su-15" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-15" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-15" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-15" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-15" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-15" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-15" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered ">4:00 PM</td>
                        <td class="td_course" id="su-16" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-16" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-16" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-16" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-16" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-16" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-16" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered ">5:00 PM</td>
                        <td class="td_course" id="su-17" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-17" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-17" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-17" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-17" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-17" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-17" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered ">6:00 PM</td>
                        <td class="td_course" id="su-18" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-18" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-18" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-18" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-18" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-18" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-18" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered ">7:00 PM</td>
                        <td class="td_course" id="su-19" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-19" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-19" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-19" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-19" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-19" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-19" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered ">8:00 PM</td>
                        <td class="td_course" id="su-20" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-20" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-20" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-20" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-20" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-20" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-20" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered ">9:00 PM</td>
                        <td class="td_course" id="su-21" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-21" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-21" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-21" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-21" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-21" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-21" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered ">10:00 PM</td>
                        <td class="td_course" id="su-22" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-22" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-22" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-22" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-22" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-22" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-22" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered ">11:00 PM</td>
                        <td class="td_course" id="su-23" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-23" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-23" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-23" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-23" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-23" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-23" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered ">12:00 AM</td>
                        <td class="td_course" id="su-00" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-00" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-00" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-00" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-00" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-00" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-00" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered ">1:00 AM</td>
                        <td class="td_course" id="su-01" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-01" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-01" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-01" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-01" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-01" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-01" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered ">2:00 AM</td>
                        <td class="td_course" id="su-02" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-02" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-02" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-02" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-02" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-02" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-02" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered ">3:00 AM</td>
                        <td class="td_course" id="su-03" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-03" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-03" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-03" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-03" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-03" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-03" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered ">4:00 AM</td>
                        <td class="td_course" id="su-04" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-04" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-04" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-04" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-04" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-04" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-04" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered ">5:00 AM</td>
                        <td class="td_course" id="su-05" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-05" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-05" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-05" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-05" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-05" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-05" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered ">6:00 AM</td>
                        <td class="td_course" id="su-06" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-06" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-06" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-06" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-06" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-06" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-06" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function () {

            function getWeekDates(weekOffset = 0) {
                const today = new Date();
                const currentDay = today.getDay();

                const startOfWeek = new Date(today.setDate(today.getDate() - currentDay + (weekOffset * 7)));
                const endOfWeek = new Date(today.setDate(startOfWeek.getDate() + 6));

                return {
                    start: startOfWeek,
                    end: endOfWeek
                };
            }

            function getLastWeek(i) {
                return getWeekDates(-i);
            }

            function getThisWeek() {
                return getWeekDates(0);
            }

            function getNextWeek(i) {
                return getWeekDates(i);
            }

            var thisWeek = getThisWeek();

            $('#start_date').html(thisWeek.start.toLocaleDateString("en-CA"));
            $('#end_date').html(thisWeek.end.toLocaleDateString("en-CA"));

            var su = new Date(thisWeek.start.setDate(thisWeek.start.getDate()));
            var mo = new Date(thisWeek.start.setDate(thisWeek.start.getDate() + 1));
            var tu = new Date(thisWeek.start.setDate(thisWeek.start.getDate() + 1));
            var we = new Date(thisWeek.start.setDate(thisWeek.start.getDate() + 1));
            var th = new Date(thisWeek.start.setDate(thisWeek.start.getDate() + 1));
            var fr = new Date(thisWeek.start.setDate(thisWeek.start.getDate() + 1));
            var sa = new Date(thisWeek.start.setDate(thisWeek.start.getDate() + 1));
            $('#su').html(su.toLocaleDateString("en-CA"));
            $('#mo').html(mo.toLocaleDateString("en-CA"));
            $('#tu').html(tu.toLocaleDateString("en-CA"));
            $('#we').html(we.toLocaleDateString("en-CA"));
            $('#th').html(th.toLocaleDateString("en-CA"));
            $('#fr').html(fr.toLocaleDateString("en-CA"));
            $('#sa').html(sa.toLocaleDateString("en-CA"));

            $("#this_week").hide();
            $("#next_week").hide();
            $("#last_week").hide();

            $("#this_week").button().click(function(){
                var thisWeek = getThisWeek();

                $('#start_date').html(thisWeek.start.toLocaleDateString("en-CA"));
                $('#end_date').html(thisWeek.end.toLocaleDateString("en-CA"));

                var su = new Date(thisWeek.start.setDate(thisWeek.start.getDate()));
                var mo = new Date(thisWeek.start.setDate(thisWeek.start.getDate() + 1));
                var tu = new Date(thisWeek.start.setDate(thisWeek.start.getDate() + 1));
                var we = new Date(thisWeek.start.setDate(thisWeek.start.getDate() + 1));
                var th = new Date(thisWeek.start.setDate(thisWeek.start.getDate() + 1));
                var fr = new Date(thisWeek.start.setDate(thisWeek.start.getDate() + 1));
                var sa = new Date(thisWeek.start.setDate(thisWeek.start.getDate() + 1));
                $('#su').html(su.toLocaleDateString("en-CA"));
                $('#mo').html(mo.toLocaleDateString("en-CA"));
                $('#tu').html(tu.toLocaleDateString("en-CA"));
                $('#we').html(we.toLocaleDateString("en-CA"));
                $('#th').html(th.toLocaleDateString("en-CA"));
                $('#fr').html(fr.toLocaleDateString("en-CA"));
                $('#sa').html(sa.toLocaleDateString("en-CA"));

                $('#next_week').data('next',0);
                $('#last_week').data('last',0);
                var course_id = $('#selectsoursechange').val();
                callAjaxForCourse(course_id);
            });

            $("#next_week").button().click(function(){
                var nummber_next0 = $('#next_week').data('next');
                if($('#last_week').data('last')  == '0'){
                    nummber_next0 = nummber_next0 + 1;
                    var nextWeek = getNextWeek(nummber_next0);
                    $('#next_week').data('next',nummber_next0);
                }else{
                    var nummber_last0 = $('#last_week').data('last');
                    nummber_last0 = nummber_last0 - 1;
                    $('#last_week').data('last', nummber_last0);
                    if(nummber_last0 == 0){
                        var nextWeek = getThisWeek();
                    }else{
                        var nextWeek = getLastWeek(nummber_last0);
                    }
                }

                $('#start_date').html(nextWeek.start.toLocaleDateString("en-CA"));
                $('#end_date').html(nextWeek.end.toLocaleDateString("en-CA"));

                var su = new Date(nextWeek.start.setDate(nextWeek.start.getDate()));
                var mo = new Date(nextWeek.start.setDate(nextWeek.start.getDate() + 1));
                var tu = new Date(nextWeek.start.setDate(nextWeek.start.getDate() + 1));
                var we = new Date(nextWeek.start.setDate(nextWeek.start.getDate() + 1));
                var th = new Date(nextWeek.start.setDate(nextWeek.start.getDate() + 1));
                var fr = new Date(nextWeek.start.setDate(nextWeek.start.getDate() + 1));
                var sa = new Date(nextWeek.start.setDate(nextWeek.start.getDate() + 1));
                $('#su').html(su.toLocaleDateString("en-CA"));
                $('#mo').html(mo.toLocaleDateString("en-CA"));
                $('#tu').html(tu.toLocaleDateString("en-CA"));
                $('#we').html(we.toLocaleDateString("en-CA"));
                $('#th').html(th.toLocaleDateString("en-CA"));
                $('#fr').html(fr.toLocaleDateString("en-CA"));
                $('#sa').html(sa.toLocaleDateString("en-CA"));

                var course_id = $('#selectsoursechange').val();
                callAjaxForCourse(course_id);
            });

            $("#last_week").button().click(function(){
                var nummber_last1 = $('#last_week').data('last');
                if($('#next_week').data('next')  == '0'){
                    nummber_last1 = nummber_last1 + 1;
                    var lastWeek = getLastWeek(nummber_last1);
                    $('#last_week').data('last',nummber_last1);
                }else{
                    var nummber_next1 = $('#next_week').data('next');
                    nummber_next1 = nummber_next1 - 1;
                    $('#next_week').data('next', nummber_next1);
                    if(nummber_next1 == 0){
                        var lastWeek = getThisWeek();
                    }else{
                        var lastWeek = getNextWeek(nummber_next1);
                    }
                }

                $('#start_date').html(lastWeek.start.toLocaleDateString("en-CA"));
                $('#end_date').html(lastWeek.end.toLocaleDateString("en-CA"));

                var su = new Date(lastWeek.start.setDate(lastWeek.start.getDate()));
                var mo = new Date(lastWeek.start.setDate(lastWeek.start.getDate() + 1));
                var tu = new Date(lastWeek.start.setDate(lastWeek.start.getDate() + 1));
                var we = new Date(lastWeek.start.setDate(lastWeek.start.getDate() + 1));
                var th = new Date(lastWeek.start.setDate(lastWeek.start.getDate() + 1));
                var fr = new Date(lastWeek.start.setDate(lastWeek.start.getDate() + 1));
                var sa = new Date(lastWeek.start.setDate(lastWeek.start.getDate() + 1));
                $('#su').html(su.toLocaleDateString("en-CA"));
                $('#mo').html(mo.toLocaleDateString("en-CA"));
                $('#tu').html(tu.toLocaleDateString("en-CA"));
                $('#we').html(we.toLocaleDateString("en-CA"));
                $('#th').html(th.toLocaleDateString("en-CA"));
                $('#fr').html(fr.toLocaleDateString("en-CA"));
                $('#sa').html(sa.toLocaleDateString("en-CA"));

                var course_id = $('#selectsoursechange').val();
                callAjaxForCourse(course_id);
            });

            $('#selectsoursechange').change(function (){
                var course_id = $(this).val();
                callAjaxForCourse(course_id);
                $("#this_week").show();
                $("#next_week").show();
                $("#last_week").show();
            });

            function callAjaxForCourse(course_id) {
                console.log('=== Starting AJAX Call ===');
                console.log('Course ID:', course_id);
                console.log('URL:', window.location.origin +'/data_entry/calendar_bycourse/'+course_id);

                $.ajax({
                    url        :window.location.origin +'/data_entry/calendar_bycourse/'+course_id,
                    type       :'GET',
                    dataType   :'JSON',
                    success    :function(result){
                        console.log('=== AJAX Success ===');
                        console.log('Full Result:', result);

                        var div = ``;
                        $('.td_course').html(div);

                        var data_startdate = result['startdate'];
                        var data_enddate = result['enddate'];
                        var data_starttime = result['starttime'].slice(0, 2);
                        var data_startmi = result['starttime'].slice(3, 5);
                        var data_endtime = result['endtime'].slice(0, 2);
                        var data_endmi = result['endtime'].slice(3, 5);
                        var data_name = result['name'];
                        var data_teacher_name = result['teacher_name'];

                        console.log('Course Details:');
                        console.log('- Name:', data_name);
                        console.log('- Teacher:', data_teacher_name);
                        console.log('- Date Range:', data_startdate, 'to', data_enddate);
                        console.log('- Time:', data_starttime + ':' + data_startmi, 'to', data_endtime + ':' + data_endmi);
                        console.log('- Days Object:', result['days']);

                        // استخراج الأيام من Object
                        var days = Object.keys(result['days']).map((key) => [key, result['days'][key]]);
                        console.log('Days Array:', days);

                        days.forEach(function(dayKey, index)  {
                            var dayCode = dayKey[0]; // 'su', 'mo', etc.
                            var dayName = dayKey[1]; // 'الأحد', 'الإثنين', etc.

                            console.log('--- Processing Day', index, '---');
                            console.log('Day Code:', dayCode, '| Day Name:', dayName);

                            var cellDate = $("#"+dayCode).html();
                            console.log('Cell #' + dayCode + ' contains:', cellDate);

                            if(!cellDate) {
                                console.warn('WARNING: Cell #' + dayCode + ' is empty or not found!');
                                return;
                            }

                            var currentDate = new Date(cellDate).toLocaleDateString('en-CA');
                            console.log('Parsed Date:', currentDate);
                            console.log('Checking if', currentDate, '>=', data_startdate, '&&', currentDate, '<=', data_enddate);

                            if(currentDate >= data_startdate && currentDate <= data_enddate){
                                console.log('✓ Date is within range! Creating course block...');

                                var endhour = data_endtime - data_starttime;
                                var top = 0 + parseInt((data_startmi * 100) / 60);
                                var height = (endhour * 100) + parseInt((data_endmi * 100) / 60) - top;

                                console.log('Calculations:');
                                console.log('- End Hour:', endhour);
                                console.log('- Top Position:', top + '%');
                                console.log('- Height:', height + '%');

                                var div = `
                            <div style="
                                position: absolute;
                                width: 100%;
                                background-color: #e09707;
                                color: white;
                                font-weight: bolder;
                                text-align: right;
                                height: `+ height +`%;
                                top: `+ top +`%;
                                right: 0;
                                z-index: 2;
                                opacity: 100%;
                                border: 1px solid aliceblue;
                                border-radius: 8px;
                                padding: 5px;
                                font-size: 13px;
                                ">
                                <span style="color: black">اسم الكورس: </span>`+ data_name +`
                                <br><span style="color: black">اسم المدرس: </span>`+ data_teacher_name +`
                            </div>
                            `;

                                var targetCell = '#' + dayCode + '-' + data_starttime;
                                console.log('Target Cell:', targetCell);

                                $(targetCell).html(div);
                                console.log('✓ Course block added successfully!');
                            } else {
                                console.log('✗ Date is outside range, skipping');
                            }
                        });

                        console.log('=== AJAX Processing Complete ===');
                    },
                    error: function (xhr, status, error) {
                        console.error('=== AJAX Error ===');
                        console.error('Status:', status);
                        console.error('Error:', error);
                        console.error('Status Code:', xhr.status);
                        console.error('Response Text:', xhr.responseText);

                        try {
                            var errorJson = JSON.parse(xhr.responseText);
                            console.error('Parsed Error:', errorJson);
                        } catch(e) {
                            console.error('Could not parse error response');
                        }

                        alert('فشل تحميل التوقيت - شوف الـ Console للتفاصيل (اضغط F12)');
                    }
                });
            }

        });
    </script>

@stop
