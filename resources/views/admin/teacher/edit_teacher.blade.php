@extends('admin.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('تعديل بيانات المعلم')}}</h2>
        </header>

    <div class="container">
        <div class="row">
                <form method="POST" enctype="multipart/form-data" action="{{ route('update.teacher', ['id' => $teacher->id]) }}">
                    @csrf
                    @method('patch')
                    <div class="row">
                        <!-- Name -->
                        <div class="mt-4 col-lg-6">
                            <x-input-label for="name" :value="__('الاسم')" />
                            <x-text-input id="name" value="{{$user->name}}" class="block mt-1 w-full" type="text" name="name"  required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- En Name -->
                        <div class="mt-4 col-lg-6">
                            <x-input-label for="en_name" :value="__('الاسم بالانجليزي')" />
                            <x-text-input id="en_name" value="{{$teacher->en_name}}" class="block mt-1 w-full" type="text" name="en_name"  required autofocus autocomplete="en_name" />
                            <x-input-error :messages="$errors->get('en_name')" class="mt-2" />
                        </div>

                        <!-- id number -->
                        <div class="mt-4 col-lg-6">
                            <x-input-label for="id_number" :value="__('رقم الهوية')" />
                            <x-text-input id="id_number" value="{{$teacher->id_number}}" class="block mt-1 w-full" type="text" name="id_number"  required autofocus autocomplete="id_number" />
                            <x-input-error :messages="$errors->get('id_number')" class="mt-2" />
                        </div>

                        
                        <!-- nationality -->
                        <div class="mt-4 col-lg-6">
                            <x-input-label for="nationality" :value="__('الجنسية')" />
                            <x-text-input id="nationality" value="{{$teacher->nationality}}" class="block mt-1 w-full" type="text" name="nationality"  required autofocus autocomplete="nationality" />
                            <x-input-error :messages="$errors->get('nationality')" class="mt-2" />
                        </div>

                        <!-- date of birth -->
                        <div class="mt-4 col-lg-6">
                            <label for="date_of_birth" class="block font-medium text-sm text-gray-700">تاريخ الميلاد </label>
                            <input type="date" id="date_of_birth"  value="{{$teacher->date_of_birth}}"  name="date_of_birth" required class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-s" />
                            @php
                                $messages = $errors->get('date_of_birth');
                            @endphp
                            @if ($messages)
                                <ul class="text-sm text-red-600 space-y-1 mt-2">
                                    @foreach ((array) $messages as $message)
                                        <li>{{ $message }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <!-- academic qualification -->
                        <div class="mt-4 col-lg-6">
                            <x-input-label for="academic_qualification" :value="__('المؤهل العلمي')" />
                            <x-text-input id="academic_qualification" value="{{$teacher->academic_qualification}}" class="block mt-1 w-full" type="text" name="academic_qualification"  required autofocus autocomplete="academic_qualification" />
                            <x-input-error :messages="$errors->get('academic_qualification')" class="mt-2" />
                        </div>

                        <!-- phone -->
                        <div class="mt-4 col-lg-6">
                            <x-input-label for="phone" :value="__('رقم الهاتف')" />
                            <x-text-input id="phone" value="{{$teacher->phone}}" class="block mt-1 w-full" type="text" name="phone"  required autofocus autocomplete="phone" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div class="mt-4 col-lg-6">
                            <x-input-label for="email" :value="__('البريد الالكتروني')" />
                            <x-text-input id="email" value="{{$user->email}}" class="block mt-1 w-full" type="email" name="email" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- User Name -->
                        <div class="mt-4 col-lg-6">
                            <x-input-label for="username" :value="__('اسم المستخدم')" />
                            <x-text-input id="username" value="{{$user->username}}" class="block mt-1 w-full" type="text" name="username"  required autofocus autocomplete="username" />
                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mt-4 col-lg-6">
                            <x-input-label for="password" :value="__('كلمة المرور')" />
                            <x-text-input id="password" class="block mt-1 w-full"
                                            type="password"
                                            name="password"
                                            autocomplete="new-password" />

                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        <!-- Confirm Password -->
                        <div class="mt-4 col-lg-6">
                            <x-input-label for="password_confirmation" :value="__('تأكيد كلمة المرور')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                            type="password"
                                            name="password_confirmation" autocomplete="new-password" />

                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="mt-4 col-lg-6">
                            <label for="pass_image" class="block font-medium text-sm text-gray-700">تغيير صورة جواز السفر</label>
                            <input value="{{$teacher->pass_image}}" class="pass_image form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-s" accept="image/png, image/jpeg, image/jpg"  type="file" id="pass_image" name="pass_image">
                        </div>

                        <div class="mt-4 col-lg-6">
                            <img id="privew_pass_image" src="{{URL::asset('storage/passport/'.$teacher->pass_image)}}" class="w-25 img-thumbnail" alt="...">
                        </div>
                        
                    </div>


                    @php 
                        use App\Enums\UserStatus;
                    @endphp
                        <div class="mt-4 col-lg-6">
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="inputGroupSelect03">تنشيط الحساب</label>
                                <select class="form-select" id="inputGroupSelect03"  name="status" required>
                                    @foreach (UserStatus::userStatusAr() as $key => $value) 
                                        @if ($key == $user->status)
                                            <option selected value="{{$key}}">{{$value}}</option>
                                        @else
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>

                    <div class="row">
                        <x-primary-button class="mt-4 col-lg-2 justify-content-center">
                            {{ __('تعديل') }}
                        </x-primary-button>
                    </div>
                </form>
        </div>
    </div>
    </section>
    <script>
        $('#pass_image').change(function (event) {
            var reader = new FileReader();
            reader.onload = function(){
            var output = document.getElementById('privew_pass_image');
            output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        });
    </script>
@stop

