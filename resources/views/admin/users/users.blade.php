@extends('admin.dashboard')
@section('content')
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">{{__('إدارة المستخدمين')}}</h2>
    </header>

    <div class="container">
        <div class="row table-responsive">
            <table class="table table-light table-hover text-center">
                <thead>
                    <tr>
                        <th scope="col"><a href="{{route('users',['orderBy' => 'id','sort' => 'asc'])}}"># <i class="fas fa-sort text-right"></a></th>
                        <th scope="col"><a href="{{route('users',['orderBy' => 'name','sort' => 'asc'])}}">الاسم <i class="fas fa-sort text-right"></a></th>
                        <th scope="col"><a href="{{route('users',['orderBy' => 'email','sort' => 'asc'])}}">البريد الالكتروني <i class="fas fa-sort text-right"></a></th>
                        <th scope="col"><a href="{{route('users',['orderBy' => 'employee_number','sort' => 'asc'])}}">رقم الموظف <i class="fas fa-sort text-right"></a></th>
                        <th scope="col">نوع المستخدم</th>
                        <th scope="col">حالة الحساب</th>
                        <th scope="col">الصلاحيات</th>
                        <th scope="col">تعديل</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                    use App\Enums\UserType; 
                    use App\Enums\UserStatus;
                    use App\Enums\EnumPermission;
                    @endphp
                    @foreach ($users as $user)
                    <tr>
                        <th scope="row">{{ $user->id }}</th>
                        <td scope="row">{{ $user->name }}</td>
                        <td scope="row">{{ $user->email }}</td>
                        <td scope="row">{{ $user->employee_number }}</td>
                        <td scope="row">{{ UserType::userTypeAr()[$user->user_type] }}</td>
                        <td scope="row">{{ UserStatus::userStatusAr()[$user->status] }}</td>
                        <td scope="row">@php foreach(json_decode($user->permission) as $item){ echo EnumPermission::enumPermissionAr()[$item].' - '; } @endphp</td>
                        <td scope="row"><a class="me-2 btn btn-lg edit-button" data-mdb-ripple-init="" href="{{route('profile.user',['id' => $user->id])}}"><i class="far fa-edit"></i></a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $users->links() }}
        </div>
    </div>
</section>
@stop