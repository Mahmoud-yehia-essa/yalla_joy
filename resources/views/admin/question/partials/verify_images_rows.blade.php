{{-- partial: verify_images_rows.blade.php
     يُستخدم في AJAX lazy loading فقط - يستقبل $rows كمصفوفة PHP عادية
--}}
@foreach($rows as $index => $row)
@php
    $globalIndex = $startIndex + $index + 1;
    $qStatus     = $row['question_image_status'];
    $hasIssue    = $row['has_any_issue'] ? '1' : '0';
    $allFound    = (!$row['has_any_issue']) ? '1' : '0';
    $answerMiss  = $row['has_answer_issue'] ? '1' : '0';
    
    $ansOkCount  = 0;
    $ansBadCount = 0;
    foreach ($row['answers'] as $ans) {
        if ($ans['answer_type'] === 'image') {
            if ($ans['image_status'] === 'found') {
                $ansOkCount++;
            } else {
                $ansBadCount++;
            }
        }
    }
@endphp
<tr class="verify-row"
    data-category-name="{{ $row['category_name'] ?? '—' }}"
    data-question-type="{{ $row['questions_type'] ?? '' }}"
    data-question-status="{{ $qStatus ?? 'na' }}"
    data-has-issue="{{ $hasIssue }}"
    data-all-found="{{ $allFound }}"
    data-has-answer-missing="{{ $answerMiss }}"
    data-ans-ok-count="{{ $ansOkCount }}"
    data-ans-bad-count="{{ $ansBadCount }}">

    {{-- رقم + ID --}}
    <td>
        <span class="fw-700 text-muted" style="font-size:0.85rem;">{{ $globalIndex }}</span><br>
        <small class="text-muted" style="font-size:0.7rem;">ID: {{ $row['id'] }}</small>
    </td>

    {{-- عنوان السؤال --}}
    <td>
        <a href="{{ route('edit.question', $row['id']) }}"
           target="_blank"
           style="text-decoration:none; color:inherit;"
           title="تعديل السؤال">
            <div class="fw-600 text-dark verify-q-title">
                <i class="bx bx-edit-alt me-1" style="font-size:0.8rem; opacity:0.5;"></i>
                {{ \Illuminate\Support\Str::limit($row['qu_title'], 55) }}
            </div>
        </a>
        @if(!empty($row['qu_title_en']))
        <div class="text-muted mt-1" style="font-size:0.75rem; direction:ltr;">
            {{ \Illuminate\Support\Str::limit($row['qu_title_en'], 45) }}
        </div>
        @endif
    </td>

    {{-- نوع السؤال --}}
    <td>
        @php $qType = $row['questions_type']; @endphp
        <span class="type-badge {{ $qType }}">
            @if($qType=='image') <i class="bx bx-image"></i> صورة
            @elseif($qType=='text') <i class="bx bx-text"></i> نصي
            @elseif($qType=='sound') <i class="bx bx-music"></i> صوت
            @elseif($qType=='video') <i class="bx bx-video"></i> فيديو
            @else {{ $qType }}
            @endif
        </span>
    </td>

    {{-- الفئة --}}
    <td>
        <small class="text-muted">{{ $row['category_name'] }}</small>
    </td>

    {{-- مسار صورة السؤال --}}
    <td>
        @if($qType === 'image')
            @if(!empty($row['qu_image']))
                <div class="d-flex align-items-center gap-2">
                    @if($qStatus === 'found')
                        <img src="{{ asset('upload/questions/images/' . $row['qu_image']) }}"
                             class="img-preview-thumb"
                             loading="lazy"
                             onclick="showImagePreview('{{ asset('upload/questions/images/' . $row['qu_image']) }}', '{{ addslashes($row['qu_title']) }}')"
                             alt="صورة السؤال">
                    @else
                        <div style="width:56px;height:56px;border-radius:8px;background:#fee2e2;border:2px dashed #ef4444;display:flex;align-items:center;justify-content:center;">
                            <i class="bx bx-image-alt" style="color:#ef4444;font-size:1.4rem;"></i>
                        </div>
                    @endif
                    <code style="font-size:0.68rem;color:#475569;word-break:break-all;max-width:90px;display:block;">{{ $row['qu_image'] }}</code>
                </div>
            @else
                <span class="text-muted" style="font-size:0.78rem;"><i class="bx bx-minus-circle me-1"></i>لا يوجد مسار</span>
            @endif
        @else
            <span class="text-muted" style="opacity:0.4;">—</span>
        @endif
    </td>

    {{-- حالة صورة السؤال --}}
    <td>
        @if($qType === 'image')
            @if($qStatus === 'found')
                <span class="status-badge found"><i class="bx bx-check-circle"></i> موجودة</span>
            @elseif($qStatus === 'missing')
                <span class="status-badge missing"><i class="bx bx-x-circle"></i> مفقودة</span>
            @elseif($qStatus === 'no_path')
                <span class="status-badge no-path"><i class="bx bx-error-circle"></i> بلا مسار</span>
            @endif
        @else
            <span class="status-badge not-applicable"><i class="bx bx-minus"></i> غير منطبق</span>
        @endif
    </td>

    {{-- الإجابات وحالة صورها --}}
    <td>
        @if(count($row['answers']) > 0)
            <ul class="answers-list">
                @foreach($row['answers'] as $aIdx => $answer)
                <li class="answer-item">
                    <div style="flex:1;min-width:0;">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="type-badge {{ $answer['answer_type'] }}" style="font-size:0.68rem;padding:2px 8px;">
                                @if($answer['answer_type']=='image') صورة
                                @elseif($answer['answer_type']=='text') نصي
                                @elseif($answer['answer_type']=='sound') صوت
                                @elseif($answer['answer_type']=='video') فيديو
                                @else {{ $answer['answer_type'] }}
                                @endif
                            </span>
                            @if($answer['answer_type'] === 'image')
                                @if($answer['image_status'] === 'found')
                                    <span class="status-badge found" style="font-size:0.68rem;padding:2px 8px;"><i class="bx bx-check"></i> موجودة</span>
                                    <img src="{{ asset('upload/answers/images/' . $answer['answer_image']) }}"
                                         class="img-preview-thumb"
                                         style="width:36px;height:36px;"
                                         loading="lazy"
                                         onclick="showImagePreview('{{ asset('upload/answers/images/' . $answer['answer_image']) }}', 'إجابة {{ $aIdx+1 }}')"
                                         alt="">
                                @elseif($answer['image_status'] === 'missing')
                                    <span class="status-badge missing" style="font-size:0.68rem;padding:2px 8px;"><i class="bx bx-x"></i> مفقودة</span>
                                    <code style="font-size:0.63rem;color:#ef4444;">{{ $answer['answer_image'] }}</code>
                                @elseif($answer['image_status'] === 'no_path')
                                    <span class="status-badge no-path" style="font-size:0.68rem;padding:2px 8px;"><i class="bx bx-error"></i> بلا مسار</span>
                                @endif
                            @else
                                <span class="text-muted" style="font-size:0.72rem;">
                                    {{ \Illuminate\Support\Str::limit($answer['answer_title'], 30) }}
                                </span>
                            @endif
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        @else
            <span class="text-muted" style="font-size:0.78rem;"><i class="bx bx-info-circle me-1"></i>لا توجد إجابات</span>
        @endif
    </td>
</tr>
@endforeach
