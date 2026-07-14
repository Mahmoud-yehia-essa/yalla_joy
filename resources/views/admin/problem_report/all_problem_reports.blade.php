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
            <div class="col-lg-3 col-md-4 col-sm-12">
                <label for="sort_by" class="form-label fw-bold" style="font-size: 14px; color: #555;">الترتيب الزمني:</label>
                <select name="sort_by" id="sort_by" class="form-select border-2" onchange="this.form.submit()">
                    <option value="latest" {{ request('sort_by') == 'latest' || !request('sort_by') ? 'selected' : '' }}>الأحدث أولاً</option>
                    <option value="oldest" {{ request('sort_by') == 'oldest' ? 'selected' : '' }}>الأقدم أولاً</option>
                </select>
            </div>

            <!-- 2. نوع المشكلة -->
            <div class="col-lg-3 col-md-4 col-sm-12">
                <label for="issue_type" class="form-label fw-bold" style="font-size: 14px; color: #555;">نوع المشكلة:</label>
                <select name="issue_type" id="issue_type" class="form-select border-2" onchange="this.form.submit()">
                    <option value="all" {{ request('issue_type') == 'all' || !request('issue_type') ? 'selected' : '' }}>كل المشاكل</option>
                    <option value="question_error" {{ request('issue_type') == 'question_error' ? 'selected' : '' }}>خطأ في السؤال</option>
                    <option value="answer_error" {{ request('issue_type') == 'answer_error' ? 'selected' : '' }}>خطأ في الإجابة</option>
                    <option value="inappropriate_content" {{ request('issue_type') == 'inappropriate_content' ? 'selected' : '' }}>محتوى غير لائق</option>
                    <option value="cheating" {{ request('issue_type') == 'cheating' ? 'selected' : '' }}>حالة غش</option>
                </select>
            </div>

            <!-- 3. حالة المشكلة -->
            <div class="col-lg-3 col-md-4 col-sm-12">
                <label for="status" class="form-label fw-bold" style="font-size: 14px; color: #555;">حالة البلاغ:</label>
                <select name="status" id="status" class="form-select border-2" onchange="this.form.submit()">
                    <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>كل الحالات</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>تم الحل</option>
                    <option value="ignored" {{ request('status') == 'ignored' ? 'selected' : '' }}>تم التجاهل</option>
                </select>
            </div>

            <!-- 4. أزرار التحكم -->
            <div class="col-lg-3 col-md-12 col-sm-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4 d-flex align-items-center justify-content-center gap-1 w-100">
                    <i class="bx bx-filter-alt"></i> تصفية
                </button>
                @if(request()->anyFilled(['sort_by', 'issue_type', 'status']))
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
                        <th>نوع المشكلة</th>
                        <th>ملاحظات إضافية</th>
                        <th>تاريخ الإبلاغ</th>
                        <th>الحالة</th>
                        <th>الاجراءات السريعة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $key => $item)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>
                                @if($item->issue_type == 'cheating')
                                    <strong>المبلِّغ:</strong> {{ $item->user->name ?? 'مستخدم غير معروف' }}
                                    @if(isset($item->user->email))
                                        <br><small class="text-muted">({{ $item->user->email }})</small>
                                    @endif
                                    <hr style="margin: 5px 0; border-top: 1px dashed #ccc;">
                                    <strong>المبلَّغ عنه:</strong>
                                    @if($item->cheatingUser)
                                        <span class="text-danger">{{ $item->cheatingUser->name }}</span>
                                        @if($item->cheatingUser->email)
                                            <br><small class="text-muted">({{ $item->cheatingUser->email }})</small>
                                        @endif
                                    @else
                                        <span class="text-danger">غير معروف (ID: {{ $item->user_id_cheating }})</span>
                                    @endif
                                @else
                                    <strong>{{ $item->user->name ?? 'مستخدم غير معروف' }}</strong>
                                    @if(isset($item->user->email))
                                        <br><small class="text-muted">{{ $item->user->email }}</small>
                                    @endif
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
                                    @if($item->status == 'pending')
                                        <form action="{{ route('update.problem.report.status', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="resolved">
                                            <button type="submit" class="btn btn-sm btn-success" title="تم الحل">
                                                <i class="fa-solid fa-check"></i> حل المشكلة
                                            </button>
                                        </form>

                                        <form action="{{ route('update.problem.report.status', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="ignored">
                                            <button type="submit" class="btn btn-sm btn-secondary" title="تجاهل">
                                                <i class="fa-solid fa-ban"></i> تجاهل
                                            </button>
                                        </form>
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
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>الرقم</th>
                        <th>المُبلِّغ (المستخدم)</th>
                        <th>السؤال المُرتبط</th>
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
