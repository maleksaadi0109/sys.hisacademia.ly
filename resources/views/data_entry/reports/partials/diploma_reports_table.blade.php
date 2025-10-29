<table class="table table-light table-hover text-center">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">اسم الطالب</th>
            <th scope="col">اسم الدبلوم</th>
            <th scope="col">سعر الدبلوم</th>
            <th scope="col">المدفوع</th>
            <th scope="col">الباقي</th>
            <th scope="col">الحالة</th>
            <th scope="col">الإجراءات</th>
        </tr>
    </thead>
    <tbody>
        @forelse($revenues as $revenue)
            <tr>
                <td>{{ $revenue->id }}</td>
                <td>{{ $revenue->user->name ?? 'N/A' }}</td>
                <td>{{ $revenue->diploma->name ?? 'N/A' }}</td>
                <td>{{ $revenue->value }} {{ $revenue->currency }}</td>
                <td>{{ $revenue->value_rec }} {{ $revenue->currency }}</td>
                <td>{{ $revenue->value_rem }} {{ $revenue->currency }}</td>
                <td>
                    @if($revenue->value_rem <= 0)
                        <span class="badge bg-success">مدفوع بالكامل</span>
                    @else
                        <span class="badge bg-warning">متبقي</span>
                    @endif
                </td>
                <td>
                    @if($revenue->value_rem <= 0)
                        <a href="{{ route('data_entry.print_student_bill', ['id' => $revenue->id, 'print' => true]) }}" 
                           target="_blank"
                           class="btn btn-sm btn-warning" title="طباعة الإيصال">
                            <i class="fas fa-print"></i>
                        </a>
                    @else
                        <a href="{{ route('data_entry.pay_remaining', ['id' => $revenue->id]) }}" 
                           class="btn btn-sm btn-info" title="دفع الباقي">
                            <i class="fas fa-money-bill-wave"></i>
                        </a>
                        <a href="{{ route('data_entry.print_student_bill', ['id' => $revenue->id, 'print' => true]) }}" 
                           target="_blank"
                           class="btn btn-sm btn-warning" title="طباعة الإيصال">
                            <i class="fas fa-print"></i>
                        </a>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center py-4">لا توجد بيانات</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-4">
    {{ $revenues->appends(request()->query())->links() }}
</div>

