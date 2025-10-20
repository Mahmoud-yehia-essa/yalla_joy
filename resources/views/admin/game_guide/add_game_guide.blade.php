@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">إضافة دليل جديد</div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                <form method="post" action="{{ route('store.game.guide') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">العنوان</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Title (English)</label>
                        <input type="text" name="name_en" class="form-control" value="{{ old('name_en') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الوصف</label>
                        <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description (English)</label>
                        <textarea name="description_en" class="form-control">{{ old('description_en') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">نوع الدليل</label>
                        <select name="type" class="form-select">
                            <option value="intro" {{ old('type')=='intro' ? 'selected' : '' }}>مقدمة</option>
                            <option value="help" {{ old('type')=='help' ? 'selected' : '' }}>مساعدة</option>
                        </select>
                    </div>

                    <div class="mb-3" id="helperSelectDiv" style="display: none;">
                        <label class="form-label">اختر وسيلة المساعدة</label>
                        <select name="game_helper_id" class="form-select">
                            <option value="">اختر</option>
                            @foreach($gameHelpers as $gameHelper)
                                <option value="{{ $gameHelper->id }}">{{ $gameHelper->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الصورة</label>
                        <input type="file" name="photo" class="form-control" id="image">
                        <img id="showImage" src="{{ url('upload/no_image.jpg') }}" style="width:100px;height:100px;margin-top:5px;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الفيديو</label>
                        <input type="file" name="video" class="form-control" id="video">
                        <video id="showVideo" width="200" height="150" controls style="margin-top:5px;"></video>
                    </div>

                    <input type="submit" class="btn btn-primary" value="إضافة الدليل">
                </form>

            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){

    // دالة لإظهار/إخفاء حقل وسيلة المساعدة وإعادة تعيينه للقيمة الافتراضية
    function toggleHelperSelect() {
        let type = $('select[name="type"]').val();
        if(type === 'help'){
            $('#helperSelectDiv').show();
            // إعادة تعيين الاختيار إلى القيمة الافتراضية
            $('#helperSelectDiv select').val('');
        } else {
            $('#helperSelectDiv').hide();
        }
    }

    // تنفيذ عند تحميل الصفحة
    toggleHelperSelect();

    // تنفيذ عند تغيير نوع الدليل
    $('select[name="type"]').change(function(){
        toggleHelperSelect();
    });

    // عرض الصورة عند اختيارها
    $('#image').change(function(e){
        let reader = new FileReader();
        reader.onload = function(e){ $('#showImage').attr('src', e.target.result); }
        reader.readAsDataURL(e.target.files[0]);
    });

    // عرض الفيديو عند اختياره
    $('#video').change(function(e){
        let reader = new FileReader();
        reader.onload = function(e){ $('#showVideo').attr('src', e.target.result); }
        reader.readAsDataURL(e.target.files[0]);
    });
});
</script>

@endsection
