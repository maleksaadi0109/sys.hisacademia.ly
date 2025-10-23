@extends('data_entry.dashboard')

@section('content')
    <section>
        <header class="flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-900">{{ __('إدارة بيانات الدراسة للطالب') }}</h2>
            <div>
                {{-- تأكد من وجود مسار باسم 'data_entry.students.create' أو قم بتحديث الرابط  --}}
                <a href="{{route("data_entry.add.student")}}" class="btn btn-primary">{{ __('إضافة طالب جديد') }}</a>
            </div>
        </header>

        <!-- Search Bar Form -->
        <div class="my-4">
            <input
                type="text"
                id="searchInput"
                class="form-control w-full"
                placeholder="ابحث باسم الطالب..."
                value="{{ request('search') }}"
                aria-label="Search by student name">
        </div>

        <!-- Container for the results table -->
        <div id="studentsTableContainer">
            {{-- The initial table content is loaded here --}}
            @include('data_entry.partials.students_table', ['students' => $students])
        </div>

    </section>

    <!-- AJAX script for live search, sorting, and pagination -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const tableContainer = document.getElementById('studentsTableContainer');
            let typingTimer;
            const debounceTime = 400; // 400ms delay

            // Function to fetch and update the table content
            function fetchStudents(url) {
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest' // Helps Laravel detect this is an AJAX request
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text();
                    })
                    .then(html => {
                        tableContainer.innerHTML = html;
                    })
                    .catch(error => {
                        console.error('There was a problem with the fetch operation:', error);
                        tableContainer.innerHTML = '<p class="text-center text-red-500">حدث خطأ أثناء تحميل البيانات.</p>';
                    });
            }

            // Listener for the search input field
            searchInput.addEventListener('keyup', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => {
                    const query = this.value;
                    // Build the URL with the current search query, preserving existing sort order
                    // The fix is here: Provide default parameters to the route() helper
                    const url = new URL("{{ route('data_entry.students', ['orderBy' => 'id', 'sort' => 'asc']) }}");
                    url.searchParams.set('search', query);

                    fetchStudents(url.toString());
                }, debounceTime);
            });

            // Use event delegation to handle clicks on pagination and sorting links
            tableContainer.addEventListener('click', function(event) {
                // Target links within pagination or table headers
                const targetLink = event.target.closest('.pagination a, thead a');

                if (targetLink) {
                    event.preventDefault(); // Stop the browser from following the link
                    const url = targetLink.getAttribute('href');
                    if (url) {
                        fetchStudents(url);
                    }
                }
            });
        });
    </script>
@stop

