@extends('financial.dashboard')
@section('content')
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">{{__('إدارة الإيرادات')}}</h2>
    </header>

    <div class="container">
        <div class="row table-responsive">
            <table class="table table-light table-hover text-center">
                <thead>
                    <tr>
                        <th scope="col"><a href="{{route('financial.revenues',['orderBy' => 'id','sort' => 'asc'])}}"># <i class="fas fa-sort text-right"></a></th>
                        <th scope="col">اسم الكورس</th>
                        <th scope="col"><a href="{{route('financial.revenues',['orderBy' => 'date_of_rec','sort' => 'asc'])}}">تاريخ التسجيل<i class="fas fa-sort text-right"></a></th>
                        <th scope="col"><a href="{{route('financial.revenues',['orderBy' => 'value','sort' => 'asc'])}}">سعر الكورس <i class="fas fa-sort text-right"></a></th>
                        <th scope="col">العملة</th>
                        <th scope="col">اسم الطالب</th>
                        <th scope="col">المدفوع</th>
                        <th scope="col">المتبقي</th>
                        <th scope="col">استكمال المتبقي</th>
                        <th scope="col">طباعة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($revenues as $revenue) 
                    <tr>
                        <td scope="row">{{ $revenue->id }}</td>
                        <td scope="row">{{ $revenue->course->name}}</td>
                        <td scope="row">{{ $revenue->date_of_rec }}</td>
                        <td scope="row">{{ $revenue->value }}</td>
                        <td scope="row">{{ $revenue->currency }}</td>
                        <td scope="row">{{ $revenue->user->name }}</td>
                        <td scope="row">{{ $revenue->value_rec }}</td>
                        <td scope="row">{{ $revenue->value_rem }}</td>
                        <td scope="row">
                            @if($revenue->value_rem > 0)
                            <a class="me-2 btn btn-lg edit-button" data-mdb-ripple-init="" href="{{route('financial.edit.revenue',['id' => $revenue->id])}}"><i class="far fa-edit"></i></a>
                            @endif
                        </td>
                        <td scope="row">
                            @if($revenue->user->student->dataStudent != null)
                                <a class="me-2 btn btn-lg edit-button" data-mdb-ripple-init="" href="{{route('financial.print_student_bill',['id' => $revenue->id])}}"><i class="fas fa-print"></i></a>
                            @else
                                <p>يجب تعبئة بيانات الدراسة للطالب ({{$revenue->user->name}})</p>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $revenues->links() }}
        </div>
    </div>
</section>

@stop