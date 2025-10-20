@extends('teacher.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('الرئيسية')}}</h2>
        </header>

        <div class="container">

            <div class="row main-row justify-content-start">
                <a href="{{route('teacher.courses')}}"  class="a-main-card" ><div class="main-card "><p><i class="fa-solid fa-usd"></p></i>الكورسات</div></a>
                <a href="{{route('teacher.teacher.calendar')}}"  class="a-main-card" ><div class="main-card "><p><i class="fa-solid fa-exchange"></p></i>الروزنامة</div></a>
            </div>
        </div>
        
    </section>
@stop