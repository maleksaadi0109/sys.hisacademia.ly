@extends('data_entry.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('تقارير الدبلومات')}}</h2>
            <p class="mt-1 text-sm text-gray-600">{{__('عرض تقارير الدبلومات والطلاب المسجلين')}}</p>
        </header>

        <div class="container mt-6">
            <!-- Search and Filter Section -->
            <div class="row g-3 mb-4">
                <div class="col-md-5">
                    <input type="text" id="searchInput" class="form-control"
                           placeholder="البحث في اسم الطالب أو الدبلوم...">
                </div>
                <div class="col-md-4">
                    <select id="paymentStatusFilter" class="form-select">
                        <option value="">جميع الحالات</option>
                        <option value="paid">مدفوع بالكامل</option>
                        <option value="pending">متبقي</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button id="printReport" class="btn btn-warning w-100">
                        <i class="fas fa-print me-2"></i>طباعة
                    </button>
                </div>
            </div>

            <!-- Reports Table Container -->
            <div class="row table-responsive">
                <div id="reportsTableContainer">
                    @include('data_entry.reports.partials.diploma_reports_table', ['revenues' => $revenues])
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            $(document).ready(function() {
                let searchTimeout;

                // Live Search
                $('#searchInput').on('keyup', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(function() {
                        filterReports();
                    }, 300);
                });

                // Payment Status Filter
                $('#paymentStatusFilter').on('change', function() {
                    filterReports();
                });

                // Print Report
                $('#printReport').on('click', function() {
                    window.print();
                });

                function filterReports() {
                    const search = $('#searchInput').val();
                    const paymentStatus = $('#paymentStatusFilter').val();

                    $.ajax({
                        url: '{{ route("data_entry.reports.diploma") }}',
                        type: 'GET',
                        data: {
                            search: search,
                            payment_status: paymentStatus
                        },
                        success: function(response) {
                            $('#reportsTableContainer').html($(response).find('#reportsTableContainer').html());
                        },
                        error: function() {
                            alert('حدث خطأ أثناء تحميل البيانات');
                        }
                    });
                }
            });
        </script>

        <style>
            @media print {
                .no-print {
                    display: none !important;
                }
            }
        </style>
    @endpush
@endsection

