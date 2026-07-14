@extends('admin.master_admin')
@section('admin')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">كل الاشعارات المرسلة</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">

        </nav>
    </div>

</div>
<!--end breadcrumb-->

<hr/>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>الرقم</th>
                        <th>المستلم</th>
                        <th>العنوان</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                        <th>الاجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notifications as $key => $item)
                        <tr>
                            <td> {{ $key + 1 }} </td>
                            <td>
                                <strong>{{ trim(($item->fname ?? '') . ' ' . ($item->lname ?? '')) ?: ($item->user_name ?? $item->email) }}</strong>
                                <br>
                                <small class="text-muted">{{ $item->email }}</small>
                            </td>
                            <td>{{ $item->title ?? 'بدون عنوان' }}</td>
                            <td>
                                @if($item->user_view == 'no')
                                    <span class="badge bg-warning text-dark">لم يتم المشاهدة</span>
                                @elseif($item->user_view == 'yes')
                                    <span class="badge bg-success">تمت المشاهدة</span>
                                @elseif($item->user_view == 'delete' || $item->user_view == 'deleted')
                                    <span class="badge bg-danger">تم الحذف</span>
                                @else
                                    <span class="badge bg-secondary">{{ $item->user_view }}</span>
                                @endif
                            </td>
                            <td>{{ $item->date }}</td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm text-white view-detail-btn" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#notificationDetailModal"
                                        data-title="{{ $item->title }}"
                                        data-des="{{ $item->des }}"
                                        data-user="{{ trim(($item->fname ?? '') . ' ' . ($item->lname ?? '')) ?: ($item->user_name ?? $item->email) }} ({{ $item->email }})"
                                        data-date="{{ $item->date }}"
                                        data-status="{{ $item->user_view }}">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <a href="{{ route('delete.notification', $item->id) }}" class="btn btn-danger btn-sm" id="delete">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>الرقم</th>
                        <th>المستلم</th>
                        <th>العنوان</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                        <th>الاجراء</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Modal تفاصيل الاشعار -->
<div class="modal fade" id="notificationDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تفاصيل الإشعار</h5>
                <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close" style="margin-left: auto; margin-right: 0;"></button>
            </div>
            <div class="modal-body" style="text-align: right; direction: rtl;">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>المستلم:</strong>
                        <span id="modal-recipient" class="ms-2 text-primary"></span>
                    </div>
                    <div class="col-md-6">
                        <strong>تاريخ الإرسال:</strong>
                        <span id="modal-date" class="ms-2"></span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <strong>الحالة:</strong>
                        <span id="modal-status" class="ms-2"></span>
                    </div>
                </div>
                <hr>
                <div class="mb-3">
                    <strong>العنوان:</strong>
                    <h5 id="modal-notification-title" class="mt-2 p-2 bg-light rounded border-start border-primary border-4" style="font-weight: 600;"></h5>
                </div>
                <div class="mb-3">
                    <strong>التفاصيل / الوصف:</strong>
                    <div id="modal-desc" class="mt-2 p-3 bg-light rounded border" style="max-height: 300px; overflow-y: auto; font-size: 15px; line-height: 1.6;"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {
        $('.view-detail-btn').on('click', function() {
            var title = $(this).data('title');
            var des = $(this).data('des');
            var user = $(this).data('user');
            var date = $(this).data('date');
            var status = $(this).data('status');
            
            $('#modal-notification-title').text(title || 'بدون عنوان');
            $('#modal-desc').html(des || 'بدون وصف أو تفاصيل'); // استخدام html() لعرض التنسيق
            $('#modal-recipient').text(user || '');
            $('#modal-date').text(date || '');
            
            var statusHtml = '';
            if (status === 'no') {
                statusHtml = '<span class="badge bg-warning text-dark">لم يتم المشاهدة</span>';
            } else if (status === 'yes') {
                statusHtml = '<span class="badge bg-success">تمت المشاهدة</span>';
            } else if (status === 'delete' || status === 'deleted') {
                statusHtml = '<span class="badge bg-danger">تم الحذف</span>';
            } else {
                statusHtml = '<span class="badge bg-secondary">' + (status || 'غير معروف') + '</span>';
            }
            $('#modal-status').html(statusHtml);
        });
    });
</script>

@endsection
