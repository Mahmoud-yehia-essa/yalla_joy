@extends('admin.master_admin')
@section('admin')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">وصف وتعريف الألعاب</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">تعليمات وبطاقات وصف الألعاب</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <div class="btn-group">
            <a href="{{ route('add.game.instruction') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> إضافة قسم / وصف جديد
            </a>
        </div>
    </div>
</div>
<!--end breadcrumb-->

<!-- Filter Buttons -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-3">
                <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('all.game.instructions') }}"
                           class="btn {{ empty($target) ? 'btn-dark' : 'btn-outline-dark' }}">
                            <i class="bx bx-list-ul"></i> الكل ({{ count($instructions) }})
                        </a>
                        <a href="{{ route('all.game.instructions', ['target' => 'session']) }}"
                           class="btn {{ $target == 'session' ? 'btn-info text-dark fw-bold' : 'btn-outline-info text-dark' }}">
                            <i class="bx bx-joystick"></i> لعبة الجلسة (وضع اللعب المحلي)
                        </a>
                        <a href="{{ route('all.game.instructions', ['target' => 'field']) }}"
                           class="btn {{ $target == 'field' ? 'btn-warning text-dark fw-bold' : 'btn-outline-warning text-dark' }}">
                            <i class="bx bx-globe"></i> لعبة الميدان (وضع اللعب أونلاين)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered align-middle" style="width:100%">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>اللعبة</th>
                        <th>نوع البطاقة</th>
                        <th>عنوان القسم</th>
                        <th>الأيقونة</th>
                        <th>المحتوى / البنود</th>
                        <th>ترتيب الظهور</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($instructions as $key => $item)
                    <tr>
                        <td class="text-center">{{ $key+1 }}</td>
                        <td class="text-center">
                            @if($item->game_target == 'session')
                                <span class="badge bg-info text-dark fs-6"><i class="bx bx-joystick me-1"></i>لعبة الجلسة</span>
                            @else
                                <span class="badge bg-warning text-dark fs-6"><i class="bx bx-globe me-1"></i>لعبة الميدان</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->section_type == 'intro')
                                <span class="badge bg-primary">مقدمة رئيسية</span>
                            @else
                                <span class="badge bg-secondary">قسم تفصيلي</span>
                            @endif
                        </td>
                        <td class="fw-bold">
                            {{ $item->title }}
                            @if($item->title_en)
                                <div class="text-muted small">{{ $item->title_en }}</div>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-dark py-1 px-2 font-monospace">{{ $item->icon ?? '-' }}</span>
                        </td>
                        <td style="max-width: 320px;">
                            @if($item->intro)
                                <div class="text-warning small mb-1"><strong>مقدمة:</strong> {{ Str::limit($item->intro, 60) }}</div>
                            @endif
                            <div class="text-truncate" style="max-width: 300px;" title="{{ $item->content }}">
                                {{ Str::limit($item->content, 90) }}
                            </div>
                        </td>
                        <td class="text-center" style="min-width: 110px;">
                            <div class="input-group input-group-sm">
                                <input type="number" class="form-control text-center fw-bold instruction-order-input"
                                       id="order-input-{{ $item->id }}"
                                       data-id="{{ $item->id }}"
                                       value="{{ $item->order_by }}"
                                       min="1">
                                <button class="btn btn-primary btn-save-instruction-order"
                                        type="button"
                                        onclick="saveInstructionOrder({{ $item->id }})"
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
                                <a href="{{ route('inactive.game.instruction', $item->id) }}" class="btn btn-sm btn-primary" title="إلغاء التفعيل">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            @else
                                <a href="{{ route('active.game.instruction', $item->id) }}" class="btn btn-sm btn-primary" title="تفعيل">
                                    <i class="fa-solid fa-eye-slash"></i>
                                </a>
                            @endif
                            <a href="{{ route('edit.game.instruction', $item->id) }}" class="btn btn-sm btn-info" title="تعديل">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a href="{{ route('delete.game.instruction', $item->id) }}" class="btn btn-sm btn-danger" id="delete" title="حذف">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="text-center">
                        <th>#</th>
                        <th>اللعبة</th>
                        <th>نوع البطاقة</th>
                        <th>عنوان القسم</th>
                        <th>الأيقونة</th>
                        <th>المحتوى / البنود</th>
                        <th>ترتيب الظهور</th>
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
    function saveInstructionOrder(id) {
        var orderVal = $('#order-input-' + id).val();
        $.ajax({
            url: "{{ route('game.instruction.update.order') }}",
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
</script>

@endsection
