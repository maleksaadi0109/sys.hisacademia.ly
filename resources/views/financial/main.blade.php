@extends('financial.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('الرئيسية')}}</h2>
        </header>

        <div class="container">

            <div class="row main-row justify-content-start">
                <a href="{{route('financial.revenues',['orderBy' => 'null','sort' => 'null'])}}" class="a-main-card" ><div class="main-card "><p><i class="fa-solid fa-usd"></p></i>الإيرادات</div></a>
                <a href="{{route('financial.expenses',['orderBy' => 'null','sort' => 'null'])}}" class="a-main-card" ><div class="main-card "><p><i class="fa-solid fa-exchange"></p></i>المصروفات</div></a>
                <a href="{{route('financial.translation_revenues',['orderBy' => 'null','sort' => 'null'])}}"  class="a-main-card" ><div class="main-card "><p><i class="fa-solid fa-usd"></p></i>إيرادات الترجمة</div></a>
                <a href="{{route('financial.teacher_revenue')}}"  class="a-main-card" ><div class="main-card "><p><i class="fa-solid fa-usd"></p></i>إيرادات المعلم</div></a>
                <a href="{{route('financial.diploma.revenues')}}"  class="a-main-card" ><div class="main-card "><p><i class="fa-solid fa-usd"></p></i>إيرادات الدبلوم</div></a>
            </div>
        </div>
        
    </section>
@stop