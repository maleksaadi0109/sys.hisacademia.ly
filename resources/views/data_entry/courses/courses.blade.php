@extends('data_entry.dashboard')
@section('content')
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">{{__('إدارة الكورسات')}}</h2>
    </header>

    <div class="container">
        <div class="row table-responsive">
            <table class="table table-light table-hover text-center">
                <thead>
                    <tr>
                        <th scope="col"><a href="{{route('data_entry.courses',['orderBy' => 'id','sort' => 'asc'])}}"># <i class="fas fa-sort text-right"></a></th>
                        <th scope="col"><a href="{{route('data_entry.courses',['orderBy' => 'name','sort' => 'asc'])}}">اسم الكورس <i class="fas fa-sort text-right"></a></th>
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
                        <th scope="col">تعديل</th>
                        <th scope="col">حذف</th>
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
                        <td scope="row"><a class="me-2 btn btn-lg edit-button" data-mdb-ripple-init="" href="{{route('data_entry.edit.course',['id' => $course->id])}}"><i class="far fa-edit"></i></a></td>
                        <td><a class="btn btn-sm delete-button " data-mdb-ripple-init="" href="{{route('data_entry.course.destroy',['id' => $course->id])}}"
                            onclick="event.preventDefault();document.getElementById('delete-form-{{ $course->id }}').submit();">
                            <i class="far fa-trash-alt"></i></a></td>

                        <form id="delete-form-{{ $course->id }}" action="{{route('data_entry.course.destroy',['id' => $course->id])}}"
                            method="post" style="display: none;">
                            @method('delete')
                            @csrf
                        </form> 
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $courses->links() }}
        </div>
    </div>
</section>

@stop