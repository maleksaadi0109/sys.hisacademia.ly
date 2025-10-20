@extends('data_entry.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('تعديل بيانات المعاملة')}}</h2>
        </header>

        <div class="container">
            <div class="row">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('data_entry.update.translation_deal',['id' => $translation_deal->id]) }}">
                        @csrf
                        @method('patch')
                        <div class="row">

                            <!-- number of sheets -->
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="number_of_sheets" :value="__('عدد الأوراق')" />
                                <x-text-input id="number_of_sheets" value="{{$translation_deal->number_of_sheets}}" class="block mt-1 w-full" type="number" name="number_of_sheets" required  autofocus autocomplete="number_of_sheets" />
                                <x-input-error :messages="$errors->get('number_of_sheets')" class="mt-2" />
                            </div>

                            <!-- number of transaction -->
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="number_of_transaction" :value="__('عدد المعاملات')" />
                                <x-text-input id="number_of_transaction" value="{{$translation_deal->number_of_transaction}}" class="block mt-1 w-full" type="number" name="number_of_transaction" required  autofocus autocomplete="number_of_transaction" />
                                <x-input-error :messages="$errors->get('number_of_transaction')" class="mt-2" />
                            </div>

                            <!-- context -->
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="context" :value="__('نوع المعاملة')" />
                                <x-text-input id="context" value="{{$translation_deal->context}}" class="block mt-1 w-full" type="text" name="context"  required autofocus autocomplete="context" />
                                <x-input-error :messages="$errors->get('context')" class="mt-2" />
                            </div>

                            <!-- language -->
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="language" :value="__('اللغة')" />
                                <x-text-input id="language" value="{{$translation_deal->language}}" class="block mt-1 w-full" type="text" name="language"  required autofocus autocomplete="language" />
                                <x-input-error :messages="$errors->get('language')" class="mt-2" />
                            </div>

                            <!-- customer -->
                            <div class="mt-5 col-lg-6">
                                <div class="input-group mb-3">
                                    <label class="input-group-text" for="inputGroupSelect01">الزبون</label>
                                    <select class="form-select" id="inputGroupSelect01" name="customer_id" required>
                                        <option selected disabled>أختر....</option>
                                        @foreach($customers as  $value)
                                            @if($translation_deal->customer_id == $value->id)
                                                <option selected value="{{$value->id}}">{{$value->name}}</option>
                                            @else
                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @php
                                        $messages = $errors->get('customer_id');
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

                            <!-- price -->
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="price" :value="__('القيمة الإجمالية')" />
                                <x-text-input id="price" value="{{$translation_deal->price}}" class="block mt-1 w-full" type="number" name="price" required  autofocus autocomplete="price" />
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>

                            <!-- currency -->
                            <div class="mt-5 col-lg-6">
                                <div class="input-group mb-3">
                                    <label class="input-group-text" for="inputGroupSelect03">العملة</label>
                                    <select class="form-select" id="inputGroupSelect03" name="currency" required>
                                        @if($translation_deal->currency == 'USD')
                                            <option selected value="USD">دولار</option>
                                            <option value="D">دينار</option>
                                        @elseif($translation_deal->currency == 'D')
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

                            <!-- translator share -->
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="translator_share" :value="__('حصة المترجم')" />
                                <x-text-input id="translator_share" value="{{$translation_deal->translator_share}}" class="block mt-1 w-full" type="number" name="translator_share" required  autofocus autocomplete="translator_share" />
                                <x-input-error :messages="$errors->get('translator_share')" class="mt-2" />
                            </div>

                            <!-- academy share -->
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="academy_share" :value="__('حصة الأكادمية')" />
                                <x-text-input id="academy_share" value="{{$translation_deal->academy_share}}" class="block mt-1 w-full" type="number" name="academy_share" required  autofocus autocomplete="academy_share" />
                                <x-input-error :messages="$errors->get('academy_share')" class="mt-2" />
                            </div>

                            <!-- received -->
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="received" :value="__('القيمة المستلمة')" />
                                <x-text-input id="received" value="{{$translation_deal->received}}" class="block mt-1 w-full" type="number" name="received" required  autofocus autocomplete="received" />
                                <x-input-error :messages="$errors->get('received')" class="mt-2" />
                            </div>

                            <!-- remaining -->
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="remaining" :value="__('القيمة المتبقية')" />
                                <x-text-input id="remaining" value="{{$translation_deal->remaining}}" class="block mt-1 w-full" readonly type="number" name="remaining" required  autofocus autocomplete="remaining" />
                                <x-input-error :messages="$errors->get('remaining')" class="mt-2" />
                            </div>

                            <!-- payment method -->
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="payment_method" :value="__('طريقة الدفع')" />
                                <x-text-input id="payment_method" value="{{$translation_deal->payment_method}}" class="block mt-1 w-full" type="text" name="payment_method"   autofocus autocomplete="payment_method" />
                                <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                            </div>

                            <!-- date of receipt -->
                            <div class="mt-4 col-lg-6">
                                <label for="date_of_receipt" class="block font-medium text-sm text-gray-700">تاريخ الاستلام</label>
                                <input type="date" readonly value="{{$translation_deal->date_of_receipt}}" id="date_of_receipt" name="date_of_receipt" required class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-s" />
                            </div>

                            <!-- due date -->
                            <div class="mt-4 col-lg-6">
                                <label for="due_date" class="block font-medium text-sm text-gray-700">تاريخ الاستحقاق </label>
                                <input type="date" id="due_date"  value="{{$translation_deal->due_date}}"  name="due_date" required class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-s" />
                                @php
                                    $messages = $errors->get('due_date');
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
                    </form>
            </div>
        </div>
    </section>

<script>
    $(document).ready(function () {
        function calcRemaining() {
            var price = $('#price').val();
            var received = $('#received').val();
            var remaining = price - received;
            $("#remaining").val(remaining);
        }
        $('#price').on('input', function () {
            calcRemaining();
        });

        $('#received').on('input', function () {
            calcRemaining();
        });
    });
</script>
@stop
