@extends('admin.dashboard')
@section('content')
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">{{__('استخراج إيصال المعلم')}}</h2>
    </header>

    <div class="container">
        <div class="row table-responsive">



            <div class="mt-5 col-lg-12">
                <form method="POST" enctype="multipart/form-data" action="{{ route('print_teacher_bill') }}">
                @csrf
                <div class="mt-5 col-lg-6">
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="selectsoursechange">المعلم</label>
                        <select class="form-select" id="selectsoursechange" name="teacher_id" required>
                            <option selected disabled>أختر....</option>
                            @foreach($teachers as $teacher)
                                @if(old('teacher_id') == $teacher->id)
                                    <option selected value="{{$teacher->id}}">{{$teacher->name}}</option>
                                @else
                                    <option value="{{$teacher->id}}">{{$teacher->name}}</option>
                                @endif
                            @endforeach
                        </select>
                        @php
                            $messages = $errors->get('teacher_id');
                        @endphp
                        @if ($messages)
                            <ul class="text-sm text-red-600 space-y-1 mt-2">
                                @foreach ((array) $messages as $message)
                                    <li>{{ $message }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="mt-5 col-lg-6">
                    <div class="input-group mb-3">
                        <label class="input-group-text" >الكورس</label>
                        <select class="form-select" id="course_select" name="course_id" required>
                        </select>
                    </div>
                </div>
                    <div class="row">
                        <x-primary-button class="mt-4 col-lg-2 justify-content-center">
                            <i class="fas fa-print"></i>{{ __('طباعة') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>

        </div>
    </div>

</section>
<script>
    $(document).ready(function () {

    $('#selectsoursechange').change(function (){
        var teacher_id = $(this).val();
        callAjaxForCourse(teacher_id);
    });

    function callAjaxForCourse(teacher_id) {
        $.ajax({
            url        :window.location.origin +'/admin/course_byteacher/'+teacher_id,
            type       :'GET',
            dataType   :'JSON',
            success    :function(result){
                var option = `<option selected disabled>اختر</option>`;
                var temp = ``;
                $('#course_select').html(option);
                result.forEach(function(item){
                    var temp = `<option value="`+item['course_id']+`">`+item['course_name']+`</option>`;
                    option = option + temp;
                });
                $('#course_select').html(option);
            },
            error: function () {
            alert('فشل تحميل الكورسات');
            }
        });
    }

    });
</script>

@stop
