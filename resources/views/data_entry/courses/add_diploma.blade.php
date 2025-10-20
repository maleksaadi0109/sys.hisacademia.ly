@extends('data_entry.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('إضافة دبلوم')}}</h2>
        </header>

        <div class="container">
            <div class="row">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('data_entry.register.diploma') }}">
                        @csrf
                        <div class="row mb-5">
                            @php 
                                use App\Enums\WeekDays;
                            @endphp
                            <!-- diploma name -->
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="diploma_name" :value="__('اسم الدبلوم')" />
                                <x-text-input id="diploma_name" value="{{old('diploma_name')}}" class="block mt-1 w-full" type="text" name="diploma_name" required autofocus autocomplete="diploma_name" />
                                <x-input-error :messages="$errors->get('diploma_name')" class="mt-2" />
                            </div>

                            <div class="mt-4 col-lg-6">
                                <x-input-label for="number_course" :value="__('عدد الكورسات')" />
                                <x-text-input id="number_course" value="{{old('number_course')}}" class="block mt-1 w-full" type="text" name="number_course" required autofocus autocomplete="number_course" />
                                <x-input-error :messages="$errors->get('number_course')" class="mt-2" />
                            </div>
                        </div>

                        <select class="teacher_options" hidden>
                            @foreach($teachers as $value)
                                    <option value="{{$value->user->id}}">{{$value->user->name}}</option>
                            @endforeach
                        </select>

                        <div class="row" id="course-row">

                        </div>

                        <div class="row">
                            <x-primary-button class="mt-4 col-lg-2 justify-content-center">
                                {{ __('إضافة') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
        </div>
    </section>

<script>

    $(document).ready(function () {

        const oldpassedArray = <?php echo json_encode(session()->getOldInput())? json_encode(session()->getOldInput()) : ''; ?>;
        if($('#number_course').val()){ 
            $('#number_course').val(oldpassedArray['number_course']);
            createCourseform(oldpassedArray['number_course']);
        }
        
        $('#number_course').on('input', function () {
            var value = $(this).val();
            if ((value !== '') && (value.indexOf('.') === -1)) {
                createCourseform($(this).val());
            }
        });

        function addScriptdate(n) {
            var script = document.createElement("script");
            script.innerHTML = `
            var multipleCancelButton = new Choices('#choices-multiple-remove-button`+n+`', {
            removeItemButton: true,
            searchResultLimit:5,
            renderChoiceLimit:5
            });`;

            $('#course-row').append(script);
        }

        function createCourseform(n){
            const option_array_relation =  {
                'su' : 'الاحد',
                'mo' : 'الاثنين',
                'tu' : 'الثلاثاء',
                'we' : 'الاربعاء',
                'th' : 'الخميس',
                'fr' : 'الجمعة',
                'sa' : 'السبت',
            };

            let stringoption = '';
            for(var index in  option_array_relation) {
                    stringoption += '<option  value="'+index+'" >'+option_array_relation[index]+'</option>';
            }

            let options = $('.teacher_options').html();


            htmlStr = ``;
            $('#course-row').html(htmlStr);
            for(let i = 1; i <= n; i++){    
                let oldname = ((oldpassedArray != '') ? 'value="' + oldpassedArray['name'+i] + '"'  : '');
                let oldsection = ((oldpassedArray != '') ? 'value="' + oldpassedArray['section'+i] + '"'  : '');;
                let oldstart_date = ((oldpassedArray != '') ? 'value="' + oldpassedArray['start_date'+i] + '"'  : '');;
                let oldend_date = ((oldpassedArray != '') ? 'value="' + oldpassedArray['end_date'+i] + '"'  : '');
                let oldlevel = ((oldpassedArray != '') ? 'value="' + oldpassedArray['level'+i] + '"'  : '');
                let oldstart_time = ((oldpassedArray != '') ? 'value="' + oldpassedArray['start_time'+i] + '"'  : '00:00');
                let oldend_time = ((oldpassedArray != '') ? 'value="' + oldpassedArray['end_time'+i] + '"'  : '00:00');
                let oldtotal_days = ((oldpassedArray != '') ? 'value="' + oldpassedArray['total_days'+i] + '"'  : '');
                let oldaverage_hours = ((oldpassedArray != '') ? 'value="' + oldpassedArray['average_hours'+i] + '"'  : '');
                let oldtotal_hours = ((oldpassedArray != '') ? 'value="' + oldpassedArray['total_hours'+i] + '"'  : '');
                let oldn_d_per_week = ((oldpassedArray != '') ? 'value="' + oldpassedArray['n_d_per_week'+i] + '"'  : '');
                let oldprice = ((oldpassedArray != '') ? 'value="' + oldpassedArray['price'+i] + '"'  : '');

                htmlStr = `
                    <div class="row">
                        <h3>الكورس (`+i+`)</h3>
                        <!-- name -->
                        <div class="mt-4 col-lg-6">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="name`+i+`">اسم الكورس</label>
                            <input class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" id="name`+i+`" `+oldname+` type="text" name="name`+i+`" required="required" autofocus="autofocus" autocomplete="name`+i+`">
                        </div>

                        <!-- section -->
                        <div class="mt-4 col-lg-6">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="section`+i+`">القسم</label>
                            <input class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" id="section`+i+`" `+oldsection+` type="text" name="section`+i+`" required="required" autofocus="autofocus" autocomplete="section`+i+`">
                        </div>

                        <!-- start date -->
                        <div class="mt-4 col-lg-6">
                            <label for="start_date`+i+`" class="block font-medium text-sm text-gray-700">تاريخ البداية </label>
                            <input type="date" id="start_date`+i+`" `+oldstart_date+` name="start_date`+i+`" required="" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-s">
                        </div>

                        <!-- end date -->
                        <div class="mt-4 col-lg-6">
                            <label for="end_date`+i+`" class="block font-medium text-sm text-gray-700">تاريخ النهاية </label>
                            <input type="date" id="end_date`+i+`"  `+oldend_date+` name="end_date`+i+`" required="" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-s">
                        </div>

                        <!-- level -->
                        <div class="mt-4 col-lg-6">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="level`+i+`">المستوى</label>
                            <input class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" id="level`+i+`" `+oldlevel+` type="text" name="level`+i+`" required="required" autofocus="autofocus" autocomplete="level`+i+`">
                        </div>

                        <!-- course start time -->
                        <div class="mt-4 col-lg-6">
                            <label for="start_time`+i+`" class="block font-medium text-sm text-gray-700">وقت بدء التدريب</label> 
                            <input type="time" id="start_time`+i+`" name="start_time`+i+`" `+oldstart_time+` class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        </div>

                        <!-- course end time -->
                        <div class="mt-4 col-lg-6">
                            <label for="end_time`+i+`" class="block font-medium text-sm text-gray-700">وقت انتهاء التدريب</label> 
                            <input type="time" id="end_time`+i+`" name="end_time`+i+`" `+oldend_time+` class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        </div>

                        <!-- total days -->
                        <div class="mt-4 col-lg-6">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="total_days`+i+`">عدد ايام الكورس (المدة الإجمالية )</label>
                            <input class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" id="total_days`+i+`" `+oldtotal_days+` type="number" name="total_days`+i+`" required="required" autofocus="autofocus" autocomplete="total_days">
                        </div>

                        <!-- average hours -->
                        <div class="mt-4 col-lg-6">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="average_hours`+i+`">المعدل اليومي للساعات</label>
                            <input class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" id="average_hours`+i+`" `+oldaverage_hours+` type="number" name="average_hours`+i+`" required="required" autofocus="autofocus" autocomplete="average_hours">
                        </div>

                        <!-- total hours -->
                        <div class="mt-4 col-lg-6">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="total_hours`+i+`">اجمالي عدد الساعات</label>
                            <input class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" id="total_hours`+i+`" `+oldtotal_hours+` type="number" name="total_hours`+i+`" required="required" autofocus="autofocus" autocomplete="total_hours">
                        </div>

                        <!-- number days per week -->
                        <div class="mt-4 col-lg-6">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="n_d_per_week`+i+`">عدد أيام الدراسة بالأسبوع</label>
                            <input class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" id="n_d_per_week`+i+`" `+oldn_d_per_week+` type="number" name="n_d_per_week`+i+`" required="required" autofocus="autofocus" autocomplete="n_d_per_week">
                        </div>

                        <div class="mt-4 col-lg-6">
                            <div class="form-select mt-4 col-lg-6"> 
                                <select id="choices-multiple-remove-button`+i+`" name="days`+i+`[]" required placeholder="أيام الدراسة" multiple>
                                    ` + stringoption + `
                                </select>
                            </div>
                        </div>

                        <!-- price -->
                        <div class="mt-4 col-lg-6">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="price`+i+`">القيمة الإجمالية للكورس</label>
                            <input class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" id="price`+i+`" `+oldprice+` type="number" name="price`+i+`" required="required" autofocus="autofocus" autocomplete="price">
                        </div>

                        <div class="mt-5 col-lg-6">
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="inputGroupSelect01`+i+`">العملة</label>
                                <select class="form-select" id="inputGroupSelect01`+i+`" name="currency`+i+`" required="">
                                    <option disabled="" selected="" value="">أختر...</option>
                                    <option value="USD">دولار</option>
                                    <option value="D">دينار</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-5 col-lg-6">
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="inputGroupSelect`+i+`">مدرس الكورس</label>
                                <select class="form-select" id="inputGroupSelect`+i+`" name="teacher_id`+i+`" required="">
                                    <option selected="" disabled="">أختر....</option>
                                    `+options+`
                                </select>
                            </div>
                        </div>

                    </div>
                    `;
                    $('#course-row').append(htmlStr);
                    addScriptdate(i);
            }
        }

    });
</script>
@stop