@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">كل الحزم</div>
    <div class="ms-auto">
        <div class="btn-group">
            <a href="{{ route('add.game.bundle') }}">
                <button type="button" class="btn btn-primary">اضافة حزمة جديدة</button>
            </a>
        </div>
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
                        <th>اسم الحزمة</th>
                        <th>الاسم بالإنجليزية</th>
                        <th>نوع الحزمة</th>
                        <th>الصورة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bundles as $key => $bundle)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $bundle->name }}</td>
                            <td>{{ $bundle->name_en }}</td>
                            <td>{{ $bundle->bundle_type }}</td>
                            <td>
                                <img onclick="showImageModal(this.src)" src="{{ asset($bundle->photo) }}" style="width: 70px; height:40px; cursor: pointer;" >
                            </td>
                            <td>
                                <!-- Toggle Active / Inactive -->
                                @if($bundle->status == 'active')
                                    <a href="{{ route('inactive.game.bundle', $bundle->id) }}" class="btn btn-primary" title="اخفاء">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                @else
                                    <a href="{{ route('active.game.bundle', $bundle->id) }}" class="btn btn-primary" title="اظهار">
                                        <i class="fa-solid fa-eye-slash"></i>
                                    </a>
                                @endif

                                <!-- Edit -->
                                <a href="{{ route('edit.game.bundle', $bundle->id) }}" class="btn btn-info" title="تعديل">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <!-- Delete -->
                                <a href="{{ route('delete.game.bundle', $bundle->id) }}" class="btn btn-danger" id="delete" title="حذف">
                                    <i class="fa-solid fa-trash"></i>
                                </a>

                                <!-- View Details -->
                                <button class="btn btn-success btn-details" data-id="{{ $bundle->id }}" title="التفاصيل">
                                    <i class="fa-solid fa-circle-info"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>الرقم</th>
                        <th>اسم الحزمة</th>
                        <th>الاسم بالإنجليزية</th>
                        <th>نوع الحزمة</th>
                        <th>الصورة</th>
                        <th>الإجراءات</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content position-relative bg-transparent border-0">
            <button type="button" class="btn text-white" data-bs-dismiss="modal" aria-label="Close"
                style="position: absolute; top: 15px; right: 15px; background-color: black; font-size: 30px; padding: 1px 10px; border-radius: 8px; z-index: 1055;">
                &times;
            </button>
            <img id="modalImage" src="" class="img-fluid rounded shadow" alt="image">
        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تفاصيل الحزمة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailsContent">
                <!-- سيتم تحميل التفاصيل هنا عبر AJAX -->
                <p class="text-center">جاري تحميل التفاصيل...</p>
            </div>
        </div>
    </div>
</div>

<script>
function showImageModal(src) {
    document.getElementById('modalImage').src = src;
    var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
    myModal.show();
}

// تحميل تفاصيل الحزمة عند الضغط على الزر
$(document).on('click', '.btn-details', function() {
    var id = $(this).data('id');
    $('#detailsContent').html('<p class="text-center">جاري تحميل التفاصيل...</p>');

    $.ajax({
        url: '/game-bundle/details/' + id,
        type: 'GET',
        success: function(res) {
            $('#detailsContent').html(res);
            var myModal = new bootstrap.Modal(document.getElementById('detailsModal'));
            myModal.show();
        },
        error: function() {
            $('#detailsContent').html('<p class="text-danger text-center">حدث خطأ أثناء جلب التفاصيل</p>');
        }
    });
});
</script>

@endsection
