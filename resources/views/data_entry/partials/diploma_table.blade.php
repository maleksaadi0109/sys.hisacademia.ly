@php
    use App\Enums\WeekDays;
@endphp

<div class="row table-responsive">
    <table class="table table-light table-hover text-center">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">{{__('اسم الكورس')}}</th>
            <th scope="col">{{__('القسم')}}</th>
            <th scope="col">{{__('تاريخ البداية')}}</th>
            <th scope="col">{{__('تاريخ النهاية')}}</th>
            <th scope="col">{{__('المستوى')}}</th>
            <th scope="col">{{__('توقيت البداية')}}</th>
            <th scope="col">{{__('توقيت النهاية')}}</th>
            <th scope="col">{{__('المدة الاجمالية')}}</th>
            <th scope="col">{{__('المعدل اليومي للساعات')}}</th>
            <th scope="col">{{__('اجمالي عدد الساعات')}}</th>
            <th scope="col">{{__('عدد أيام الدراسة بالأسبوع')}}</th>
            <th scope="col">{{__('أيام الدراسة')}}</th>
            <th scope="col">{{__('اسم المدرس')}}</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($diplomaCourse as $course)
            <tr>
                <td>{{ $course->id }}</td>
                <td>{{ $course->name }}</td>
                <td>{{ $course->section }}</td>
                <td>{{ $course->start_date }}</td>
                <td>{{ $course->end_date }}</td>
                <td>{{ $course->level }}</td>
                <td>{{ date("h:i A", strtotime($course->start_time)) }}</td>
                <td>{{ date("h:i A", strtotime($course->end_time)) }}</td>
                <td>{{ $course->total_days }}</td>
                <td>{{ $course->average_hours }}</td>
                <td>{{ $course->total_hours }}</td>
                <td>{{ $course->n_d_per_week }}</td>
                <td>
                    <div class="div_days_table">
                        @foreach(json_decode($course->days) as $value)
                            <span class="days_table">{{WeekDays::WeekDaysAr()[$value]}}</span>
                        @endforeach
                    </div>
                </td>
                <td>{{ $course->user->name }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="14" class="text-center py-4">
                    {{__('لا توجد بيانات تطابق بحثك.')}}
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
