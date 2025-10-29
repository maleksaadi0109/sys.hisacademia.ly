@extends('admin.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('الرئيسية')}}</h2>
        </header>

        <div class="container">
            <div class="row main-row justify-content-between">
                <a href="{{route('users',['orderBy' => 'null','sort' => 'null'])}}" class="a-main-card" ><div class="main-card "><p><i class="fa-solid fa-users-cog"></p></i>إدارة المستخدمين</div></a>
                <a href="{{route('students',['orderBy' => 'null','sort' => 'null'])}}" class="a-main-card" ><div class="main-card "><p><i class="fa-solid fa-graduation-cap"></p></i>إدارة بيانات الدراسة للطالب</div></a>
                <a href="{{route('courses',['orderBy' => 'null','sort' => 'null'])}}" class="a-main-card" ><div class="main-card "><p><i class="fa-solid fa-book"></p></i>إدارة الكورسات</div></a>
            </div>

            <div class="row main-row justify-content-between">
                <a href="{{route('translation_revenues',['orderBy' => 'null','sort' => 'null'])}}"  class="a-main-card" ><div class="main-card "><p><i class="fa-solid fa-usd"></p></i>إيرادات الترجمة</div></a>
                <a href="{{route('teacher_revenue')}}"  class="a-main-card" ><div class="main-card "><p><i class="fa-solid fa-usd"></p></i>إيرادات المعلم</div></a>
                <a href="{{route('translation_deals',['orderBy' => 'null','sort' => 'null'])}}" class="a-main-card" ><div class="main-card "><p><i class="fa-solid fa-book"></p></i>إدارة المعاملات</div></a>
            </div>

            <div class="row main-row justify-content-between">
                <a href="{{route("diploma.course")}}"  class="a-main-card" ><div class="main-card "><p><i class="fa-solid fa-book"></p></i>إدارة الدبلومات</div></a>
                <a href="{{route('diploma.revenues')}}"  class="a-main-card" ><div class="main-card "><p><i class="fa-solid fa-usd"></p></i>إيرادات الدبلوم</div></a>
                <a href="{{route('admin.user_requests')}}"  class="a-main-card" ><div class="main-card "><p><i class="fa-solid fa-users"></p></i>نشاط المستخدمين</div></a>
            </div>

            <div class="row main-row justify-content-between">
                <a href="{{route('revenues',['orderBy' => 'null','sort' => 'null'])}}"  class="a-main-card" ><div class="main-card "><p><i class="fa-solid fa-usd"></p></i>إيرادات الكورسات</div></a>
                <a href="{{route('expenses',['orderBy' => 'null','sort' => 'null'])}}" class="a-main-card" ><div class="main-card "><p><i class="fa-solid fa-exchange"></p></i>المصروفات</div></a>
                <a href="{{route('admin.coupons.index')}}" class="a-main-card" ><div class="main-card "><p><i class="fa-solid fa-ticket"></p></i>إدارة الكوبونات</div></a>
            </div>
            
        </div>
        
    </section>
@stop