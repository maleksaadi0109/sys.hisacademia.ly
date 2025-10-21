@extends('admin.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('تعديل بيانات حساب')}} ( {{$user->name}} )</h2>
        </header>

    <div class="container">
        <div class="row">
                <form method="POST" action="{{route('update.user',['id' => $user->id])}}">
                    @csrf
                    @method('patch')
                    <div class="row">
                        <!-- Name -->
                        <div class="mt-4 col-lg-6">
                            <x-input-label for="name" :value="__('الاسم')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" value="{{$user->name}}" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <!-- User Name -->
                        <div class="mt-4 col-lg-6">
                            <x-input-label for="username" :value="__('اسم المستخدم')" />
                            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" value="{{$user->username}}" required autofocus autocomplete="username" />
                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div class="mt-4 col-lg-6">
                            <x-input-label for="email" :value="__('البريد الالكتروني')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" value="{{$user->email}}" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
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
                    </div>
                    @php 
                    use App\Enums\UserType; 
                    use App\Enums\UserStatus;
                    use App\Enums\EnumPermission;
                    @endphp
                    <div class="row" >  
                        <div class="mt-4 col-lg-6">
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="inputGroupSelect01">نوع المستخدم</label>
                                <select class="form-select" id="inputGroupSelect01" name="user_type" required>
                                    @foreach (UserType::userTypeAr() as $key => $value) 
                                    @if($key =="student" || $key =="teacher")
                                        
                                    @else
                                        @if ($key == $user->user_type)
                                            <option selected value="{{$key}}">{{$value}}</option>
                                        @else
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endif
                                    @endif
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('user_type')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-4 col-lg-6">
                            <div class="form-select mt-4 col-lg-6"> 
                                <select id="choices-multiple-remove-button" name="permission[]" placeholder="الصلاحيات" required multiple>
                                    <option disabled value>أختر...</option>
                                    @foreach (EnumPermission::enumPermissionAr() as $key => $value) 
                                        @if (in_array($key,json_decode($user->permission)))
                                            <option selected value="{{$key}}">{{$value}}</option>
                                        @else
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        <x-input-error :messages="$errors->get('permission')" class="mt-2" />
                    </div>

                        
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
        $(document).ready(function () {
            var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
            removeItemButton: true,
            searchResultLimit:5,
            renderChoiceLimit:5
            });
        });
    </script>
@stop
