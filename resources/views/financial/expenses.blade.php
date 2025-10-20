@extends('financial.dashboard')
@section('content')
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">{{__('إدارة المصروفات')}}</h2>
    </header>

    <div class="container">

        <a href="{{route('financial.add.expense')}}" class="mt-3 mb-4 btn btn-primary" role="button">اضافة فاتورة مصروف</a>
        <div class="row table-responsive">
            <table class="table table-light table-hover text-center">
                <thead>
                    <tr>
                        <th scope="col"><a href="{{route('financial.expenses',['orderBy' => 'id','sort' => 'asc'])}}"># <i class="fas fa-sort text-right"></a></th>
                        <th scope="col">رقم المراجعة</th>
                        <th scope="col"><a href="{{route('financial.expenses',['orderBy' => 'value','sort' => 'asc'])}}">قيمة الفاتورة<i class="fas fa-sort text-right"></a></th>
                        <th scope="col">العملة</th>
                        <th scope="col"><a href="{{route('financial.expenses',['orderBy' => 'date','sort' => 'asc'])}}">التاريخ<i class="fas fa-sort text-right"></a></th>
                        <th scope="col">البيان</th>
                        <th scope="col">القسم</th>
                        <th scope="col">رقم الفاتورة</th>
                        <th scope="col">تعديل</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($expenses as $expense) 
                    <tr>
                        <td scope="row">{{ $expense->id }}</td>
                        <td scope="row">{{ $expense->bill_number}}</td>
                        <td scope="row">{{ $expense->value }}</td>
                        <td scope="row">{{ $expense->currency }}</td>
                        <td scope="row">{{ $expense->date }}</td>
                        <td scope="row">{{ $expense->context }}</td>
                        <td scope="row">{{ $expense->section }}</td>
                        <td scope="row">{{ $expense->review_number }}</td>
                        <td scope="row"><a class="me-2 btn btn-lg edit-button" data-mdb-ripple-init="" href="{{route('financial.edit.expense',['id' => $expense->id])}}"><i class="far fa-edit"></i></a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $expenses->links() }}
        </div>
    </div>
</section>

@stop