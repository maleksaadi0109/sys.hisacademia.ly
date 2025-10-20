@extends('admin.dashboard')
@section('content')
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900"> {{ __('عمليات المستخدمين') }}</h2>
    </header>

    <div class="container">
        <div class="row table-responsive">
            <table class="table table-light table-hover text-center">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">اسم المستخدم</th>
                        <th scope="col">عنوان العملية</th>
                        <th scope="col">Method</th>
                        <th scope="col">Payload</th>
                        <th scope="col">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $request)
                        <tr>
                            <td scope="row">{{ $request->id }}</td>
                            <td scope="row">{{ $request->user->name }}</td>
                            <td scope="row">{{ $request->url }}</td>
                            <td scope="row">{{ $request->method }}</td>
                            <td scope="row">{{ $request->payload}}</td>
                            <td scope="row">{{ $request->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{$requests->links()}}
        </div>
    </div>
</section>
@stop
