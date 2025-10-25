@extends('admin.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900" style="margin-top: 20px">{{__('الرئيسية')}}</h2>
        </header>

        <div class="container">
            <div class="row main-row">
                <a href="{{route('users',['orderBy' => 'null','sort' => 'null'])}}" class="a-main-card" >
                    <div class="card-super main-card less-width">
                        <div class="card-contents">
                            <i class="fa-solid fa-users-cog"></i>
                            <p>إدارة المستخدمين</p>
                        </div>
                    </div>
                </a>
                <a href="{{route('students',['orderBy' => 'null','sort' => 'null'])}}" class="a-main-card" >
                    <div class="card-super main-card less-width">
                        <div class="card-contents">
                            <i class="fa-solid fa-graduation-cap"></i>
                            <p>إدارة بيانات الدراسة للطالب</p>
                        </div>
                    </div>
                </a>
                <a href="{{route('admin.user_requests')}}"  class="a-main-card" >
                    <div class="card-super main-card less-width">
                        <div class="card-contents">
                            <i class="fa-solid fa-users"></i>
                            <p>نشاط المستخدمين</p>
                        </div>
                    </div>
                </a>
                <a href="{{route('courses',['orderBy' => 'null','sort' => 'null'])}}" class="a-main-card" >
                    <div class="card-super main-card less-width">
                        <div class="card-contents">
                            <i class="fa-solid fa-book"></i>
                            <p>إدارة الكورسات</p>
                        </div>
                    </div>
                </a>
                <a href="{{route('translation_deals',['orderBy' => 'null','sort' => 'null'])}}" class="a-main-card" >
                    <div class="card-super main-card less-width">
                        <div class="card-contents">
                            <i class="fa-solid fa-book"></i>
                            <p>إدارة المعاملات</p>
                        </div>
                    </div>
                </a>
                <a href="{{route("diploma.course")}}"  class="a-main-card" >
                    <div class="card-super main-card less-width">
                        <div class="card-contents">
                            <i class="fa-solid fa-book"></i>
                            <p>إدارة الدبلومات</p>
                        </div>
                    </div>
                </a>
                <a href="{{route('translation_revenues',['orderBy' => 'null','sort' => 'null'])}}"  class="a-main-card" >
                    <div class="card-super main-card less-width">
                        <div class="card-contents">
                            <i class="fa-solid fa-usd"></i>
                            <p>إيرادات الترجمة</p>
                        </div>
                    </div>
                </a>
                <a href="{{route('teacher_revenue')}}"  class="a-main-card" >
                    <div class="card-super main-card less-width">
                        <div class="card-contents">
                            <i class="fa-solid fa-usd"></i>
                            <p>إيرادات المعلم</p>
                        </div>
                    </div>
                </a>
                <a href="{{route('diploma.revenues')}}"  class="a-main-card" >
                    <div class="card-super main-card less-width">
                        <div class="card-contents">
                            <i class="fa-solid fa-usd"></i>
                            <p>إيرادات الدبلوم</p>
                        </div>
                    </div>
                </a>
                <a href="{{route('revenues',['orderBy' => 'null','sort' => 'null'])}}"  class="a-main-card" >
                    <div class="card-super main-card less-width">
                        <div class="card-contents">
                            <i class="fa-solid fa-usd"></i>
                            <p>إيرادات الكورسات</p>
                        </div>
                    </div>
                </a>
                <a href="{{route('expenses',['orderBy' => 'null','sort' => 'null'])}}" class="a-main-card" >
                    <div class="card-super main-card less-width">
                        <div class="card-contents">
                            <i class="fa-solid fa-exchange"></i>
                            <p>المصروفات</p>
                        </div>
                    </div>
                </a>
            </div>
            
        </div>
        
    </section>
@stop