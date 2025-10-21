@extends('admin.dashboard')
@section('content')
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">{{__('إدارة بيانات الدراسة للطالب')}}</h2>
    </header>

    <div class="container">
        <div class="row table-responsive">
            <table class="table table-light table-hover text-center">
                <thead>
                    <tr>
                        <th scope="col"><a href="{{route('students',['orderBy' => 'id','sort' => 'asc'])}}"># <i class="fas fa-sort text-right"></a></th>
                        <th scope="col">اسم الطالب</th>
                        <th scope="col"><a href="{{route('students',['orderBy' => 'en_name','sort' => 'asc'])}}">اسم الطالب بالانجليزي<i class="fas fa-sort text-right"></a></th>                              
                        <th scope="col">الايميل</th>
                        <th scope="col">رقم الهوية</th>
                        <th scope="col">الجنسية</th>
                        <th scope="col">رقم الهاتف</th>
                        <th scope="col">حالة الحساب</th>
                        <th scope="col">تعديل بيانات الطالب</th>
                        <th scope="col">تعديل بيانات الدراسة</th>
                        <th scope="col">حذف</th>

                    </tr>
                </thead>
                <tbody>
                    @php 
                        use App\Enums\UserStatus; 
                    @endphp
                    @foreach ($students as $student) 
                    <tr>
                        <td scope="row">{{ $student->id }}</td>
                        <td scope="row">{{ $student->user->name }}</td>
                        <td scope="row">{{ $student->en_name }}</td>
                        <td scope="row">{{ $student->user->email }}</td>
                        <td scope="row">{{ $student->id_number  }}</td>
                        <td scope="row">{{ $student->nationality }}</td>
                        <td scope="row">{{ $student->phone }}</td>
                        <td scope="row">{{ UserStatus::userStatusAr()[$student->user->status] }}</td>
                        <td scope="row"><a class="me-2 btn btn-lg edit-button" data-mdb-ripple-init="" href="{{route('edit.student',['id' => $student->id])}}"><i class="far fa-edit"></i></a></td>
                        <td scope="row"><a class="me-2 btn btn-lg edit-button" data-mdb-ripple-init="" href="{{route('edit.studentData',['id' => $student->id])}}"><i class="far fa-edit"></i></a></td>
                        <td><a class="btn btn-sm delete-button " data-mdb-ripple-init="" href="{{route('student.destroy',['id' => $student->id])}}"
                            onclick="event.preventDefault();document.getElementById('delete-form-{{ $student->id }}').submit();">
                            <i class="far fa-trash-alt"></i></a></td>

                        <form id="delete-form-{{ $student->id }}" action="{{route('student.destroy',['id' => $student->id])}}"
                            method="post" style="display: none;">
                            @method('delete')
                            @csrf
                        </form> 
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $students->links() }}
        </div>
    </div>
</section>

@stop