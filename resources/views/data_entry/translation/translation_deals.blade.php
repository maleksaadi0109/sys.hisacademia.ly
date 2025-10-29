@extends('data_entry.dashboard')
@section('content')
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">{{__('إدارة المعاملات')}}</h2>
    </header>
    
    <div class="container">
        <!-- Add Translation Deal Button -->
        <div class="row mb-4">
            <div class="col-12">
                <a href="{{ route('data_entry.add.translation_deal') }}" class="btn btn-success btn-lg">
                    <i class="fas fa-plus me-2"></i>إضافة معاملة جديدة
                </a>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" id="searchInput" class="form-control"
                       placeholder="البحث في اسم الزبون أو اللغة أو نوع المعاملة...">
            </div>
            <div class="col-md-2">
                <select id="languageFilter" class="form-select">
                    <option value="">جميع اللغات</option>
                    @foreach($languages as $language)
                        <option value="{{ $language }}">{{ $language }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select id="paymentMethodFilter" class="form-select">
                    <option value="">جميع طرق الدفع</option>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method }}">{{ $method }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select id="currencyFilter" class="form-select">
                    <option value="">جميع العملات</option>
                    @foreach($currencies as $currency)
                        <option value="{{ $currency }}">{{ $currency }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button id="resetFilters" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-undo me-2"></i>إعادة تعيين
                </button>
            </div>
        </div>

        <!-- Translation Deals Table Container -->
        <div class="row table-responsive">
            <div id="translationDealsTableContainer">
                @include('data_entry.translation.partials.translation_deals_table', ['translationDeals' => $translationDeals])
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
                    filterTranslationDeals();
                }, 300);
            });

            // Language Filter
            $('#languageFilter').on('change', function() {
                filterTranslationDeals();
            });

            // Payment Method Filter
            $('#paymentMethodFilter').on('change', function() {
                filterTranslationDeals();
            });

            // Currency Filter
            $('#currencyFilter').on('change', function() {
                filterTranslationDeals();
            });

            // Reset Filters
            $('#resetFilters').on('click', function() {
                $('#searchInput').val('');
                $('#languageFilter').val('');
                $('#paymentMethodFilter').val('');
                $('#currencyFilter').val('');
                filterTranslationDeals();
            });

            function filterTranslationDeals() {
                const search = $('#searchInput').val();
                const language = $('#languageFilter').val();
                const paymentMethod = $('#paymentMethodFilter').val();
                const currency = $('#currencyFilter').val();

                // Show loading indicator
                $('#translationDealsTableContainer').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> جاري التحميل...</div>');

                $.ajax({
                    url: '{{ route("data_entry.translation_deals.ajax") }}',
                    type: 'GET',
                    data: {
                        search: search,
                        language: language,
                        payment_method: paymentMethod,
                        currency: currency
                    },
                    success: function(response) {
                        $('#translationDealsTableContainer').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        console.error('Response:', xhr.responseText);
                        console.error('Status:', status);
                        
                        let errorMessage = 'حدث خطأ أثناء تحميل البيانات';
                        if (xhr.status === 404) {
                            errorMessage = 'الصفحة غير موجودة';
                        } else if (xhr.status === 500) {
                            errorMessage = 'خطأ في الخادم';
                        } else if (xhr.status === 0) {
                            errorMessage = 'لا يمكن الاتصال بالخادم';
                        }
                        
                        $('#translationDealsTableContainer').html('<div class="alert alert-danger text-center">' + errorMessage + '</div>');
                    }
                });
            }
        });
    </script>
@endpush
@stop