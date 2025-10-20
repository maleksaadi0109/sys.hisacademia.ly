@extends('admin.dashboard')
@section('content')
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">{{__('إدارة المعاملات')}}</h2>
    </header>
    <div class="container">
        <div class="row table-responsive">
            <table class="table table-light table-hover text-center">
                <thead>
                    <tr>
                        <th scope="col"><a href="{{route('translation_deals',['orderBy' => 'id','sort' => 'asc'])}}"># <i class="fas fa-sort text-right"></a></th>
                        <th scope="col">اسم الزبون</th>
                        <th scope="col">عدد الاوراق</th>
                        <th scope="col">عدد المعاملات</th>
                        <th scope="col">نوع المعاملة</th>
                        <th scope="col">اللغة</th>
                        <th scope="col">السعر</th>
                        <th scope="col">العملة</th>
                        <th scope="col">طريقة الدفع</th>
                        <th scope="col">تاريخ الاستلام</th>
                        <th scope="col">تاريخ الاستحقاق</th>
                        <th scope="col">تاريخ التسليم</th>
                        <th scope="col">تعديل البيانات</th>
                        <th scope="col">حذف</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($translationDeals as $translationDeal) 
                    <tr>
                        <td scope="row">{{ $translationDeal->id }}</td>
                        <td scope="row">{{ $translationDeal->customer->name }}</td>
                        <td scope="row">{{ $translationDeal->number_of_sheets }}</td>
                        <td scope="row">{{ $translationDeal->number_of_transaction }}</td>
                        <td scope="row">{{ $translationDeal->context }}</td>
                        <td scope="row">{{ $translationDeal->language }}</td>
                        <td scope="row">{{ $translationDeal->price }}</td>
                        <td scope="row">{{ $translationDeal->currency }}</td>
                        <td scope="row">{{ $translationDeal->payment_method }}</td>
                        <td scope="row">{{ $translationDeal->date_of_receipt }}</td>
                        <td scope="row">{{ $translationDeal->due_date }}</td>
                        <td scope="row">{{ $translationDeal->delivery_date == null ? "لم يتم التسليم" :$translationDeal->delivery_date }}</td>
                        <td scope="row"><a class="me-2 btn btn-lg edit-button" data-mdb-ripple-init="" href="{{route('edit.translation_deal',['id' => $translationDeal->id])}}"><i class="far fa-edit"></i></a></td>
                        <td><a class="btn btn-sm delete-button " data-mdb-ripple-init="" href="{{route('translation_deal.destroy',['id' => $translationDeal->id])}}"
                            onclick="event.preventDefault();document.getElementById('delete-form-{{ $translationDeal->id }}').submit();">
                            <i class="far fa-trash-alt"></i></a></td>

                        <form id="delete-form-{{ $translationDeal->id }}" action="{{route('translation_deal.destroy',['id' => $translationDeal->id])}}"
                            method="post" style="display: none;">
                            @method('delete')
                            @csrf
                        </form> 
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $translationDeals->links() }}
        </div>
    </div>
</section>

@stop