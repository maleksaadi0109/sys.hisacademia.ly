@extends('financial.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('تقرير معاملات الترجمة')}}</h2>
        </header>

        <div class="container">
            <div class="row">

                    <form method="POST" enctype="multipart/form-data" action="{{ route('financial.generate.translation.report') }}">
                        @csrf
                        <div class="row">

                            <div class="mt-0 col-lg-6">
                                <label for="from_date" class="block font-medium text-sm text-gray-700"> من التاريخ</label>
                                <input type="date"  value="{{request('from_date')}}" name="from_date" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-s" />
                            </div>

                            <div class="mt-0 col-lg-6">
                                <label for="to_date" class="block font-medium text-sm text-gray-700">إلى التاريخ</label>
                                <input type="date"   value="{{request('to_date')}}" name="to_date" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-s" />
                            </div>

                            <div class="row">
                                <x-primary-button class="mt-4 col-lg-2 justify-content-center">
                                    {{ __('عرض التقرير') }}
                                </x-primary-button>
                            </div>

                        </div>
                    </form>

                    @if($translationDeals)
                        <div class="container">
                            <div class="row table-responsive">
                                <table class="table table-light table-hover text-center">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">اسم الزبون</th>
                                            <th scope="col">عدد الاوراق</th>
                                            <th scope="col">عدد المعاملات</th>
                                            <th scope="col">نوع المعاملة</th>
                                            <th scope="col">اللغة</th>
                                            <th scope="col">السعر</th>
                                            <th scope="col">العملة</th>
                                            <th scope="col">طريقة الدفع</th>
                                            <th scope="col">تاريخ الاستلام</th>
                                            <th scope="col">تاريخ الاستحقاق</th>
                                            <th scope="col">تاريخ التسليم</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($translationDeals as $translationDeal) 
                                        <tr>
                                            <td scope="row">{{ $translationDeal->id }}</td>
                                            <td scope="row">{{ $translationDeal->customer->name }}</td>
                                            <td scope="row">{{ $translationDeal->number_of_sheets }}</td>
                                            <td scope="row">{{ $translationDeal->number_of_transaction }}</td>
                                            <td scope="row">{{ $translationDeal->context }}</td>
                                            <td scope="row">{{ $translationDeal->language }}</td>
                                            <td scope="row">{{ $translationDeal->price }}</td>
                                            <td scope="row">{{ $translationDeal->currency }}</td>
                                            <td scope="row">{{ $translationDeal->payment_method }}</td>
                                            <td scope="row">{{ $translationDeal->date_of_receipt }}</td>
                                            <td scope="row">{{ $translationDeal->due_date }}</td>
                                            <td scope="row">{{ $translationDeal->delivery_date == null ? "لم يتم التسليم" :$translationDeal->delivery_date }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $translationDeals->links() }}
                            </div>
                        </div>
                    @endif
        </div>
    </div>
</section>
@stop