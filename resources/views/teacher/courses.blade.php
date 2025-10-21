@extends('teacher.dashboard')
@section('content')
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">{{__(' كورسات المدرس')}}</h2>
    </header>

    <div class="container">
        <div class="row table-responsive">
            <table class="table table-light table-hover text-center">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">اسم الكورس</th>
                        <th scope="col">القسم</th>                              
                        <th scope="col">تاريخ البداية</th>
                        <th scope="col">تاريخ النهاية</th>
                        <th scope="col">المستوى</th>
                        <th scope="col">توقيت البداية</th>
                        <th scope="col">توقيت النهاية</th>
                        <th scope="col">المدة الاجمالية</th>
                        <th scope="col">المعدل اليومي للساعات</th>
                        <th scope="col">اجمالي عدد الساعات</th>
                        <th scope="col">عدد أيام الدراسة بالأسبوع</th>
                        <th scope="col">أيام الدراسة </th>
                        <th scope="col">اسم المدرس</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        use App\Enums\WeekDays; 
                    @endphp
                    @foreach ($courses as $course) 
                    <tr>
                        <td scope="row">{{ $course->id }}</td>
                        <td scope="row">{{ $course->name }}</td>
                        <td scope="row">{{ $course->section }}</td>
                        <td scope="row">{{ $course->start_date }}</td>
                        <td scope="row">{{ $course->end_date }}</td>
                        <td scope="row">{{ $course->level }}</td>
                        <td scope="row">{{ date("h:i A", strtotime($course->start_time)) }}</td>
                        <td scope="row">{{ date("h:i A", strtotime($course->end_time)) }}</td>
                        <td scope="row">{{ $course->total_days }}</td>
                        <td scope="row">{{ $course->average_hours }}</td>
                        <td scope="row">{{ $course->total_hours }}</td>
                        <td scope="row">{{ $course->n_d_per_week }}</td>
                        <td scope="row">
                        <div class="div_days_table">
                            @foreach(json_decode($course->days) as $value)
                            <span class="days_table">{{WeekDays::WeekDaysAr()[$value]}}</span>
                            @endforeach
                        </div>
                        </td>
                        <td scope="row">{{ $course->user->name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

@stop