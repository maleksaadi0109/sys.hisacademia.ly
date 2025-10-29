@extends('data_entry.dashboard')

@section('content')
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">{{__('إدارة بيانات الزبائن')}}</h2>
    </header>

    <div class="container">
        <!-- Add Customer Button -->
        <div class="row mb-4">
            <div class="col-12">
                <a href="{{ route('data_entry.add.customer') }}" class="btn btn-success btn-lg">
                    <i class="fas fa-plus me-2"></i>إضافة زبون جديد
                </a>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <input type="text" id="searchInput" class="form-control"
                       placeholder="البحث في اسم الزبون أو رقم الهاتف...">
            </div>
            <div class="col-md-4">
                <select id="addressFilter" class="form-select">
                    <option value="">جميع العناوين</option>
                    @foreach($addresses as $address)
                        <option value="{{ $address }}">{{ $address }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button id="resetFilters" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-undo me-2"></i>إعادة تعيين
                </button>
            </div>
        </div>

        <!-- Customers Table Container -->
        <div class="row table-responsive">
            <div id="customersTableContainer">
                @include('data_entry.translation.partials.customers_table', ['customers' => $customers])
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
                    filterCustomers();
                }, 300);
            });

            // Address Filter
            $('#addressFilter').on('change', function() {
                filterCustomers();
            });

            // Reset Filters
            $('#resetFilters').on('click', function() {
                $('#searchInput').val('');
                $('#addressFilter').val('');
                filterCustomers();
            });

            function filterCustomers() {
                const search = $('#searchInput').val();
                const address = $('#addressFilter').val();

                // Show loading indicator
                $('#customersTableContainer').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> جاري التحميل...</div>');

                $.ajax({
                    url: '{{ route("data_entry.customers.ajax") }}',
                    type: 'GET',
                    data: {
                        search: search,
                        address: address
                    },
                    success: function(response) {
                        $('#customersTableContainer').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        console.error('Response:', xhr.responseText);
                        console.error('Status:', status);
                        console.error('XHR:', xhr);
                        
                        let errorMessage = 'حدث خطأ أثناء تحميل البيانات';
                        if (xhr.status === 404) {
                            errorMessage = 'الصفحة غير موجودة';
                        } else if (xhr.status === 500) {
                            errorMessage = 'خطأ في الخادم';
                        } else if (xhr.status === 0) {
                            errorMessage = 'لا يمكن الاتصال بالخادم';
                        }
                        
                        $('#customersTableContainer').html('<div class="alert alert-danger text-center">' + errorMessage + '</div>');
                    }
                });
            }
        });
    </script>
@endpush
</section>

@stop