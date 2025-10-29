@extends('data_entry.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('الرئيسية')}}</h2>
        </header>

        <div class="container">
            <div class="row main-row justify-content-start">
                <a href="{{route('data_entry.students',['orderBy' => 'null','sort' => 'null'])}}" class="a-main-card" ><div class="main-card "><p><i class="fa-solid fa-graduation-cap"></p></i>إدارة بيانات الدراسة للطالب</div></a>
                <a href="{{route('data_entry.courses',['orderBy' => 'null','sort' => 'null'])}}" class="a-main-card" ><div class="main-card "><p><i class="fa-solid fa-book"></p></i>إدارة الكورسات</div></a>
                <a href="{{route("data_entry.buy.course")}}" class="a-main-card" ><div class="main-card "><p><i class="fa-solid fa-book"></p></i>حجز كورس للطالب</div></a>
            </div>

            <div class="row main-row justify-content-start">
                <a href="{{route('data_entry.translation_deals',['orderBy' => 'null','sort' => 'null'])}}" class="a-main-card" ><div class="main-card "><p><i class="fa-solid fa-book"></p></i>إدارة المعاملات</div></a>
                <a href="{{route("data_entry.diploma.course")}}"  class="a-main-card" ><div class="main-card "><p><i class="fa-solid fa-book"></p></i>إدارة الدبلومات</div></a>
                <a href="{{route("data_entry.booking")}}"  class="a-main-card" ><div class="main-card "><p><i class="fa-solid fa-building"></p></i>حجز مساحة عمل</div></a>
            </div>

            <div class="row main-row justify-content-start">
                <a href="{{route("data_entry.reports")}}"  class="a-main-card" ><div class="main-card "><p><i class="fa-solid fa-file-lines"></p></i>التقارير</div></a>
            </div>
        </div>
        
    </section>
@stop