@extends('data_entry.dashboard')

@php
    // Helper function to translate booking type
    function translateBookingType($type) {
        switch ($type) {
            case 'daily': return 'يومي';
            case 'weekly': return 'أسبوعي';
            case 'monthly': return 'شهري';
            case 'three_month': return '3 أشهر';
            default: return $type;
        }
    }
    // Helper function to translate status
    function translateBookingStatus($status) {
        switch ($status) {
            case 'pending': return 'قيد الانتظار';
            case 'confirmed': return 'مؤكد';
            case 'cancelled': return 'ملغي';
            case 'completed': return 'مكتمل';
            default: return $status;
        }
    }
@endphp

@section('content')
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" dir="rtl" style="text-align: right;">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">إدارة حجوزات مساحات العمل</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="{{ route('data_entry.booking') }}" class="btn btn-sm btn-success">
                    <i class="fa fa-plus"></i> إضافة حجز جديد
                </a>
            </div>
        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Search and Filter Form --}}
        <div class="row g-3 mb-3">
            <div class="col-md-5">
                <input type="text" id="liveSearchInput" class="form-control form-control-sm" placeholder="ابحث باسم العميل أو الطالب...">
            </div>
            <div class="col-md-4">
                <select id="bookingTypeFilter" class="form-select form-select-sm">
                    <option value="">-- فلترة حسب نوع الحجز --</option>
                    <option value="daily">يومي</option>
                    <option value="weekly">أسبوعي</option>
                    <option value="monthly">شهري</option>
                    <option value="three_month">3 أشهر</option>
                </select>
            </div>
            <div class="col-md-3">
                <select id="statusFilter" class="form-select form-select-sm">
                    <option value="">-- فلترة حسب الحالة --</option>
                    <option value="pending">قيد الانتظار</option>
                    <option value="confirmed">مؤكد</option>
                    <option value="cancelled">ملغي</option>
                    <option value="completed">مكتمل</option>
                </select>
            </div>
        </div>

        {{-- Bookings Table --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm text-center">
                <thead class="table-primary">
                <tr>
                    <th scope="col" style="width:5%;">#</th>
                    <th scope="col">اسم العميل/الطالب</th>
                    <th scope="col">مساحة العمل</th>
                    <th scope="col">نوع الحجز</th>
                    <th scope="col">تاريخ البدء</th>
                    <th scope="col">تاريخ الانتهاء</th>
                    <th scope="col">السعر الإجمالي</th>
                    <th scope="col">الحالة</th>
                    <th scope="col">تاريخ التسجيل</th>
                </tr>
                </thead>
                <tbody id="bookingsTableBody">
                @forelse ($bookings as $booking)
                    <tr data-booking-type="{{ $booking->booking_type }}" data-status="{{ $booking->status }}">
                        <td>{{ $booking->id }}</td>
                        <td>
                            {{-- Display student name if available, otherwise customer name --}}
                            @if($booking->student_id && $booking->student?->user)
                                <span class="badge bg-info text-dark">طالب:</span> {{ $booking->student->user->name }}
                            @elseif($booking->name)
                                {{ $booking->name }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $booking->coworkingSpace->name ?? 'غير متوفر' }}{{ $booking->coworkingSpace->location ? ' - ' . $booking->coworkingSpace->location : '' }}</td>
                        <td class="booking-type">{{ translateBookingType($booking->booking_type) }}</td>
                        <td>{{ $booking->start_date instanceof \Carbon\Carbon ? $booking->start_date->format('Y-m-d') : $booking->start_date }}</td>
                        <td>{{ $booking->end_date instanceof \Carbon\Carbon ? $booking->end_date->format('Y-m-d') : $booking->end_date }}</td>
                        <td>{{ number_format($booking->total_price, 2) }} د.ل</td>
                        <td class="status">
                            <span class="badge
                                @switch($booking->status)
                                    @case('pending') bg-warning text-dark @break
                                    @case('confirmed') bg-success @break
                                    @case('cancelled') bg-danger @break
                                    @case('completed') bg-secondary @break
                                    @default bg-light text-dark
                                @endswitch">
                                {{ translateBookingStatus($booking->status) }}
                            </span>
                        </td>
                        <td>{{ $booking->created_at ? $booking->created_at->format('Y-m-d') : '-' }}</td>
                    </tr>
                @empty
                    <tr id="no-bookings-row">
                        <td colspan="9">لا توجد حجوزات لعرضها.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Links --}}
        <div class="mt-3 d-flex justify-content-center">
            @if(isset($bookings) && method_exists($bookings, 'links'))
                {{ $bookings->links() }}
            @endif
        </div>

    </main>

    {{-- Client-side Search and Filter Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('liveSearchInput');
            const typeFilter = document.getElementById('bookingTypeFilter');
            const statusFilter = document.getElementById('statusFilter');
            const tableBody = document.getElementById('bookingsTableBody');
            const tableRows = tableBody.getElementsByTagName('tr');
            const noBookingsRow = document.getElementById('no-bookings-row');

            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedType = typeFilter.value;
                const selectedStatus = statusFilter.value;
                let matchFound = false;

                if (!tableRows || tableRows.length === 0 || (tableRows.length === 1 && noBookingsRow)) {
                    return;
                }

                for (let i = 0; i < tableRows.length; i++) {
                    const row = tableRows[i];

                    if (row.id === 'no-bookings-row') continue;

                    const nameCell = row.cells[1];
                    const rowType = row.getAttribute('data-booking-type');
                    const rowStatus = row.getAttribute('data-status');

                    let nameMatch = true;
                    let typeMatch = true;
                    let statusMatch = true;

                    if (searchTerm && nameCell) {
                        const nameText = (nameCell.textContent || nameCell.innerText).toLowerCase();
                        nameMatch = nameText.includes(searchTerm);
                    }

                    if (selectedType) {
                        typeMatch = (rowType === selectedType);
                    }

                    if (selectedStatus) {
                        statusMatch = (rowStatus === selectedStatus);
                    }

                    if (nameMatch && typeMatch && statusMatch) {
                        row.style.display = '';
                        matchFound = true;
                    } else {
                        row.style.display = 'none';
                    }
                }

                if(noBookingsRow){
                    noBookingsRow.style.display = matchFound ? 'none' : '';
                }
            }

            searchInput.addEventListener('keyup', filterTable);
            typeFilter.addEventListener('change', filterTable);
            statusFilter.addEventListener('change', filterTable);
        });
    </script>

@endsection
