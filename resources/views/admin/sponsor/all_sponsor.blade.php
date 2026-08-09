@extends('admin.master_admin')
@section('admin')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">كل الرعاة</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">

        </nav>
    </div>
    <div class="ms-auto">
        <div class="btn-group">
            <a href="{{route('add.sponsor.new')}}" >

<button type="button" class="btn btn-primary">

    اضافة راعي جديد

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
                        <th>اسم الراعي</th>
                        <th>ترتيب الظهور</th>
                        <th>تاريخ الاضافة</th>
                        <th>الصورة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sponsors as $key => $item)
                    <tr>
                        <td class="text-center">{{ $key+1 }}</td>
                        <td>{{ $item->title }}</td>
                        <td class="text-center" style="min-width: 130px;">
                            <div class="input-group input-group-sm">
                                <input type="number" class="form-control text-center fw-bold sponsor-order-input"
                                       id="order-input-{{ $item->id }}"
                                       data-id="{{ $item->id }}"
                                       value="{{ $item->order_by }}"
                                       placeholder="-"
                                       min="1">
                                <button class="btn btn-primary btn-save-order"
                                        type="button"
                                        onclick="saveSponsorOrderBtn(event, {{ $item->id }})"
                                        data-id="{{ $item->id }}"
                                        title="حفظ الترتيب">
                                    <i class="fa-solid fa-floppy-disk"></i>
                                </button>
                            </div>
                        </td>
                        <td class="text-center">{{ $item->created_at ? $item->created_at->diffForHumans() : 'لم يتم التحديد' }}</td>
                        <td class="text-center">
                            <img onclick="showImageModal(this.src)" src="{{ ($item->photo && file_exists(public_path($item->photo))) ? asset($item->photo) : url('upload/no_image.jpg') }}" style="width: 70px; height:40px; cursor: pointer; object-fit: cover;" class="rounded">
                        </td>
                        <td class="text-center">
                            <a href="{{route('edit.sponsor',$item->id)}}" class="btn btn-sm btn-info">تعديل</a>
                            <a href="{{ route('delete.sponsor',$item->id) }}" class="btn btn-sm btn-danger" id="delete">حذف</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="text-center">
                        <th>#</th>
                        <th>اسم الراعي</th>
                        <th>ترتيب الظهور</th>
                        <th>تاريخ الاضافة</th>
                        <th>الصورة</th>
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

    function saveSponsorOrderAjax(sponsorId, orderByVal, confirmSwap) {
        $.ajax({
            url: "{{ route('sponsor.update.order') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: sponsorId,
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
                        title: 'تنبيه وجود راعي بنفس الترتيب!',
                        text: response.message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'نعم، استبدل المراكز',
                        cancelButtonText: 'إلغاء'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            saveSponsorOrderAjax(sponsorId, orderByVal, true);
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

    function saveSponsorOrderBtn(e, sponsorId) {
        if (e) e.preventDefault();
        var orderByVal = $('#order-input-' + sponsorId).val();
        saveSponsorOrderAjax(sponsorId, orderByVal, false);
    }

    $(document).on('click', '.btn-save-order', function(e) {
        e.preventDefault();
        var sponsorId = $(this).data('id');
        if (sponsorId) {
            var orderByVal = $('#order-input-' + sponsorId).val();
            saveSponsorOrderAjax(sponsorId, orderByVal, false);
        }
    });
</script>



@endsection
