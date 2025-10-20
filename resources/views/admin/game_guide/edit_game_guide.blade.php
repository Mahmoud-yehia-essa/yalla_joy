@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">تعديل الدليل</div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                <form method="post" action="{{ route('update.game.guide') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $guide->id }}">

                    <div class="mb-3">
                        <label class="form-label">العنوان</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name',$guide->name) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Title (English)</label>
                        <input type="text" name="name_en" class="form-control" value="{{ old('name_en',$guide->name_en) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الوصف</label>
                        <textarea name="description" class="form-control">{{ old('description',$guide->description) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description (English)</label>
                        <textarea name="description_en" class="form-control">{{ old('description_en',$guide->description_en) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">نوع الدليل</label>
                        <select name="type" class="form-select">
                            <option value="intro" {{ old('type',$guide->type)=='intro' ? 'selected' : '' }}>مقدمة</option>
                            <option value="help" {{ old('type',$guide->type)=='help' ? 'selected' : '' }}>مساعدة</option>
                        </select>
                    </div>

                    <!-- حقل وسيلة المساعدة -->
                    <div class="mb-3" id="helperSelectDiv" style="display: none;">
                        <label class="form-label">اختر وسيلة المساعدة</label>
                        <select name="game_helper_id" class="form-select">
                            <option value="">اختر</option>
                            @foreach($gameHelpers as $gameHelper)
                                <option value="{{ $gameHelper->id }}"
                                    {{ old('game_helper_id', $guide->game_helper_id) == $gameHelper->id ? 'selected' : '' }}>
                                    {{ $gameHelper->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الصورة</label>
                        <input type="file" name="photo" class="form-control" id="image">
                        <img id="showImage" src="{{ asset($guide->photo) }}" style="width:100px;height:100px;margin-top:5px;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الفيديو</label>
                        <input type="file" name="video" class="form-control" id="video">
                        @if($guide->video)
                        <video id="showVideo" width="200" height="150" controls style="margin-top:5px;">
                            <source src="{{ asset($guide->video) }}" type="video/mp4">
                        </video>
                        @else
                        <video id="showVideo" width="200" height="150" controls style="margin-top:5px;"></video>
                        @endif
                    </div>

                    <input type="submit" class="btn btn-primary" value="تحديث الدليل">
                </form>

            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){

    function toggleHelperSelect() {
        let type = $('select[name="type"]').val();
        if(type === 'help'){
            $('#helperSelectDiv').show();
            // إعادة تعيين الحقل إلى "اختر" إذا تم تغييره من "مقدمة"
            if(!$('#helperSelectDiv select').data('loaded')){
                $('#helperSelectDiv select').val('{{ old('game_helper_id', $guide->game_helper_id) }}');
                $('#helperSelectDiv select').data('loaded', true);
            } else {
                $('#helperSelectDiv select').val('');
            }
        } else {
            $('#helperSelectDiv').hide();
        }
    }

    toggleHelperSelect();

    $('select[name="type"]').change(function(){
        // عند تغيير النوع، أعِد تعيين الحقل
        $('#helperSelectDiv select').data('loaded', false);
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
