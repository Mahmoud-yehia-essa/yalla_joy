@extends('admin.master_admin')
@section('admin')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">كل المستخدمين</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">

        </nav>
    </div>
    <div class="ms-auto">
        <div class="btn-group">
            <a href="{{route('add.user')}}" >

<button type="button" class="btn btn-primary">

    اضافة مستخدم

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
<th>إسم الأول</th>
<th>إسم العائلة</th>
<th>البريد الإلكتروني</th>
<th>تاريخ التسجيل</th>
<th>طريقة التسجيل</th>

<th>النقاط</th>

<th> الصورة</th>
<th>الاجراء</th>
</tr>
</thead>
<tbody>
@foreach($users as $key => $item)
<tr>
<td> {{ $key+1 }} </td>
<td>{{ $item->fname }}</td>
<td>{{ $item->lname }}</td>
<td>{{ $item->email }}</td>
<td>{{ $item->created_at ? $item->created_at->diffForHumans() : 'لم يتم التحديد' }}</td>

<td class="text-center">
    @if($item->register_type == 'normal')
        <i class="fa-solid fa-envelope fa-2x" title="Email"></i>
    @elseif($item->register_type == 'google')
        <i class="fa-brands fa-google fa-2x" style="color:#DB4437;" title="Google"></i>
    @elseif($item->register_type == 'facebook')
        <i class="fa-brands fa-facebook fa-2x" style="color:#1877F2;" title="Facebook"></i>
    @elseif($item->register_type == 'apple')
        <i class="fa-brands fa-apple fa-2x" style="color:#000;" title="Apple"></i>
    @else
        <i class="fa-solid fa-question fa-2x" title="Unknown"></i>
    @endif
</td>

<td>{{ $item->points }}</td>

<td>
    <img
        onclick="showImageModal(this.src)"
        class="rounded-circle"
        src="{{ (!empty($item->photo) && $item->photo != 'non') ? url('upload/user_images/'.$item->photo) : url('upload/no_image.jpg') }}"
        style="width: 50px; height:50px; border: 2px solid #0aa2dd; cursor: pointer;"
    >
</td>
<td>

@if($item->status == 'active')
<a href="{{ route('inactive.user',$item->id) }}" class="btn btn-primary" title="ايقاف التفعيل"> <i class="fa-solid fa-thumbs-down"></i> </a>
@else
<a href="{{ route('active.user',$item->id) }}" class="btn btn-primary" title="تفعيل"> <i class="fa-solid fa-thumbs-up"></i> </a>
@endif
<a href="{{ route('edit.user',$item->id) }}" class="btn btn-info" title="Edit Data"> <i class="fa fa-pencil"></i> </a>

<a href="{{ route('delete.user',$item->id) }}" class="btn btn-danger" id="delete" title="Delete Data" ><i class="fa fa-trash"></i></a>

</td>
</tr>
@endforeach


</tbody>
<tfoot>
<tr>
    <th>الرقم</th>
    <th>إسم الأول</th>
    <th>إسم العائلة</th>
    <th>البريد الإلكتروني</th>
    <th>تاريخ التسجيل</th>
    <th>طريقة التسجيل</th>

    <th> النقاط</th>

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
