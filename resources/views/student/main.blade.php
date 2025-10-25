@extends('student.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900" style="margin-top: 20px">{{__('الرئيسية')}}</h2>
        </header>

        <div class="container">

            <div class="row main-row justify-content-start">
                <a href="{{route('student.courses')}}" class="a-main-card" >
                    <div class="card-super main-card">
                        <div class="card-contents">
                            <i class="fa-solid fa-graduation-cap"></i>
                            <p>الكورسات</p>
                        </div>
                    </div>
                </a>
                <a href="{{route('student.student.calendar')}}" class="a-main-card" >
                    <div class="card-super main-card">
                        <div class="card-contents">
                            <i class="fa-solid fa-calendar"></i>
                            <p>الروزنامة</p>
                        </div>
                    </div>
                </a>
                <!-- <a href="" class="a-main-card" ><div class="main-card "><p><i class="fa-solid fa-exchange"></p></i>الروزنامة</div></a> -->
            </div>
        </div>
        
    </section>
@stop