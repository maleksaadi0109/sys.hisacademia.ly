@extends('data_entry.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('تعديل بيانات الزبون')}}</h2>
        </header>

        <div class="container">
            <div class="row">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('data_entry.update.customer',['id' => $customer->id]) }}">
                        @csrf
                        @method('patch')
                        <div class="row">

                            <!-- Name -->
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="name" :value="__('الاسم')" />
                                <x-text-input id="name" value="{{$customer->name}}" class="block mt-1 w-full" type="text" name="name"  required autofocus autocomplete="name" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- phone -->
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="naphoneme" :value="__('رقم الهاتف')" />
                                <x-text-input id="phone" value="{{$customer->phone}}" class="block mt-1 w-full" type="text" name="phone"  required autofocus autocomplete="phone" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>

                            <!-- address -->
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="address" :value="__('العنوان')" />
                                <x-text-input id="address" value="{{$customer->address}}" class="block mt-1 w-full" type="text" name="address"  required autofocus autocomplete="address" />
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
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
@stop