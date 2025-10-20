@extends('admin.master_admin')
@section('admin')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">كل أنواع عناصر اللعبة</div>
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
                        <th>اسم النوع</th>
                        {{-- <th>اسم النوع بالإنجليزية</th> --}}
                        {{-- <th>الوصف</th>
                        <th>الوصف بالإنجليزية</th> --}}
                        <th>الصورة</th>
                        <th>الحالة</th>
                        <th>الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($itemTypes as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->name }}</td>
                        {{-- <td>{{ $item->name_en }}</td>
                        <td>{{ $item->description }}</td> --}}
                        {{-- <td>{{ $item->description_en }}</td> --}}
                        <td>
                            <img onclick="showImageModal(this.src)"
                                 src="{{ $item->photo ? asset($item->photo) : url('upload/no_image.jpg') }}"
                                 style="width: 70px; height:40px; cursor: pointer;">
                        </td>
                        <td>
                            @if($item->status == 'active')
                                <a href="{{ route('inactive.item.type', $item->id) }}" class="btn btn-primary" title="اخفاء">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            @else
                                <a href="{{ route('active.item.type', $item->id) }}" class="btn btn-primary" title="اظهار">
                                    <i class="fa-solid fa-eye-slash"></i>
                                </a>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('edit.item.type', $item->id) }}" class="btn btn-info">تعديل</a>
                            <a href="{{ route('delete.item.type', $item->id) }}" class="btn btn-danger" id="delete">حذف</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                  <th>الرقم</th>
                        <th>اسم النوع</th>
                        {{-- <th>اسم النوع بالإنجليزية</th> --}}
                        {{-- <th>الوصف</th>
                        <th>الوصف بالإنجليزية</th> --}}
                        <th>الصورة</th>
                        <th>الحالة</th>
                        <th>الإجراء</th>
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

            <!-- Close Button -->
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

            <!-- Image -->
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
