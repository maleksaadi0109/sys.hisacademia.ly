<div class="row table-responsive">
    <table class="table table-light table-hover text-center">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">{{__('اسم الكورس')}}</th>
            <th scope="col">{{__('القسم')}}</th>
            <th scope="col">{{__('تاريخ البداية')}}</th>
            <th scope="col">{{__('تاريخ النهاية')}}</th>
            <th scope="col">{{__('المستوى')}}</th>
            <th scope="col">{{__('توقيت البداية')}}</th>
            <th scope="col">{{__('توقيت النهاية')}}</th>
            <th scope="col">{{__('المدة الاجمالية')}}</th>
            <th scope="col">{{__('المعدل اليومي للساعات')}}</th>
            <th scope="col">{{__('اجمالي عدد الساعات')}}</th>
            <th scope="col">{{__('عدد أيام الدراسة بالأسبوع')}}</th>
            <th scope="col">{{__('أيام الدراسة')}}</th>
            <th scope="col">{{__('اسم المدرس')}}</th>
            <th scope="col">{{__('تعديل')}}</th>
            <th scope="col">{{__('حذف')}}</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($courses as $course)
            <tr>
                <td>{{ $course->id }}</td>
                <td>{{ $course->name }}</td>
                <td>{{ $course->section }}</td>
                <td>{{ $course->start_date }}</td>
                <td>{{ $course->end_date }}</td>
                <td>{{ $course->level }}</td>
                <td>{{ date("h:i A", strtotime($course->start_time)) }}</td>
                <td>{{ date("h:i A", strtotime($course->end_time)) }}</td>
                <td>{{ $course->total_days }}</td>
                <td>{{ $course->average_hours }}</td>
                <td>{{ $course->total_hours }}</td>
                <td>{{ $course->n_d_per_week }}</td>
                <td>
                    <div>
                        @foreach(json_decode($course->days) as $value)
                            <span class="badge bg-warning text-dark">{{ App\Enums\WeekDays::WeekDaysAr()[$value] }}</span>
                        @endforeach
                    </div>
                </td>
                <td>{{ $course->user->name }}</td>
                <td>
                    <a href="{{ route('data_entry.edit.course', ['id' => $course->id]) }}" class="btn btn-primary btn-sm">
                        <i class="far fa-edit"></i>
                    </a>
                </td>
                <td>
                    <form method="POST" action="{{ route('data_entry.course.destroy', ['id' => $course->id]) }}" onsubmit="return confirm('{{ __('هل أنت متأكد؟') }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="far fa-trash-alt"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="16" class="text-center">{{__('لا توجد بيانات تطابق بحثك.')}}</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $courses->appends(request()->query())->links() }}
</div>
