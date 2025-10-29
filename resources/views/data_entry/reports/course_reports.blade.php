@extends('data_entry.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('تقارير الكورسات')}}</h2>
            <p class="mt-1 text-sm text-gray-600">{{__('عرض تقارير الكورسات والطلاب المسجلين')}}</p>
        </header>

        <div class="container mt-6">
            <!-- Search and Filter Section -->
            <div class="row g-3 mb-4 no-print">
                <div class="col-md-4">
                    <input type="text" id="searchInput" class="form-control"
                           placeholder="البحث في اسم الطالب أو الكورس...">
                </div>
                <div class="col-md-3">
                    <select id="sectionFilter" class="form-select">
                        <option value="">جميع الأقسام</option>
                        @foreach($sections as $section)
                            <option value="{{ $section }}">{{ $section }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="paymentStatusFilter" class="form-select">
                        <option value="">جميع الحالات</option>
                        <option value="paid">مدفوع بالكامل</option>
                        <option value="pending">متبقي</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button id="printReport" class="btn btn-warning w-100">
                        <i class="fas fa-print me-2"></i>طباعة الجدول
                    </button>
                </div>
            </div>

            <!-- Reports Table Container -->
            <div class="row table-responsive">
                <div id="reportsTableContainer">
                    @include('data_entry.reports.partials.course_reports_table', ['revenues' => $revenues])
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

                // Section Filter
                $('#sectionFilter').on('change', function() {
                    filterReports();
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
                    const search = $('#searchInput').val().toLowerCase();
                    const section = $('#sectionFilter').val();
                    const paymentStatus = $('#paymentStatusFilter').val();

                    $.ajax({
                        url: '{{ route("data_entry.reports.course.ajax") }}',
                        type: 'GET',
                        data: {
                            search: search,
                            section: section,
                            payment_status: paymentStatus
                        },
                        success: function(response) {
                            $('#reportsTableContainer').html(response);
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
                
                body {
                    font-size: 12px;
                }
                
                .table {
                    font-size: 11px;
                }
                
                thead {
                    background-color: #f8f9fa !important;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }
                
                @page {
                    margin: 1cm;
                }
            }
            
            .print-title {
                display: none;
            }
            
            @media print {
                .print-title {
                    display: block;
                    text-align: center;
                    font-size: 18px;
                    font-weight: bold;
                    margin-bottom: 20px;
                }
            }
        </style>
        
        <div class="print-title">
            <h2>تقارير الكورسات</h2>
            <p>تاريخ الطباعة: {{ date('Y-m-d H:i:s') }}</p>
        </div>
    @endpush
@endsection

