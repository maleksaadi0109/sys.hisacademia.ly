@extends('student.dashboard')
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
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered">7:00 AM</td>
                        <td class="td_course" id="su-07" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-07" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-07" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-07" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-07" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-07" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-07" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered">8:00 AM</td>
                        <td class="td_course" id="su-08" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-08" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-08" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-08" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-08" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-08" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-08" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered">9:00 AM</td>
                        <td class="td_course" id="su-09" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-09" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-09" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-09" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-09" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-09" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-09" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered">10:00 AM</td>
                        <td class="td_course" id="su-10" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-10" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-10" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-10" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-10" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-10" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-10" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered">11:00 AM</td>
                        <td class="td_course" id="su-11" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-11" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-11" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-11" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-11" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-11" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-11" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered">12:00 PM</td>
                        <td class="td_course" id="su-12" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-12" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-12" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-12" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-12" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-12" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-12" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered">1:00 PM</td>
                        <td class="td_course" id="su-13" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-13" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-13" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-13" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-13" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-13" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-13" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered">2:00 PM</td>
                        <td class="td_course" id="su-14" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-14" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-14" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-14" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-14" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-14" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-14" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered">3:00 PM</td>
                        <td class="td_course" id="su-15" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-15" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-15" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-15" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-15" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-15" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-15" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered">4:00 PM</td>
                        <td class="td_course" id="su-16" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-16" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-16" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-16" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-16" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-16" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-16" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered">5:00 PM</td>
                        <td class="td_course" id="su-17" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-17" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-17" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-17" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-17" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-17" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-17" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered">6:00 PM</td>
                        <td class="td_course" id="su-18" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-18" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-18" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-18" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-18" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-18" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-18" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered">7:00 PM</td>
                        <td class="td_course" id="su-19" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-19" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-19" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-19" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-19" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-19" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-19" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered">8:00 PM</td>
                        <td class="td_course" id="su-20" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-20" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-20" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-20" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-20" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-20" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-20" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered">9:00 PM</td>
                        <td class="td_course" id="su-21" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-21" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-21" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-21" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-21" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-21" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-21" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered">10:00 PM</td>
                        <td class="td_course" id="su-22" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-22" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-22" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-22" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-22" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-22" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-22" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered">11:00 PM</td>
                        <td class="td_course" id="su-23" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-23" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-23" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-23" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-23" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-23" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-23" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered">12:00 AM</td>
                        <td class="td_course" id="su-00" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-00" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-00" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-00" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-00" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-00" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-00" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered">1:00 AM</td>
                        <td class="td_course" id="su-01" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-01" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-01" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-01" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-01" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-01" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-01" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered">2:00 AM</td>
                        <td class="td_course" id="su-02" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-02" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-02" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-02" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-02" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-02" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-02" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered">3:00 AM</td>
                        <td class="td_course" id="su-03" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-03" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-03" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-03" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-03" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-03" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-03" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered">4:00 AM</td>
                        <td class="td_course" id="su-04" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-04" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-04" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-04" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-04" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-04" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-04" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered">5:00 AM</td>
                        <td class="td_course" id="su-05" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="mo-05" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="tu-05" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="we-05" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="th-05" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="fr-05" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                        <td class="td_course" id="sa-05" scope="row" style="padding: 1.5rem 0.5rem; position: relative;"></td>
                    </tr>
                    <tr>
                        <td scope="row" style="padding: 1.5rem 0.5rem; position: relative;" class="table-success table-bordered">6:00 AM</td>
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

                // Calculate start of week (Sunday)
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

            function getLastWeek(i) {
                return getWeekDates(-i);
            }

            function getThisWeek() {
                return getWeekDates(0);
            }

            function getNextWeek(i) {
                return getWeekDates(i);
            }

            // Function to get individual day dates from week start
            function getWeekDays(weekStart) {
                const days = [];
                for (let i = 0; i < 7; i++) {
                    const day = new Date(weekStart);
                    day.setDate(weekStart.getDate() + i);
                    days.push(day);
                }
                return days;
            }

            // Function to update date displays
            function updateDateDisplays(week) {
                $('#start_date').html(week.start.toLocaleDateString("en-CA"));
                $('#end_date').html(week.end.toLocaleDateString("en-CA"));

                const days = getWeekDays(week.start);
                const dayIds = ['su', 'mo', 'tu', 'we', 'th', 'fr', 'sa'];

                dayIds.forEach((id, index) => {
                    $('#' + id).html(days[index].toLocaleDateString("en-CA"));
                });
            }

            // Initialize with current week
            var thisWeek = getThisWeek();
            updateDateDisplays(thisWeek);
            callAjaxForCourse();

            // This Week button
            $("#this_week").button().click(function(){
                var thisWeek = getThisWeek();
                updateDateDisplays(thisWeek);

                $('#next_week').data('next', 0);
                $('#last_week').data('last', 0);

                callAjaxForCourse();
            });

            // Next Week button
            $("#next_week").button().click(function(){
                var nummber_next0 = $('#next_week').data('next') || 0;
                var nummber_last0 = $('#last_week').data('last') || 0;
                var nextWeek;

                if(nummber_last0 == 0){
                    nummber_next0 = nummber_next0 + 1;
                    nextWeek = getNextWeek(nummber_next0);
                    $('#next_week').data('next', nummber_next0);
                } else {
                    nummber_last0 = nummber_last0 - 1;
                    $('#last_week').data('last', nummber_last0);

                    if(nummber_last0 == 0){
                        nextWeek = getThisWeek();
                    } else {
                        nextWeek = getLastWeek(nummber_last0);
                    }
                }

                updateDateDisplays(nextWeek);
                callAjaxForCourse();
            });

            // Last Week button
            $("#last_week").button().click(function(){
                var nummber_last1 = $('#last_week').data('last') || 0;
                var nummber_next1 = $('#next_week').data('next') || 0;
                var lastWeek;

                if(nummber_next1 == 0){
                    nummber_last1 = nummber_last1 + 1;
                    lastWeek = getLastWeek(nummber_last1);
                    $('#last_week').data('last', nummber_last1);
                } else {
                    nummber_next1 = nummber_next1 - 1;
                    $('#next_week').data('next', nummber_next1);

                    if(nummber_next1 == 0){
                        lastWeek = getThisWeek();
                    } else {
                        lastWeek = getNextWeek(nummber_next1);
                    }
                }

                updateDateDisplays(lastWeek);
                callAjaxForCourse();
            });

            function callAjaxForCourse() {
                var start_date = $('#start_date').html();
                var end_date = $('#end_date').html();

                // التحقق من وجود التواريخ
                if (!start_date || !end_date) {
                    console.error("Start or end date is missing in display elements.");
                    return;
                }

                $.ajax({
                    url: "{{ route('student.calendar.data') }}",
                    type: 'GET',
                    dataType: 'JSON',
                    // **أضف هذا الجزء لإرسال البيانات**
                    data: {
                        start_date: start_date,
                        end_date: end_date
                    },

                    success: function(result){
                        // ... بقية كود النجاح يبقى كما هو
                        $('.td_course').html(''); // Clear all course cells

                        result.forEach(function(item){
                            var data_startdate = item['startdate'];
                            var data_enddate = item['enddate'];
                            var data_starttime = parseInt(item['starttime'].slice(0, 2));
                            var data_startmi = parseInt(item['starttime'].slice(3, 5));
                            var data_endtime = parseInt(item['endtime'].slice(0, 2));
                            var data_endmi = parseInt(item['endtime'].slice(3, 5));
                            var data_name = item['name'];
                            var data_teacher_name = item['teacher_name'];

                            var days = Object.keys(item['days']).map((key) => [key, item['days'][key]]);

                            days.forEach(function(currentValue, index) {
                                // Cell Date is the date of the displayed day in the calendar header
                                var cellDate = new Date($("#" + currentValue[0]).html());
                                var startDate = new Date(data_startdate);
                                var endDate = new Date(data_enddate);

                                // Check if the cell date falls within the course's start and end dates
                                if(cellDate >= startDate && cellDate <= endDate){
                                    var endhour = data_endtime - data_starttime;
                                    // ... حسابات top و height تبقى كما هي
                                    var top = Math.round((data_startmi * 100) / 60);
                                    var height = (endhour * 100) + Math.round((data_endmi * 100) / 60) - top;

                                    // Format start time with leading zero
                                    var timeSlot = data_starttime < 10 ? '0' + data_starttime : data_starttime;

                                    var div = `
                                        <div style="
                                            position: absolute;
                                            width: 100%;
                                            background-color: #e09707;
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
                                            overflow: hidden;
                                            ">
                                            <span style="color: black">اسم الكورس: </span>${data_name}
                                            <br><span style="color: black">اسم المدرس: </span>${data_teacher_name}
                                        </div>
                                    `;

                                    $('#' + currentValue[0] + '-' + timeSlot).append(div);
                                }
                            });
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading schedule:', error);
                        alert('فشل تحميل التوقيت');
                    }
                });
            }

        });
    </script>
@stop
