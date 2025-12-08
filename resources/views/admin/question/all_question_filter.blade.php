@extends('admin.master_admin')
@section('admin')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">نتائج البحث</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">

        </nav>
    </div>
    <div class="ms-auto">
        <div class="btn-group">
            <a href="{{route('filter.question')}}" >

<button type="button" class="btn btn-primary">

     بحث جديد

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
<th>السؤال</th>
<th>نوع السؤال</th>

<th>نوع اللعبة</th>

        <th>الفئة الرئيسية</th>

<th>الفئة الفرعية</th>

<th> الاجابة</th>
<th> تاريخ الاضافة</th>

<th>الاجراء</th>
</tr>
</thead>
<tbody>
@foreach($questions as $key => $item)
<tr>
<td> {{ $key+1 }} </td>
<td class="question-column text-wrap">{{ $item->qu_title}}</td>
<td>
    @if ($item->questions_type == "text")

    {{'نصي'}}
    @elseif($item->questions_type == "image")
    {{'صورة'}}
    @elseif($item->questions_type == "video")
    {{'فيديو'}}
    @elseif($item->questions_type == "sound")
    {{'صوت'}}

    @endif
</td>


<td>{{ $item->gameType->type_name}}</td>

<td>{{ $item->mainCategory->main_category_name}}</td>

<td>{{ $item->category->category_name}}</td>

<td class="text-wrap"> {{
// we can make loop to get more than answer but now we need one answer only
$item->answers->first()->answer_title ?? 'لم يتم تحديد الاجابة'

    }}  </td>

<td >{{ $item->created_at ? $item->created_at->diffForHumans() : 'لم يتم التحديد' }}</td>

<td>
<a href="{{route('edit.question',$item->id)}}" class="btn btn-info">تعديل</a>
<a href="{{ route('delete.question',$item->id) }}" class="btn btn-danger" id="delete" >حذف</a>

</td>
</tr>
@endforeach


</tbody>
<tfoot>
<tr>
   <th>الرقم</th>
<th>السؤال</th>
<th>نوع السؤال</th>

<th>نوع اللعبة</th>

        <th>الفئة الرئيسية</th>

<th>الفئة الفرعية</th>

<th> الاجابة</th>
<th> تاريخ الاضافة</th>

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
