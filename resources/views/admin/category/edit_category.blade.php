@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">تعديل الفئة</div>
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

                            <form method="post" action="{{ route('edit.category.store') }}" enctype="multipart/form-data">
                                @csrf


                                <input type="hidden" name="id" value="{{ $category->id }}">
                                <input type="hidden" name="old_image" value="{{ $category->category_photo }}">




                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">اختر نوع اللعبة</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">


                                        <select  name="game_type_id" class="form-select" aria-label="Default select example">
                                            <option selected="" value="non">الرجاء إختيار نوع اللعبة</option>

                                            @foreach ($gameType as $item )
                                            {{-- <option value="{{$item->id}}" >{{$item->type_name}}</option> --}}
                                            <option value="{{$item->id}}" {{ old('game_type_id',$category->game_type_id) == $item->id ? 'selected' : '' }}>{{$item->type_name}} - {{$item->type_name_en}}</option>

                                            @endforeach

                                        </select>

                                        @error('game_type_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>



                                 <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">اختر الفئة الرئيسية</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">


                                        <select  name="main_category_id" class="form-select" aria-label="Default select example">
                                            {{-- <option selected="" value="non">الرجاء إختيار الفئة الرئيسية</option> --}}
                                            {{-- <option value="{{$item->id}}" {{ old('game_type_id',$category->game_type_id) == $item->id ? 'selected' : '' }}>{{$item->type_name}} - {{$item->type_name_en}}</option> --}}
                                            <option selected="" value="non">الرجاء إختيار الفئة الرئيسية</option>

                                            @foreach ($mainCategories as $item )
                                            {{-- <option value="{{$item->id}}" >{{$item->type_name}}</option> --}}
                                            <option value="{{$item->id}}" {{ old('main_category_id',$category->main_category_id) == $item->id ? 'selected' : '' }}>{{$item->main_category_name}} - {{$item->main_category_name_en}}</option>

                                            @endforeach
                                        </select>

                                        @error('main_category_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>



                                <!-- Category Name -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">اسم الفئة</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" value="{{$category->category_name}}" name="category_name" class="form-control" value="{{ old('category_name') }}" />
                                        @error('category_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>


                                   <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Category Name</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" value="{{$category->category_name_en}}" name="category_name_en" class="form-control" value="{{ old('category_name_en') }}" />
                                        @error('category_name_en')
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
                                        <input type="text" name="category_description" value="{{$category->category_description}}" class="form-control" value="{{ old('category_description') }}" />
                                        @error('category_description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>


                                     <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Description</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="category_description_en" value="{{$category->category_description_en}}" class="form-control" value="{{ old('category_description_en') }}" />
                                        @error('category_description_en')
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
                                        <input type="file" name="category_photo" class="form-control" id="image" />
                                        @error('category_photo')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Image Preview -->
                                <div class="row mb-3">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <img id="showImage" src="{{ (!empty($category->category_photo) && file_exists(public_path($category->category_photo))) ? url($category->category_photo) : url('upload/no_image.jpg') }}" alt="Preview" style="width:100px; height: 100px;">
                                    </div>
                                </div>



                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">هل الفئة مميزة؟</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">


                                        <select  name="special" class="form-select" aria-label="Default select example">

                                            <option value="inactive" {{ old('special',$category->special) == 'inactive' ? 'selected' : '' }} >لا</option>

                                            <option value="active" {{ old('special',$category->special) == 'active' ? 'selected' : '' }} >نعم</option>




                                        </select>

                                        @error('category_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="submit" class="btn btn-primary px-4" value="تعديل الفئة" />
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

<script>
$(document).ready(function(){
    var gameTypeSelect = $('select[name="game_type_id"]');
    var mainCategorySelect = $('select[name="main_category_id"]');
    var selectedMainCategoryId = "{{ old('main_category_id', $category->main_category_id) }}"; // keep selected on edit

    function loadMainCategories(gameTypeId, preselectId = null){
        if(gameTypeId && gameTypeId !== 'non'){
            // Disable and show loading
            mainCategorySelect.prop('disabled', true)
                              .empty()
                              .append('<option value="">جاري التحميل...</option>');

            $.ajax({
                url: '/get-main-categories/' + gameTypeId,
                type: 'GET',
                dataType: 'json',
                success: function(data){
                    mainCategorySelect.empty();
                    mainCategorySelect.append('<option value="non">الرجاء إختيار الفئة الرئيسية</option>');

                    $.each(data, function(key, value){
                        mainCategorySelect.append('<option value="'+ value.id +'">'+ value.main_category_name +' - '+ value.main_category_name_en +'</option>');
                    });

                    // Preselect on edit
                    if(preselectId){
                        mainCategorySelect.val(preselectId);
                    }

                    mainCategorySelect.prop('disabled', false);
                },
                error: function(){
                    mainCategorySelect.empty().append('<option value="">حدث خطأ، حاول مرة أخرى</option>');
                    mainCategorySelect.prop('disabled', false);
                }
            });
        } else {
            mainCategorySelect.prop('disabled', false)
                              .empty()
                              .append('<option value="non">الرجاء إختيار الفئة الرئيسية</option>');
        }
    }

    // Trigger when game type changes
    gameTypeSelect.on('change', function(){
        loadMainCategories($(this).val());
    });

    // Auto-load on page load for edit
    if(gameTypeSelect.val() && gameTypeSelect.val() !== 'non'){
        loadMainCategories(gameTypeSelect.val(), selectedMainCategoryId);
    }
});
</script>

@endsection
