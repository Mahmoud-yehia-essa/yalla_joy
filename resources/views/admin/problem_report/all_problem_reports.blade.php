@extends('admin.master_admin')
@section('admin')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">إدارة البلاغات</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">جميع البلاغات عن المشاكل</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="{{ route('export.problem.reports', request()->query()) }}" class="btn btn-success px-3 d-flex align-items-center gap-1">
            <i class="bx bx-download"></i> تصدير إلى Excel
        </a>
    </div>
</div>
<!--end breadcrumb-->

<hr/>

<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('all.problem.reports') }}" class="row g-3 mb-4 align-items-end">
            <!-- 1. ترتيب حسب التاريخ -->
            <div class="col-lg-2 col-md-4 col-sm-6">
                <label for="sort_by" class="form-label fw-bold" style="font-size: 14px; color: #555;">الترتيب الزمني:</label>
                <select name="sort_by" id="sort_by" class="form-select border-2" onchange="this.form.submit()">
                    <option value="latest" {{ request('sort_by') == 'latest' || !request('sort_by') ? 'selected' : '' }}>الأحدث أولاً</option>
                    <option value="oldest" {{ request('sort_by') == 'oldest' ? 'selected' : '' }}>الأقدم أولاً</option>
                </select>
            </div>

            <!-- 2. مصدر المشكلة -->
            <div class="col-lg-2 col-md-4 col-sm-6">
                <label for="report_type" class="form-label fw-bold" style="font-size: 14px; color: #555;">مصدر المشكلة:</label>
                <select name="report_type" id="report_type" class="form-select border-2" onchange="this.form.submit()">
                    <option value="all" {{ request('report_type') == 'all' || !request('report_type') ? 'selected' : '' }}>الكل</option>
                    <option value="question" {{ request('report_type') == 'question' ? 'selected' : '' }}>السؤال</option>
                    <option value="answer" {{ request('report_type') == 'answer' ? 'selected' : '' }}>الإجابة</option>
                </select>
            </div>

            <!-- 3. نوع المشكلة -->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <label for="issue_type" class="form-label fw-bold" style="font-size: 14px; color: #555;">نوع المشكلة:</label>
                <select name="issue_type" id="issue_type" class="form-select border-2" onchange="this.form.submit()">
                    <option value="all" {{ request('issue_type') == 'all' || !request('issue_type') ? 'selected' : '' }}>كل المشاكل</option>
                    <option value="question_error" {{ request('issue_type') == 'question_error' ? 'selected' : '' }}>خطأ في السؤال</option>
                    <option value="answer_error" {{ request('issue_type') == 'answer_error' ? 'selected' : '' }}>خطأ في الإجابة</option>
                    <option value="inappropriate_content" {{ request('issue_type') == 'inappropriate_content' ? 'selected' : '' }}>محتوى غير لائق</option>
                    <option value="cheating" {{ request('issue_type') == 'cheating' ? 'selected' : '' }}>حالة غش</option>
                </select>
            </div>

            <!-- 4. حالة المشكلة -->
            <div class="col-lg-2 col-md-4 col-sm-6">
                <label for="status" class="form-label fw-bold" style="font-size: 14px; color: #555;">حالة البلاغ:</label>
                <select name="status" id="status" class="form-select border-2" onchange="this.form.submit()">
                    <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>كل الحالات</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>تم الحل</option>
                    <option value="ignored" {{ request('status') == 'ignored' ? 'selected' : '' }}>تم التجاهل</option>
                </select>
            </div>

            <!-- 5. أزرار التحكم -->
            <div class="col-lg-3 col-md-12 col-sm-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4 d-flex align-items-center justify-content-center gap-1 w-100">
                    <i class="bx bx-filter-alt"></i> تصفية
                </button>
                @if(request()->anyFilled(['sort_by', 'report_type', 'issue_type', 'status']))
                    <a href="{{ route('all.problem.reports') }}" class="btn btn-outline-secondary px-3 d-flex align-items-center justify-content-center w-100">
                        إعادة تعيين
                    </a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered align-middle" style="width:100%">
                <thead>
                    <tr>
                        <th>الرقم</th>
                        <th>المُبلِّغ (المستخدم)</th>
                        <th>السؤال المُرتبط</th>
                        <th>مصدر المشكلة</th>
                        <th>نوع المشكلة</th>
                        <th>ملاحظات إضافية</th>
                        <th>تاريخ الإبلاغ</th>
                        <th>الحالة</th>
                        <th>الاجراءات السريعة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $key => $item)
                        @php
                            // Reporter details
                            $reporterUser = $item->user;
                            $reporterName = $reporterUser ? (trim(($reporterUser->fname ?? '') . ' ' . ($reporterUser->lname ?? '')) ?: ($reporterUser->user_name ?? $reporterUser->name ?? $reporterUser->email ?? 'مستخدم غير معروف')) : 'مستخدم غير معروف';
                            
                            $reporterPhoto = url('upload/no_image.jpg');
                            if ($reporterUser && !empty($reporterUser->photo) && $reporterUser->photo !== 'non') {
                                $p = $reporterUser->photo;
                                if (str_starts_with($p, 'http://') || str_starts_with($p, 'https://')) {
                                    $reporterPhoto = $p;
                                } elseif (str_starts_with($p, 'upload/user_images/')) {
                                    if (file_exists(public_path($p))) { $reporterPhoto = url($p); }
                                } else {
                                    if (file_exists(public_path('upload/user_images/' . $p))) { $reporterPhoto = url('upload/user_images/' . $p); }
                                }
                            }

                            // Cheating User details if applicable
                            $cheatingUser = $item->cheatingUser;
                            $cheatingName = $cheatingUser ? (trim(($cheatingUser->fname ?? '') . ' ' . ($cheatingUser->lname ?? '')) ?: ($cheatingUser->user_name ?? $cheatingUser->name ?? $cheatingUser->email ?? 'مستخدم غير معروف')) : ('غير معروف (ID: ' . $item->user_id_cheating . ')');
                            
                            $cheatingPhoto = url('upload/no_image.jpg');
                            if ($cheatingUser && !empty($cheatingUser->photo) && $cheatingUser->photo !== 'non') {
                                $cp = $cheatingUser->photo;
                                if (str_starts_with($cp, 'http://') || str_starts_with($cp, 'https://')) {
                                    $cheatingPhoto = $cp;
                                } elseif (str_starts_with($cp, 'upload/user_images/')) {
                                    if (file_exists(public_path($cp))) { $cheatingPhoto = url($cp); }
                                } else {
                                    if (file_exists(public_path('upload/user_images/' . $cp))) { $cheatingPhoto = url('upload/user_images/' . $cp); }
                                }
                            }
                        @endphp
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>
                                @if($item->issue_type == 'cheating')
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <img src="{{ $reporterPhoto }}" class="rounded-circle border shadow-sm" style="width: 40px; height: 40px; object-fit: cover; border-color: #0aa2dd !important;" alt="avatar">
                                        <div>
                                            <strong>المبلِّغ:</strong> {{ $reporterName }}
                                            @if(isset($reporterUser->email))
                                                <br><small class="text-muted">({{ $reporterUser->email }})</small>
                                            @endif
                                        </div>
                                    </div>
                                    <hr style="margin: 5px 0; border-top: 1px dashed #ccc;">
                                    <div class="d-flex align-items-center gap-2 mt-1">
                                        <img src="{{ $cheatingPhoto }}" class="rounded-circle border border-danger shadow-sm" style="width: 40px; height: 40px; object-fit: cover;" alt="avatar">
                                        <div>
                                            <strong>المبلَّغ عنه:</strong> <span class="text-danger fw-bold">{{ $cheatingName }}</span>
                                            @if(isset($cheatingUser->email))
                                                <br><small class="text-muted">({{ $cheatingUser->email }})</small>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $reporterPhoto }}" class="rounded-circle border shadow-sm" style="width: 45px; height: 45px; object-fit: cover; border-color: #0aa2dd !important;" alt="avatar">
                                        <div>
                                            <strong class="d-block text-dark">{{ $reporterName }}</strong>
                                            @if(isset($reporterUser->email))
                                                <small class="text-muted">{{ $reporterUser->email }}</small>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($item->issue_type == 'cheating')
                                    <span class="text-muted">غير مرتبط بسؤال (حالة غش)</span>
                                @else
                                    @if($item->question)
                                        <a href="{{ route('edit.question', $item->question_id) }}" class="text-primary fw-bold" title="تعديل السؤال" target="_blank">
                                            {{ Str::limit($item->question->qu_title, 60, '...') }}
                                        </a>
                                        <br><small class="text-muted">ID: {{ $item->question_id }}</small>
                                    @else
                                        <span class="text-danger">سؤال غير موجود (ID: {{ $item->question_id }})</span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if($item->report_type == 'answer')
                                    <span class="badge text-white" style="font-size: 13px; padding: 6px 12px; background-color: #6f42c1;">الإجابة</span>
                                @elseif($item->report_type == 'question')
                                    <span class="badge bg-primary text-white" style="font-size: 13px; padding: 6px 12px;">السؤال</span>
                                @else
                                    <span class="badge bg-primary text-white" style="font-size: 13px; padding: 6px 12px;">السؤال</span>
                                @endif
                            </td>
                            <td>
                                @if($item->issue_type == 'question_error')
                                    <span class="badge bg-warning text-dark" style="font-size: 13px; padding: 6px 12px;">خطأ في السؤال</span>
                                @elseif($item->issue_type == 'answer_error')
                                    <span class="badge bg-info text-dark" style="font-size: 13px; padding: 6px 12px;">خطأ في الإجابة</span>
                                @elseif($item->issue_type == 'inappropriate_content')
                                    <span class="badge bg-danger text-white" style="font-size: 13px; padding: 6px 12px;">محتوى غير لائق</span>
                                @elseif($item->issue_type == 'cheating')
                                    <span class="badge bg-danger text-white" style="font-size: 13px; padding: 6px 12px;">حالة غش</span>
                                @else
                                    <span class="badge bg-secondary text-white" style="font-size: 13px; padding: 6px 12px;">{{ $item->issue_type }}</span>
                                @endif
                            </td>
                            <td>
                                @if($item->additional_notes)
                                    <span title="{{ $item->additional_notes }}">{{ Str::limit($item->additional_notes, 50, '...') }}</span>
                                @else
                                    <span class="text-muted">لا توجد ملاحظات</span>
                                @endif
                            </td>
                            <td>
                                {{ $item->created_at 
                                    ? $item->created_at->format('Y-m-d H:i') . ' (' . $item->created_at->diffForHumans() . ')' 
                                    : 'N/A' 
                                }}
                            </td>
                            <td>
                                @if($item->status == 'pending')
                                    <span class="badge bg-light-warning text-warning text-uppercase" style="font-size: 12px; border: 1px solid;">قيد الانتظار</span>
                                @elseif($item->status == 'resolved')
                                    <span class="badge bg-light-success text-success text-uppercase" style="font-size: 12px; border: 1px solid;">تم الحل</span>
                                @elseif($item->status == 'ignored')
                                    <span class="badge bg-light-secondary text-secondary text-uppercase" style="font-size: 12px; border: 1px solid;">تم التجاهل</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#viewModal{{ $item->id }}" title="عرض التفاصيل الكاملة">
                                        <i class="fa-solid fa-eye"></i> التفاصيل
                                    </button>

                                    @if($item->status == 'pending')
                                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#statusModal{{ $item->id }}_resolved" title="حل المشكلة">
                                            <i class="fa-solid fa-check"></i> حل المشكلة
                                        </button>

                                        <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#statusModal{{ $item->id }}_ignored" title="تجاهل البلاغ">
                                            <i class="fa-solid fa-ban"></i> تجاهل
                                        </button>
                                    @else
                                        <!-- Reset to pending button if already resolved/ignored -->
                                        <form action="{{ route('update.problem.report.status', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="pending">
                                            <button type="submit" class="btn btn-sm btn-warning" title="إعادة فتح البلاغ">
                                                <i class="fa-solid fa-undo"></i> إعادة فتح
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('delete.problem.report', $item->id) }}" class="btn btn-sm btn-danger" id="delete" title="حذف البلاغ">
                                        <i class="fa-solid fa-trash"></i> حذف
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <!-- View Problem Report Modal -->
                        <div class="modal fade" id="viewModal{{ $item->id }}" tabindex="-1" aria-labelledby="viewModalLabel{{ $item->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content border-0 shadow-lg">
                                    <div class="modal-header bg-dark text-white py-3">
                                        <h5 class="modal-title fs-5 fw-bold text-white d-flex align-items-center gap-2" id="viewModalLabel{{ $item->id }}">
                                            <i class="fa-solid fa-file-circle-exclamation text-warning"></i> تفاصيل البلاغ #{{ $item->id }}
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4 text-start" style="direction: rtl;">
                                        <!-- 1. Summary Header Cards -->
                                        <div class="row g-3 mb-4">
                                            <div class="col-md-3 col-6">
                                                <div class="p-3 border rounded bg-light text-center h-100">
                                                    <small class="text-muted d-block mb-1">مصدر المشكلة</small>
                                                    @if($item->report_type == 'answer')
                                                        <span class="badge text-white" style="font-size: 13px; padding: 6px 12px; background-color: #6f42c1;">الإجابة</span>
                                                    @else
                                                        <span class="badge bg-primary text-white" style="font-size: 13px; padding: 6px 12px;">السؤال</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6">
                                                <div class="p-3 border rounded bg-light text-center h-100">
                                                    <small class="text-muted d-block mb-1">نوع المشكلة</small>
                                                    @if($item->issue_type == 'question_error')
                                                        <span class="badge bg-warning text-dark" style="font-size: 13px; padding: 6px 12px;">خطأ في السؤال</span>
                                                    @elseif($item->issue_type == 'answer_error')
                                                        <span class="badge bg-info text-dark" style="font-size: 13px; padding: 6px 12px;">خطأ في الإجابة</span>
                                                    @elseif($item->issue_type == 'inappropriate_content')
                                                        <span class="badge bg-danger text-white" style="font-size: 13px; padding: 6px 12px;">محتوى غير لائق</span>
                                                    @elseif($item->issue_type == 'cheating')
                                                        <span class="badge bg-danger text-white" style="font-size: 13px; padding: 6px 12px;">حالة غش</span>
                                                    @else
                                                        <span class="badge bg-secondary text-white" style="font-size: 13px; padding: 6px 12px;">{{ $item->issue_type }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6">
                                                <div class="p-3 border rounded bg-light text-center h-100">
                                                    <small class="text-muted d-block mb-1">حالة البلاغ</small>
                                                    @if($item->status == 'pending')
                                                        <span class="badge bg-light-warning text-warning text-uppercase" style="font-size: 12px; border: 1px solid;">قيد الانتظار</span>
                                                    @elseif($item->status == 'resolved')
                                                        <span class="badge bg-light-success text-success text-uppercase" style="font-size: 12px; border: 1px solid;">تم الحل</span>
                                                    @elseif($item->status == 'ignored')
                                                        <span class="badge bg-light-secondary text-secondary text-uppercase" style="font-size: 12px; border: 1px solid;">تم التجاهل</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6">
                                                <div class="p-3 border rounded bg-light text-center h-100">
                                                    <small class="text-muted d-block mb-1">تاريخ الإبلاغ</small>
                                                    <strong class="d-block text-dark" style="font-size: 12px;">{{ $item->created_at ? $item->created_at->format('Y-m-d H:i') : 'N/A' }}</strong>
                                                    <small class="text-muted" style="font-size: 11px;">({{ $item->created_at ? $item->created_at->diffForHumans() : '' }})</small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 2. Users Section -->
                                        <div class="card mb-3 border shadow-sm">
                                            <div class="card-body">
                                                <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">
                                                    <i class="fa-solid fa-user me-1"></i> معلومات أطراف البلاغ
                                                </h6>
                                                <div class="row g-3">
                                                    <div class="col-md-{{ $item->issue_type == 'cheating' ? '6' : '12' }}">
                                                        <div class="p-3 border rounded bg-light d-flex align-items-center gap-3">
                                                            <img src="{{ $reporterPhoto }}" class="rounded-circle border shadow-sm" style="width: 55px; height: 55px; object-fit: cover; border-color: #0aa2dd !important;" alt="avatar">
                                                            <div>
                                                                <label class="fw-bold text-muted d-block small mb-0">المُبلِّغ عن المشكلة:</label>
                                                                <span class="fw-bold text-dark fs-6">{{ $reporterName }}</span>
                                                                @if(isset($reporterUser->email))
                                                                    <br><small class="text-muted"><i class="fa-solid fa-envelope me-1"></i> {{ $reporterUser->email }}</small>
                                                                @endif
                                                                <br><small class="text-muted"><i class="fa-solid fa-id-card me-1"></i> معرف (ID): {{ $item->user_id }}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if($item->issue_type == 'cheating')
                                                        <div class="col-md-6">
                                                            <div class="p-3 border rounded bg-light border-danger d-flex align-items-center gap-3">
                                                                <img src="{{ $cheatingPhoto }}" class="rounded-circle border border-danger shadow-sm" style="width: 55px; height: 55px; object-fit: cover;" alt="avatar">
                                                                <div>
                                                                    <label class="fw-bold text-danger d-block small mb-0">المستهدف / المُبلَّغ عنه بالغش:</label>
                                                                    <span class="fw-bold text-danger fs-6">{{ $cheatingName }}</span>
                                                                    @if(isset($cheatingUser->email))
                                                                        <br><small class="text-muted"><i class="fa-solid fa-envelope me-1"></i> {{ $cheatingUser->email }}</small>
                                                                    @endif
                                                                    <br><small class="text-muted"><i class="fa-solid fa-id-card me-1"></i> معرف (ID): {{ $item->user_id_cheating }}</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 3. Question Section -->
                                        @if($item->issue_type != 'cheating')
                                            <div class="card mb-3 border shadow-sm">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                                                        <h6 class="fw-bold text-info m-0">
                                                            <i class="fa-solid fa-circle-question me-1"></i> السؤال المرتبط بالبلاغ
                                                        </h6>
                                                        @if($item->question)
                                                            <a href="{{ route('edit.question', $item->question_id) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                <i class="fa-solid fa-pen-to-square me-1"></i> فتح السؤال والتعديل عليه
                                                            </a>
                                                        @endif
                                                    </div>
                                                    @if($item->question)
                                                        <div class="p-3 bg-light border rounded">
                                                            <div class="mb-2">
                                                                <span class="badge bg-secondary">ID السؤال: {{ $item->question_id }}</span>
                                                            </div>
                                                            <h6 class="fw-bold text-dark mb-0" style="line-height: 1.6;">{{ $item->question->qu_title }}</h6>
                                                        </div>
                                                    @else
                                                        <div class="p-3 bg-light border rounded text-danger fw-bold">
                                                            ⚠️ السؤال المرتبط غير موجود حالياً (ID: {{ $item->question_id }}).
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        <!-- 4. Additional Notes Section -->
                                        <div class="card border shadow-sm">
                                            <div class="card-body">
                                                <h6 class="fw-bold text-dark mb-2 border-bottom pb-2">
                                                    <i class="fa-solid fa-comment-dots text-warning me-1"></i> الملاحظات الإضافية المُرسلة مع البلاغ
                                                </h6>
                                                <div class="p-3 bg-light border rounded text-dark" style="white-space: pre-wrap; font-size: 14px;">{{ $item->additional_notes ?: 'لا توجد ملاحظات إضافية تم تقديمها مع هذا البلاغ.' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light justify-content-between">
                                        <div class="d-flex gap-2">
                                            @if($item->status == 'pending')
                                                <button type="button" class="btn btn-success px-3" data-bs-toggle="modal" data-bs-target="#statusModal{{ $item->id }}_resolved" data-bs-dismiss="modal">
                                                    <i class="fa-solid fa-check me-1"></i> حل المشكلة
                                                </button>

                                                <button type="button" class="btn btn-secondary px-3" data-bs-toggle="modal" data-bs-target="#statusModal{{ $item->id }}_ignored" data-bs-dismiss="modal">
                                                    <i class="fa-solid fa-ban me-1"></i> تجاهل
                                                </button>
                                            @else
                                                <form action="{{ route('update.problem.report.status', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="pending">
                                                    <button type="submit" class="btn btn-warning px-3">
                                                        <i class="fa-solid fa-undo me-1"></i> إعادة فتح البلاغ
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn-dark px-4" data-bs-dismiss="modal">إغلاق</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Change Modal (Resolved) -->
                        <div class="modal fade" id="statusModal{{ $item->id }}_resolved" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <form action="{{ route('update.problem.report.status', $item->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="resolved">
                                        <div class="modal-header bg-success text-white py-3">
                                            <h5 class="modal-title fs-5 fw-bold text-white">
                                                <i class="fa-solid fa-check-circle me-1"></i> تغيير حالة البلاغ #{{ $item->id }} إلى (تم الحل)
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4 text-start" style="direction: rtl;">
                                            <p class="text-dark fw-bold mb-3">سيتم تحديث حالة البلاغ إلى <span class="badge bg-success">تم الحل</span>.</p>

                                            <!-- Toggle Notification -->
                                            <div class="card border p-3 bg-light mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="send_notification" value="1" id="sendNotif_resolved_{{ $item->id }}" onchange="document.getElementById('notifBox_resolved_{{ $item->id }}').style.display = this.checked ? 'block' : 'none'">
                                                    <label class="form-check-label fw-bold text-dark me-2" for="sendNotif_resolved_{{ $item->id }}">
                                                        🔔 إرسال إشعار للمستخدم المُبلِّغ (اختياري)
                                                    </label>
                                                </div>
                                            </div>

                                            <!-- Notification Details Box -->
                                            <div id="notifBox_resolved_{{ $item->id }}" style="display: none;" class="p-3 border rounded bg-white shadow-sm">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small text-muted">عنوان الإشعار:</label>
                                                    <input type="text" class="form-control" name="notification_title" value="{{ $item->question ? 'تحديث بشأن البلاغ الخاص بك للسؤال: ' . $item->question->qu_title : 'تحديث بشأن البلاغ الخاص بك (#' . $item->id . ')' }}">
                                                </div>
                                                <div class="mb-0">
                                                    <label class="form-label fw-bold small text-muted">تفاصيل الإشعار ومحتواه:</label>
                                                    <textarea class="form-control" name="notification_details" rows="4">{{ $item->question ? "تم الحل!\nتمت معالجة البلاغ المقدم منك بشأن السؤال: (\"" . $item->question->qu_title . "\") بنجاح.\nنشكرك جزيل الشكر على اهتمامك ومساعدتك القيمة في تحسين جودة المحتوى!" : "تم الحل!\nتمت معالجة البلاغ الخاص بك بنجاح.\nنشكرك جزيل الشكر على اهتمامك ومساعدتك القيمة معنا!" }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="submit" class="btn btn-success px-4">
                                                <i class="fa-solid fa-check"></i> تأكيد الحفظ
                                            </button>
                                            <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">إلغاء</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Status Change Modal (Ignored) -->
                        <div class="modal fade" id="statusModal{{ $item->id }}_ignored" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <form action="{{ route('update.problem.report.status', $item->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="ignored">
                                        <div class="modal-header bg-secondary text-white py-3">
                                            <h5 class="modal-title fs-5 fw-bold text-white">
                                                <i class="fa-solid fa-ban me-1"></i> تغيير حالة البلاغ #{{ $item->id }} إلى (تم التجاهل)
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4 text-start" style="direction: rtl;">
                                            <p class="text-dark fw-bold mb-3">سيتم تحديث حالة البلاغ إلى <span class="badge bg-secondary">تم التجاهل</span>.</p>

                                            <!-- Toggle Notification -->
                                            <div class="card border p-3 bg-light mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="send_notification" value="1" id="sendNotif_ignored_{{ $item->id }}" onchange="document.getElementById('notifBox_ignored_{{ $item->id }}').style.display = this.checked ? 'block' : 'none'">
                                                    <label class="form-check-label fw-bold text-dark me-2" for="sendNotif_ignored_{{ $item->id }}">
                                                        🔔 إرسال إشعار للمستخدم المُبلِّغ (اختياري)
                                                    </label>
                                                </div>
                                            </div>

                                            <!-- Notification Details Box -->
                                            <div id="notifBox_ignored_{{ $item->id }}" style="display: none;" class="p-3 border rounded bg-white shadow-sm">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small text-muted">عنوان الإشعار:</label>
                                                    <input type="text" class="form-control" name="notification_title" value="{{ $item->question ? 'تحديث بشأن البلاغ الخاص بك للسؤال: ' . $item->question->qu_title : 'تحديث بشأن البلاغ الخاص بك (#' . $item->id . ')' }}">
                                                </div>
                                                <div class="mb-0">
                                                    <label class="form-label fw-bold small text-muted">تفاصيل الإشعار ومحتواه:</label>
                                                    <textarea class="form-control" name="notification_details" rows="4">{{ $item->question ? "تحديث بشأن البلاغ:\nتمت مراجعة البلاغ المقدم من قبلك بخصوص السؤال: (\"" . $item->question->qu_title . "\").\nنشكرك على حرصك واهتمامك الدائم بالتواصل معنا!" : "تحديث بشأن البلاغ:\nتمت مراجعة البلاغ المقدم من قبلك بنجاح.\nنشكرك على حرصك واهتمامك الدائم بالتواصل معنا!" }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="submit" class="btn btn-secondary px-4">
                                                <i class="fa-solid fa-check"></i> تأكيد الحفظ
                                            </button>
                                            <button type="button" class="btn btn-dark px-3" data-bs-dismiss="modal">إلغاء</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>الرقم</th>
                        <th>المُبلِّغ (المستخدم)</th>
                        <th>السؤال المُرتبط</th>
                        <th>مصدر المشكلة</th>
                        <th>نوع المشكلة</th>
                        <th>ملاحظات إضافية</th>
                        <th>تاريخ الإبلاغ</th>
                        <th>الحالة</th>
                        <th>الاجراءات السريعة</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@endsection
