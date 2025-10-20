@extends('admin.master_admin')
@section('admin')

<style>



</style>

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">كل الأدوار</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">

        </nav>
    </div>
    <div class="ms-auto">
        <div class="btn-group">
            <a href="{{route('add.roles')}}" >

<button type="button" class="btn btn-primary">

    اضافة دور جديد

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
<th>اسم الدور</th>
<th>الصلاحيات  </th>

<th>التاريخ</th>
<th>الاجراء</th>
</tr>
</thead>
<tbody>
@foreach($roles as $key => $item)
<tr>
<td> {{ $key+1 }} </td>
<td>{{ $item->name }} </td>

<td style="white-space: normal; word-wrap: break-word; max-width: 450px; font-size: 17px;">
    @foreach($item->permissions as $perm)
        <span class="badge rounded-pill bg-danger mb-1 me-1"> {{ $perm->name }}</span>
    @endforeach
</td>



<td>
    {{ $item->created_at
    ? $item->created_at->format('Y-m-d') . ' (' . $item->created_at->diffForHumans(['parts' => 1]) . ' تقريبًا)'
    : 'لم يتم التحديد'
}}

</td>

<td>






<a href="{{route('edit.roles',$item->id)}}" class="btn btn-info">تعديل الإسم</a>

<a href="{{route('role.permission.edit',$item->id)}}" class="btn btn-info">تعديل الصلاحيات</a>

<a href="{{ route('delete.roles',$item->id) }}" class="btn btn-danger" id="delete" >حذف</a>



</td>
</tr>
@endforeach


</tbody>
<tfoot>
<tr>
<th>الرقم</th>
<th>اسم الدور</th>
<th>التاريخ</th>
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
