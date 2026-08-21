@extends('admin.master_admin')
@section('admin')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">التحكم في وسائل المساعدة</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">جميع وسائل المساعدة</li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->

<hr/>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered align-middle" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th style="width: 80px;">الأيقونة</th>
                        <th>اسم الوسيلة</th>
                        <th>الوصف (يظهر عند الضغط على i)</th>
                        <th>التوقيت</th>
                        <th>الحالة</th>
                        <th style="width: 120px;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gameHelpers as $key => $helper)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td class="text-center">
                                @if($helper->photo)
                                    <img onclick="showImageModal(this.src)" src="{{ asset($helper->photo) }}" class="rounded-circle shadow-sm" style="width: 50px; height: 50px; object-fit: contain; background: #f0f4f8; padding: 4px; cursor: pointer;" title="اضغط للتكبير">
                                @else
                                    <span class="badge bg-secondary">بدون صورة</span>
                                @endif
                            </td>
                            <td>
                                <strong class="text-primary fs-6">{{ $helper->name }}</strong>
                                @if($helper->name_en)
                                    <br><small class="text-muted">{{ $helper->name_en }}</small>
                                @endif
                            </td>
                            <td>
                                <div style="max-width: 250px; white-space: normal; font-size: 13px;">
                                    {{ $helper->description ?? '-' }}
                                </div>
                            </td>
                            <td>
                                @if($helper->use_before_question)
                                    <span class="badge bg-info text-dark">قبل اختيار السؤال</span>
                                @else
                                    <span class="badge bg-light text-dark border">أثناء السؤال</span>
                                @endif
                            </td>
                            <td>
                                @if($helper->status == 'active')
                                    <span class="badge bg-success">ظاهر (نشط)</span>
                                @else
                                    <span class="badge bg-danger">مخفي</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-1">
                                    @if($helper->status == 'active')
                                        <a href="{{ route('inactive.game.helper', $helper->id) }}" class="btn btn-sm btn-outline-secondary" title="إخفاء وسيلة المساعدة">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('active.game.helper', $helper->id) }}" class="btn btn-sm btn-outline-success" title="إظهار وسيلة المساعدة">
                                            <i class="fa-solid fa-eye-slash"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('edit.game.helper', $helper->id) }}" class="btn btn-sm btn-info text-white" title="تعديل">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content position-relative bg-dark border-0 p-3" style="border-radius: 16px;">
            <button type="button" class="btn text-white" data-bs-dismiss="modal" aria-label="Close"
                style="position: absolute; top: 10px; right: 10px; background-color: rgba(0,0,0,0.6); font-size: 24px; padding: 2px 12px; border-radius: 8px; z-index: 1055;">
                &times;
            </button>
            <div class="text-center">
                <img id="modalImage" src="" class="img-fluid rounded" style="max-height: 400px; object-fit: contain;" alt="image">
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
</script>

@endsection
