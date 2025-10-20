@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="col-lg-16">
    <div class="card">
        <div class="card-body">

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form method="POST" action="{{ route('save.game.coin') }}" enctype="multipart/form-data">
                @csrf

                <div class="row mb-3">
                    <div class="col-sm-3"><h6>عملة اللعبة</h6></div>
                    <div class="col-sm-9">
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3"><h6>Game Coin</h6></div>
                    <div class="col-sm-9">
                        <input type="text" name="name_en" class="form-control" value="{{ old('name_en') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3"><h6>صورة العملة</h6></div>
                    <div class="col-sm-9">
                        <input type="file" name="photo" class="form-control" id="image"/>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-9">
                        <img id="showImage" src="{{ url('upload/no_image.jpg') }}" style="width:100px; height:100px;">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-9">
                        <input type="submit" class="btn btn-success px-4" value="إضافة">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#image').change(function(e){
            var reader = new FileReader();
            reader.onload = function(e){ $('#showImage').attr('src', e.target.result); }
            reader.readAsDataURL(e.target.files[0]);
        });
    });
</script>
@endsection
