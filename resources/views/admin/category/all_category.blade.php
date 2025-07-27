@extends('admin.master_admin')
@section('admin')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">كل الفئات</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">

        </nav>
    </div>
    <div class="ms-auto">
        <div class="btn-group">
            <a href="{{route('add.category')}}" >

<button type="button" class="btn btn-primary">

    اضافة فئة

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
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
<tr>
<th>الرقم</th>
<th>إسم الفئة</th>
<th>عدد الأسئلة في الفئة</th>

<th> الصورة</th>
<th>الاجراء</th>
</tr>
</thead>
<tbody>
@foreach($category as $key => $item)
<tr>
<td> {{ $key+1 }} </td>
<td>{{ $item->category_name }} </td>
<td style="width: 50px; font-size: 1.1rem;"><span class="badge  bg-dark">
    {{count($item->questions)}}
</span></td>



<td> <img onclick="showImageModal(this.src)" src="{{ asset($item->category_photo) }}" style="width: 70px; height:40px; cursor: pointer;" >  </td>

<td>





    @if($item->status == 'active')
    <a href="{{ route('inactive.category', $item->id) }}" class="btn btn-primary" title="اخفاء">
        <i class="fa-solid fa-eye"></i>
    </a>
@else
    <a href="{{ route('active.category', $item->id) }}" class="btn btn-primary" title="اظهار">

        <i class="fa-solid fa-eye-slash"></i>

    </a>
@endif
<a href="{{route('edit.category',$item->id)}}" class="btn btn-info">تعديل</a>
<a href="{{ route('delete.category',$item->id) }}" class="btn btn-danger" id="delete" >حذف</a>

@if($item->special == 'active')

<img  title="مميز" style="width: 30px; height:30px;" src="{{asset('backend/assets/images/logo-icon.png')}}" >


@endif
</td>
</tr>
@endforeach


</tbody>
<tfoot>
<tr>
    <th>الرقم</th>
    <th>إسم الفئة</th>
    <th> الصورة</th>
    <th>الاجراء</th>
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

        <!-- Rectangular Close Button -->
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
        <img id="modalImage" src="" class="img-fluid rounded shadow"  alt="image">
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
