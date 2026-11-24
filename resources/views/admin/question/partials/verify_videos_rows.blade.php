{{-- partial: verify_videos_rows.blade.php
     يُستخدم في AJAX lazy loading لصفحة التحقق من فيديوهات الأسئلة والإجابات
--}}
@foreach($rows as $index => $row)
@php
    $globalIndex = $startIndex + $index + 1;
    $qType       = $row['questions_type'];
    $qInfo       = $row['question_video_info'];
    $qStatus     = $row['question_video_status'];
    $hasIssue    = $row['has_any_issue'] ? '1' : '0';
    $allFound    = (!$row['has_any_issue']) ? '1' : '0';
    $answerMiss  = $row['has_answer_issue'] ? '1' : '0';
    $hasCorrupt  = $row['has_corrupted'] ? '1' : '0';
    $hasWarning  = $row['has_warning'] ? '1' : '0';
    $editUrl     = route('edit.question', $row['id']);

    $onlineVideoAnswers = array_values(array_filter($row['online_answers'], fn($a) => $a['answer_type'] === 'video'));
    $onlineNonVideoCount = count($row['online_answers']) - count($onlineVideoAnswers);
@endphp
<tr class="verify-row"
    data-category-name="{{ $row['category_name'] ?? '—' }}"
    data-question-type="{{ $qType ?? '' }}"
    data-question-status="{{ $qStatus ?? 'na' }}"
    data-has-issue="{{ $hasIssue }}"
    data-all-found="{{ $allFound }}"
    data-has-answer-missing="{{ $answerMiss }}"
    data-has-corrupted="{{ $hasCorrupt }}"
    data-has-warning="{{ $hasWarning }}"
    data-ans-ok-count="{{ $row['ans_ok_count'] }}"
    data-ans-bad-count="{{ $row['ans_bad_count'] }}">

    {{-- رقم + ID --}}
    <td>
        <span class="fw-700 text-muted" style="font-size:0.85rem;">{{ $globalIndex }}</span><br>
        <span class="badge bg-light text-dark border mt-1" style="font-size:0.68rem;">ID: {{ $row['id'] }}</span>
    </td>

    {{-- عنوان السؤال --}}
    <td>
        <a href="{{ $editUrl }}"
           target="_blank"
           style="text-decoration:none; color:inherit;"
           title="فتح صفحة تعديل السؤال">
            <div class="fw-600 text-dark verify-q-title">
                <i class="bx bx-edit-alt me-1" style="font-size:0.82rem; opacity:0.55;"></i>
                {{ \Illuminate\Support\Str::limit($row['qu_title'], 60) }}
            </div>
        </a>
        @if(!empty($row['qu_title_en']))
        <div class="text-muted mt-1" style="font-size:0.74rem; direction:ltr; text-align:right;">
            {{ \Illuminate\Support\Str::limit($row['qu_title_en'], 50) }}
        </div>
        @endif
    </td>

    {{-- النوع والفئة --}}
    <td>
        <div class="d-flex flex-column gap-1 align-items-start">
            <span class="type-badge {{ $qType }}">
                @if($qType === 'video') <i class="bx bx-video"></i> فيديو
                @elseif($qType === 'image') <i class="bx bx-image"></i> صورة
                @elseif($qType === 'sound') <i class="bx bx-music"></i> صوت
                @elseif($qType === 'text') <i class="bx bx-text"></i> نصي
                @else {{ $qType }}
                @endif
            </span>
            <small class="text-muted fw-600" style="font-size:0.74rem;">
                <i class="bx bx-folder me-1" style="opacity:0.6;"></i>{{ $row['category_name'] }}
            </small>
        </div>
    </td>

    {{-- فيديو السؤال والمسار --}}
    <td>
        @if($qType === 'video' && $qInfo)
            @if(!empty($row['qu_video']))
                <div class="d-flex align-items-start gap-2">
                    @if($qStatus === 'found')
                        <button type="button"
                                class="video-play-tile btn-play-video"
                                title="تشغيل ومعاينة الفيديو"
                                data-video-src="{{ $row['qu_video_url'] }}"
                                data-video-title="{{ $row['qu_title'] }}"
                                data-video-path="{{ $row['qu_video'] }}"
                                data-video-size="{{ $qInfo['size_formatted'] }}"
                                data-video-ext="{{ strtoupper($qInfo['extension']) }}"
                                data-video-scope="فيديو السؤال"
                                data-edit-url="{{ $editUrl }}">
                            <i class="bx bx-play-circle"></i>
                            <span>{{ strtoupper($qInfo['extension'] ?: 'VID') }}</span>
                        </button>
                    @elseif($qStatus === 'corrupted')
                        <div class="video-status-tile tile-corrupted" title="ملف فارغ / تالف (0 بايت)">
                            <i class="bx bx-bug"></i>
                            <span>0 B</span>
                        </div>
                    @else
                        <div class="video-status-tile tile-missing" title="الملف غير موجود على الخادم">
                            <i class="bx bx-video-off"></i>
                            <span>مفقود</span>
                        </div>
                    @endif

                    <div style="min-width:0; flex:1;">
                        <div class="d-flex align-items-center gap-1 flex-wrap">
                            <code class="video-path-code" title="{{ $row['qu_video'] }}">{{ $row['qu_video'] }}</code>
                            <button type="button"
                                    class="btn-micro-icon btn-copy-path"
                                    data-copy-text="{{ $row['qu_video'] }}"
                                    title="نسخ المسار">
                                <i class="bx bx-copy"></i>
                            </button>
                            @if($qStatus === 'found' && $row['qu_video_url'])
                                <a href="{{ $row['qu_video_url'] }}"
                                   target="_blank"
                                   class="btn-micro-icon"
                                   title="فتح الرابط المباشر">
                                    <i class="bx bx-link-external"></i>
                                </a>
                            @endif
                        </div>

                        <div class="d-flex align-items-center gap-1 flex-wrap mt-1">
                            @if($qStatus === 'found')
                                <span class="meta-pill {{ $qInfo['is_large'] ? 'pill-warn' : 'pill-size' }}">
                                    <i class="bx bx-hdd"></i> {{ $qInfo['size_formatted'] }}
                                </span>
                                @if(!$qInfo['is_web_compatible'])
                                    <span class="meta-pill pill-warn" title="صيغة قد لا تعمل بسلاسة على جميع الأجهزة">
                                        <i class="bx bx-error"></i> صيغة {{ strtoupper($qInfo['extension']) }}
                                    </span>
                                @endif
                            @endif
                            @if($qInfo['encoding_mismatch'])
                                <span class="meta-pill pill-info" title="الملف موجود بترميز مختلف ويمكن إصلاحه بزر ضبط الأسماء">
                                    <i class="bx bx-magic-wand"></i> قابل للإصلاح بالضبط
                                </span>
                            @elseif($qInfo['needs_sanitize'])
                                <span class="meta-pill pill-warn" title="الاسم يحتوي على مسافات أو أحرف غير قياسية">
                                    <i class="bx bx-rename"></i> اسم غير قياسي
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <span class="text-warning fw-600" style="font-size:0.78rem;">
                    <i class="bx bx-error-circle me-1"></i>لا يوجد مسار مسجل
                </span>
            @endif
        @else
            <span class="text-muted" style="opacity:0.45;">—</span>
        @endif
    </td>

    {{-- حالة فيديو السؤال --}}
    <td>
        @if($qType === 'video')
            @if($qStatus === 'found')
                <span class="status-badge found"><i class="bx bx-check-circle"></i> موجود وسليم</span>
            @elseif($qStatus === 'corrupted')
                <span class="status-badge corrupted"><i class="bx bx-bug"></i> تالف (0B)</span>
            @elseif($qStatus === 'missing')
                <span class="status-badge missing"><i class="bx bx-x-circle"></i> مفقود</span>
            @elseif($qStatus === 'no_path')
                <span class="status-badge no-path"><i class="bx bx-error-circle"></i> بلا مسار</span>
            @endif
        @else
            <span class="status-badge not-applicable"><i class="bx bx-minus"></i> غير منطبق</span>
        @endif
    </td>

    {{-- فيديوهات الإجابات (المحلية والأونلاين) --}}
    <td>
        <ul class="answers-list">
            {{-- الإجابات المحلية (Local) --}}
            @foreach($row['local_answers'] as $aIdx => $answer)
                <li class="answer-item">
                    <span class="scope-tag scope-local">محلي</span>
                    <div style="flex:1; min-width:0;">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="type-badge {{ $answer['answer_type'] }}" style="font-size:0.68rem; padding:2px 8px;">
                                @if($answer['answer_type'] === 'video') فيديو
                                @elseif($answer['answer_type'] === 'image') صورة
                                @elseif($answer['answer_type'] === 'sound') صوت
                                @elseif($answer['answer_type'] === 'text') نصي
                                @else {{ $answer['answer_type'] }}
                                @endif
                            </span>

                            @if($answer['answer_type'] === 'video' && $answer['video_info'])
                                @php $aInfo = $answer['video_info']; $aSt = $aInfo['status']; @endphp
                                @if($aSt === 'found')
                                    <span class="status-badge found" style="font-size:0.68rem; padding:2px 8px;">
                                        <i class="bx bx-check"></i> موجود ({{ $aInfo['size_formatted'] }})
                                    </span>
                                    <button type="button"
                                            class="btn-play-inline btn-play-video"
                                            data-video-src="{{ $answer['url'] }}"
                                            data-video-title="إجابة محلية — {{ $row['qu_title'] }}"
                                            data-video-path="{{ $answer['answer_video'] }}"
                                            data-video-size="{{ $aInfo['size_formatted'] }}"
                                            data-video-ext="{{ strtoupper($aInfo['extension']) }}"
                                            data-video-scope="إجابة محلية (Local)"
                                            data-edit-url="{{ $editUrl }}">
                                        <i class="bx bx-play"></i> تشغيل
                                    </button>
                                @elseif($aSt === 'corrupted')
                                    <span class="status-badge corrupted" style="font-size:0.68rem; padding:2px 8px;">
                                        <i class="bx bx-bug"></i> تالف (0B)
                                    </span>
                                @elseif($aSt === 'missing')
                                    <span class="status-badge missing" style="font-size:0.68rem; padding:2px 8px;">
                                        <i class="bx bx-x"></i> مفقود
                                    </span>
                                @elseif($aSt === 'no_path')
                                    <span class="status-badge no-path" style="font-size:0.68rem; padding:2px 8px;">
                                        <i class="bx bx-error"></i> بلا مسار
                                    </span>
                                @endif

                                @if(!empty($answer['answer_video']))
                                    <code class="video-path-code" style="max-width:140px;" title="{{ $answer['answer_video'] }}">{{ $answer['answer_video'] }}</code>
                                    <button type="button" class="btn-micro-icon btn-copy-path" data-copy-text="{{ $answer['answer_video'] }}" title="نسخ المسار">
                                        <i class="bx bx-copy"></i>
                                    </button>
                                @endif

                                @if($aInfo['encoding_mismatch'])
                                    <span class="meta-pill pill-info"><i class="bx bx-magic-wand"></i> قابل للإصلاح</span>
                                @elseif($aInfo['needs_sanitize'])
                                    <span class="meta-pill pill-warn"><i class="bx bx-rename"></i> اسم غير قياسي</span>
                                @endif
                                @if($aInfo['is_large'])
                                    <span class="meta-pill pill-warn"><i class="bx bx-hdd"></i> حجم كبير</span>
                                @endif
                            @else
                                <span class="text-muted" style="font-size:0.73rem;">
                                    {{ \Illuminate\Support\Str::limit($answer['answer_title'] ?: '—', 32) }}
                                </span>
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach

            {{-- إجابات الأونلاين التي تحتوي على فيديو --}}
            @foreach($onlineVideoAnswers as $oAns)
                @php $oaInfo = $oAns['video_info']; $oaSt = $oaInfo['status'] ?? 'no_path'; @endphp
                <li class="answer-item">
                    <span class="scope-tag scope-online">أونلاين #{{ $oAns['index'] }}</span>
                    <div style="flex:1; min-width:0;">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            @if($oaSt === 'found')
                                <span class="status-badge found" style="font-size:0.68rem; padding:2px 8px;">
                                    <i class="bx bx-check"></i> موجود ({{ $oaInfo['size_formatted'] }})
                                </span>
                                <button type="button"
                                        class="btn-play-inline btn-play-video"
                                        data-video-src="{{ $oAns['url'] }}"
                                        data-video-title="إجابة أونلاين #{{ $oAns['index'] }} — {{ $row['qu_title'] }}"
                                        data-video-path="{{ $oAns['answer_video'] }}"
                                        data-video-size="{{ $oaInfo['size_formatted'] }}"
                                        data-video-ext="{{ strtoupper($oaInfo['extension']) }}"
                                        data-video-scope="إجابة أونلاين #{{ $oAns['index'] }} (Online)"
                                        data-edit-url="{{ $editUrl }}">
                                    <i class="bx bx-play"></i> تشغيل
                                </button>
                            @elseif($oaSt === 'corrupted')
                                <span class="status-badge corrupted" style="font-size:0.68rem; padding:2px 8px;">
                                    <i class="bx bx-bug"></i> تالف (0B)
                                </span>
                            @elseif($oaSt === 'missing')
                                <span class="status-badge missing" style="font-size:0.68rem; padding:2px 8px;">
                                    <i class="bx bx-x"></i> مفقود
                                </span>
                            @elseif($oaSt === 'no_path')
                                <span class="status-badge no-path" style="font-size:0.68rem; padding:2px 8px;">
                                    <i class="bx bx-error"></i> بلا مسار
                                </span>
                            @endif

                            @if(!empty($oAns['answer_video']))
                                <code class="video-path-code" style="max-width:140px;" title="{{ $oAns['answer_video'] }}">{{ $oAns['answer_video'] }}</code>
                                <button type="button" class="btn-micro-icon btn-copy-path" data-copy-text="{{ $oAns['answer_video'] }}" title="نسخ المسار">
                                    <i class="bx bx-copy"></i>
                                </button>
                            @endif

                            @if($oaInfo && $oaInfo['encoding_mismatch'])
                                <span class="meta-pill pill-info"><i class="bx bx-magic-wand"></i> قابل للإصلاح</span>
                            @elseif($oaInfo && $oaInfo['needs_sanitize'])
                                <span class="meta-pill pill-warn"><i class="bx bx-rename"></i> اسم غير قياسي</span>
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach

            @if(count($row['local_answers']) === 0 && count($onlineVideoAnswers) === 0)
                <li class="answer-item">
                    <span class="text-muted" style="font-size:0.76rem;"><i class="bx bx-info-circle me-1"></i>لا توجد إجابات</span>
                </li>
            @elseif($onlineNonVideoCount > 0 && count($onlineVideoAnswers) === 0)
                <li class="answer-item" style="border-bottom:none; padding-top:2px;">
                    <span class="text-muted" style="font-size:0.71rem;">
                        <i class="bx bx-globe me-1"></i>إجابات أونلاين: {{ $onlineNonVideoCount }} (بدون فيديو)
                    </span>
                </li>
            @endif
        </ul>
    </td>

    {{-- إجراء سريع --}}
    <td class="text-center">
        <a href="{{ $editUrl }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3" style="font-size:0.75rem; white-space:nowrap;">
            <i class="bx bx-edit me-1"></i> تعديل
        </a>
    </td>
</tr>
@endforeach
