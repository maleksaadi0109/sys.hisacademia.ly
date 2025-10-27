@extends('data_entry.dashboard')


{{-- The $spaces variable is expected to be passed from the controller --}}
{{-- Example Controller Logic: --}}
{{-- use App\Models\CoworkingSpace; --}}
{{-- $spaces = CoworkingSpace::latest()->paginate(15); --}}
{{-- return view('data_entry.coworking_spaces.index', compact('spaces')); --}}

@section('content')
    {{-- Added dir="rtl" and text-align: right style --}}
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" dir="rtl" style="text-align: right;">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">إدارة مساحات العمل</h1>

        </div>

        {{-- Search and Filter Form --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <input type="text" id="liveSearchInput" class="form-control" placeholder="ابحث بالاسم أو الموقع...">
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                {{-- Placeholder for filters --}}
            </div>
        </div>

        {{-- Coworking Spaces Table - Styled similar to the example --}}
        <div class="table-responsive">
            {{-- Added table-bordered and explicit header background color --}}
            <table class="table table-bordered table-striped table-sm text-center">
                <thead class="table-primary"> {{-- Using table-primary for header bg like example --}}
                <tr>
                    {{-- Added specific width to the '#' column --}}
                    <th scope="col" style="width: 5%;">#</th>
                    <th scope="col">الاسم</th>
                    <th scope="col">الموقع</th>
                    <th scope="col">السعر اليومي</th>
                    <th scope="col">السعر الأسبوعي</th>
                    <th scope="col">السعر الشهري</th>
                    <th scope="col">سعر 3 أشهر</th>
                    <th scope="col">وقت الفتح</th>
                    <th scope="col">وقت الإغلاق</th>
                    <th scope="col">سرعة الإنترنت</th>
                    <th scope="col">تاريخ الإنشاء</th>
                    {{-- Removed Actions column --}}
                </tr>
                </thead>
                <tbody id="spacesTableBody">
                {{-- Use the $spaces variable passed from the controller --}}
                @forelse ($spaces as $space)
                    <tr>
                        <td>{{ $space->id }}</td>
                        <td>{{ $space->name }}</td>
                        <td>{{ $space->location ?? '-' }}</td>
                        <td>{{ number_format($space->daily_price ?? 0, 2) }} د.ل</td>
                        <td>{{ number_format($space->weekly_price ?? 0, 2) }} د.ل</td>
                        <td>{{ number_format($space->monthly_price ?? 0, 2) }} د.ل</td>
                        <td>{{ number_format($space->three_month_price ?? 0, 2) }} د.ل</td>
                        <td>{{ $space->open_time ? \Carbon\Carbon::parse($space->open_time)->format('h:i A') : '-' }}</td>
                        <td>{{ $space->close_time ? \Carbon\Carbon::parse($space->close_time)->format('h:i A') : '-' }}</td>
                        <td>{{ $space->internet_speed ?? '-' }}</td>
                        <td>{{ $space->created_at ? $space->created_at->format('Y-m-d') : '-' }}</td>
                        {{-- Removed Actions column content --}}
                    </tr>
                @empty
                    <tr>
                        {{-- Adjusted colspan --}}
                        <td colspan="11">لا توجد مساحات عمل لعرضها.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Links --}}
        <div class="mt-3 d-flex justify-content-center">
            {{-- Check if $spaces exists and is paginated before rendering links --}}
            @if(isset($spaces) && method_exists($spaces, 'links'))
                {{ $spaces->links() }}
            @endif
        </div>

    </main>

    {{-- Simple Live Search Script (remains the same) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('liveSearchInput');
            const tableBody = document.getElementById('spacesTableBody');
            const tableRows = tableBody.getElementsByTagName('tr');

            // Handle case where table might initially be empty
            if (!tableRows || tableRows.length === 0) return;

            // Check if the only row is the "empty" message row
            const isEmptyRow = tableRows.length === 1 && tableRows[0].cells.length > 1 && tableRows[0].cells[0].getAttribute('colspan');

            searchInput.addEventListener('keyup', function () {
                const searchTerm = searchInput.value.toLowerCase();
                let foundMatch = false;

                for (let i = 0; i < tableRows.length; i++) {
                    const row = tableRows[i];
                    // Skip the "empty" row during filtering
                    if (row.cells.length > 1 && row.cells[0].getAttribute('colspan')) {
                        row.style.display = 'none'; // Initially hide empty row during search
                        continue;
                    }

                    // Indices adjusted because Actions column is removed
                    const nameCell = row.cells[1];
                    const locationCell = row.cells[2];
                    let rowText = '';

                    if (nameCell) {
                        rowText += (nameCell.textContent || nameCell.innerText).toLowerCase();
                    }
                    if (locationCell) {
                        rowText += ' ' + (locationCell.textContent || locationCell.innerText).toLowerCase();
                    }

                    if (rowText.indexOf(searchTerm) > -1) {
                        row.style.display = '';
                        foundMatch = true;
                    } else {
                        row.style.display = 'none';
                    }
                }

                // Show the "empty" row ONLY if no matches were found AND the table wasn't initially empty
                if (!foundMatch && !isEmptyRow) {
                    for (let i = 0; i < tableRows.length; i++) {
                        if (tableRows[i].cells.length > 1 && tableRows[i].cells[0].getAttribute('colspan')) {
                            tableRows[i].style.display = ''; // Show the original empty message row
                            break;
                        }
                    }
                }
            });
        });
    </script>

@endsection

