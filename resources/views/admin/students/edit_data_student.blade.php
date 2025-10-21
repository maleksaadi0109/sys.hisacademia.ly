@extends('admin.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('تعديل بيانات الدراسة')}}</h2>
        </header>

    <div class="container">
        <div class="row">
                <form method="POST" enctype="multipart/form-data" action="{{ route('update.dataStudent', ['id' => $student->id]) }}">
                    @csrf
                    @method('patch')
                    <div class="row">
                        
                    @php 
                    use App\Enums\Attend;
                    use App\Enums\CourseType;
                    use App\Enums\WeekDays;
                    use App\Enums\CulturalActivity;
                    @endphp
                    
                    @if($dataStudent != null)

                        <!-- section -->
                        <div class="mt-4 col-lg-6">
                            <x-input-label for="section" :value="__('القسم')" />
                            <x-text-input id="section" value="{{$dataStudent->section}}" class="block mt-1 w-full" type="text" name="section"   autofocus autocomplete="section" />
                            <x-input-error :messages="$errors->get('section')" class="mt-2" />
                        </div>

                        <!-- level -->
                        <div class="mt-4 col-lg-6">
                            <x-input-label for="level" :value="__('المستوى')" />
                            <x-text-input id="level" value="{{$dataStudent->level}}" class="block mt-1 w-full" type="text" name="level"   autofocus autocomplete="section" />
                            <x-input-error :messages="$errors->get('level')" class="mt-2" />
                        </div>


                        <div class="mt-4 col-lg-6">
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="inputGroupSelect03">الحضور</label>
                                <select class="form-select" id="inputGroupSelect03"  name="attend" >
                                    @foreach (Attend::attendAr() as $key => $value) 
                                        @if ($key == $dataStudent->attend)
                                            <option selected value="{{$key}}">{{$value}}</option>
                                        @else
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('attend')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-4 col-lg-6">
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="inputGroupSelect03">نوع الدرس</label>
                                <select class="form-select" id="inputGroupSelect03"  name="course_type" >
                                    @foreach (CourseType::courseTypeAr() as $key => $value) 
                                        @if ($key == $dataStudent->course_type)
                                            <option selected value="{{$key}}">{{$value}}</option>
                                        @else
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('course_type')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-4 col-lg-6">
                            <div class="form-select mt-4 col-lg-6"> 
                                <select id="choices-multiple-remove-button" name="course_days[]" required placeholder="ايام الدرس" multiple>
                                    @foreach (WeekDays::weekDaysAr() as $key => $value) 
                                        @if (in_array($key,json_decode($dataStudent->course_days)))
                                            <option selected value="{{$key}}">{{$value}}</option>
                                        @else
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <x-input-error :messages="$errors->get('course_days')" class="mt-2" />
                        </div>

                        <!-- course start time -->
                        <div class="mt-4 col-lg-6">
                            <label for="course_start_time" class="block font-medium text-sm text-gray-700">وقت بدء الدرس</label> 
                            <input type="time" id="course_start_time" name="course_start_time" value="{{ date("H:i", strtotime($dataStudent->course_start_time) ) }}"  class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                            @php
                                $messages = $errors->get('course_start_time');
                            @endphp
                            @if ($messages)
                                <ul class="text-sm text-red-600 space-y-1 mt-2">
                                    @foreach ((array) $messages as $message)
                                        <li>{{ $message }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <!-- course end time -->
                        <div class="mt-4 col-lg-6">
                            <label for="course_end_time" class="block font-medium text-sm text-gray-700">وقت انتهاء الدرس</label> 
                            <input type="time" id="course_end_time" name="course_end_time" value="{{ date("H:i", strtotime($dataStudent->course_end_time) ) }}"  class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                            @php
                                $messages = $errors->get('course_end_time');
                            @endphp
                            @if ($messages)
                                <ul class="text-sm text-red-600 space-y-1 mt-2">
                                    @foreach ((array) $messages as $message)
                                        <li>{{ $message }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <!-- classroom name -->
                        <div class="mt-4 col-lg-6">
                            <x-input-label for="classroom_name" :value="__('اسم القاعة')" />
                            <x-text-input id="classroom_name" value="{{$dataStudent->classroom_name}}" class="block mt-1 w-full" type="text" name="classroom_name"   autofocus autocomplete="classroom_name" />
                            <x-input-error :messages="$errors->get('classroom_name')" class="mt-2" />
                        </div>


                        <div class="mt-4 col-lg-6">
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="inputGroupSelect03">ترغب في دعوتك في حالة وجود نشاط ثقاي</label>
                                <select class="form-select" id="inputGroupSelect03"  name="cultural_activity" required>
                                    @foreach (CulturalActivity::culturalActivityAr() as $key => $value) 
                                        @if ($key == $dataStudent->cultural_activity)
                                            <option selected value="{{$key}}">{{$value}}</option>
                                        @else
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('cultural_activity')" class="mt-2" />
                            </div>
                        </div>

                        <!-- payment method -->
                        <div class="mt-4 col-lg-6">
                            <x-input-label for="payment_method" :value="__('طريقة الدفع')" />
                            <x-text-input id="payment_method" value="{{$dataStudent->payment_method}}" class="block mt-1 w-full" type="text" name="payment_method"   autofocus autocomplete="payment_method" />
                            <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                        </div>

                        <div class="mt-4 col-lg-6">
                            <label for="signature" class="block font-medium text-sm text-gray-700">تغيير توقيع الطالب</label>
                            <input value="{{$dataStudent->signature}}" class="pass_image form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-s" accept="image/png, image/jpeg, image/jpg"  type="file" id="signature" name="signature">
                        </div>

                        <div class="mt-4 col-lg-6">
                            <img id="privew_signature" src="{{URL::asset('storage/signature/'.$dataStudent->signature)}}" class="w-25 img-thumbnail" alt="...">
                        </div>

                        <div class="row">
                            <x-primary-button class="mt-4 col-lg-2 justify-content-center">
                                {{ __('تعديل') }}
                            </x-primary-button>
                        </div>

                @else
                    <!-- section -->
                    <div class="mt-4 col-lg-6">
                        <x-input-label for="section" :value="__('القسم')" />
                        <x-text-input id="section" value="{{old('section')}}" class="block mt-1 w-full" type="text" name="section"   autofocus autocomplete="section" />
                        <x-input-error :messages="$errors->get('section')" class="mt-2" />
                    </div>

                    <!-- level -->
                    <div class="mt-4 col-lg-6">
                        <x-input-label for="level" :value="__('المستوى')" />
                        <x-text-input id="level" value="{{old('level')}}" class="block mt-1 w-full" type="text" name="level"   autofocus autocomplete="section" />
                        <x-input-error :messages="$errors->get('level')" class="mt-2" />
                    </div>


                    <div class="mt-4 col-lg-6">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupSelect03">الحضور</label>
                            <select class="form-select" id="inputGroupSelect03"  name="attend" >
                                <option selected disabled value >أختر...</option>
                                @foreach (Attend::attendAr() as $key => $value) 
                                    @if ($key == old('attend'))
                                        <option selected value="{{$key}}">{{$value}}</option>
                                    @else
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('attend')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-4 col-lg-6">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupSelect03">نوع الدرس</label>
                            <select class="form-select" id="inputGroupSelect03"  name="course_type" >
                                <option selected disabled value >أختر...</option>
                                @foreach (CourseType::courseTypeAr() as $key => $value) 
                                    @if ($key == old('course_type'))
                                        <option selected value="{{$key}}">{{$value}}</option>
                                    @else
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('course_type')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-4 col-lg-6">
                        <div class="form-select mt-4 col-lg-6"> 
                            <select id="choices-multiple-remove-button" name="course_days[]" required placeholder="ايام الدرس" multiple>
                                @foreach (WeekDays::weekDaysAr() as $key => $value) 
                                    @if (old('course_days') && in_array($key,old('course_days')))
                                        <option selected value="{{$key}}">{{$value}}</option>
                                    @else
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <x-input-error :messages="$errors->get('course_days')" class="mt-2" />
                    </div>

                    <!-- course start time -->
                    <div class="mt-4 col-lg-6">
                        <label for="course_start_time" class="block font-medium text-sm text-gray-700">وقت بدء الدرس</label> 
                        <input type="time" id="course_start_time" name="course_start_time" value="{{ date("H:i", strtotime(old('course_start_time')) ) }}"  class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                        @php
                            $messages = $errors->get('course_start_time');
                        @endphp
                        @if ($messages)
                            <ul class="text-sm text-red-600 space-y-1 mt-2">
                                @foreach ((array) $messages as $message)
                                    <li>{{ $message }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <!-- course end time -->
                    <div class="mt-4 col-lg-6">
                        <label for="course_end_time" class="block font-medium text-sm text-gray-700">وقت انتهاء الدرس</label> 
                        <input type="time" id="course_end_time" name="course_end_time" value="{{ date("H:i", strtotime(old('course_end_time')) ) }}"  class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                        @php
                            $messages = $errors->get('course_end_time');
                        @endphp
                        @if ($messages)
                            <ul class="text-sm text-red-600 space-y-1 mt-2">
                                @foreach ((array) $messages as $message)
                                    <li>{{ $message }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <!-- classroom name -->
                    <div class="mt-4 col-lg-6">
                        <x-input-label for="classroom_name" :value="__('اسم القاعة')" />
                        <x-text-input id="classroom_name" value="{{old('classroom_name')}}" class="block mt-1 w-full" type="text" name="classroom_name"   autofocus autocomplete="classroom_name" />
                        <x-input-error :messages="$errors->get('classroom_name')" class="mt-2" />
                    </div>


                    <div class="mt-4 col-lg-6">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupSelect03">ترغب في دعوتك في حالة وجود نشاط ثقاي</label>
                            <select class="form-select" id="inputGroupSelect03"  name="cultural_activity" required>
                                <option selected disabled value>أختر...</option>
                                @foreach (CulturalActivity::culturalActivityAr() as $key => $value) 
                                    @if ($key == old('cultural_activity'))
                                        <option selected value="{{$key}}">{{$value}}</option>
                                    @else
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('cultural_activity')" class="mt-2" />
                        </div>
                    </div>

                    <!-- payment method -->
                    <div class="mt-4 col-lg-6">
                        <x-input-label for="payment_method" :value="__('طريقة الدفع')" />
                        <x-text-input id="payment_method" value="{{old('payment_method')}}" class="block mt-1 w-full" type="text" name="payment_method"   autofocus autocomplete="payment_method" />
                        <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                    </div>

                    <div class="mt-4 col-lg-6">
                        <label for="signature" class="block font-medium text-sm text-gray-700">توقيع الطالب</label>
                        <input  class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-s" accept="image/png, image/jpeg, image/jpg" type="file" name="signature">
                    </div>

                    <div class="row">
                        <x-primary-button class="mt-4 col-lg-2 justify-content-center">
                            {{ __('اضافة') }}
                        </x-primary-button>
                    </div>
                @endif
                
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

            $('#signature').change(function (event) {
            var reader = new FileReader();
            reader.onload = function(){
            var output = document.getElementById('privew_signature');
            output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        });
        });
    </script>

@stop

