@extends('admin.master_admin')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">البحث  المتقدم</div>
</div>
<!--end breadcrumb-->
<div class="container">
    <div class="main-body">
        <div class="row">
            <div class="col-lg-8">
                <form action="{{ route('filter.category.search') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
      @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                        <div class="card-body">


 {{-- <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">البحث</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="search" class="form-control" placeholder="الكل" />
                                        @error('search')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div> --}}

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">اختر نوع اللعبة</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">


                                        <select  name="game_type_id" class="form-select" aria-label="Default select example">
                                            <option selected="" value="non">الكل</option>

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
                                            <option selected="" value="non">الكل</option>

                                        </select>

                                        @error('main_category_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>




                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">اختر الفئة الفرعية</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">


                                        <select  name="category_id" class="form-select" aria-label="Default select example">
                                            <option selected="" value="non">الكل</option>


                                        </select>

                                        @error('category_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>





























                        </div>
                    </div>

            </div>
        </div>

        <!-- Answer Section -->

        <div class="row">
            <div class="col-lg-8">




















                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-9 text-secondary">
                                <input type="submit" class="btn btn-primary px-4" value="ابحث" />
                            </div>
                        </div>

                    </div>







                </div>


                </form>
            </div>
        </div>








    </div>
</div>

<!-- jQuery to Show/Hide File Inputs -->
<script type="text/javascript">


    $(document).ready(function(){
    function toggleFileInput(typeSelector, fileInputSelector) {
        $(typeSelector).change(function() {
            if ($(this).val() == "image" || $(this).val() == "sound" || $(this).val() == "video") {
                $(fileInputSelector).show();
            } else {
                $(fileInputSelector).hide();
                $(fileInputSelector).find('input[type="file"]').val('');
                $(fileInputSelector).find('#questionPreview, #answerPreview').empty();
            }
        });
    }

    function previewFile(inputSelector, previewSelector) {
        $(inputSelector).change(function() {
            const file = this.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                if (file.type.startsWith('image/')) {
                    $(previewSelector).html('<img src="' + e.target.result + '" alt="Preview" width="110" class="mt-2">');
                } else if (file.type.startsWith('audio/')) {
                    $(previewSelector).html('<audio controls><source src="' + e.target.result + '" type="audio/mpeg"></audio>');
                } else if (file.type.startsWith('video/')) {
                    $(previewSelector).html('<video width="400px"  controls><source src="' + e.target.result + '" type="video/mp4"></video>');
                }
            };
            if (file) {
                reader.readAsDataURL(file);
            }
        });
    }

    toggleFileInput('input[name="questions_type"]', '#question_file_input');
    toggleFileInput('input[name="answer_type"]', '#answer_file_input');

    previewFile('#questionFile', '#questionPreview');
    previewFile('#answerFile', '#answerPreview');
});



$(document).ready(function(){

    function toggleFileInput(radioSelector, fileInputSelector, previewSelector) {
        $(radioSelector).change(function() {
            if ($(this).val() == "image" || $(this).val() == "sound" || $(this).val() == "video") {
                $(fileInputSelector).show();
            } else {
                $(fileInputSelector).hide();
                $(fileInputSelector).find('input[type="file"]').val('');
                $(previewSelector).empty();
            }
        });
    }

    function previewFile(inputSelector, previewSelector) {
        $(inputSelector).change(function() {
            const file = this.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                if (file.type.startsWith('image/')) {
                    $(previewSelector).html('<img src="' + e.target.result + '" alt="Preview" width="110" class="mt-2">');
                } else if (file.type.startsWith('audio/')) {
                    $(previewSelector).html('<audio controls><source src="' + e.target.result + '" type="audio/mpeg"></audio>');
                } else if (file.type.startsWith('video/')) {
                    $(previewSelector).html('<video width="200" controls><source src="' + e.target.result + '" type="video/mp4"></video>');
                }
            };
            if (file) reader.readAsDataURL(file);
        });
    }

    // Apply to online answers (2,3,4)
    toggleFileInput('input[name="answer_type_two"]', '#answer_two_file_input', '#answerPreviewTwo');
    toggleFileInput('input[name="answer_type_three"]', '#answer_three_file_input', '#answerPreviewThree');
    toggleFileInput('input[name="answer_type_four"]', '#answer_four_file_input', '#answerPreviewFour');

    previewFile('#answerFileTwo', '#answerPreviewTwo');
    previewFile('#answerFileThree', '#answerPreviewThree');
    previewFile('#answerFileFour', '#answerPreviewFour');
});


</script>




<script>
$(document).ready(function(){

    // Helper to reset a select with default text
    function resetSelect(selectElement, defaultText){
        $(selectElement).prop('disabled', false)
                        .empty()
                        .append('<option value="non">' + defaultText + '</option>');
    }

    // Reusable function for AJAX loading of options
    function loadOptions(triggerSelect, targetSelect, urlPrefix, valueField, textField, defaultText) {
        $(triggerSelect).on('change', function(){
            var selectedId = $(this).val();
            var target = $(targetSelect);

            if(selectedId && selectedId !== 'non'){
                // Show loading
                target.prop('disabled', true)
                      .empty()
                      .append('<option value="">جاري التحميل...</option>');

                $.ajax({
                    url: urlPrefix + selectedId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data){
                        target.empty();
                        target.append('<option value="non">' + defaultText + '</option>');
                        $.each(data, function(key, value){
                            target.append('<option value="'+ value[valueField] +'">'+ value[textField] +'</option>');
                        });
                        target.prop('disabled', false);
                    },
                    error: function(){
                        resetSelect(target, defaultText);
                        target.append('<option value="">حدث خطأ، حاول مرة أخرى</option>');
                    }
                });
            } else {
                resetSelect(target, defaultText);
            }
        });
    }

    // game_type_id → main_category_id
    loadOptions(
        'select[name="game_type_id"]',
        'select[name="main_category_id"]',
        '/get-main-categories/',
        'id',
        'main_category_name',
        'الكل'
    );

    // main_category_id → category_id (subcategories)
    loadOptions(
        'select[name="main_category_id"]',
        'select[name="category_id"]',
        '/get-sub-categories/',
        'id',
        'category_name',
        'الكل'
    );

    // 👉 Reset only category_id when game_type changes (NOT main_category)
    $('select[name="game_type_id"]').on('change', function(){
        resetSelect('select[name="category_id"]', 'الكل');
    });

});
</script>


<script>
$(document).ready(function() {
    $('#qu_points').on('input', function() {
        $('#qu_points_online').val($(this).val());
    });
});



$(document).ready(function() {
    $('#answer_title').on('input', function() {
        $('#answer_title_one').val($(this).val());
    });


 $('#answer_title_en').on('input', function() {
        $('#answer_title_one_en').val($(this).val());
    });


});

</script>
@endsection
