<table class="table table-light table-hover text-center">
    <thead>
        <tr>
            <th scope="col"><a href="{{route('data_entry.translation_deals',['orderBy' => 'id','sort' => 'asc'])}}"># <i class="fas fa-sort text-right"></a></th>
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
           
        </tr>
    </thead>
    <tbody>
        @forelse($translationDeals as $translationDeal)
            <tr>
                <td scope="row">{{ $translationDeal->id }}</td>
                <td scope="row">{{ $translationDeal->customer->name ?? 'N/A' }}</td>
                <td scope="row">{{ $translationDeal->number_of_sheets }}</td>
                <td scope="row">{{ $translationDeal->number_of_transaction }}</td>
                <td scope="row">{{ $translationDeal->context }}</td>
                <td scope="row">{{ $translationDeal->language }}</td>
                <td scope="row">{{ $translationDeal->price }}</td>
                <td scope="row">{{ $translationDeal->currency }}</td>
                <td scope="row">{{ $translationDeal->payment_method }}</td>
                <td scope="row">{{ $translationDeal->date_of_receipt }}</td>
                <td scope="row">{{ $translationDeal->due_date }}</td>
                <td scope="row">{{ $translationDeal->delivery_date == null ? "لم يتم التسليم" : $translationDeal->delivery_date }}</td>
               

                <form id="delete-form-{{ $translationDeal->id }}" 
                      action="{{route('data_entry.translation_deal.destroy',['id' => $translationDeal->id])}}"
                      method="post" style="display: none;">
                    @method('delete')
                    @csrf
                </form> 
            </tr>
        @empty
            <tr>
                <td colspan="14" class="text-center py-4">لا توجد بيانات تطابق بحثك.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-4">
    {{ $translationDeals->appends(request()->query())->links() }}
</div>
