@php
    use App\Enums\UserStatus;
@endphp

<div class="container">
    <div class="row table-responsive">
        <table class="table table-light table-hover text-center">
            <thead>
            <tr>
                <th scope="col">
                    <a href="{{route('data_entry.teachers',['orderBy' => 'id','sort' => request('sort') == 'asc' ? 'desc' : 'asc', 'search' => request('search')])}}">
                        # <i class="fas fa-sort text-right"></i>
                    </a>
                </th>
                <th scope="col">اسم المعلم</th>
                <th scope="col">
                    <a href="{{route('data_entry.teachers',['orderBy' => 'en_name','sort' => request('sort') == 'asc' ? 'desc' : 'asc', 'search' => request('search')])}}">
                        اسم المعلم بالانجليزي <i class="fas fa-sort text-right"></i>
                    </a>
                </th>
                <th scope="col">الايميل</th>
                <th scope="col">رقم الهوية</th>
                <th scope="col">الجنسية</th>
                <th scope="col">رقم الهاتف</th>
                <th scope="col">حالة الحساب</th>

            </tr>
            </thead>
            <tbody>
            @forelse ($teachers as $teacher)
                <tr>
                    <td scope="row">{{ $teacher->id }}</td>
                    <td scope="row">{{ $teacher->user->name }}</td>
                    <td scope="row">{{ $teacher->en_name }}</td>
                    <td scope="row">{{ $teacher->user->email }}</td>
                    <td scope="row">{{ $teacher->id_number }}</td>
                    <td scope="row">{{ $teacher->nationality }}</td>
                    <td scope="row">{{ $teacher->phone }}</td>
                    <td scope="row">{{ UserStatus::userStatusAr()[$teacher->user->status] }}</td>

                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center py-4">
                        <p class="text-gray-500">لا توجد نتائج للبحث</p>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $teachers->appends(request()->query())->links() }}
        </div>
    </div>
</div>

