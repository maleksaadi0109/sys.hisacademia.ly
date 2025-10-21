@extends('admin.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('تقرير معاملات الترجمة')}}</h2>
        </header>

        <div class="container">
            <div class="row">

                <form id="reportForm" method="POST" enctype="multipart/form-data" action="{{ route('generate.translation.report') }}">
                    @csrf
                    <div class="row">

                        <div class="mt-0 col-lg-6">
                            <label for="from_date" class="block font-medium text-sm text-gray-700"> من التاريخ</label>
                            <input id="from_date" type="date"  value="{{request('from_date')}}" name="from_date" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-s" />
                        </div>

                        <div class="mt-0 col-lg-6">
                            <label for="to_date" class="block font-medium text-sm text-gray-700">إلى التاريخ</label>
                            <input id="to_date" type="date"   value="{{request('to_date')}}" name="to_date" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-s" />
                        </div>

                        <div class="row mt-3 g-2 align-items-end">
                            <div class="col-lg-3">
                                <label for="monthInput" class="block font-medium text-sm text-gray-700">الشهر</label>
                                <input id="monthInput" type="month" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-s" />
                                {{-- This is the new error message element --}}
                                <span id="monthError" class="mt-1" style="color: red; display: none;">الرجاء تحديد الشهر أولاً.</span>
                            </div>
                            <div class="col-lg-9 d-flex gap-2 mt-4">
                                <button type="button" id="btnThisMonth" class="btn btn-primary">تقرير هذا الشهر</button>
                                <button type="button" id="btnLastMonth" class="btn btn-outline-primary">تقرير الشهر السابق</button>
                                <button type="button" id="btnShowSelectedMonth" class="btn btn-secondary">عرض تقرير الشهر المحدد</button>
                                <x-primary-button class="col-lg-2 justify-content-center">
                                    {{ __('عرض التقرير') }}
                                </x-primary-button>
                            </div>
                        </div>

                    </div>
                </form>

                @if($translationDeals)
                    <div class="container mt-4">
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

    {{-- Script updated to show/hide the error message --}}
    <script>
        (function () {
            const form = document.getElementById('reportForm');
            const fromInput = document.getElementById('from_date');
            const toInput = document.getElementById('to_date');
            const monthInput = document.getElementById('monthInput');
            const monthError = document.getElementById('monthError'); // Get the error span

            const pad = n => String(n).padStart(2, '0');
            const fmt = d => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;

            function monthRange(year, monthIndex) {
                const first = new Date(year, monthIndex, 1);
                const last = new Date(year, monthIndex + 1, 0);
                return [first, last];
            }

            function setRangeAndSubmit(from, to) {
                fromInput.value = fmt(from);
                toInput.value = fmt(to);
                form.submit();
            }

            document.getElementById('btnThisMonth').addEventListener('click', () => {
                monthError.style.display = 'none'; // Hide error if open
                const now = new Date();
                const [f, t] = monthRange(now.getFullYear(), now.getMonth());
                setRangeAndSubmit(f, t);
            });

            document.getElementById('btnLastMonth').addEventListener('click', () => {
                monthError.style.display = 'none'; // Hide error if open
                const now = new Date();
                const m = now.getMonth() - 1;
                const y = m < 0 ? now.getFullYear() - 1 : now.getFullYear();
                const mi = (m + 12) % 12;
                const [f, t] = monthRange(y, mi);
                setRangeAndSubmit(f, t);
            });

            // Updated Event Listener for Selected Month
            document.getElementById('btnShowSelectedMonth').addEventListener('click', () => {
                monthError.style.display = 'none'; // Hide error on every click

                if (!monthInput.value) {
                    // Instead of alert:
                    monthError.style.display = 'block'; // Show the red text
                    return; // Stop the function
                }

                // If value exists, continue as normal
                const [yearStr, monthStr] = monthInput.value.split('-');
                const y = parseInt(yearStr, 10);
                const m = parseInt(monthStr, 10) - 1;
                const [f, t] = monthRange(y, m);
                setRangeAndSubmit(f, t);
            });

            // (Optional but good UX) Hide the error if the user starts typing
            monthInput.addEventListener('input', () => {
                if (monthInput.value) {
                    monthError.style.display = 'none';
                }
            });
        })();
    </script>
@stop
