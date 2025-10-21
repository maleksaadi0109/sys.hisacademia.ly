@extends('financial.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('استكمال المتبقي من المعاملة')}}</h2>
        </header>

    <div class="container">
        <div class="row">
            <form method="POST" action="{{route('financial.update.translation_revenue',['id' => $translationDel->id])}}">
                @csrf
                @method('patch')
                <div class="row">
                    <h5>تكلفة هذه المعاملة (السعر: {{$translationDel->price}})</h5>
                    <h5>المدفوع من هذه الفاتورة:( {{$translationDel->received}})</h5>
                    <h5>المتبقي من هذه الفاتورة:( {{$translationDel->remaining}})</h5>
                    <div class="mt-4 col-lg-6">
                        <x-input-label for="received" :value="__('القيمة المدفوعة')" />
                        <x-text-input id="received" value="{{old('received')}}" class="block mt-1 w-full" type="number" min="0" name="received" required  autofocus autocomplete="received" />
                        <x-input-error :messages="$errors->get('received')" class="mt-2" />
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