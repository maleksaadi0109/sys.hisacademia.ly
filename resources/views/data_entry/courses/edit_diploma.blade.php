@extends('data_entry.dashboard')
@section('content')
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{__('تعديل الدبلوم')}}</h2>
        </header>

        <div class="container">
            <div class="row">
                <form method="POST" enctype="multipart/form-data" action="{{ route('data_entry.update.diploma', ['id' => $diploma->id]) }}">
                    @csrf
                    @method('patch')
                    <div class="row">
                        @php 
                        use App\Enums\WeekDays;
                        @endphp
                        <!-- diploma name -->
                        <div class="mt-4 col-lg-6">
                            <x-input-label for="diploma_name" :value="__('اسم الدبلوم')" />
                            <x-text-input id="diploma_name" value="{{$diploma->name}}" class="block mt-1 w-full" type="text" name="diploma_name" required autofocus autocomplete="diploma_name" />
                            <x-input-error :messages="$errors->get('diploma_name')" class="mt-2" />
                        </div>
                        @if($isfindStudentindiploma)
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="number_course" :value="__('لا يمكن تعديل عدد الكورسات لانه يوجد طلاب مسجلين في هذا الدبلوم')" />
                                <x-text-input id="number_course" disabled value="{{$diploma->number_course}}" class="block mt-1 w-full" type="text" name="number_course" required autofocus autocomplete="number_course" />
                                <x-input-error :messages="$errors->get('number_course')" class="mt-2" />
                            </div>
                        @else
                            <div class="mt-4 col-lg-6">
                                <x-input-label for="number_course" :value="__('عدد الكورسات')" />
                                <x-text-input id="number_course" value="{{$diploma->number_course}}" class="block mt-1 w-full" type="text" name="number_course" required autofocus autocomplete="number_course" />
                                <x-input-error :messages="$errors->get('number_course')" class="mt-2" />
                            </div>
                        @endif
                        <select class="teacher_options" hidden>
                            @foreach($teachers as $value)
                                    <option value="{{$value->user->id}}">{{$value->user->name}}</option>
                            @endforeach
                        </select>

                        <div class="row mt-5" id="course-row">
                            @foreach($diploma->diplomaCourses as $key=>$course)
                                <div class="row">
                                    <h3>الكورس ({{$key+1}})</h3>
                                    <!-- name -->
                                    <div class="mt-4 col-lg-6">
                                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="name{{$key+1}}">اسم الكورس</label>
                                        <input class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" id="name{{$key+1}}" value="{{$course->name}}" type="text" name="name{{$key+1}}" required="required" autofocus="autofocus" autocomplete="name{{$key+1}}">
                                    </div>
            
                                    <!-- section -->
                                    <div class="mt-4 col-lg-6">
                                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="section{{$key+1}}">القسم</label>
                                        <input class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" id="section{{$key+1}}" value="{{$course->section}}" type="text" name="section{{$key+1}}" required="required" autofocus="autofocus" autocomplete="section{{$key+1}}">
                                    </div>
            
                                    <!-- start date -->
                                    <div class="mt-4 col-lg-6">
                                        <label for="start_date{{$key+1}}" class="block font-medium text-sm text-gray-700">تاريخ البداية </label>
                                        <input type="date" id="start_date{{$key+1}}" value="{{$course->start_date}}" name="start_date{{$key+1}}" required="" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-s">
                                    </div>
            
                                    <!-- end date -->
                                    <div class="mt-4 col-lg-6">
                                        <label for="end_date{{$key+1}}" class="block font-medium text-sm text-gray-700">تاريخ النهاية </label>
                                        <input type="date" id="end_date{{$key+1}}"  value="{{$course->end_date}}" name="end_date{{$key+1}}" required="" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-s">
                                    </div>
            
                                    <!-- level -->
                                    <div class="mt-4 col-lg-6">
                                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="level{{$key+1}}">المستوى</label>
                                        <input class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" id="level{{$key+1}}" value="{{$course->level}}" type="text" name="level{{$key+1}}" required="required" autofocus="autofocus" autocomplete="level{{$key+1}}">
                                    </div>
            
                                    <!-- course start time -->
                                    <div class="mt-4 col-lg-6">
                                        <label for="start_time{{$key+1}}" class="block font-medium text-sm text-gray-700">وقت بدء التدريب</label> 
                                        <input type="time" id="start_time{{$key+1}}" name="start_time{{$key+1}}" value="{{date("H:i", strtotime($course->start_time))}}" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    </div>
            
                                    <!-- course end time -->
                                    <div class="mt-4 col-lg-6">
                                        <label for="end_time{{$key+1}}" class="block font-medium text-sm text-gray-700">وقت انتهاء التدريب</label> 
                                        <input type="time" id="end_time{{$key+1}}" name="end_time{{$key+1}}" value="{{date("H:i", strtotime($course->end_time))}}" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    </div>
            
                                    <!-- total days -->
                                    <div class="mt-4 col-lg-6">
                                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="total_days{{$key+1}}">عدد ايام الكورس (المدة الإجمالية )</label>
                                        <input class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" id="total_days{{$key+1}}" value="{{$course->total_days}}" type="number" name="total_days{{$key+1}}" required="required" autofocus="autofocus" autocomplete="total_days">
                                    </div>
            
                                    <!-- average hours -->
                                    <div class="mt-4 col-lg-6">
                                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="average_hours{{$key+1}}">المعدل اليومي للساعات</label>
                                        <input class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" id="average_hours{{$key+1}}" value="{{$course->average_hours}}" type="number" name="average_hours{{$key+1}}" required="required" autofocus="autofocus" autocomplete="average_hours">
                                    </div>
            
                                    <!-- total hours -->
                                    <div class="mt-4 col-lg-6">
                                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="total_hours{{$key+1}}">اجمالي عدد الساعات</label>
                                        <input class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" id="total_hours{{$key+1}}" value="{{$course->total_hours}}" type="number" name="total_hours{{$key+1}}" required="required" autofocus="autofocus" autocomplete="total_hours">
                                    </div>
            
                                    <!-- number days per week -->
                                    <div class="mt-4 col-lg-6">
                                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="n_d_per_week{{$key+1}}">عدد أيام الدراسة بالأسبوع</label>
                                        <input class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" id="n_d_per_week{{$key+1}}" value="{{$course->n_d_per_week}}" type="number" name="n_d_per_week{{$key+1}}" required="required" autofocus="autofocus" autocomplete="n_d_per_week">
                                    </div>
            
                                    <div class="mt-4 col-lg-6">
                                        <div class="form-select mt-4 col-lg-6"> 
                                            <select id="choices-multiple-remove-button{{$key+1}}" name="days{{$key+1}}[]" required placeholder="أيام الدراسة" multiple>
                                            @foreach (WeekDays::weekDaysAr() as $index => $value) 
                                                @if (in_array($index,json_decode($course->days)))
                                                    <option selected value="{{$index}}">{{$value}}</option>
                                                @else
                                                    <option value="{{$index}}">{{$value}}</option>
                                                @endif
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
            
                                    <!-- price -->
                                    <div class="mt-4 col-lg-6">
                                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="price{{$key+1}}">القيمة الإجمالية للكورس</label>
                                        <input class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" id="price{{$key+1}}" value="{{$course->price}}" type="number" name="price{{$key+1}}" required="required" autofocus="autofocus" autocomplete="price">
                                    </div>
            
                                    <div class="mt-5 col-lg-6">
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" for="inputGroupSelect01{{$key+1}}">العملة</label>
                                            <select class="form-select" id="inputGroupSelect01{{$key+1}}" name="currency{{$key+1}}" required="">
                                                @if($course->currency == "USD")
                                                <option selected value="USD">دولار</option>
                                                <option value="D">دينار</option>
                                                @else
                                                <option value="USD">دولار</option>
                                                <option selected value="D">دينار</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
            
                                    <div class="mt-5 col-lg-6">
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" for="inputGroupSelect{{$key+1}}">مدرس الكورس</label>
                                            <select class="form-select" id="inputGroupSelect{{$key+1}}" name="teacher_id{{$key+1}}" required="">
                                                <option selected="" disabled="">أختر....</option>
                                                @foreach($teachers as $value)
                                                    @if($course->teacher_id == $value->user->id)
                                                        <option selected value="{{$value->user->id}}">{{$value->user->name}}</option>
                                                    @else
                                                        <option value="{{$value->user->id}}">{{$value->user->name}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
            
                                    <script>
                                        $(document).ready(function () {
                                            var multipleCancelButton = new Choices('#choices-multiple-remove-button{{$key+1}}', {
                                            removeItemButton: true,
                                            searchResultLimit:5,
                                            renderChoiceLimit:5
                                            });
                                        });
                                    </script>
                                </div>
                            @endforeach
                        </div>

                        <div class="row">
                            <x-primary-button class="mt-4 col-lg-2 justify-content-center">
                                {{ __('تعديل') }}
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>

        $(document).ready(function () {
    
            const oldpassedArray = <?php echo json_encode(session()->getOldInput())? json_encode(session()->getOldInput()) : ''; ?>;
            
            $('#number_course').on('input', function () {
                var value = $(this).val();
                if ((value !== '') && (value.indexOf('.') === -1 && value > 0)) {
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