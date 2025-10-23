<div class="row table-responsive">
    <table class="table table-light table-hover text-center">
        <thead>
        <tr>
            <th>#</th>
            <th>اسم الطالب</th>
            <th>اسم الطالب بالانجليزي</th>
            <th>الإيميل</th>
            <th>رقم الهوية</th>
            <th>الجنسية</th>
            <th>رقم الهاتف</th>
            <th>حالة الحساب</th>
        </tr>
        </thead>
        <tbody>
        @php
            use App\Enums\UserStatus;
        @endphp
        @forelse ($students as $student)
            <tr>
                <td>{{ $student->id }}</td>
                <td>{{ $student->user->name }}</td>
                <td>{{ $student->en_name }}</td>
                <td>{{ $student->user->email }}</td>
                <td>{{ $student->id_number }}</td>
                <td>{{ $student->nationality }}</td>
                <td>{{ $student->phone }}</td>
                <td>{{ UserStatus::userStatusAr()[$student->user->status] }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center py-4">لا توجد بيانات تطابق بحثك.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $students->appends(request()->query())->links() }}
</div>

