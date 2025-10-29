<table class="table table-light table-hover text-center">
    <thead>
        <tr>
            <th scope="col"><a href="{{route('data_entry.customers',['orderBy' => 'id','sort' => 'asc'])}}"># <i class="fas fa-sort text-right"></a></th>
            <th scope="col"><a href="{{route('data_entry.customers',['orderBy' => 'name','sort' => 'asc'])}}">اسم الزبون<i class="fas fa-sort text-right"></a></th>
            <th scope="col">رقم الهاتف</th>
            <th scope="col">العنوان</th>
           
        </tr>
    </thead>
    <tbody>
        @forelse($customers as $customer)
            <tr>
                <td scope="row">{{ $customer->id }}</td>
                <td scope="row">{{ $customer->name }}</td>
                <td scope="row">{{ $customer->phone }}</td>
                <td scope="row">{{ $customer->address }}</td>
                

                <form id="delete-form-{{ $customer->id }}" 
                      action="{{route('data_entry.customer.destroy',['id' => $customer->id])}}"
                      method="post" style="display: none;">
                    @method('delete')
                    @csrf
                </form> 
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center py-4">لا توجد بيانات تطابق بحثك.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-4">
    {{ $customers->appends(request()->query())->links() }}
</div>
