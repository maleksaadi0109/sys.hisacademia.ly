@extends('admin.dashboard')
@section('content')
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">{{__('إدارة الدبلومات')}}</h2>
    </header>
    @php 
        use App\Enums\WeekDays; 
    @endphp

    <div class="container">

        <div class="row mb-5">
            <form method="GET" enctype="multipart/form-data" action="{{ route('diploma.course') }}">
                @csrf
                <div class="mt-5 col-lg-6">
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="inputGroupSelect04">اسم الدبلوم</label>
                        <select class="form-select" id="inputGroupSelect04" name="diploma_id" required>
                            <option selected disabled>أختر....</option>
                            @foreach($allDiploma as  $diploma)
                                @if(old('diploma_id') == $diploma->id)
                                    <option selected value="{{$diploma->id}}">{{$diploma->name}}</option>
                                @else
                                    <option value="{{$diploma->id}}">{{$diploma->name}}</option>
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
                
                <div class="row">
                    <x-primary-button class="mt-4 col-lg-2 justify-content-center">
                        {{ __('عرض الكورسات') }}
                    </x-primary-button>
                </div>
            </form>
        </div>

        @if($diplomaCourse)
        <div class="row col-12">
            <div class="col-4">
                <h5>تعديل بيانات الدبلوم
                    <a class="me-2 btn btn-lg edit-button" data-mdb-ripple-init="" href="{{route('edit.diploma',['id' => $diplomaCourse[0]->diploma_id])}}"><i class="far fa-edit"></i></a>
                </h5>
            </div>
            <div class="col-8">
                <h5 class="d-inline">حذف هذا الدبلوم</h5>
                <a class="btn btn-sm delete-button " data-mdb-ripple-init="" href="{{route('diploma.destroy',['id' => $diplomaCourse[0]->diploma_id])}}"
                    onclick="event.preventDefault();document.getElementById('delete-form-{{ $diplomaCourse[0]->diploma_id }}').submit();">
                    <i class="far fa-trash-alt"></i></a>

                <form id="delete-form-{{ $diplomaCourse[0]->diploma_id }}" action="{{route('diploma.destroy',['id' => $diplomaCourse[0]->diploma_id])}}"
                    method="post" style="display: none;">
                    @method('delete')
                    @csrf
                </form> 
            </div>
        </div>
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

                        @foreach ($diplomaCourse as $course) 
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
        @endif
    </div>

</section>

@stop