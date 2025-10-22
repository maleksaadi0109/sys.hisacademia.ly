@php
use App\Enums\UserType;
use App\Enums\EnumPermission;
@endphp
<x-app-layout>
    <x-slot name="header">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href=""><img class="header-logo" src="{{asset('logo.jpg')}}">الأكاديمية الإسبانية الليبية</a>

        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="عرض/إخفاء لوحة التنقل">
            <span class="navbar-toggler-icon"></span>
        </button>


        <div class="navbar-nav">
            <div class="nav-item text-nowrap d-flex">
                <a class="px-3 username-nav"><i class="fa fa-user"> </i>{{ Auth::user()->name . ' (' . UserType::userTypeAr()[Auth::user()->user_type] .')'}}</a>
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
                        <a class="nav-link active" href="/">
                            <svg width="24" height="24" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M8 0L0 6V8H1V15H4V10H7V15H15V8H16V6L14 4.5V1H11V2.25L8 0ZM9 10H12V13H9V10Z" fill="#adb5bd"></path> </g></svg>
                            {{ __('لوحة التحكم') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <svg height="24px" width="24px" version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve" fill="#adb5bd"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <style type="text/css"> .st0{fill:#adb5bd;} </style> <g> <path class="st0" d="M505.837,180.418L279.265,76.124c-7.349-3.385-15.177-5.093-23.265-5.093c-8.088,0-15.914,1.708-23.265,5.093 L6.163,180.418C2.418,182.149,0,185.922,0,190.045s2.418,7.896,6.163,9.627l226.572,104.294c7.349,3.385,15.177,5.101,23.265,5.101 c8.088,0,15.916-1.716,23.267-5.101l178.812-82.306v82.881c-7.096,0.8-12.63,6.84-12.63,14.138c0,6.359,4.208,11.864,10.206,13.618 l-12.092,79.791h55.676l-12.09-79.791c5.996-1.754,10.204-7.259,10.204-13.618c0-7.298-5.534-13.338-12.63-14.138v-95.148 l21.116-9.721c3.744-1.731,6.163-5.504,6.163-9.627S509.582,182.149,505.837,180.418z"></path> <path class="st0" d="M256,346.831c-11.246,0-22.143-2.391-32.386-7.104L112.793,288.71v101.638 c0,22.314,67.426,50.621,143.207,50.621c75.782,0,143.209-28.308,143.209-50.621V288.71l-110.827,51.017 C278.145,344.44,267.25,346.831,256,346.831z"></path> </g> </g></svg>
                            {{ __('بيانات الطالب')}}
                        </a>
                        <div class="collapse div-collapse" id="collapse-1" style="">
                            <ul class="nav flex-column ul-collapse">
                                <li class="li-collapse"><a href="{{route("data_entry.add.student")}}" class="link-white d-inline-flex text-decoration-none rounded">إضافة بيانات الطالب</a></li>
                                <li class="li-collapse"><a href="{{route('data_entry.students',['orderBy' => 'null','sort' => 'null'])}}" class="link-white d-inline-flex text-decoration-none rounded">إدارة بيانات الدراسة للطالب</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                        <svg fill="#adb5bd" height="24px" width="24px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 511.999 511.999" xml:space="preserve" stroke="#adb5bd"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M302.195,11.833H13.049C5.842,11.833,0,17.675,0,24.882v214.289c0,7.207,5.842,13.049,13.049,13.049h283.839 l-34.352-21.329c-2.247-1.396-4.282-3.002-6.109-4.768H26.097V37.93h263.049v126.703c4.01,0.847,7.943,2.39,11.625,4.677 l14.473,8.986V24.882C315.244,17.675,309.402,11.833,302.195,11.833z"></path> </g> </g> <g> <g> <path d="M216.857,134.337c-4.352-3.43-10.665-2.685-14.097,1.668c-3.432,4.353-2.686,10.665,1.668,14.097l44.279,34.914 c0.63-1.371,1.34-2.719,2.156-4.034c2.883-4.643,6.649-8.401,10.94-11.206L216.857,134.337z"></path> </g> </g> <g> <g> <circle cx="419.71" cy="81.405" r="37.557"></circle> </g> </g> <g> <g> <path d="M511.33,173.609c-0.118-23.528-19.355-42.67-42.884-42.67H450.26c-17.831,46.242-11.329,29.381-22.507,58.37l4.709-23.724 c0.346-1.744,0.067-3.555-0.79-5.113l-7.381-13.424l6.551-11.914c0.454-0.826,0.438-1.829-0.041-2.64 c-0.479-0.811-1.352-1.308-2.293-1.308h-17.96c-0.942,0-1.813,0.497-2.293,1.308s-0.495,1.815-0.041,2.64l6.537,11.889 l-7.367,13.4c-0.873,1.589-1.147,3.438-0.77,5.211l5.438,23.675c-3.119-8.087-21.08-52.728-23.255-58.37h-17.83 c-23.529,0-42.766,19.141-42.884,42.67c-0.098,19.565-0.016,3.179-0.17,33.884l-36.702-22.787 c-8.501-5.28-19.674-2.667-24.953,5.836c-5.279,8.503-2.666,19.675,5.836,24.954l64.219,39.873 c12.028,7.47,27.609-1.167,27.68-15.304c0.036-7.091,0.292-57.809,0.334-66.275c0.013-2.092,1.714-3.776,3.805-3.769 c2.089,0.007,3.779,1.703,3.779,3.794c-0.018,87.323-0.394,111.735-0.394,304.606c0,12.01,9.736,21.746,21.746,21.746 s21.746-9.736,21.746-21.746V304.604h9.388v173.817c0,12.01,9.736,21.746,21.746,21.746s21.746-9.736,21.746-21.746l0.008-304.612 c0-1.981,1.604-3.589,3.586-3.595c1.981-0.006,3.595,1.594,3.605,3.577l0.669,133.132c0.05,9.977,8.154,18.03,18.119,18.03 c0.031,0,0.062,0,0.094,0c10.007-0.05,18.081-8.205,18.03-18.212L511.33,173.609z"></path> </g> </g> </g></svg>
                        {{ __('بيانات المعلم')}}
                        </a>
                        <div class="collapse div-collapse" id="collapse-1" style="">
                            <ul class="nav flex-column ul-collapse">
                                <li class="li-collapse"><a href="{{route("data_entry.add.teacher")}}" class="link-white d-inline-flex text-decoration-none rounded">إضافة معلم</a></li>
                                <li class="li-collapse"><a href="{{route("data_entry.teachers",['orderBy' => 'null','sort' => 'null'])}}" class="link-white d-inline-flex text-decoration-none rounded">إدارة المعلمين</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <svg width="24" height="24" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg" fill="#adb5bd"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill="none" stroke="#adb5bd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M1 2h16v11H1z"></path> <path fill="none" stroke="#adb5bd" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M4 5.5v5s3-1 5 0v-5s-2-2-5 0zM9 5.5v5s3-1 5 0v-5s-2-2-5 0z"></path> <path fill="#adb5bd" stroke="#adb5bd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M8.5 14l-3 3h7l-3-3z"></path> </g></svg>
                            {{__('الكورسات')}}
                        </a>
                        <div class="collapse div-collapse" id="collapse-1" style="">
                            <ul class="nav flex-column ul-collapse">
                                <li class="li-collapse"><a href="{{route("data_entry.add.course")}}" class="link-white d-inline-flex text-decoration-none rounded">إضافة كورس</a></li>
                                <li class="li-collapse"><a href="{{route('data_entry.courses',['orderBy' => 'null','sort' => 'null'])}}" class="link-white d-inline-flex text-decoration-none rounded">إدارة الكورسات</a></li>
                                <li class="li-collapse"><a href="{{route("data_entry.buy.course")}}" class="link-white d-inline-flex text-decoration-none rounded">حجز كورس للطالب</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <circle cx="12" cy="16" r="3" stroke="#adb5bd" stroke-width="1.5"></circle> <path d="M12 19.2599L9.73713 21.4293C9.41306 21.74 9.25102 21.8953 9.1138 21.9491C8.80111 22.0716 8.45425 21.9667 8.28977 21.7C8.21758 21.583 8.19509 21.3719 8.1501 20.9496C8.1247 20.7113 8.112 20.5921 8.07345 20.4922C7.98715 20.2687 7.80579 20.0948 7.57266 20.0121C7.46853 19.9751 7.3442 19.963 7.09553 19.9386C6.65512 19.8955 6.43491 19.8739 6.31283 19.8047C6.03463 19.647 5.92529 19.3145 6.05306 19.0147C6.10913 18.8832 6.27116 18.7278 6.59523 18.4171L8.07345 16.9999L9.1138 15.9596" stroke="#adb5bd" stroke-width="1.5"></path> <path d="M12 19.2599L14.2629 21.4294C14.5869 21.7401 14.749 21.8954 14.8862 21.9492C15.1989 22.0717 15.5457 21.9668 15.7102 21.7001C15.7824 21.5831 15.8049 21.372 15.8499 20.9497C15.8753 20.7113 15.888 20.5921 15.9265 20.4923C16.0129 20.2688 16.1942 20.0949 16.4273 20.0122C16.5315 19.9752 16.6558 19.9631 16.9045 19.9387C17.3449 19.8956 17.5651 19.874 17.6872 19.8048C17.9654 19.6471 18.0747 19.3146 17.9469 19.0148C17.8909 18.8832 17.7288 18.7279 17.4048 18.4172L15.9265 17L15 16.0735" stroke="#adb5bd" stroke-width="1.5"></path> <path d="M17.3197 17.9957C19.2921 17.9748 20.3915 17.8512 21.1213 17.1213C22 16.2426 22 14.8284 22 12V9M7 17.9983C4.82497 17.9862 3.64706 17.8897 2.87868 17.1213C2 16.2426 2 14.8284 2 12L2 8C2 5.17157 2 3.75736 2.87868 2.87868C3.75736 2 5.17157 2 8 2L16 2C18.8284 2 20.2426 2 21.1213 2.87868C21.6112 3.36857 21.828 4.02491 21.9239 5" stroke="#adb5bd" stroke-width="1.5" stroke-linecap="round"></path> <path d="M9 6L15 6" stroke="#adb5bd" stroke-width="1.5" stroke-linecap="round"></path> <path d="M7 9.5H9M17 9.5H12.5" stroke="#adb5bd" stroke-width="1.5" stroke-linecap="round"></path> </g></svg>
                            {{__('الدبلومات')}}
                        </a>
                        <div class="collapse div-collapse" id="collapse-1" style="">
                            <ul class="nav flex-column ul-collapse">
                                <li class="li-collapse"><a href="{{route("data_entry.add.diploma")}}" class="link-white d-inline-flex text-decoration-none rounded">إضافة دبلوم</a></li>
                                <li class="li-collapse"><a href="{{route("data_entry.diploma.course")}}" class="link-white d-inline-flex text-decoration-none rounded">إدارة الدبلومات</a></li>
                                <li class="li-collapse"><a href="{{route("data_entry.buy.diploma")}}" class="link-white d-inline-flex text-decoration-none rounded">حجز دبلوم للطالب</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <svg width="24" height="24"  viewBox="0 0 48 48" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" fill="#adb5bd"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <defs> <style> .cls-1 { fill: none; stroke:#adb5bd; stroke-linecap: round; stroke-linejoin: round; } </style> </defs> <line class="cls-1" x1="12.62" y1="24.31" x2="17.94" y2="11.42"></line> <line class="cls-1" x1="23.04" y1="24.35" x2="17.94" y2="11.42"></line> <line class="cls-1" x1="21.34" y1="20.02" x2="14.39" y2="20.02"></line> <g> <line class="cls-1" x1="32.63" y1="25.38" x2="39.35" y2="25.38"></line> <path class="cls-1" d="M35.68,25.38c0,4.34-5.29,11.51-10.59,12.61"></path> <path class="cls-1" d="M27.93,32.79c2.13,2.4,5.61,4.74,8.82,5.2"></path> </g> <rect class="cls-1" x="5.5" y="5.5" width="24.67" height="24.67" rx="3.64" ry="3.64"></rect> <path class="cls-1" d="M17.83,30.17v8.69c0,2,1.64,3.64,3.64,3.64h17.38c2,0,3.64-1.64,3.64-3.64V21.47c0-2-1.64-3.64-3.64-3.64h-8.69"></path> </g></svg>
                            {{__('الترجمة')}}
                        </a>
                        <div class="collapse div-collapse" id="collapse-1" style="">
                            <ul class="nav flex-column ul-collapse">
                                <li class="li-collapse"><a href="{{route("data_entry.add.customer")}}" class="link-white d-inline-flex text-decoration-none rounded">إضافة زبون</a></li>
                                <li class="li-collapse"><a href="{{route('data_entry.customers',['orderBy' => 'null','sort' => 'null'])}}" class="link-white d-inline-flex text-decoration-none rounded">إدارة الزبائن</a></li>
                                <li class="li-collapse"><a href="{{route("data_entry.add.translation_deal")}}" class="link-white d-inline-flex text-decoration-none rounded">إضافة معاملة</a></li>
                                <li class="li-collapse"><a href="{{route('data_entry.translation_deals',['orderBy' => 'null','sort' => 'null'])}}" class="link-white d-inline-flex text-decoration-none rounded">إدارة المعاملات</a></li>
                            </ul>
                        </div>
                    </li>

                    @if(in_array(EnumPermission::view_report,json_decode(Auth::user()->permission)))
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <svg width="24" height="24" fill="#adb5bd" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>report</title> <path d="M6 11h4v17h-4v-17zM22 16v12h4v-12h-4zM14 28h4v-24h-4v24z"></path> </g></svg>
                            {{__('التقارير')}}
                        </a>
                        <div class="collapse div-collapse" id="collapse-1" style="">
                            <ul class="nav flex-column ul-collapse">
                                <li class="li-collapse"><a href="{{route('data_entry.general.monthly.report')}}" class="link-white d-inline-flex text-decoration-none rounded">التقرير الشهري الشامل</a></li>
                                <li class="li-collapse"><a href="{{route('data_entry.course.report')}}" class="link-white d-inline-flex text-decoration-none rounded">تقرير الكورسات</a></li>
                                <li class="li-collapse"><a href="{{route('data_entry.translation.report')}}" class="link-white d-inline-flex text-decoration-none rounded">تقرير الترجمة</a></li>
                            </ul>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
        </nav>
    </x-slot>

    <x-slot name="title"><h1>{{__('أكاديمية متخصصة في تدريس اللغات والترجمة')}}</h1></x-slot>

    @yield('content')

</x-app-layout>
