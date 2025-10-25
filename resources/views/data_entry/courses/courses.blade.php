@extends('data_entry.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('إدارة الكورسات')}}</h2>
        </header>

        <!-- Search and Filter Section -->
        <div class="container mb-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" id="searchInput" class="form-control"
                           placeholder="البحث في الكورسات...">
                </div>
                <div class="col-md-4">
                    <select id="sectionFilter" class="form-select">
                        <option value="">جميع الأقسام</option>
                        @foreach($sections as $section)
                            <option value="{{ $section }}">{{ $section }}</option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>

        <!-- THIS SHOULD ONLY APPEAR ONCE -->
        <div class="container" id="coursesTableContainer">
            @include('data_entry.partials.courses_table', ['courses' => $courses])
        </div>
    </section>

    @push('scripts')
        <script>
            $(document).ready(function() {
                let searchTimeout;

                $('#searchInput').on('keyup', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(function() {
                        filterCourses();
                    }, 500);
                });

                $('#sectionFilter').on('change', function() {
                    filterCourses();
                });

                $('#resetFilters').on('click', function() {
                    $('#searchInput').val('');
                    $('#sectionFilter').val('');
                    filterCourses();
                });

                function filterCourses() {
                    const search = $('#searchInput').val();
                    const section = $('#sectionFilter').val();

                    $.ajax({
                        url: '{{ route("data_entry.courses") }}',
                        type: 'GET',
                        data: { search: search, section: section },
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        success: function(response) {
                            $('#coursesTableContainer').html(response);
                        },
                        error: function(xhr) {
                            console.error('Error:', xhr);
                        }
                    });
                }

                $(document).on('click', '.pagination a', function(e) {
                    e.preventDefault();
                    const url = $(this).attr('href');
                    const search = $('#searchInput').val();
                    const section = $('#sectionFilter').val();

                    $.ajax({
                        url: url,
                        type: 'GET',
                        data: { search: search, section: section },
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        success: function(response) {
                            $('#coursesTableContainer').html(response);
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
