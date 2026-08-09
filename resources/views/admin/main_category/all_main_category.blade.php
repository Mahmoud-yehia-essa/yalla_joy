@extends('admin.master_admin')
@section('admin')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">كل الفئات الرئيسية</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">

        </nav>
    </div>
    <div class="ms-auto">
        <div class="btn-group">
            <a href="{{route('add.main.category')}}" >

<button type="button" class="btn btn-primary">

    اضافة فئة رئيسية جديد

</button>
</a>


        </div>
    </div>
</div>
<!--end breadcrumb-->

<hr/>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered align-middle" style="width:100%">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>main_category_id</th>
                        <th>نوع اللعبة</th>
                        <th>الفئة الرئيسية</th>
                        <th>تخصيص اللعبة</th>
                        <th>ترتيب الظهور</th>
                        <th>عدد الفئات الفرعية</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mainCategory as $key => $item)
                    <tr>
                        <td class="text-center">{{ $key+1 }}</td>
                        <td class="text-center">{{ $item->id }}</td>
                        <td>{{ $item->gameType ? $item->gameType->type_name : '-' }}</td>
                        <td class="fw-bold">{{ $item->main_category_name }}</td>
                        <td class="text-center">
                            @if($item->display_target == 'session')
                                <span class="badge bg-info text-dark">لعبة الجلسة</span>
                            @elseif($item->display_target == 'field')
                                <span class="badge bg-warning text-dark">لعبة الميدان</span>
                            @else
                                <span class="badge bg-secondary">الاثنين معاً</span>
                            @endif
                        </td>
                        <td class="text-center" style="min-width: 130px;">
                            <div class="input-group input-group-sm">
                                <input type="number" class="form-control text-center fw-bold main-category-order-input"
                                       id="order-input-{{ $item->id }}"
                                       data-id="{{ $item->id }}"
                                       value="{{ $item->order_by }}"
                                       placeholder="-"
                                       min="1">
                                <button class="btn btn-primary btn-save-main-category-order"
                                        type="button"
                                        onclick="saveMainCategoryOrderBtn(event, {{ $item->id }})"
                                        data-id="{{ $item->id }}"
                                        title="حفظ الترتيب">
                                    <i class="fa-solid fa-floppy-disk"></i>
                                </button>
                            </div>
                        </td>
                        <td class="text-center" style="width: 50px; font-size: 1.1rem;">
                            <span class="badge bg-dark">{{ count($item->categories) }}</span>
                        </td>
                        <td class="text-center">
                            @if($item->status == 'active')
                                <a href="{{ route('inactive.main.category', $item->id) }}" class="btn btn-sm btn-primary" title="اخفاء">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            @else
                                <a href="{{ route('active.main.category', $item->id) }}" class="btn btn-sm btn-primary" title="اظهار">
                                    <i class="fa-solid fa-eye-slash"></i>
                                </a>
                            @endif
                            <a href="{{route('edit.main.category',$item->id)}}" class="btn btn-sm btn-info">تعديل</a>
                            <a href="{{ route('delete.main.category',$item->id) }}" class="btn btn-sm btn-danger" id="delete">حذف</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="text-center">
                        <th>#</th>
                        <th>main_category_id</th>
                        <th>نوع اللعبة</th>
                        <th>الفئة الرئيسية</th>
                        <th>تخصيص اللعبة</th>
                        <th>ترتيب الظهور</th>
                        <th>عدد الفئات الفرعية</th>
                        <th>الإجراءات</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content position-relative bg-transparent border-0">
        <button type="button"
                class="btn text-white"
                data-bs-dismiss="modal"
                aria-label="Close"
                style="
                  position: absolute;
                  top: 15px;
                  right: 15px;
                  background-color: black;
                  font-size: 30px;
                  padding: 1px 10px;
                  border-radius: 8px;
                  z-index: 1055;
                ">
            &times;
        </button>
        <img id="modalImage" src="" class="img-fluid rounded shadow" alt="image">
      </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showImageModal(src) {
        document.getElementById('modalImage').src = src;
        var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
        myModal.show();
    }

    function saveMainCategoryOrderAjax(categoryId, orderByVal, confirmSwap) {
        $.ajax({
            url: "{{ route('main.category.update.order') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: categoryId,
                order_by: orderByVal,
                confirm_swap: confirmSwap ? 1 : 0
            },
            success: function(response) {
                if (response.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم التحديث!',
                        text: response.message,
                        timer: 1800,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else if (response.is_duplicate) {
                    Swal.fire({
                        title: 'تنبيه وجود فئة بنفس الترتيب!',
                        text: response.message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'نعم، استبدل المراكز',
                        cancelButtonText: 'إلغاء'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            saveMainCategoryOrderAjax(categoryId, orderByVal, true);
                        }
                    });
                }
            },
            error: function(xhr) {
                var errorMsg = 'حدث خطأ أثناء حفظ الترتيب';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'تنبيه!',
                    text: errorMsg,
                    confirmButtonText: 'حسناً'
                });
            }
        });
    }

    function saveMainCategoryOrderBtn(e, categoryId) {
        if (e) e.preventDefault();
        var orderByVal = $('#order-input-' + categoryId).val();
        saveMainCategoryOrderAjax(categoryId, orderByVal, false);
    }

    $(document).on('click', '.btn-save-main-category-order', function(e) {
        e.preventDefault();
        var categoryId = $(this).data('id');
        if (categoryId) {
            var orderByVal = $('#order-input-' + categoryId).val();
            saveMainCategoryOrderAjax(categoryId, orderByVal, false);
        }
    });
</script>



@endsection
