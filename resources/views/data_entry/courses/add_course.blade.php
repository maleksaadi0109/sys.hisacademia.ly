@extends('data_entry.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('إضافة كورس')}}</h2>
        </header>

        <div class="container">
            <div class="row">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('data_entry.register.course') }}">
                        @csrf
                        <div class="row">
                            @php 
                            use App\Enums\WeekDays;
                            @endphp
                            <!-- name -->
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="name" :value="__('اسم الكورس')" />
                                <x-text-input id="name" value="{{old('name')}}" class="block mt-1 w-full" type="text" name="name" required autofocus autocomplete="name" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- section -->
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="section" :value="__('القسم')" />
                                <x-text-input id="section" value="{{old('section')}}" class="block mt-1 w-full" type="text" name="section" required  autofocus autocomplete="section" />
                                <x-input-error :messages="$errors->get('section')" class="mt-2" />
                            </div>

                            <!-- start date -->
                            <div class="mt-4 col-lg-6">
                                <label for="start_date" class="block font-medium text-sm text-gray-700">تاريخ البداية </label>
                                <input type="date" id="start_date"  value="{{old('start_date')}}"  name="start_date" required class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-s" />
                                @php
                                    $messages = $errors->get('start_date');
                                @endphp
                                @if ($messages)
                                    <ul class="text-sm text-red-600 space-y-1 mt-2">
                                        @foreach ((array) $messages as $message)
                                            <li>{{ $message }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>

                            <!-- end date -->
                            <div class="mt-4 col-lg-6">
                                <label for="end_date" class="block font-medium text-sm text-gray-700">تاريخ النهاية </label>
                                <input type="date" id="end_date"  value="{{old('end_date')}}"  name="end_date" required class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-s" />
                                @php
                                    $messages = $errors->get('end_date');
                                @endphp
                                @if ($messages)
                                    <ul class="text-sm text-red-600 space-y-1 mt-2">
                                        @foreach ((array) $messages as $message)
                                            <li>{{ $message }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>

                            <!-- level -->
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="level" :value="__('المستوى')" />
                                <x-text-input id="level" value="{{old('level')}}" class="block mt-1 w-full" type="text" name="level" required  autofocus autocomplete="level" />
                                <x-input-error :messages="$errors->get('level')" class="mt-2" />
                            </div>

                            <!-- course start time -->
                            <div class="mt-4 col-lg-6">
                                <label for="start_time" class="block font-medium text-sm text-gray-700">وقت بدء التدريب</label> 
                                <input type="time" id="start_time" name="start_time" value="{{ date("H:i", strtotime(old('start_time')) ) }}"  class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                                @php
                                    $messages = $errors->get('start_time');
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
                                <label for="end_time" class="block font-medium text-sm text-gray-700">وقت انتهاء التدريب</label> 
                                <input type="time" id="end_time" name="end_time" value="{{ date("H:i", strtotime(old('end_time')) ) }}"  class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                                @php
                                    $messages = $errors->get('end_time');
                                @endphp
                                @if ($messages)
                                    <ul class="text-sm text-red-600 space-y-1 mt-2">
                                        @foreach ((array) $messages as $message)
                                            <li>{{ $message }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>

                            <!-- total days -->
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="total_days" :value="__('عدد ايام الكورس (المدة الإجمالية )')" />
                                <x-text-input id="total_days" value="{{old('total_days')}}" class="block mt-1 w-full" type="number" name="total_days" required  autofocus autocomplete="total_days" />
                                <x-input-error :messages="$errors->get('total_days')" class="mt-2" />
                            </div>

                            <!-- average hours -->
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="average_hours" :value="__('المعدل اليومي للساعات')" />
                                <x-text-input id="average_hours" value="{{old('average_hours')}}" class="block mt-1 w-full" type="number" name="average_hours" required  autofocus autocomplete="average_hours" />
                                <x-input-error :messages="$errors->get('average_hours')" class="mt-2" />
                            </div>

                            <!-- total hours -->
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="total_hours" :value="__('اجمالي عدد الساعات')" />
                                <x-text-input id="total_hours" value="{{old('total_hours')}}" class="block mt-1 w-full" type="number" name="total_hours" required  autofocus autocomplete="total_hours" />
                                <x-input-error :messages="$errors->get('total_hours')" class="mt-2" />
                            </div>

                            <!-- number days per week -->
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="n_d_per_week" :value="__('عدد أيام الدراسة بالأسبوع')" />
                                <x-text-input id="n_d_per_week" value="{{old('n_d_per_week')}}" class="block mt-1 w-full" type="number" name="n_d_per_week" required  autofocus autocomplete="n_d_per_week" />
                                <x-input-error :messages="$errors->get('n_d_per_week')" class="mt-2" />
                            </div>

                            <div class="mt-4 col-lg-6">
                                <div class="form-select mt-4 col-lg-6"> 
                                    <select id="choices-multiple-remove-button" name="days[]" required placeholder="أيام الدراسة" multiple>
                                        @foreach (WeekDays::weekDaysAr() as $key => $value) 
                                            @if (old('days') && in_array($key,old('days')))
                                                <option selected value="{{$key}}">{{$value}}</option>
                                            @else
                                                <option value="{{$key}}">{{$value}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <x-input-error :messages="$errors->get('days')" class="mt-2" />
                            </div>

                            <!-- price -->
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="price" :value="__('القيمة الإجمالية للكورس')" />
                                <x-text-input id="price" value="{{old('price')}}" class="block mt-1 w-full" type="number" name="price" required  autofocus autocomplete="price" />
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>

                            <div class="mt-5 col-lg-6">
                                <div class="input-group mb-3">
                                    <label class="input-group-text" for="inputGroupSelect03">العملة</label>
                                    <select class="form-select" id="inputGroupSelect03" name="currency" required>
                                        @if(old('currency') == 'USD')
                                            <option selected value="USD">دولار</option>
                                            <option value="D">دينار</option>
                                        @elseif(old('currency') == 'D')
                                            <option value="USD">دولار</option>
                                            <option selected value="D">دينار</option>
                                        @else
                                            <option disabled selected value >أختر...</option>
                                            <option value="USD">دولار</option>
                                            <option value="D">دينار</option>
                                        @endif
                                    </select>
                                    @php
                                        $messages = $errors->get('currency');
                                    @endphp
                                    @if ($messages)
                                        <ul class="text-sm text-red-600 space-y-1 mt-2">
                                            @foreach ((array) $messages as $message)
                                                <li>{{ $message }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-5 col-lg-6">
                                <div class="input-group mb-3">
                                    <label class="input-group-text" for="inputGroupSelect04">مدرس الكورس</label>
                                    <select class="form-select" id="inputGroupSelect04" name="teacher_id" required>
                                        <option selected disabled>أختر....</option>
                                        @foreach($teachers as  $value)
                                            @if(old('teacher_id') == $value->user->id)
                                                <option selected value="{{$value->user->id}}">{{$value->user->name}}</option>
                                            @else
                                                <option value="{{$value->user->id}}">{{$value->user->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @php
                                        $messages = $errors->get('teacher_id');
                                    @endphp
                                    @if ($messages)
                                        <ul class="text-sm text-red-600 space-y-1 mt-2">
                                            @foreach ((array) $messages as $message)
                                                <li>{{ $message }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <x-primary-button class="mt-4 col-lg-2 justify-content-center">
                                {{ __('إضافة') }}
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