@extends('admin.master_admin')
@section('admin')

<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">كل أدلة اللعبة</div>
    <div class="ms-auto">
        <div class="btn-group">
            <a href="{{ route('add.game.guide') }}">
                <button type="button" class="btn btn-primary">إضافة دليل جديد</button>
            </a>
        </div>
    </div>
</div>

<hr/>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>العنوان</th>
                        <th>Title (EN)</th>
                        <th>الوصف</th>
                        <th>Description (EN)</th>
                        <th>الصورة</th>
                        <th>الفيديو</th>
                        <th>النوع</th>
                        <th>الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($guides as $key => $item)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->name_en }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($item->description,30) }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($item->description_en,30) }}</td>
                        <td>
                            @if($item->photo)
                            <img onclick="showImageModal(this.src)" src="{{ asset($item->photo) }}" style="width:70px;height:40px;cursor:pointer;">
                            @endif
                        </td>
                        <td>
                            @if($item->video)
                            <video width="100" height="60" controls>
                                <source src="{{ asset($item->video) }}" type="video/mp4">
                            </video>
                            @endif
                        </td>
                        <td>{{ $item->type }}</td>
                        <td>
                            <a href="{{ route('edit.game.guide',$item->id) }}" class="btn btn-info">تعديل</a>
                            <a href="{{ route('delete.game.guide',$item->id) }}" class="btn btn-danger" id="delete">حذف</a>
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
    <div class="modal-content position-relative bg-transparent border-0">
      <button type="button" class="btn text-white" data-bs-dismiss="modal" style="position:absolute;top:15px;right:15px;background-color:black;font-size:30px;padding:1px 10px;border-radius:8px;">&times;</button>
      <img id="modalImage" src="" class="img-fluid rounded shadow" alt="image">
    </div>
  </div>
</div>

<script>
function showImageModal(src){
    document.getElementById('modalImage').src = src;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}
</script>

@endsection
