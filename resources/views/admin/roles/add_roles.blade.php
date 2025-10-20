@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">اضافة دور جديد</div>
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

                            <form method="post" action="{{ route('add.roles.store') }}" enctype="multipart/form-data">
                                @csrf


                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">اسم الدور</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" />
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>






















                                <!-- Submit Button -->
                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="submit" class="btn btn-primary px-4" value="اضافة دور جديد" />
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
