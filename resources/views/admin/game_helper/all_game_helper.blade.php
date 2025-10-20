@extends('admin.master_admin')
@section('admin')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">كل مساعدات اللعبة</div>
    <div class="ms-auto">
        <div class="btn-group">
            <a href="{{ route('add.game.helper') }}">
                <button type="button" class="btn btn-primary">اضافة مساعدة جديدة</button>
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
                        <th>المساعدة</th>
                        <th>الاسم بالانجليزية</th>
                        <th>المستوى</th>
                        <th>الصورة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gameHelpers as $key => $helper)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $helper->name }}</td>
                            <td>{{ $helper->name_en }}</td>

                             <td>{{ $helper->levels == null ? 'الكل' : $helper->levels->name}}</td>

                            <td>
                                <img onclick="showImageModal(this.src)" src="{{ asset($helper->photo) }}" style="width: 70px; height:40px; cursor: pointer;">
                            </td>
                            <td>
                                @if($helper->status == 'active')
                                    <a href="{{ route('inactive.game.helper', $helper->id) }}" class="btn btn-primary" title="اخفاء">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                @else
                                    <a href="{{ route('active.game.helper', $helper->id) }}" class="btn btn-primary" title="اظهار">
                                        <i class="fa-solid fa-eye-slash"></i>
                                    </a>
                                @endif
                                <a href="{{ route('edit.game.helper', $helper->id) }}" class="btn btn-info">تعديل</a>
                                <a href="{{ route('delete.game.helper', $helper->id) }}" class="btn btn-danger" id="delete">حذف</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>الرقم</th>
                        <th>المساعدة</th>
                        <th>الاسم بالانجليزية</th>
                        <th>المستوى</th>

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

<script>
function showImageModal(src) {
    document.getElementById('modalImage').src = src;
    var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
    myModal.show();
}
</script>

@endsection
