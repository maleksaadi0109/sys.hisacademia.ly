@extends('data_entry.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('إدارة الدبلومات')}}</h2>
        </header>
        @php
            use App\Enums\WeekDays;
        @endphp

        <div class="container">
            <!-- Diploma Selection Form -->
            <div class="row mb-5">
                <div class="col-lg-6">
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="diplomaSelect">اسم الدبلوم</label>
                        <select class="form-select" id="diplomaSelect" name="diploma_id" required>
                            <option value="">أختر....</option>
                            @foreach($allDiploma as $diploma)
                                <option value="{{$diploma->id}}"
                                    {{ request('diploma_id') == $diploma->id ? 'selected' : '' }}>
                                    {{$diploma->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            @if($diplomaCourse && count($diplomaCourse) > 0)
                <!-- Diploma Actions -->


                <!-- Search and Filter Section -->
                <div class="container mb-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="text" id="searchInput" class="form-control"
                                   placeholder="البحث في الكورسات (الاسم، القسم، المستوى، المدرس)..."
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4">
                            <select id="sectionFilter" class="form-select">
                                <option value="">جميع الأقسام</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section }}" {{ request('section') == $section ? 'selected' : '' }}>
                                        {{ $section }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>

                <!-- Diploma Courses Table Container -->
                <div class="container" id="diplomaCoursesTableContainer">
                    @include('data_entry.partials.diploma_table', ['diplomaCourse' => $diplomaCourse])
                </div>
            @endif
        </div>
    </section>

    @push('scripts')
        <script>
            $(document).ready(function() {
                let searchTimeout;

                // Diploma selection change
                $('#diplomaSelect').on('change', function() {
                    const diplomaId = $(this).val();
                    if (diplomaId) {
                        window.location.href = '{{ route("data_entry.diploma.course") }}?diploma_id=' + diplomaId;
                    }
                });

                // Live search with debounce
                $('#searchInput').on('keyup', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(function() {
                        filterDiplomaCourses();
                    }, 500);
                });

                // Section filter change
                $('#sectionFilter').on('change', function() {
                    filterDiplomaCourses();
                });

                // Reset filters
                $('#resetFilters').on('click', function() {
                    $('#searchInput').val('');
                    $('#sectionFilter').val('');
                    filterDiplomaCourses();
                });

                // Filter function
                function filterDiplomaCourses() {
                    const search = $('#searchInput').val();
                    const section = $('#sectionFilter').val();
                    const diplomaId = $('#diplomaSelect').val();

                    if (!diplomaId) {
                        return;
                    }

                    $.ajax({
                        url: '{{ route("data_entry.diploma.course") }}',
                        type: 'GET',
                        data: {
                            diploma_id: diplomaId,
                            search: search,
                            section: section
                        },
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        success: function(response) {
                            $('#diplomaCoursesTableContainer').html(response);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            alert('حدث خطأ أثناء تحميل البيانات');
                        }
                    });
                }
            });
        </script>
    @endpush

@stop
