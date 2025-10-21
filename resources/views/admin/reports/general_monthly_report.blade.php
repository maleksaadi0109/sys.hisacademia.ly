@extends('admin.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{ __('التقرير الشهري الشامل') }}</h2>
        </header>

        <div class="container">
            <div class="row">
                <form id="reportForm" method="POST" enctype="multipart/form-data" action="{{ route('generate.general.monthly.report') }}">
                    @csrf
                    <div class="row">
                        <div class="mt-0 col-lg-6">
                            <label for="from_date" class="block font-medium text-sm text-gray-700">يبدأ من التاريخ</label>
                            {{-- Added required --}}
                            <input id="from_date" type="date" value="{{ request('from_date') }}" name="from_date" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-s" required />
                        </div>

                        <div class="mt-0 col-lg-6">
                            <label for="to_date" class="block font-medium text-sm text-gray-700">ينتهي في التاريخ</label>
                            {{-- Added required --}}
                            <input id="to_date" type="date" value="{{ request('to_date') }}" name="to_date" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-s" required />
                        </div>

                        <div class="row mt-3 g-2 align-items-end">
                            <div class="col-lg-3">
                                <label for="monthInput" class="block font-medium text-sm text-gray-700">الشهر</label>
                                <input id="monthInput" type="month" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-s" />
                                {{-- Added error message span --}}
                                <span id="monthError" class="mt-1" style="color: red; display: none;">الرجاء تحديد الشهر أولاً.</span>
                            </div>
                            <div class="col-lg-9 d-flex gap-2 mt-4">
                                {{-- Updated button text --}}
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

                {{-- Changed to isset($data) and added null coalescing (?? 0) for safety --}}
                @if(isset($data))
                    <div class="container mt-4">
                        <div class="row table-responsive">
                            <table class="table table-hover text-center table-bordered">
                                <thead class="table-dark">
                                <tr>
                                    <th scope="col" colspan="9">عدد الكورسات التي تم بيعها</th>
                                </tr>
                                </thead>
                                <tbody class="table-success">
                                <tr>
                                    <td colspan="9">{{ $data['count_course'] ?? 0 }}</td>
                                </tr>
                                </tbody>
                            </table>

                            <table class="table table-hover text-center table-bordered">
                                <thead class="table-dark">
                                <tr>
                                    <th scope="col" colspan="9">عدد معاملات الترجمة التي تم بيعها</th>
                                </tr>
                                </thead>
                                <tbody class="table-success">
                                <tr>
                                    <td colspan="9">{{ $data['count_translation'] ?? 0 }}</td>
                                </tr>
                                </tbody>
                            </table>

                            <table class="table table-hover text-center table-bordered">
                                <thead class="table-dark">
                                <tr>
                                    <th scope="col" colspan="9">عدد الدبلومات التي تم بيعها</th>
                                </tr>
                                </thead>
                                <tbody class="table-success">
                                <tr>
                                    <td colspan="9">{{ $data['count_diploma'] ?? 0 }}</td>
                                </tr>
                                </tbody>
                            </table>

                            <table class="table table-hover text-center table-bordered">
                                <thead>
                                <tr class="table-primary">
                                    <th scope="col" colspan="9">ايرادات الدولار</th>
                                </tr>
                                <tr class="table-primary">
                                    <th scope="col" colspan="3">ايرادات الكورسات</th>
                                    <th scope="col" colspan="3">ايرادات الترجمة</th>
                                    <th scope="col" colspan="3">ايرادات الدبلومات</th>
                                </tr>
                                <tr class="table-dark">
                                    <th scope="col">الإجمالي</th>
                                    <th scope="col">المدفوع</th>
                                    <th scope="col">المتبقي</th>
                                    <th scope="col">الإجمالي</th>
                                    <th scope="col">المدفوع</th>
                                    <th scope="col">المتبقي</th>
                                    <th scope="col">الإجمالي</th>
                                    <th scope="col">المدفوع</th>
                                    <th scope="col">المتبقي</th>
                                </tr>
                                </thead>
                                <tbody class="table-success">
                                <tr>
                                    <td>{{ $data['t_course_price_usd'] ?? 0 }}</td>
                                    <td>{{ $data['t_course_rec_usd'] ?? 0 }}</td>
                                    <td>{{ $data['t_course_rem_usd'] ?? 0 }}</td>
                                    <td>{{ $data['t_translation_price_usd'] ?? 0 }}</td>
                                    <td>{{ $data['t_translation_rec_usd'] ?? 0 }}</td>
                                    <td>{{ $data['t_translation_rem_usd'] ?? 0 }}</td>
                                    <td>{{ $data['t_diploma_price_usd'] ?? 0 }}</td>
                                    <td>{{ $data['t_diploma_rec_usd'] ?? 0 }}</td>
                                    <td>{{ $data['t_diploma_rem_usd'] ?? 0 }}</td>
                                </tr>
                                <tr class="table-dark">
                                    <th colspan="3">الاجمالي الكلي</th>
                                    <th colspan="3">المدفوع الكلي</th>
                                    <th colspan="3">المتبقي الكلي</th>
                                </tr>
                                <tr>
                                    <td colspan="3">{{ $data['t_price_usd'] ?? 0 }}</td>
                                    <td colspan="3">{{ $data['t_rec_usd'] ?? 0 }}</td>
                                    <td colspan="3">{{ $data['t_rem_usd'] ?? 0 }}</td>
                                </tr>
                                </tbody>
                            </table>

                            <table class="table table-hover text-center table-bordered">
                                <thead>
                                <tr class="table-primary">
                                    <th scope="col" colspan="9">ايرادات الدينار</th>
                                </tr>
                                <tr class="table-primary">
                                    <th scope="col" colspan="3">ايرادات الكورسات</th>
                                    <th scope="col" colspan="3">ايرادات الترجمة</th>
                                    <th scope="col" colspan="3">ايرادات الدبلومات</th>
                                </tr>
                                <tr class="table-dark">
                                    <th scope="col">الإجمالي</th>
                                    <th scope="col">المدفوع</th>
                                    <th scope="col">المتبقي</th>
                                    <th scope="col">الإجمالي</th>
                                    <th scope="col">المدفوع</th>
                                    <th scope="col">المتبقي</th>
                                    <th scope="col">الإجمالي</th>
                                    <th scope="col">المدفوع</th>
                                    <th scope="col">المتبقي</th>
                                </tr>
                                </thead>
                                <tbody class="table-success">
                                <tr>
                                    <td>{{ $data['t_course_price_D'] ?? 0 }}</td>
                                    <td>{{ $data['t_course_rec_D'] ?? 0 }}</td>
                                    <td>{{ $data['t_course_rem_D'] ?? 0 }}</td>
                                    <td>{{ $data['t_translation_price_D'] ?? 0 }}</td>
                                    <td>{{ $data['t_translation_rec_D'] ?? 0 }}</td>
                                    <td>{{ $data['t_translation_rem_D'] ?? 0 }}</td>
                                    <td>{{ $data['t_diploma_price_D'] ?? 0 }}</td>
                                    <td>{{ $data['t_diploma_rec_D'] ?? 0 }}</td>
                                    <td>{{ $data['t_diploma_rem_D'] ?? 0 }}</td>
                                </tr>
                                <tr class="table-dark">
                                    <th colspan="3">الاجمالي الكلي</th>
                                    <th colspan="3">المدفوع الكلي</th>
                                    <th colspan="3">المتبقي الكلي</th>
                                </tr>
                                <tr>
                                    <td colspan="3">{{ $data['t_price_D'] ?? 0 }}</td>
                                    <td colspan="3">{{ $data['t_rec_D'] ?? 0 }}</td>
                                    <td colspan="3">{{ $data['t_rem_D'] ?? 0 }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Updated script with error handling --}}
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
                monthError.style.display = 'none'; // Hide error
                const now = new Date();
                const [f, t] = monthRange(now.getFullYear(), now.getMonth());
                setRangeAndSubmit(f, t);
            });

            document.getElementById('btnLastMonth').addEventListener('click', () => {
                monthError.style.display = 'none'; // Hide error
                const now = new Date();
                const m = now.getMonth() - 1;
                const y = m < 0 ? now.getFullYear() - 1 : now.getFullYear();
                const mi = (m + 12) % 12;
                const [f, t] = monthRange(y, mi);
                setRangeAndSubmit(f, t);
            });

            // Updated listener for selected month
            document.getElementById('btnShowSelectedMonth').addEventListener('click', () => {
                monthError.style.display = 'none'; // Hide error on click

                if (!monthInput.value) {
                    monthError.style.display = 'block'; // Show error
                    return; // Stop
                }

                // Continue if value exists
                const [yearStr, monthStr] = monthInput.value.split('-');
                const y = parseInt(yearStr, 10);
                const m = parseInt(monthStr, 10) - 1;
                const [f, t] = monthRange(y, m);
                setRangeAndSubmit(f, t);
            });

            // (Optional) Hide error if user selects a month
            monthInput.addEventListener('input', () => {
                if (monthInput.value) {
                    monthError.style.display = 'none';
                }
            });
        })();
    </script>
@stop
