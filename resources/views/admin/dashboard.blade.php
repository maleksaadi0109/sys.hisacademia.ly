@php
use App\Enums\UserType;
@endphp
<x-app-layout>
    <x-slot name="header">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="/"><img class="header-logo" src="{{asset('logo-small.png')}}">الأكاديمية الإسبانية الليبية</a>

        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="عرض/إخفاء لوحة التنقل">
            <span class="navbar-toggler-icon"></span>
        </button>


        <div class="navbar-nav">
            <div class="nav-item text-nowrap d-flex">
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
                        <a class="nav-link active" href="/">
                            <svg width="24" height="24" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M8 0L0 6V8H1V15H4V10H7V15H15V8H16V6L14 4.5V1H11V2.25L8 0ZM9 10H12V13H9V10Z" fill="#adb5bd"></path> </g></svg>
                            {{ __('لوحة التحكم') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M1.5 6.5C1.5 3.46243 3.96243 1 7 1C10.0376 1 12.5 3.46243 12.5 6.5C12.5 9.53757 10.0376 12 7 12C3.96243 12 1.5 9.53757 1.5 6.5Z" fill="#adb5bd"></path> <path d="M14.4999 6.5C14.4999 8.00034 14.0593 9.39779 13.3005 10.57C14.2774 11.4585 15.5754 12 16.9999 12C20.0375 12 22.4999 9.53757 22.4999 6.5C22.4999 3.46243 20.0375 1 16.9999 1C15.5754 1 14.2774 1.54153 13.3005 2.42996C14.0593 3.60221 14.4999 4.99966 14.4999 6.5Z" fill="#adb5bd"></path> <path d="M0 18C0 15.7909 1.79086 14 4 14H10C12.2091 14 14 15.7909 14 18V22C14 22.5523 13.5523 23 13 23H1C0.447716 23 0 22.5523 0 22V18Z" fill="#adb5bd"></path> <path d="M16 18V23H23C23.5522 23 24 22.5523 24 22V18C24 15.7909 22.2091 14 20 14H14.4722C15.4222 15.0615 16 16.4633 16 18Z" fill="#adb5bd"></path> </g></svg>
                            {{ __('المستخدمين') }}
                        </a>
                        <div class="collapse div-collapse" id="collapse-1" style="">
                            <ul class="nav flex-column ul-collapse">
                                <li class="li-collapse"><a href="{{route("add.user")}}" class="link-white d-inline-flex text-decoration-none rounded">إضافة مستخدم</a></li>
                                <li class="li-collapse"><a href="{{route('users',['orderBy' => 'null','sort' => 'null'])}}" class="link-white d-inline-flex text-decoration-none rounded">إدارة المستخدمين</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <svg height="24px" width="24px" version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve" fill="#adb5bd"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <style type="text/css"> .st0{fill:#adb5bd;} </style> <g> <path class="st0" d="M505.837,180.418L279.265,76.124c-7.349-3.385-15.177-5.093-23.265-5.093c-8.088,0-15.914,1.708-23.265,5.093 L6.163,180.418C2.418,182.149,0,185.922,0,190.045s2.418,7.896,6.163,9.627l226.572,104.294c7.349,3.385,15.177,5.101,23.265,5.101 c8.088,0,15.916-1.716,23.267-5.101l178.812-82.306v82.881c-7.096,0.8-12.63,6.84-12.63,14.138c0,6.359,4.208,11.864,10.206,13.618 l-12.092,79.791h55.676l-12.09-79.791c5.996-1.754,10.204-7.259,10.204-13.618c0-7.298-5.534-13.338-12.63-14.138v-95.148 l21.116-9.721c3.744-1.731,6.163-5.504,6.163-9.627S509.582,182.149,505.837,180.418z"></path> <path class="st0" d="M256,346.831c-11.246,0-22.143-2.391-32.386-7.104L112.793,288.71v101.638 c0,22.314,67.426,50.621,143.207,50.621c75.782,0,143.209-28.308,143.209-50.621V288.71l-110.827,51.017 C278.145,344.44,267.25,346.831,256,346.831z"></path> </g> </g></svg>
                            {{ __('بيانات الطالب')}}
                        </a>
                        <div class="collapse div-collapse" id="collapse-1" style="">
                            <ul class="nav flex-column ul-collapse">
                                <li class="li-collapse"><a href="{{route("add.student")}}" class="link-white d-inline-flex text-decoration-none rounded">إضافة بيانات الطالب</a></li>
                                <li class="li-collapse"><a href="{{route('students',['orderBy' => 'null','sort' => 'null'])}}" class="link-white d-inline-flex text-decoration-none rounded">إدارة بيانات الدراسة للطالب</a></li>
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
                                <li class="li-collapse"><a href="{{route("add.teacher")}}" class="link-white d-inline-flex text-decoration-none rounded">إضافة معلم</a></li>
                                <li class="li-collapse"><a href="{{route("teachers",['orderBy' => 'null','sort' => 'null'])}}" class="link-white d-inline-flex text-decoration-none rounded">إدارة المعلمين</a></li>
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
                                <li class="li-collapse"><a href="{{route("add.course")}}" class="link-white d-inline-flex text-decoration-none rounded">إضافة كورس</a></li>
                                <li class="li-collapse"><a href="{{route('courses',['orderBy' => 'null','sort' => 'null'])}}" class="link-white d-inline-flex text-decoration-none rounded">إدارة الكورسات</a></li>
                                <li class="li-collapse"><a href="{{route("buy.course")}}" class="link-white d-inline-flex text-decoration-none rounded">حجز كورس للطالب</a></li>
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
                                <li class="li-collapse"><a href="{{route("add.diploma")}}" class="link-white d-inline-flex text-decoration-none rounded">إضافة دبلوم</a></li>
                                <li class="li-collapse"><a href="{{route("diploma.course")}}" class="link-white d-inline-flex text-decoration-none rounded">إدارة الدبلومات</a></li>
                                <li class="li-collapse"><a href="{{route("buy.diploma")}}" class="link-white d-inline-flex text-decoration-none rounded">حجز دبلوم للطالب</a></li>
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
                                <li class="li-collapse"><a href="{{route("add.customer")}}" class="link-white d-inline-flex text-decoration-none rounded">إضافة زبون</a></li>
                                <li class="li-collapse"><a href="{{route('customers',['orderBy' => 'null','sort' => 'null'])}}" class="link-white d-inline-flex text-decoration-none rounded">إدارة الزبائن</a></li>
                                <li class="li-collapse"><a href="{{route("add.translation_deal")}}" class="link-white d-inline-flex text-decoration-none rounded">إضافة معاملة</a></li>
                                <li class="li-collapse"><a href="{{route('translation_deals',['orderBy' => 'null','sort' => 'null'])}}" class="link-white d-inline-flex text-decoration-none rounded">إدارة المعاملات</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M14 22H10C6.22876 22 4.34315 22 3.17157 20.8284C2 19.6569 2 17.7712 2 14V12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12V14C22 17.7712 22 19.6569 20.8284 20.8284C20.1752 21.4816 19.3001 21.7706 18 21.8985" stroke="#adb5bd" stroke-width="1.5" stroke-linecap="round"></path> <path d="M7 4V2.5" stroke="#adb5bd" stroke-width="1.5" stroke-linecap="round"></path> <path d="M17 4V2.5" stroke="#adb5bd" stroke-width="1.5" stroke-linecap="round"></path> <path d="M21.5 9H16.625H10.75M2 9H5.875" stroke="#adb5bd" stroke-width="1.5" stroke-linecap="round"></path> <path d="M18 17C18 17.5523 17.5523 18 17 18C16.4477 18 16 17.5523 16 17C16 16.4477 16.4477 16 17 16C17.5523 16 18 16.4477 18 17Z" fill="#adb5bd"></path> <path d="M18 13C18 13.5523 17.5523 14 17 14C16.4477 14 16 13.5523 16 13C16 12.4477 16.4477 12 17 12C17.5523 12 18 12.4477 18 13Z" fill="#adb5bd"></path> <path d="M13 17C13 17.5523 12.5523 18 12 18C11.4477 18 11 17.5523 11 17C11 16.4477 11.4477 16 12 16C12.5523 16 13 16.4477 13 17Z" fill="#adb5bd"></path> <path d="M13 13C13 13.5523 12.5523 14 12 14C11.4477 14 11 13.5523 11 13C11 12.4477 11.4477 12 12 12C12.5523 12 13 12.4477 13 13Z" fill="#adb5bd"></path> <path d="M8 17C8 17.5523 7.55228 18 7 18C6.44772 18 6 17.5523 6 17C6 16.4477 6.44772 16 7 16C7.55228 16 8 16.4477 8 17Z" fill="#adb5bd"></path> <path d="M8 13C8 13.5523 7.55228 14 7 14C6.44772 14 6 13.5523 6 13C6 12.4477 6.44772 12 7 12C7.55228 12 8 12.4477 8 13Z" fill="#adb5bd"></path> </g></svg>
                            {{__('الروزنامة')}}
                        </a>
                        <div class="collapse div-collapse" id="collapse-1" style="">
                            <ul class="nav flex-column ul-collapse">
                                <li class="li-collapse"><a href="{{route("course.calendar")}}" class="link-white d-inline-flex text-decoration-none rounded">روزنامة الكورس</a></li>
                                <li class="li-collapse"><a href="{{route("teacher.calendar")}}" class="link-white d-inline-flex text-decoration-none rounded">روزنامة المعلم</a></li>
                                <li class="li-collapse"><a href="{{route("student.calendar")}}" class="link-white d-inline-flex text-decoration-none rounded">روزنامة الطالب</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <svg width="24" height="24" fill="#adb5bd" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 124.029 124.029" xml:space="preserve" stroke="#adb5bd"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M46.163,71.234c9.877,0,17.884-8.008,17.884-17.884c0-9.877-8.006-17.884-17.884-17.884S28.279,43.473,28.279,53.35 C28.279,63.226,36.286,71.234,46.163,71.234z M45.299,54.589c-3.425-1.24-5.643-2.806-5.643-5.839 c0-2.805,1.957-4.958,5.251-5.545v-3.197h2.676v3.034c2.054,0.065,3.457,0.554,4.5,1.076l-0.88,3.001 c-0.782-0.359-2.218-1.109-4.437-1.109c-2.283,0-3.098,1.174-3.098,2.283c0,1.337,1.173,2.088,3.946,3.197 c3.654,1.37,5.286,3.131,5.286,6.034c0,2.772-1.924,5.219-5.513,5.806v3.359h-2.675v-3.166c-2.055-0.062-4.11-0.65-5.285-1.432 l0.882-3.067c1.271,0.75,3.098,1.435,5.088,1.435c2.056,0,3.458-1.011,3.458-2.576C48.854,56.415,47.712,55.47,45.299,54.589z"></path> <path d="M72.838,39.089c9.909,0,17.941-8.033,17.941-17.942c0-9.909-8.032-17.942-17.941-17.942 c-9.909,0-17.941,8.033-17.941,17.942C54.897,31.056,62.929,39.089,72.838,39.089z M63.415,21.899h1.833c0-0.164,0-0.36,0-0.524 c0-0.393,0.033-0.817,0.066-1.177h-1.899v-2.029h2.192c0.394-1.997,1.277-3.698,2.455-4.941c1.767-1.963,4.156-3.076,7.068-3.076 c1.963,0,3.6,0.491,4.68,1.015l-0.818,3.043c-0.85-0.457-2.191-0.884-3.665-0.884c-1.638,0-3.078,0.589-4.155,1.8 c-0.689,0.72-1.18,1.8-1.441,3.043h8.574v2.029h-8.967c-0.064,0.36-0.064,0.752-0.064,1.145c0,0.196,0,0.36,0,0.557h9.033v1.995 h-8.674c0.23,1.408,0.721,2.455,1.41,3.208c1.11,1.21,2.682,1.766,4.385,1.766c1.603,0,3.106-0.556,3.86-0.98l0.688,2.944 c-1.08,0.655-2.914,1.31-5.008,1.31c-2.978,0-5.66-1.179-7.395-3.371c-1.047-1.211-1.768-2.848-2.062-4.877h-2.097V21.899z"></path> <path d="M108.582,47.367c8.531,0,15.447-6.916,15.447-15.447c0-8.531-6.916-15.447-15.447-15.447s-15.447,6.916-15.447,15.447 C93.135,40.451,100.051,47.367,108.582,47.367z M105.004,22.763l2.367,5.1c0.507,1.042,0.816,1.916,1.184,2.817h0.057 c0.339-0.845,0.703-1.831,1.184-2.874l2.452-5.043h3.662l-4.931,8.255h3.494v1.719h-4.424v1.916h4.424v1.719h-4.424v4.705h-3.24 v-4.705h-4.395v-1.719h4.395v-1.916h-4.395v-1.719h3.521l-4.676-8.255H105.004z"></path> <path d="M74.703,60.601c0,8.813,7.145,15.958,15.957,15.958c8.814,0,15.958-7.145,15.958-15.958 c0-8.814-7.144-15.958-15.958-15.958S74.703,51.787,74.703,60.601z M96.219,51.708l-0.64,2.707 c-0.554-0.321-1.397-0.583-2.532-0.583c-2.154,0-2.941,1.455-2.941,3.144c0,0.989,0.146,1.776,0.322,2.619h4.074v2.474h-3.755 c0.03,0.902,0.03,1.775-0.204,2.59c-0.291,0.99-0.873,1.834-1.688,2.562v0.058h8.179v2.94H84.285v-1.951 c1.748-0.813,3.29-2.562,3.29-4.627c0-0.553-0.028-1.02-0.116-1.571h-2.91v-2.474h2.502c-0.146-0.815-0.291-1.775-0.291-2.707 c0-3.58,2.533-5.908,6.142-5.908C94.473,50.981,95.578,51.33,96.219,51.708z"></path> <path d="M116.212,81.589c-2.056-1.637-6.036-3.549-10.499-3.549c-3.775,0-9.246,1.369-13.893,7.879 c-4.18,5.336-9.419,7.496-13.674,8.314c0.382-0.953,0.63-2.098,0.637-3.717c0.038-9.051-7.176-15.855-17.151-15.9l-35.608,0.031 c-3.342,0-6.631,0.986-9.404,2.771v-4.633H0v48.039h16.619v-5.104c0.628,0.311,1.493,0.938,1.493,0.938 c2.861,1.578,7.295,3.515,11.818,3.515h32.713c28.179,0,47.634-15.787,54.69-23.607c2.108-2.338,4.292-4.756,4.041-7.801 C121.152,86.044,119.15,83.929,116.212,81.589z M11.053,83.398H5.244v-5.809h5.809V83.398z M113.021,92.675 c-6.535,7.244-24.528,21.69-50.377,21.69H29.931c-5.531,0-11.487-4.326-11.541-4.368c-0.51-0.376-1.117-0.568-1.729-0.568 c-0.013,0-0.027,0.004-0.042,0.004V83.835c0.636,0.012,1.27-0.171,1.795-0.57l0.635-0.479c1.976-1.501,4.452-2.328,6.961-2.328 l35.595-0.032c5.68,0.025,11.399,3.286,11.37,10.068c0,3.916-2.611,4.117-2.752,4.152H44.13c-1.604,0-2.904,1.301-2.904,2.904 c0,1.603,1.3,2.902,2.904,2.902h27.693l1.543-0.004c4.347-0.001,15.295-1.027,23.073-11.008c0.025-0.034,0.053-0.069,0.076-0.104 c2.586-3.641,5.68-5.487,9.196-5.487c3.175,0,5.89,1.494,6.88,2.283c2.91,2.317,2.991,3.077,2.993,3.108 C115.596,89.825,113.727,91.896,113.021,92.675z"></path> </g> </g> </g></svg>
                            {{__('المالية')}}
                        </a>
                        <div class="collapse div-collapse" id="collapse-1" style="">
                            <ul class="nav flex-column ul-collapse">
                                <li class="li-collapse"><a href="{{route('revenues',['orderBy' => 'null','sort' => 'null'])}}" class="link-white d-inline-flex text-decoration-none rounded">إيرادات الكورسات</a></li>
                                <li class="li-collapse"><a href="{{route('translation_revenues',['orderBy' => 'null','sort' => 'null'])}}" class="link-white d-inline-flex text-decoration-none rounded">إيرادات الترجمة</a></li>
                                <li class="li-collapse"><a href="{{route('teacher_revenue')}}" class="link-white d-inline-flex text-decoration-none rounded">إيرادات المعلم</a></li>
                                <li class="li-collapse"><a href="{{route('diploma.revenues')}}" class="link-white d-inline-flex text-decoration-none rounded">إيرادات الدبلوم</a></li>
                                <li class="li-collapse"><a href="{{route('expenses',['orderBy' => 'null','sort' => 'null'])}}" class="link-white d-inline-flex text-decoration-none rounded">مصروفات</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <svg width="24" height="24" fill="#adb5bd" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>report</title> <path d="M6 11h4v17h-4v-17zM22 16v12h4v-12h-4zM14 28h4v-24h-4v24z"></path> </g></svg>
                            {{__('التقارير')}}
                        </a>
                        <div class="collapse div-collapse" id="collapse-1" style="">
                            <ul class="nav flex-column ul-collapse">
                                <li class="li-collapse"><a href="{{route('general.monthly.report')}}" class="link-white d-inline-flex text-decoration-none rounded">التقرير الشهري الشامل</a></li>
                                <li class="li-collapse"><a href="{{route('course.report')}}" class="link-white d-inline-flex text-decoration-none rounded">تقرير الكورسات</a></li>
                                <li class="li-collapse"><a href="{{route('translation.report')}}" class="link-white d-inline-flex text-decoration-none rounded">تقرير الترجمة</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M1.5 6.5C1.5 3.46243 3.96243 1 7 1C10.0376 1 12.5 3.46243 12.5 6.5C12.5 9.53757 10.0376 12 7 12C3.96243 12 1.5 9.53757 1.5 6.5Z" fill="#adb5bd"></path> <path d="M14.4999 6.5C14.4999 8.00034 14.0593 9.39779 13.3005 10.57C14.2774 11.4585 15.5754 12 16.9999 12C20.0375 12 22.4999 9.53757 22.4999 6.5C22.4999 3.46243 20.0375 1 16.9999 1C15.5754 1 14.2774 1.54153 13.3005 2.42996C14.0593 3.60221 14.4999 4.99966 14.4999 6.5Z" fill="#adb5bd"></path> <path d="M0 18C0 15.7909 1.79086 14 4 14H10C12.2091 14 14 15.7909 14 18V22C14 22.5523 13.5523 23 13 23H1C0.447716 23 0 22.5523 0 22V18Z" fill="#adb5bd"></path> <path d="M16 18V23H23C23.5522 23 24 22.5523 24 22V18C24 15.7909 22.2091 14 20 14H14.4722C15.4222 15.0615 16 16.4633 16 18Z" fill="#adb5bd"></path> </g></svg>
                            {{__('نشاط المستخدمين')}}
                        </a>
                        <div class="collapse div-collapse" id="collapse-1" style="">
                            <ul class="nav flex-column ul-collapse">
                                <li class="li-collapse"><a href="{{route('admin.user_requests')}}" class="link-white d-inline-flex text-decoration-none rounded">عرض</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </x-slot>

    <x-slot name="title"><h1>{{__('أكاديمية متخصصة في تدريس اللغات والترجمة')}}</h1></x-slot>

    @yield('content')
    @yield('scripts')
</x-app-layout>
