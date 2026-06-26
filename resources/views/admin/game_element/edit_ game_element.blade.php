@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">تعديل الفئة الرئيسية </div>
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
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="post" action="{{ route('edit.main.category.store') }}" enctype="multipart/form-data">
                                @csrf


                                <input type="hidden" name="id" value="{{ $main_category->id }}">
                                <input type="hidden" name="old_image" value="{{ $main_category->main_category_photo }}">
                                <!-- Category Name -->

                                     <!-- Question  category-->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">اختر نوع اللعبة</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">


                                        <select  name="game_type_id" class="form-select" aria-label="Default select example">
                                            <option value="non">الرجاء إختيار نوع اللعبة</option>

                                            @foreach ($gameType as $item )
                                            {{-- <option value="{{$item->id}}" {{ old('game_type_id') == $item->id ? 'selected' : '' }}>{{$item->type_name}}</option> --}}
                                            <option value="{{$item->id}}" {{ old('game_type_id',$main_category->game_type_id) == $item->id ? 'selected' : '' }}>{{$item->type_name}} - {{$item->type_name_en}}</option>

                                            @endforeach

                                        </select>

                                        @error('game_type_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>



                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">اسم الفئة الرئيسية</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" value="{{$main_category->main_category_name}}" name="main_category_name" class="form-control" value="{{ old('main_category_name') }}" />
                                        @error('main_category_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>


                                 <div class="row mb-3">
                                    <div class="col-sm-3">
                                <h6 class="mb-0" >Main Category Name</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" value="{{$main_category->main_category_name_en}}" name="main_category_name_en" class="form-control" value="{{ old('main_category_name_en') }}" />
                                        @error('main_category_name_en')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>






                                <!-- Category Description -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">الوصف</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="main_category_description" value="{{$main_category->main_category_description}}" class="form-control" value="{{ old('main_category_description') }}" />
                                        @error('main_category_description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>



                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Description</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="main_category_description_en" value="{{$main_category->main_category_description_en}}" class="form-control" value="{{ old('main_category_description_en') }}" />
                                        @error('main_category_description_en')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Category Photo -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">الصورة</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="file" name="main_category_photo" class="form-control" id="image" />
                                        @error('main_category_photo')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Image Preview -->
                                <div class="row mb-3">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <img id="showImage" src="{{ (!empty($main_category->main_category_photo) && file_exists(public_path($main_category->main_category_photo))) ? url($main_category->main_category_photo) : url('upload/no_image.jpg') }}" alt="Preview" style="width:100px; height: 100px;">
                                    </div>
                                </div>





                                <!-- Submit Button -->
                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="submit" class="btn btn-primary px-4" value="تعديل الفئة الرئيسية " />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- jQuery for Image Preview -->
                    <script type="text/javascript">
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
@endsection
