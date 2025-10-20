@extends('admin.master_admin')
@section('admin')

<div class="card">
    <div class="card-header">
        <h5>تعديل اللعبة</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('update.user.game') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $game->id }}">
            <input type="hidden" name="old_photo" value="{{ $game->photo }}">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>اسم اللعبة</label>
                    <input type="text" name="name" class="form-control" value="{{ $game->name }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label>الخصوصية</label>
                    <select name="privacy" class="form-select">
                        <option value="privacy" {{ $game->privacy == 'privacy' ? 'selected' : '' }}>خاص</option>
                        <option value="public" {{ $game->privacy == 'public' ? 'selected' : '' }}>عام</option>
                    </select>
                </div>

                <div class="col-md-12 mb-3">
                    <label>الوصف</label>
                    <textarea name="des" class="form-control" rows="4">{{ $game->des }}</textarea>
                </div>

                <div class="col-md-12 mb-3">
                    <label>صورة اللعبة</label>
                    @if($game->photo)
                        <div class="mb-2">
                            <img id="oldImage" src="{{ asset($game->photo) }}" style="width: 150px; border:1px solid #ddd; padding:2px;">
                        </div>
                    @endif
                    <input type="file" name="photo" class="form-control" accept="image/*" onchange="previewImage(event)">
                    <div class="mt-2">
                        <img id="previewImage" style="display:none; width:150px; border:1px solid #ddd; padding:2px;">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">تحديث اللعبة</button>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('previewImage');
        output.src = reader.result;
        output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>

@endsection
