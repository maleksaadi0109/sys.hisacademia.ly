@extends('admin.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('تقرير الكورسات')}}</h2>
        </header>

        <div class="container">
            <div class="row">

                <form id="reportForm" method="POST" enctype="multipart/form-data" action="{{ route('generate.course.report') }}">
                    @csrf
                    <div class="row">

                        <div class="mt-0 col-lg-6">
                            <label for="from_date" class="block font-medium text-sm text-gray-700">يبدأ من التاريخ</label>
                            {{-- Added required --}}
                            <input id="from_date" type="date"  value="{{request('from_date')}}" name="from_date" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-s" required />
                        </div>

                        <div class="mt-0 col-lg-6">
                            <label for="to_date" class="block font-medium text-sm text-gray-700">ينتهي في التاريخ</label>
                            {{-- Added required --}}
                            <input id="to_date" type="date"   value="{{request('to_date')}}" name="to_date" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-s" required />
                        </div>

                        <div class="row mt-3 g-2 align-items-end">
                            <div class="col-lg-3">
                                <label for="monthInput" class="block font-medium text-sm text-gray-700">الشهر</label>
                                <input id="monthInput" type="month" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-s" />
                                {{-- Added error message span --}}
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

                @php
                    use App\Enums\WeekDays;
                @endphp
                @if($courses)
                    <div class="container mt-4"> {{-- Added margin-top --}}
                        <div class="row table-responsive">
                            <table class="table table-light table-hover text-center">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">اسم الكورس</th>
                                    <th scope="col">اسم الدبلوم</th>
                                    <th scope="col">القسم</th>
                                    <th scope="col">تاريخ البداية</th>
                                    <th scope="col">تاريخ النهاية</th>
                                    <th scope="col">المستوى</th>
                                    <th scope="col">توقيت البداية</th>
                                    <th scope="col">توقيت النهاية</th>
                                    <th scope="col">المدة الاجمالية</th>
                                    <th scope="col">المعدل اليومي للساعات</th>
                                    <th scope="col">اجمالي عدد الساعات</th>
                                    <th scope="col">عدد أيام الدراسة بالأسبوع</th>
                                    <th scope="col">أيام الدراسة </th>
                                    <th scope="col">اسم المدرس</th>
                                    <th scope="col">سعر الكورس</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($courses as $course)
                                    <tr>
                                        <td scope="row">{{ $course->id }}</td>
                                        <td scope="row">{{ $course->name }}</td>
                                        <td scope="row">{{$course->diploma != null? $course->diploma->name : "لا ينتمي الى دبلوم"}}</td>
                                        <td scope="row">{{ $course->section }}</td>
                                        <td scope="row">{{ $course->start_date }}</td>
                                        <td scope="row">{{ $course->end_date }}</td>
                                        <td scope="row">{{ $course->level }}</td>
                                        <td scope="row">{{ date("h:i A", strtotime($course->start_time)) }}</td>
                                        <td scope="row">{{ date("h:i A", strtotime($course->end_time)) }}</td>
                                        <td scope="row">{{ $course->total_days }}</td>
                                        <td scope="row">{{ $course->average_hours }}</td>
                                        <td scope="row">{{ $course->total_hours }}</td>
                                        <td scope="row">{{ $course->n_d_per_week }}</td>
                                        <td scope="row">
                                            <div class="div_days_table">
                                                @foreach(json_decode($course->days) as $value)
                                                    <span class="days_table">{{WeekDays::WeekDaysAr()[$value]}}</span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td scope="row">{{ $course->user->name }}</td>
                                        <td scope="row">{{ $course->price }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $courses->links() }}
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
