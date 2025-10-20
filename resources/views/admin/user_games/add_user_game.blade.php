@extends('admin.master_admin')
@section('admin')

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 style="color: white">إضافة لعبة جديدة</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('store.user.game') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">اسم اللعبة</label>
                    <input type="text" name="name" class="form-control" placeholder="أدخل اسم اللعبة" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">الخصوصية</label>
                    <select name="privacy" class="form-select">
                        <option value="privacy">خاص</option>
                        <option value="public">عام</option>
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="form-label">الوصف</label>
                    <textarea name="des" class="form-control" rows="4" placeholder="أدخل وصف اللعبة"></textarea>
                </div>

                <div class="col-md-12">
                    <label class="form-label">صورة اللعبة</label>
                    <input type="file" name="photo" class="form-control" accept="image/*" onchange="previewImage(event)">
                    <div class="mt-3">
                        <img id="imagePreview" src="#" alt="صورة اللعبة" style="display:none; width:150px; height:150px; border-radius:10px; border:1px solid #ccc;">
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success"><i class="fa-solid fa-save"></i> حفظ اللعبة</button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    var input = event.target;
    var reader = new FileReader();
    reader.onload = function(){
        var dataURL = reader.result;
        var output = document.getElementById('imagePreview');
        output.src = dataURL;
        output.style.display = 'block';
    };
    reader.readAsDataURL(input.files[0]);
}
</script>

@endsection
