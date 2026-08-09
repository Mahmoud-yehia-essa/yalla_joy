@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">اضافة فئة جديدة</div>
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

                            <form method="post" action="{{ route('add.category.store') }}" enctype="multipart/form-data">
                                @csrf



                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">اختر نوع اللعبة</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">


                                        <select  name="game_type_id" class="form-select" aria-label="Default select example">
                                            <option selected="" value="non">الرجاء إختيار نوع اللعبة</option>

                                            @foreach ($gameType as $item )
                                            <option value="{{$item->id}}" >{{$item->type_name}}</option>

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
                                            <option selected="" value="non">الرجاء إختيار الفئة الرئيسية</option>

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
                                        <input type="text" name="category_name" class="form-control" value="{{ old('category_name') }}" />
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
                                        <input type="text" name="category_name_en" class="form-control" value="{{ old('category_name_en') }}" />
                                        @error('category_name_en')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Order By -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">ترتيب الظهور</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="number" name="order_by" class="form-control" value="{{ old('order_by') }}" placeholder="أدخل رقم الترتيب (مثال: 1, 2, 3...)" min="1" />
                                        @error('order_by')
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
                                        <input type="text" name="category_description" class="form-control" value="{{ old('category_description') }}" />
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
                                        <input type="text" name="category_description_en" class="form-control" value="{{ old('category_description_en') }}" />
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
                                        <img id="showImage" src="{{ url('upload/no_image.jpg') }}" alt="Preview" style="width:100px; height: 100px;">
                                    </div>
                                </div>


                                      <!-- category special-->
                                      <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">هل الفئة مميزة؟</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">


                                            <select  name="special" class="form-select" aria-label="Default select example">

                                                <option value="inactive" >لا</option>

                                                <option value="active" >نعم</option>


                                            </select>

                                            @error('category_id') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                <!-- Submit Button -->
                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="submit" class="btn btn-primary px-4" value="اضافة فئة جديدة" />
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

{{-- <script>
$(document).ready(function(){
    $('select[name="game_type_id"]').on('change', function(){
        var game_type_id = $(this).val();
        if(game_type_id && game_type_id !== 'non'){
            $.ajax({
                url: '/get-main-categories/' + game_type_id,
                type: 'GET',
                dataType: 'json',
                success: function(data){
                    var select = $('select[name="main_category_id"]');
                    select.empty();
                    select.append('<option value="non">الرجاء إختيار الفئة الرئيسية</option>');
                    $.each(data, function(key, value){
                        select.append('<option value="'+ value.id +'">'+ value.main_category_name +'</option>');
                    });
                }
            });
        } else {
            $('select[name="main_category_id"]').empty().append('<option value="non">الرجاء إختيار الفئة الرئيسية</option>');
        }
    });
});
</script> --}}

<script>
$(document).ready(function(){
    $('select[name="game_type_id"]').on('change', function(){
        var game_type_id = $(this).val();
        var select = $('select[name="main_category_id"]');

        if(game_type_id && game_type_id !== 'non'){
            // Disable and show loading
            select.prop('disabled', true)
                  .empty()
                  .append('<option value="">جاري التحميل...</option>');

            $.ajax({
                url: '/get-main-categories/' + game_type_id,
                type: 'GET',
                dataType: 'json',
                success: function(data){
                    select.empty();
                    select.append('<option value="non">الرجاء إختيار الفئة الرئيسية</option>');
                    $.each(data, function(key, value){
                        select.append('<option value="'+ value.id +'">'+ value.main_category_name +'</option>');
                    });
                    // Re-enable after loading
                    select.prop('disabled', false);
                },
                error: function(){
                    select.empty().append('<option value="">حدث خطأ، حاول مرة أخرى</option>');
                    select.prop('disabled', false);
                }
            });
        } else {
            select.prop('disabled', false)
                  .empty()
                  .append('<option value="non">الرجاء إختيار الفئة الرئيسية</option>');
        }
    });
});
</script>


@endsection
