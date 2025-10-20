@extends('admin.master_admin')
@section('admin')

<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">تعديل نوع عنصر لعبة</div>
</div>
<hr/>

<div class="card">
    <div class="card-body p-4">

        <form method="post" action="{{ route('update.item.type') }}" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="id" value="{{ $itemType->id }}">
            <input type="hidden" name="old_image" value="{{ $itemType->photo }}">

            <div class="row">

                <!-- Arabic Name -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">اسم النوع</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $itemType->name) }}">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- English Name -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">اسم النوع بالإنجليزية</label>
                    <input type="text" name="name_en" class="form-control" value="{{ old('name_en', $itemType->name_en) }}">
                    @error('name_en')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Arabic Description -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">الوصف</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $itemType->description) }}</textarea>
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- English Description -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">الوصف بالإنجليزية</label>
                    <textarea name="description_en" class="form-control" rows="3">{{ old('description_en', $itemType->description_en) }}</textarea>
                    @error('description_en')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Image Upload -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">صورة النوع</label>
                    <input type="file" name="photo" class="form-control" id="imageInput">
                    @error('photo')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                    <div class="mt-2">
                        <img id="showImage" src="{{ $itemType->photo ? asset($itemType->photo) : url('upload/no_image.jpg') }}"
                             alt="image preview" style="width: 100px; height: 100px; border:1px solid #ccc; border-radius:8px;">
                    </div>
                </div>

            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary px-4">تحديث</button>
                {{-- <a href="{{ route('all.item.type') }}" class="btn btn-secondary">رجوع</a> --}}
            </div>
        </form>
    </div>
</div>

<script>
    // Show image preview
    document.getElementById('imageInput').addEventListener('change', function(e) {
        let reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('showImage').src = e.target.result;
        }
        reader.readAsDataURL(this.files[0]);
    });
</script>

@endsection
