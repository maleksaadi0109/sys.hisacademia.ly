@extends('financial.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('التقرير الشهري الشامل')}}</h2>
        </header>

        <div class="container">
            <div class="row">

                <form method="POST" enctype="multipart/form-data" action="{{ route('financial.generate.general.monthly.report') }}">
                    @csrf
                    <div class="row">

                        <div class="mt-0 col-lg-6">
                            <label for="from_date" class="block font-medium text-sm text-gray-700">يبدأ من التاريخ</label>
                            <input type="date"  value="{{request('from_date')}}" name="from_date" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-s" />
                        </div>

                        <div class="mt-0 col-lg-6">
                            <label for="to_date" class="block font-medium text-sm text-gray-700">ينتهي في التاريخ</label>
                            <input type="date"   value="{{request('to_date')}}" name="to_date" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-s" />
                        </div>

                        <div class="row">
                            <x-primary-button class="mt-4 col-lg-2 justify-content-center">
                                {{ __('عرض التقرير') }}
                            </x-primary-button>
                        </div>
                        
                    </div>
                </form>

                @if($data)
                    <div class="container">
                        <div class="row table-responsive">
                            <table class="table table-hover text-center table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col" colspan="6">عدد الكورسات التي تم بيعها</th>                              
                                    </tr>
                                </thead>
                                <tbody class="table-success">
                                    <tr>
                                        <td scope="col" colspan="6">{{$data['count_course']}}</td>                              
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-hover text-center table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col" colspan="6">عدد معاملات الترجمة التي تم بيعها</th>                              
                                    </tr>
                                </thead>
                                <tbody class="table-success">
                                    <tr>
                                        <td scope="col" colspan="6">{{$data['count_translation']}}</th>                              
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-hover text-center table-bordered">
                                <thead class="table-primary">
                                    <tr>
                                        <th scope="col" colspan="6">ايرادات الدولار</th>                              
                                    </tr>
                                    <tr>
                                        <th scope="col" colspan="3">ايرادات الكورسات</th>   
                                        <th scope="col" colspan="3">ايرادات الترجمة</th>                                                         
                                    </tr>
                                </thead>
                                    <tr class="table-dark">
                                        <th scope="col">الإجمالي</th>
                                        <th scope="col">المدفوع</th>
                                        <th scope="col">المتبقي</th>
                                        <th scope="col">الإجمالي</th>
                                        <th scope="col">المدفوع</th>
                                        <th scope="col">المتبقي</th>
                                    </tr>
                                </thead>
                                <tbody class="table-success">
                                    <tr>
                                        <td scope="col">{{$data['t_course_price_usd']}}</td>
                                        <td scope="col">{{$data['t_course_rec_usd']}}</td>
                                        <td scope="col">{{$data['t_course_rem_usd']}}</td>
                                        <td scope="col">{{$data['t_translation_price_usd']}}</td>
                                        <td scope="col">{{$data['t_translation_rec_usd']}}</td>
                                        <td scope="col">{{$data['t_translation_rem_usd']}}</td>
                                    </tr>
                                </tbody>
                                <thead class="table-dark">
                                    <tr>
                                        <td scope="col" colspan="2">الاجمالي الكلي</td> 
                                        <td scope="col" colspan="2">المدفوع الكلي</td> 
                                        <td scope="col" colspan="2">المتبقي الكلي</td>                                     </tr>
                                    <tr>
                                </thead>
                                <tbody class="table-success">
                                    <tr>
                                        <td scope="col" colspan="2">{{$data['t_price_usd']}}</td> 
                                        <td scope="col" colspan="2">{{$data['t_rec_usd']}}</td> 
                                        <td scope="col" colspan="2">{{$data['t_rem_usd']}}</td> 
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-hover text-center table-bordered">
                                <thead class="table-primary">
                                    <tr>
                                        <th scope="col" colspan="6">ايرادات الدينار</th>                              
                                    </tr>
                                    <tr>
                                        <th scope="col" colspan="3">ايرادات الكورسات</th>   
                                        <th scope="col" colspan="3">ايرادات الترجمة</th>                                                         
                                    </tr>
                                </thead>
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">الإجمالي</th>
                                        <th scope="col">المدفوع</th>
                                        <th scope="col">المتبقي</th>
                                        <th scope="col">الإجمالي</th>
                                        <th scope="col">المدفوع</th>
                                        <th scope="col">المتبقي</th>
                                    </tr>
                                </thead>
                                <tbody class="table-success">
                                    <tr>
                                        <td scope="col">{{$data['t_course_price_D']}}</td>
                                        <td scope="col">{{$data['t_course_rec_D']}}</td>
                                        <td scope="col">{{$data['t_course_rem_D']}}</td>
                                        <td scope="col">{{$data['t_translation_price_D']}}</td>
                                        <td scope="col">{{$data['t_translation_rec_D']}}</td>
                                        <td scope="col">{{$data['t_translation_rem_D']}}</td>
                                    </tr>
                                </tbody>

                                <thead class="table-dark">
                                    <tr>
                                        <td scope="col" colspan="2">الاجمالي الكلي</td> 
                                        <td scope="col" colspan="2">المدفوع الكلي</td> 
                                        <td scope="col" colspan="2">المتبقي الكلي</td>                                     </tr>
                                    <tr>
                                </thead>
                                <tbody class="table-success">
                                    <tr>
                                        <td scope="col" colspan="2">{{$data['t_price_D']}}</td> 
                                        <td scope="col" colspan="2">{{$data['t_rec_D']}}</td> 
                                        <td scope="col" colspan="2">{{$data['t_rem_D']}}</td> 
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </section>
@stop