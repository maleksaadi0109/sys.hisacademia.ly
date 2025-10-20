@extends('data_entry.dashboard')
@section('content')
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">{{__('إدارة بيانات المعلمين')}}</h2>
    </header>

    <div class="container">
        <div class="row table-responsive">
            <table class="table table-light table-hover text-center">
                <thead>
                    <tr>
                        <th scope="col"><a href="{{route('data_entry.teachers',['orderBy' => 'id','sort' => 'asc'])}}"># <i class="fas fa-sort text-right"></a></th>
                        <th scope="col">اسم المعلم</th>
                        <th scope="col"><a href="{{route('data_entry.teachers',['orderBy' => 'en_name','sort' => 'asc'])}}">اسم المعلم بالانجليزي<i class="fas fa-sort text-right"></a></th>                              
                        <th scope="col">الايميل</th>
                        <th scope="col">رقم الهوية</th>
                        <th scope="col">الجنسية</th>
                        <th scope="col">رقم الهاتف</th>
                        <th scope="col">حالة الحساب</th>
                        <th scope="col">تعديل بيانات المعلم</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        use App\Enums\UserStatus; 
                    @endphp
                    @foreach ($teachers as $teacher) 
                    <tr>
                        <td scope="row">{{ $teacher->id }}</td>
                        <td scope="row">{{ $teacher->user->name }}</td>
                        <td scope="row">{{ $teacher->en_name }}</td>
                        <td scope="row">{{ $teacher->user->email }}</td>
                        <td scope="row">{{ $teacher->id_number  }}</td>
                        <td scope="row">{{ $teacher->nationality }}</td>
                        <td scope="row">{{ $teacher->phone }}</td>
                        <td scope="row">{{ UserStatus::userStatusAr()[$teacher->user->status] }}</td>
                        <td scope="row"><a class="me-2 btn btn-lg edit-button" data-mdb-ripple-init="" href="{{route('data_entry.edit.teacher',['id' => $teacher->id])}}"><i class="far fa-edit"></i></a></td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $teachers->links() }}
        </div>
    </div>
</section>

@stop