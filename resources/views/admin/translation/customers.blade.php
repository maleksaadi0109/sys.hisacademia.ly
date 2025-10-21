@extends('admin.dashboard')
@section('content')
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">{{__('إدارة بيانات الزبائن')}}</h2>
    </header>

    <div class="container">
        <div class="row table-responsive">
            <table class="table table-light table-hover text-center">
                <thead>
                    <tr>
                        <th scope="col"><a href="{{route('customers',['orderBy' => 'id','sort' => 'asc'])}}"># <i class="fas fa-sort text-right"></a></th>
                        <th scope="col"><a href="{{route('customers',['orderBy' => 'name','sort' => 'asc'])}}">اسم الزبون<i class="fas fa-sort text-right"></a></th>
                        <th scope="col">رقم الهاتف</th>
                        <th scope="col">العنوان</th>
                        <th scope="col">تعديل بيانات الزبون</th>
                        <th scope="col">حذف</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer) 
                    <tr>
                        <td scope="row">{{ $customer->id }}</td>
                        <td scope="row">{{ $customer->name }}</td>
                        <td scope="row">{{ $customer->phone }}</td>
                        <td scope="row">{{ $customer->address }}</td>
                        <td scope="row"><a class="me-2 btn btn-lg edit-button" data-mdb-ripple-init="" href="{{route('edit.customer',['id' => $customer->id])}}"><i class="far fa-edit"></i></a></td>
                        <td><a class="btn btn-sm delete-button " data-mdb-ripple-init="" href="{{route('customer.destroy',['id' => $customer->id])}}"
                            onclick="event.preventDefault();document.getElementById('delete-form-{{ $customer->id }}').submit();">
                            <i class="far fa-trash-alt"></i></a></td>

                        <form id="delete-form-{{ $customer->id }}" action="{{route('customer.destroy',['id' => $customer->id])}}"
                            method="post" style="display: none;">
                            @method('delete')
                            @csrf
                        </form> 
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $customers->links() }}
        </div>
    </div>
</section>

@stop