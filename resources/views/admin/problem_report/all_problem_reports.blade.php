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
        <a href="{{ route('export.problem.reports') }}" class="btn btn-success px-3 d-flex align-items-center gap-1">
            <i class="bx bx-download"></i> تصدير إلى Excel
        </a>
    </div>
</div>
<!--end breadcrumb-->

<hr/>

<div class="card">
    <div class="card-body">
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
                                <strong>{{ $item->user->name ?? 'مستخدم غير معروف' }}</strong>
                                @if(isset($item->user->email))
                                    <br><small class="text-muted">{{ $item->user->email }}</small>
                                @endif
                            </td>
                            <td>
                                @if($item->question)
                                    <a href="{{ route('edit.question', $item->question_id) }}" class="text-primary fw-bold" title="تعديل السؤال" target="_blank">
                                        {{ Str::limit($item->question->qu_title, 60, '...') }}
                                    </a>
                                    <br><small class="text-muted">ID: {{ $item->question_id }}</small>
                                @else
                                    <span class="text-danger">سؤال غير موجود (ID: {{ $item->question_id }})</span>
                                @endif
                            </td>
                            <td>
                                @if($item->issue_type == 'question_error')
                                    <span class="badge bg-warning text-dark" style="font-size: 13px; padding: 6px 12px;">خطأ في السؤال</span>
                                @elseif($item->issue_type == 'answer_error')
                                    <span class="badge bg-info text-dark" style="font-size: 13px; padding: 6px 12px;">خطأ في الإجابة</span>
                                @elseif($item->issue_type == 'inappropriate_content')
                                    <span class="badge bg-danger text-white" style="font-size: 13px; padding: 6px 12px;">محتوى غير لائق</span>
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
