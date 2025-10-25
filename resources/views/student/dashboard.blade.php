@php
use App\Enums\UserType;
@endphp
<x-app-layout>
    <x-slot name="header">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="/student/dashboard" style="padding: 15px 0; font-weight: normal; font-size: 19px;"><img class="header-logo" src="{{asset('logo-small.png')}}" style="width: 50px; height: 50px;">الأكاديمية الإسبانية الليبية</a>

        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="عرض/إخفاء لوحة التنقل">
            <span class="navbar-toggler-icon"></span>
        </button>


        <div class="navbar-nav" style="align-items: center; margin-left: 20px;">
            <div class="nav-item text-nowrap d-flex" style="margin: 0 !important;">
                <a class="px-3 username-nav"><i class="fa fa-user"> </i>{{ Auth::user()->name }}</a>
                <form method="POST" action="{{ route('logout') }}">
                        @csrf
                    <a class="nav-link px-3" href="{{route('logout')}}" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('تسجيل الخروج') }}</a>
                </form>
            </div>
        </div>
    </x-slot>

    <x-slot name="nav">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse" style="">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="/student/dashboard">
                            <div class="nav-content">
                                <i class="fa-solid fa-house color-white" style="margin-left: 8px;"></i>
                                {{ __('لوحة التحكم') }}
                            </div>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <div class="nav-content">
                                <i class="fa-solid fa-user" style="margin-left: 8px;"></i>
                                {{__('الطالب')}}
                            </div>
                        </a>
                        <div class="collapse div-collapse" id="collapse-1" style="">
                            <ul class="nav flex-column ul-collapse">
                                <li class="li-collapse" style="margin: 5px 0; border-radius: 6px; transition: all 0.3s ease-in-out; padding: 4px 10px 4px 0;" ><a style="color: white !important;" href="{{route('student.courses')}}" class="link-white d-inline-flex text-decoration-none rounded">الكورسات</a></li>
                                <li class="li-collapse" style="margin: 5px 0; border-radius: 6px; transition: all 0.3s ease-in-out; padding: 4px 10px 4px 0;" ><a style="color: white !important;" href="{{route('student.student.calendar')}}" class="link-white d-inline-flex text-decoration-none rounded">الروزنامة</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </x-slot>

    @yield('content')

</x-app-layout>
