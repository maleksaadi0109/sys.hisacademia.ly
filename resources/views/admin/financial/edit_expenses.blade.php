@extends('admin.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('تعديل فاتورة مصروف')}}</h2>
        </header>

        <div class="container">
            <div class="row">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('update.expense',['id' => $expense->id]) }}">
                        @csrf
                        @method('patch')
                        <div class="row">

                            <!-- context -->
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="context" :value="__('البيان')" />
                                <x-text-input id="context" value="{{$expense->context}}" class="block mt-1 w-full" type="text" name="context" required  autofocus autocomplete="context" />
                                <x-input-error :messages="$errors->get('context')" class="mt-2" />
                            </div>

                            <!-- section -->
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="section" :value="__('القسم')" />
                                <x-text-input id="section" value="{{$expense->section}}" class="block mt-1 w-full" type="text" name="section" required  autofocus autocomplete="section" />
                                <x-input-error :messages="$errors->get('section')" class="mt-2" />
                            </div>

                            <!-- review number -->
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="review_number" :value="__('رقم الفاتورة')" />
                                <x-text-input id="review_number" value="{{$expense->review_number}}" class="block mt-1 w-full" type="text" name="review_number" required  autofocus autocomplete="review_number" />
                                <x-input-error :messages="$errors->get('review_number')" class="mt-2" />
                            </div>

                            <!-- value -->
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="value" :value="__('قيمة الفاتورة')" />
                                <x-text-input id="value" value="{{$expense->value}}" class="block mt-1 w-full" type="number" min="0" name="value" required  autofocus autocomplete="value" />
                                <x-input-error :messages="$errors->get('value')" class="mt-2" />
                            </div>

                            <div class="mt-5 col-lg-6">
                                <div class="input-group mb-3">
                                    <label class="input-group-text" for="inputGroupSelect03">العملة</label>
                                    <select class="form-select" id="inputGroupSelect03" name="currency" required>
                                        @if($expense->currency == 'USD')
                                            <option selected value="USD">دولار</option>
                                            <option value="D">دينار</option>
                                        @elseif($expense->currency == 'D')
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

                            <!-- date -->
                            <div class="mt-4 col-lg-6">
                                <label for="date" class="block font-medium text-sm text-gray-700">التاريخ</label>
                                <input type="date" id="date"  value="{{$expense->date}}"  name="date" required class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-s" />
                                @php
                                    $messages = $errors->get('date');
                                @endphp
                                @if ($messages)
                                    <ul class="text-sm text-red-600 space-y-1 mt-2">
                                        @foreach ((array) $messages as $message)
                                            <li>{{ $message }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>



                            <div class="row">
                                <x-primary-button class="mt-4 col-lg-2 justify-content-center">
                                    {{ __('تعديل') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
            </div>
        </div>
    </section>
@stop