@extends('admin.master_admin')
@section('admin')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">إدارة الشروط والأحكام</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">الشروط والأحكام</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <div class="btn-group">
            <a href="{{ route('add.terms.and.conditions') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> إضافة بند جديد
            </a>
        </div>
    </div>
</div>
<!--end breadcrumb-->

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title mb-0 fw-bold"><i class="bx bx-book-open text-primary me-1"></i> بنود الشروط والأحكام لتطبيق فيك تحدي</h5>
            <span class="badge bg-dark fs-6">إجمالي البنود: {{ count($terms) }}</span>
        </div>
        <hr/>
        <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered align-middle" style="width:100%">
                <thead>
                    <tr class="text-center">
                        <th style="width: 50px;">#</th>
                        <th>العنوان (عربي)</th>
                        <th>العنوان (English)</th>
                        <th>المحتوى والبنود (عربي)</th>
                        <th>المحتوى والبنود (English)</th>
                        <th style="min-width: 140px; width: 140px;">الترتيب</th>
                        <th style="width: 90px;">الحالة</th>
                        <th style="width: 130px;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($terms as $key => $item)
                    <tr>
                        <td class="text-center fw-bold">{{ $key+1 }}</td>
                        <td class="fw-bold text-primary">
                            {{ $item->title }}
                        </td>
                        <td class="fw-bold text-secondary" dir="ltr">
                            {{ $item->title_en ?? '-' }}
                        </td>
                        <td style="max-width: 280px;">
                            <div class="text-truncate" style="max-width: 270px; white-space: pre-line;" title="{{ $item->content }}">
                                {{ Str::limit($item->content, 120) }}
                            </div>
                        </td>
                        <td style="max-width: 280px;" dir="ltr">
                            <div class="text-truncate" style="max-width: 270px; white-space: pre-line;" title="{{ $item->content_en }}">
                                {{ $item->content_en ? Str::limit($item->content_en, 120) : '-' }}
                            </div>
                        </td>
                        <td class="text-center" style="min-width: 140px; width: 140px;">
                            <div class="d-flex align-items-center justify-content-center gap-1" style="margin: 0 auto; width: 120px;">
                                <input type="number"
                                       class="form-control form-control-sm text-center fw-bold term-order-input"
                                       id="order-input-{{ $item->id }}"
                                       data-id="{{ $item->id }}"
                                       value="{{ $item->order_by }}"
                                       min="1"
                                       style="width: 65px; height: 36px; font-size: 15px; border-radius: 6px; border: 1.5px solid #0d6efd; background-color: #fff;">
                                <button class="btn btn-sm btn-primary btn-save-term-order d-flex align-items-center justify-content-center"
                                        type="button"
                                        onclick="saveTermOrder({{ $item->id }})"
                                        style="width: 40px; height: 36px; border-radius: 6px; flex-shrink: 0;"
                                        title="حفظ الترتيب">
                                    <i class="fa-solid fa-floppy-disk"></i>
                                </button>
                            </div>
                        </td>
                        <td class="text-center">
                            @if($item->status == 'active')
                                <span class="badge bg-success">مفعل</span>
                            @else
                                <span class="badge bg-danger">غير مفعل</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->status == 'active')
                                <a href="{{ route('inactive.terms.and.conditions', $item->id) }}" class="btn btn-sm btn-primary" title="إلغاء التفعيل">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            @else
                                <a href="{{ route('active.terms.and.conditions', $item->id) }}" class="btn btn-sm btn-primary" title="تفعيل">
                                    <i class="fa-solid fa-eye-slash"></i>
                                </a>
                            @endif
                            <a href="{{ route('edit.terms.and.conditions', $item->id) }}" class="btn btn-sm btn-info" title="تعديل">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a href="{{ route('delete.terms.and.conditions', $item->id) }}" class="btn btn-sm btn-danger" id="delete" title="حذف">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="text-center">
                        <th>#</th>
                        <th>العنوان (عربي)</th>
                        <th>العنوان (English)</th>
                        <th>المحتوى والبنود (عربي)</th>
                        <th>المحتوى والبنود (English)</th>
                        <th style="min-width: 140px; width: 140px;">الترتيب</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function saveTermOrder(id) {
        var orderVal = $('#order-input-' + id).val();
        $.ajax({
            url: "{{ route('terms.and.conditions.update.order') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: id,
                order_by: orderVal
            },
            success: function(response) {
                if (response.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم التحديث!',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'تنبيه!',
                    text: 'حدث خطأ أثناء حفظ الترتيب',
                    confirmButtonText: 'حسناً'
                });
            }
        });
    }

    $(document).on('keydown', '.term-order-input', function(e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            e.preventDefault();
            var id = $(this).data('id');
            saveTermOrder(id);
        }
    });
</script>

@endsection
