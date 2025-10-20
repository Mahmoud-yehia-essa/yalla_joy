@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">إضافة مساعد جديد</div>
    </div>

    <div class="container">
        <div class="main-body">
            <div class="row">
                <div class="col-lg-10">
                    <div class="card">
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                                </div>
                            @endif

                            <form method="post" action="{{ route('store.game.helper') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">اسم المساعد</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">Name</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="name_en" dir="ltr" class="form-control" value="{{ old('name_en') }}" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">الوصف</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="description" class="form-control" value="{{ old('description') }}" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">Description</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="description_en" dir="ltr" class="form-control" value="{{ old('description_en') }}" />
                                    </div>
                                </div>

                                     <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">اختر المستوى</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <select name="level_id" class="form-select">
                                            <option value="">-- الكل --</option>
                                            @foreach($levels as $level)
                                                <option value="{{ $level->id }}" {{ old('level_id') == $level->id ? 'selected' : '' }}>
                                                    {{ $level->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('game_coin_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">الصورة</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="file" name="photo" class="form-control" id="image" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <img id="showImage" src="{{ url('upload/no_image.jpg') }}" style="width:100px; height:100px;">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="submit" class="btn btn-primary px-4" value="إضافة مساعد" />
                                    </div>
                                </div>
                            </form>

                            <script>
                                $(document).ready(function(){
                                    $('#image').change(function(e){
                                        var reader = new FileReader();
                                        reader.onload = function(e){
                                            $('#showImage').attr('src', e.target.result);
                                        }
                                        reader.readAsDataURL(e.target.files[0]);
                                    });
                                });
                            </script>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
