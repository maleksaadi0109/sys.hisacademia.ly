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
    {{-- Enhanced Styles for This Page Only --}}
    <style>
        /* Summary Cards Styling */
        .summary-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 1.5rem;
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
        }
        
        .summary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .summary-card.success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .summary-card.warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        
        .summary-card.info {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }
        
        .summary-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .summary-label {
            font-size: 0.95rem;
            opacity: 0.9;
            font-weight: 500;
        }
        
        .summary-icon {
            font-size: 2rem;
            opacity: 0.8;
            margin-bottom: 1rem;
        }
        
        /* Enhanced Table Styling */
        .enhanced-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
        }
        
        .enhanced-table .table {
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .enhanced-table .table thead th {
            background: linear-gradient(135deg, #374151 0%, #1f2937 100%);
            color: white;
            font-weight: 600;
            padding: 1rem 0.75rem;
            border: none;
            font-size: 0.9rem;
            text-align: center;
            position: relative;
        }
        
        .enhanced-table .table thead th:first-child {
            border-top-left-radius: 12px;
        }
        
        .enhanced-table .table thead th:last-child {
            border-top-right-radius: 12px;
        }
        
        .enhanced-table .table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .enhanced-table .table tbody tr:hover {
            background-color: #f8fafc;
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .enhanced-table .table tbody tr:last-child td:first-child {
            border-bottom-left-radius: 12px;
        }
        
        .enhanced-table .table tbody tr:last-child td:last-child {
            border-bottom-right-radius: 12px;
        }
        
        .enhanced-table .table tbody td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
            border: none;
            font-size: 0.9rem;
            color: #374151;
        }
        
        /* Enhanced Badge Styling */
        .enhanced-badge {
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.5rem 0.75rem;
            border-radius: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        /* Enhanced Form Controls */
        .enhanced-form-control {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background: #fafafa;
        }
        
        .enhanced-form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: white;
        }
        
        .enhanced-form-control:hover {
            border-color: #d1d5db;
            background: white;
        }
        
        /* Enhanced Button */
        .enhanced-btn {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .enhanced-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        
        /* Enhanced Alert */
        .enhanced-alert {
            border: none;
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        /* Enhanced Pagination */
        .enhanced-pagination {
            background: white;
            padding: 1rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
        }
        
        .enhanced-pagination .page-link {
            border: none;
            color: #667eea;
            padding: 0.75rem 1rem;
            margin: 0 0.25rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .enhanced-pagination .page-link:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }
        
        .enhanced-pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .summary-card {
                margin-bottom: 1rem;
            }
            
            .summary-number {
                font-size: 2rem;
            }
            
            .enhanced-table .table tbody td {
                padding: 0.75rem 0.5rem;
                font-size: 0.85rem;
            }
        }
    </style>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" dir="rtl" style="text-align: right;">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">إدارة حجوزات مساحات العمل</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="{{ route('data_entry.booking') }}" class="btn btn-success enhanced-btn">
                    <i class="fa fa-plus"></i> إضافة حجز جديد
                </a>
            </div>
        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success enhanced-alert alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Booking Summary Cards --}}
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="summary-card info">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="summary-icon">
                                <i class="fa fa-calendar-alt"></i>
                            </div>
                            <div class="summary-number">{{ $bookings->count() }}</div>
                            <div class="summary-label">إجمالي الحجوزات</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-card success">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="summary-icon">
                                <i class="fa fa-check-circle"></i>
                            </div>
                            <div class="summary-number">{{ $bookings->where('status', 'confirmed')->count() }}</div>
                            <div class="summary-label">حجوزات مؤكدة</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-card warning">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="summary-icon">
                                <i class="fa fa-clock"></i>
                            </div>
                            <div class="summary-number">{{ $bookings->where('status', 'pending')->count() }}</div>
                            <div class="summary-label">حجوزات قيد الانتظار</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Enhanced Search and Filter Form --}}
        <div class="card mb-4" style="border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); border: 1px solid #e5e7eb;">
            <div class="card-body">
                <h6 class="card-title mb-3" style="color: #374151; font-weight: 600;">
                    <i class="fa fa-search me-2"></i>البحث والفلترة
                </h6>
                <div class="row g-3">
                    <div class="col-md-5">
                        <label for="liveSearchInput" class="form-label" style="font-weight: 500; color: #6b7280;">البحث</label>
                        <input type="text" id="liveSearchInput" class="form-control enhanced-form-control" placeholder="ابحث باسم العميل أو الطالب...">
                    </div>
                    <div class="col-md-4">
                        <label for="bookingTypeFilter" class="form-label" style="font-weight: 500; color: #6b7280;">نوع الحجز</label>
                        <select id="bookingTypeFilter" class="form-select enhanced-form-control">
                            <option value="">-- جميع الأنواع --</option>
                            <option value="daily">يومي</option>
                            <option value="weekly">أسبوعي</option>
                            <option value="monthly">شهري</option>
                            <option value="three_month">3 أشهر</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="statusFilter" class="form-label" style="font-weight: 500; color: #6b7280;">الحالة</label>
                        <select id="statusFilter" class="form-select enhanced-form-control">
                            <option value="">-- جميع الحالات --</option>
                            <option value="pending">قيد الانتظار</option>
                            <option value="confirmed">مؤكد</option>
                            <option value="cancelled">ملغي</option>
                            <option value="completed">مكتمل</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Enhanced Bookings Table --}}
        <div class="enhanced-table">
            <div class="table-responsive">
                <table class="table text-center">
                    <thead>
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
                        <td>
                            <span class="badge bg-light text-dark enhanced-badge fw-bold">#{{ $booking->id }}</span>
                        </td>
                        <td>
                            {{-- Display student name if available, otherwise customer name --}}
                            @if($booking->student_id && $booking->student?->user)
                                <div class="d-flex align-items-center justify-content-center">
                                    <span class="badge bg-info enhanced-badge me-2">طالب</span>
                                    <strong>{{ $booking->student->user->name }}</strong>
                                </div>
                            @elseif($booking->name)
                                <strong>{{ $booking->name }}</strong>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div>
                                <strong>{{ $booking->coworkingSpace->name ?? 'غير متوفر' }}</strong>
                                @if($booking->coworkingSpace->location)
                                    <br><small class="text-muted">{{ $booking->coworkingSpace->location }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-primary enhanced-badge">{{ translateBookingType($booking->booking_type) }}</span>
                        </td>
                        <td>
                            <i class="fa fa-calendar text-muted me-1"></i>
                            {{ $booking->start_date instanceof \Carbon\Carbon ? $booking->start_date->format('Y-m-d') : $booking->start_date }}
                        </td>
                        <td>
                            <i class="fa fa-calendar-check text-muted me-1"></i>
                            {{ $booking->end_date instanceof \Carbon\Carbon ? $booking->end_date->format('Y-m-d') : $booking->end_date }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fa fa-coins text-warning me-2"></i>
                                <strong class="text-success">{{ number_format($booking->total_price, 2) }} د.ل</strong>
                            </div>
                        </td>
                        <td class="status">
                            <span class="badge enhanced-badge
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
                        <td colspan="9">
                            <div class="text-center py-5">
                                <i class="fa fa-calendar-times fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">لا توجد حجوزات لعرضها</h5>
                                <p class="text-muted">ابدأ بإضافة حجز جديد باستخدام الزر أعلاه</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            </div>
        </div>

        {{-- Enhanced Pagination Links --}}
        <div class="mt-4 d-flex justify-content-center">
            @if(isset($bookings) && method_exists($bookings, 'links'))
                <div class="enhanced-pagination">
                    {{ $bookings->links() }}
                </div>
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
