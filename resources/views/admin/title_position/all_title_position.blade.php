@extends('admin.master_admin')
@section('admin')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">كل المراكز</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb"></nav>
    </div>
    <div class="ms-auto">
        <div class="btn-group">
            <a href="{{ route('add.title.position') }}">
                <button type="button" class="btn btn-primary">اضافة مركز جديد</button>
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
                        <th>الاسم</th>
                        <th>Name (EN)</th>
                         <th>نوع العنصر</th>

                        <th>السعر بعملة اللعبة</th>
                        <th>النقاط المطلوبة</th>
                        <th>الصورة</th>
                        <th>الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($titlePositions as $key => $item)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->name_en }}</td>
                         <td>
    @if ($item->type == 'game')
        <span class="badge bg-danger">لعبة</span>

    @elseif ($item->type == 'positions')
        <span class="badge bg-primary"> لقب</span>

    @elseif ($item->type == 'clothe')
        <span class="badge bg-success">ملابس</span>

    @elseif ($item->type == 'accessorie')
        <span class="badge bg-warning">إكسسوار</span>
    @endif
</td>

                        <td><span class="badge bg-dark">{{ $item->coins ?? 0 }}</span></td>
                        <td><span class="badge bg-secondary">{{ $item->points ?? 0 }}</span></td>

                        <td>
                            @if($item->photo && file_exists(public_path($item->photo)))
                                <img onclick="showImageModal(this.src)" src="{{ asset($item->photo) }}" style="width: 70px; height:40px; cursor: pointer;">
                            @else
                                <img src="{{ url('upload/no_image.jpg') }}" style="width: 70px; height:40px;">
                            @endif
                        </td>

                        <td>
                            @if($item->status == 'active')
                                <a href="{{ route('inactive.title.position', $item->id) }}" class="btn btn-primary" title="اخفاء">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            @else
                                <a href="{{ route('active.title.position', $item->id) }}" class="btn btn-primary" title="اظهار">
                                    <i class="fa-solid fa-eye-slash"></i>
                                </a>
                            @endif

                            <a href="{{ route('edit.title.position', $item->id) }}" class="btn btn-info">تعديل</a>
                            <a href="{{ route('delete.title.position', $item->id) }}" class="btn btn-danger" id="delete">حذف</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>الرقم</th>
                        <th>الاسم</th>
                        <th>Name (EN)</th>
                        <th>العملات</th>
                        <th>النقاط</th>
                        <th>الصورة</th>
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
                style="position: absolute; top: 15px; right: 15px; background-color: black; font-size: 30px; padding: 1px 10px; border-radius: 8px; z-index: 1055;">
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
