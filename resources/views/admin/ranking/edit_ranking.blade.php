@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">تعديل الرتبة</div>
    </div>
    <!--end breadcrumb-->

    <div class="container">
        <div class="main-body">
            <div class="row">
                <div class="col-lg-10">
                    <div class="card">
                        <div class="card-body">
                            <!-- Display Validation Errors -->
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                                </div>
                            @endif

                            <form method="post" action="{{ route('update.ranking') }}" enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="id" value="{{ $ranking->id }}">
                                <input type="hidden" name="old_image" value="{{ $ranking->photo }}">

                                <!-- Ranking Name -->
                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">الرتبة</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="name" class="form-control" value="{{ $ranking->name }}" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">Ranking</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="name_en" dir="ltr" class="form-control" value="{{ $ranking->name_en }}" />
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">الوصف</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="description" class="form-control" value="{{ $ranking->description }}" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">Description</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="description_en" dir="ltr" class="form-control" value="{{ $ranking->description_en }}" />
                                    </div>
                                </div>

                                <!-- Level -->
                                {{-- <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">المستوى</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <select name="level_id" class="form-select">
                                            @foreach($levels as $level)
                                                <option value="{{ $level->id }}" {{ $ranking->level_id == $level->id ? 'selected' : '' }}>
                                                    {{ $level->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}

                                <!-- Photo -->
                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">الصورة</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="file" name="photo" class="form-control" id="image" />
                                    </div>
                                </div>

                                <!-- Image Preview -->
                                <div class="row mb-3">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <img id="showImage" src="{{ url($ranking->photo ?? 'upload/no_image.jpg') }}" style="width:100px; height:100px;">
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="submit" class="btn btn-primary px-4" value="تعديل الرتبة" />
                                    </div>
                                </div>
                            </form>

                            <!-- jQuery for Image Preview -->
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
