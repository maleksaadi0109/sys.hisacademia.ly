@extends('admin.dashboard')
@section('content')
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">{{__('إرادات المعاملات')}}</h2>
    </header>
    <div class="container">
        <div class="row table-responsive">
            <table class="table table-light table-hover text-center">
                <thead>
                    <tr>
                        <th scope="col"><a href="{{route('translation_revenues',['orderBy' => 'id','sort' => 'asc'])}}"># <i class="fas fa-sort text-right"></a></th>
                        <th scope="col">اسم الزبون</th>
                        <th scope="col">نوع المعاملة</th>
                        <th scope="col">السعر</th>
                        <th scope="col">العملة</th>
                        <th scope="col">حصة المترجم</th>
                        <th scope="col">حصة الأكادمية</th>
                        <th scope="col">المدفوع</th>
                        <th scope="col">المتبقي</th>
                        <th scope="col">طريقة الدفع</th>
                        <th scope="col">تاريخ الاستلام</th>
                        <th scope="col">تاريخ الاستحقاق</th>
                        <th scope="col">تاريخ التسليم</th>
                        <th scope="col">تعديل البيانات</th>
                        <th scope="col">طباعة الفاتورة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($translationDeals as $translationDeal) 
                    <tr>
                        <td scope="row">{{ $translationDeal->id }}</td>
                        <td scope="row">{{ $translationDeal->customer->name }}</td>
                        <td scope="row">{{ $translationDeal->context }}</td>
                        <td scope="row">{{ $translationDeal->price }}</td>
                        <td scope="row">{{ $translationDeal->currency }}</td>
                        <td scope="row">{{ $translationDeal->translator_share }}</td>
                        <td scope="row">{{ $translationDeal->academy_share }}</td>
                        <td scope="row">{{ $translationDeal->received }}</td>
                        <td scope="row">{{ $translationDeal->remaining }}</td>
                        <td scope="row">{{ $translationDeal->payment_method }}</td>
                        <td scope="row">{{ $translationDeal->date_of_receipt }}</td>
                        <td scope="row">{{ $translationDeal->due_date }}</td>
                        <td scope="row">{{ $translationDeal->delivery_date == null ? "لم يتم التسليم" :$translationDeal->delivery_date }}</td>
                        <td scope="row"><a class="me-2 btn btn-lg edit-button" data-mdb-ripple-init="" href="{{route('edit.translation_deal',['id' => $translationDeal->id])}}"><i class="far fa-edit"></i></a></td>
                        <td scope="row"><a class="me-2 btn btn-lg edit-button" data-mdb-ripple-init="" href="{{route('print_translation_bill',['id' => $translationDeal->id])}}"><i class="fas fa-print"></i></a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $translationDeals->links() }}
        </div>
    </div>
</section>

@stop