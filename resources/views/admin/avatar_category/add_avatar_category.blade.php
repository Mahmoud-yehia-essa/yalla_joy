@extends('admin.master_admin')
@section('admin')

<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">إضافة تصنيف أفاتار</div>
</div>
<hr/>

<div class="card">
    <div class="card-body p-4">

        <form method="post" action="{{ route('store.avatar.category') }}" enctype="multipart/form-data">
            @csrf

            <div class="row">

                <!-- Name -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">اسم التصنيف</label>
                    <input type="text" name="name" class="form-control" placeholder="أدخل اسم التصنيف (مثال: ملابس، عيون)">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Image Upload -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">صورة التصنيف</label>
                    <input type="file" name="image" class="form-control" id="imageInput">
                    @error('image')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                    <div class="mt-2">
                        <img id="showImage" src="{{ url('upload/no_image.jpg') }}" alt="image preview"
                             style="width: 100px; height: 100px; border:1px solid #ccc; border-radius:8px; object-fit: cover;">
                    </div>
                </div>

            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary px-4">حفظ</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Show image preview
    document.getElementById('imageInput').addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('showImage').src = e.target.result;
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>

@endsection
