@extends('data_entry.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('إضافة زبون')}}</h2>
        </header>

        <div class="container">
            <!-- Back Button -->
            <div class="row mb-4">
                <div class="col-12">
                    <a href="{{ route('data_entry.customers', ['orderBy' => 'null', 'sort' => 'null']) }}" 
                       class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-right me-2"></i>العودة إلى قائمة الزبائن
                    </a>
                </div>
            </div>
            <div class="row">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('data_entry.register.customer') }}">
                        @csrf
                        <div class="row">

                            <!-- Name -->
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="name" :value="__('الاسم')" />
                                <x-text-input id="name" value="{{old('name')}}" class="block mt-1 w-full" type="text" name="name"  required autofocus autocomplete="name" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- phone -->
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="naphoneme" :value="__('رقم الهاتف')" />
                                <x-text-input id="phone" value="{{old('phone')}}" class="block mt-1 w-full" type="text" name="phone"  required autofocus autocomplete="phone" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>

                            <!-- address -->
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="address" :value="__('العنوان')" />
                                <x-text-input id="address" value="{{old('address')}}" class="block mt-1 w-full" type="text" name="address"  required autofocus autocomplete="address" />
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>
                        </div>

                        <div class="row">
                            <x-primary-button class="mt-4 col-lg-2 justify-content-center">
                                {{ __('حفظ') }}
                            </x-primary-button>
                        </div>
                    </form>
            </div>
        </div>
    </section>
@stop