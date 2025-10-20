@extends('financial.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('استكمال المتبقي من الكورس')}}</h2>
        </header>

    <div class="container">
        <div class="row">
            <form method="POST" action="{{route('financial.update.revenue',['id' => $revenue->id])}}">
                @csrf
                @method('patch')
                <div class="row">
                    <h5>سعر هذا الكورس (السعر: {{$revenue->course->price}})</h5>
                    <h5>المدفوع من هذه الفاتورة:( {{$revenue->value_rec}})</h5>
                    <h5>المتبقي من هذه الفاتورة:( {{$revenue->value_rem}})</h5>
                    <div class="mt-4 col-lg-6">
                        <x-input-label for="value_rec" :value="__('القيمة المدفوعة')" />
                        <x-text-input id="value_rec" value="{{old('value_rec')}}" class="block mt-1 w-full" type="number" min="0" name="value_rec" required  autofocus autocomplete="value_rec" />
                        <x-input-error :messages="$errors->get('value_rec')" class="mt-2" />
                    </div>

                    <div class="row">
                        <x-primary-button class="mt-4 col-lg-2 justify-content-center">
                            {{ __('حفظ') }}
                        </x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </section>
@stop