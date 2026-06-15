@foreach($questions as $key => $item)
<tr>
<td style="text-align: center;">
    <input type="checkbox" class="form-check-input question-checkbox" value="{{ $item->id }}">
</td>
<td> {{ ($questions->currentPage() - 1) * $questions->perPage() + $loop->iteration }} </td>
<td class="question-column text-wrap">{{ $item->qu_title}}</td>
<td>
    @if ($item->questions_type == "text")

    {{'نصي'}}
    @elseif($item->questions_type == "image")
    {{'صورة'}}
    @elseif($item->questions_type == "video")
    {{'فيديو'}}
    @elseif($item->questions_type == "sound")
    {{'صوت'}}

    @endif
</td>
<td>{{ $item->category ? $item->category->category_name : 'بدون فئة' }}</td>

<td class="text-wrap"> {{
// we can make loop to get more than answer but now we need one answer only
$item->answers->first()->answer_title ?? 'لم يتم تحديد الاجابة'

    }}  </td>

<td >{{ $item->created_at ? $item->created_at->diffForHumans() : 'لم يتم التحديد' }}</td>

<td>
<!-- Hidden details div -->
<div class="d-none question-details-data" id="details-{{ $item->id }}">
    <div class="row mb-3 border-bottom pb-2">
        <div class="col-md-4">
            <strong class="text-dark">نوع اللعبة</strong><br>
            <span class="text-muted">{{ $item->gameType->game_type_name ?? 'غير محدد' }}</span>
        </div>
        <div class="col-md-4">
            <strong class="text-dark">الفئة الرئيسية</strong><br>
            <span class="text-muted">{{ $item->mainCategory->main_category_name ?? 'غير محدد' }}</span>
        </div>
        <div class="col-md-4">
            <strong class="text-dark">الفئة الفرعية</strong><br>
            <span class="text-muted">{{ $item->category->category_name ?? 'غير محدد' }}</span>
        </div>
    </div>
    
    <div class="row mb-3 border-bottom pb-2">
        <div class="col-md-6">
            <strong class="text-dark">السؤال</strong><br>
            <span class="text-primary fs-6">{{ $item->qu_title }}</span>
        </div>
        <div class="col-md-6">
            <strong class="text-dark">Question</strong><br>
            <span class="text-primary fs-6" style="direction: ltr; display: inline-block;">{{ $item->qu_title_en }}</span>
        </div>
    </div>

    <div class="row mb-3 border-bottom pb-2">
        <div class="col-md-6">
            <strong class="text-dark">تلميح للسؤال</strong><br>
            <span class="text-muted">{{ $item->qu_hint ?: 'بدون تلميح' }}</span>
        </div>
        <div class="col-md-6">
            <strong class="text-dark">تلميح للسؤال بالانجليزية</strong><br>
            <span class="text-muted" style="direction: ltr; display: inline-block;">{{ $item->qu_hint_en ?: 'No hint' }}</span>
        </div>
    </div>

    <div class="row mb-3 border-bottom pb-2">
        <div class="col-md-3">
            <strong class="text-dark">نقاط السؤال</strong><br>
            <span class="badge bg-success px-3 py-2 mt-1">{{ $item->qu_points }}</span>
        </div>
        <div class="col-md-3">
            <strong class="text-dark">نقاط السؤال OnLine</strong><br>
            <span class="badge bg-success px-3 py-2 mt-1">{{ $item->qu_points_online }}</span>
        </div>
        <div class="col-md-3">
            <strong class="text-dark">توقيت السؤال</strong><br>
            <span class="badge bg-warning text-dark px-3 py-2 mt-1">{{ $item->time_counter ?: 'بدون' }} {{ $item->time_counter ? 'دقيقة' : '' }}</span>
        </div>
        <div class="col-md-3">
            <strong class="text-dark">توقيت السؤال OnLine</strong><br>
            <span class="badge bg-warning text-dark px-3 py-2 mt-1">{{ $item->time_counter_online ?: '10' }} ثانية</span>
        </div>
    </div>

    <div class="row mb-3 border-bottom pb-2">
        <div class="col-md-4">
            <strong class="text-dark">نوع السؤال</strong><br>
            <span class="text-muted">
                {{ $item->questions_type == 'text' ? 'نصي' : ($item->questions_type == 'image' ? 'صورة' : ($item->questions_type == 'sound' ? 'ملف صوتي' : 'ملف فيديو')) }}
            </span>
        </div>
        <div class="col-md-8">
            <strong class="text-dark">الملف المرفق للسؤال</strong><br>
            @if($item->questions_type == 'image' && $item->qu_image)
                <img src="{{ asset('upload/questions/images/'.$item->qu_image) }}" class="img-fluid rounded shadow-sm mt-2" style="max-height: 200px;">
            @elseif($item->questions_type == 'sound' && $item->qu_sound)
                <audio controls src="{{ asset('upload/questions/sounds/'.$item->qu_sound) }}" class="w-100 mt-2"></audio>
            @elseif($item->questions_type == 'video' && $item->qu_video)
                <video controls src="{{ asset('upload/questions/videos/'.$item->qu_video) }}" class="w-100 rounded shadow-sm mt-2" style="max-height: 200px;"></video>
            @else
                <span class="text-muted mt-1 d-inline-block">بدون ملف</span>
            @endif
        </div>
    </div>

    <h5 class="text-info mt-4 mb-3 border-bottom pb-2"><i class="bx bx-list-check"></i> الاجابة Local</h5>
    @php
        $localAnswer = $item->answers->first();
    @endphp
    @if($localAnswer)
    <div class="row mb-3">
        <div class="col-md-4">
            <strong class="text-dark">الاجابة</strong><br>
            <span class="text-primary">{{ $localAnswer->answer_title }}</span>
        </div>
        <div class="col-md-4">
            <strong class="text-dark">Answer</strong><br>
            <span class="text-primary" style="direction: ltr; display: inline-block;">{{ $localAnswer->answer_title_en }}</span>
        </div>
        <div class="col-md-4">
            <strong class="text-dark">نوع الاجابة</strong><br>
            <span class="text-muted">
                {{ $localAnswer->answer_type == 'text' ? 'نصي' : ($localAnswer->answer_type == 'image' ? 'صورة' : ($localAnswer->answer_type == 'sound' ? 'ملف صوتي' : 'ملف فيديو')) }}
            </span>
        </div>
        @if($localAnswer->answer_type != 'text')
        <div class="col-md-12 mt-3">
            @if($localAnswer->answer_type == 'image' && $localAnswer->answer_image)
                <img src="{{ asset('upload/answers/images/'.$localAnswer->answer_image) }}" class="img-fluid rounded shadow-sm" style="max-height: 150px;">
            @elseif($localAnswer->answer_type == 'sound' && $localAnswer->answer_sound)
                <audio controls src="{{ asset('upload/answers/sounds/'.$localAnswer->answer_sound) }}" class="w-100"></audio>
            @elseif($localAnswer->answer_type == 'video' && $localAnswer->answer_video)
                <video controls src="{{ asset('upload/answers/videos/'.$localAnswer->answer_video) }}" class="w-100 rounded shadow-sm" style="max-height: 150px;"></video>
            @endif
        </div>
        @endif
    </div>
    @else
    <p class="text-muted">لا يوجد إجابة محلية</p>
    @endif

    <h5 class="text-info mt-4 mb-3 border-bottom pb-2"><i class="bx bx-globe"></i> الاجابات OnLine</h5>
    @if($item->answerQuestionOnlines->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-sm text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 15%;">الحالة</th>
                        <th style="width: 35%;">الاجابة (عربي)</th>
                        <th style="width: 35%;">الاجابة (إنجليزي)</th>
                        <th style="width: 15%;">النوع</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($item->answerQuestionOnlines as $ans)
                    <tr>
                        <td>
                            @if($ans->is_correct)
                                <span class="badge bg-success">إجابة 1 الصحيحة</span>
                            @else
                                <span class="badge bg-danger">إجابة {{ $loop->iteration }}</span>
                            @endif
                        </td>
                        <td class="text-dark fw-bold">{{ $ans->answer_title }}</td>
                        <td class="text-dark fw-bold" style="direction: ltr;">{{ $ans->answer_title_en }}</td>
                        <td>
                            <span class="text-muted small d-block mb-1">
                                {{ $ans->answer_type == 'text' ? 'نصي' : ($ans->answer_type == 'image' ? 'صورة' : ($ans->answer_type == 'sound' ? 'ملف صوتي' : 'ملف فيديو')) }}
                            </span>
                            @if($ans->answer_type == 'image' && $ans->answer_image)
                                <img src="{{ asset('upload/answers/online/images/'.$ans->answer_image) }}" class="img-fluid rounded shadow-sm" style="max-height: 80px;">
                            @elseif($ans->answer_type == 'sound' && $ans->answer_sound)
                                <audio controls src="{{ asset('upload/answers/online/sounds/'.$ans->answer_sound) }}" class="w-100" style="height:35px;"></audio>
                            @elseif($ans->answer_type == 'video' && $ans->answer_video)
                                <video controls src="{{ asset('upload/answers/online/videos/'.$ans->answer_video) }}" class="w-100 rounded shadow-sm" style="max-height: 80px;"></video>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
    <p class="text-muted">لا توجد إجابات أونلاين</p>
    @endif
</div>

<a href="javascript:;" class="btn btn-warning view-preview-btn" data-id="{{ $item->id }}">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
      <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
      <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
    </svg>
</a>
<a href="{{route('edit.question',$item->id)}}" class="btn btn-info">تعديل</a>
<a href="{{ route('delete.question',$item->id) }}" class="btn btn-danger" id="delete" >حذف</a>

</td>
</tr>
@endforeach
