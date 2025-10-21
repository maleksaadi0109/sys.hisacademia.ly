@extends('financial.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('تقرير الكورسات')}}</h2>
        </header>

        <div class="container">
            <div class="row">

                    <form method="POST" enctype="multipart/form-data" action="{{ route('financial.generate.course.report') }}">
                        @csrf
                        <div class="row">

                            <div class="mt-0 col-lg-6">
                                <label for="from_date" class="block font-medium text-sm text-gray-700">يبدأ من التاريخ</label>
                                <input type="date"  value="{{request('from_date')}}" name="from_date" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-s" />
                            </div>

                            <div class="mt-0 col-lg-6">
                                <label for="to_date" class="block font-medium text-sm text-gray-700">ينتهي في التاريخ</label>
                                <input type="date"   value="{{request('to_date')}}" name="to_date" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-s" />
                            </div>

                            <div class="row">
                                <x-primary-button class="mt-4 col-lg-2 justify-content-center">
                                    {{ __('عرض التقرير') }}
                                </x-primary-button>
                            </div>

                        </div>
                    </form>
                    @php 
                        use App\Enums\WeekDays; 
                    @endphp
                    @if($courses)
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
                                            <th scope="col">سعر الكورس</th>
                                        </tr>
                                    </thead>
                                    <tbody>
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
                                            <td scope="row">{{ $course->price }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $courses->links() }}
                            </div>
                        </div>
                    @endif

            </div>
        </div>
    </section>
@stop