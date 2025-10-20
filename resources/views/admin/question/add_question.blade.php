@extends('admin.master_admin')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">اضافة سؤال جديد</div>
</div>
<!--end breadcrumb-->
<div class="container">
    <div class="main-body">
        <div class="row">
            <div class="col-lg-8">
                <form action="{{ route('add.question.store') }}" method="POST" enctype="multipart/form-data">
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




                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">اختر الفئة الفرعية</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">


                                        <select  name="category_id" class="form-select" aria-label="Default select example">
                                            <option selected="" value="non">الرجاء إختيار الفئة الفرعية</option>


                                        </select>

                                        @error('category_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>



                                  <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">اختر نوع العملة</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">


                                        <select  name="game_coin_id" class="form-select" aria-label="Default select example">
                                            <option selected="" value="non">الرجاء إختيار نوع العملة</option>

                                            @foreach ($gameCoin as $item )
                                            <option value="{{$item->id}}" >{{$item->name}}</option>

                                            @endforeach

                                        </select>

                                        @error('game_coin_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>



                                <div class="row mb-3">
    <div class="col-sm-3">
        <h6 class="mb-0">عدد العملات من النوع المختار</h6>
    </div>
    <div class="col-sm-9 text-secondary">
        <input id="coins_number" name="coins_number" type="number" class="form-control" value="{{ old('coins_number') }}" />
        @error('coins_number') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
</div>


                            <!-- Question Title -->
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">السؤال</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input name="qu_title" type="text" class="form-control" value="{{ old('qu_title') }}" />
                                    @error('qu_title') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>


                                 <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Question</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input name="qu_title_en" type="text" class="form-control" value="{{ old('qu_title_en') }}" />
                                    @error('qu_title_en') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>




                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">تلميح للسؤال</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input name="qu_hint" type="text" class="form-control" value="{{ old('qu_hint') }}" />
                                                                  <small>في حالة استخدام وسيلة المساعدة </small>

                                    @error('qu_hint') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                            </div>



                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">تلميح للسؤال بالانجليزية</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input name="qu_hint_en" type="text" class="form-control" value="{{ old('qu_hint_en') }}" />
                                    @error('qu_hint_en') <span class="text-danger">{{ $message }}</span> @enderror
                                                                     <small>في حالة استخدام وسيلة المساعدة </small>

                                </div>
                            </div>


                            <!-- Question Points -->
                           <!-- نقاط السؤال -->
{{-- <div class="row mb-3">
    <div class="col-sm-3">
        <h6 class="mb-0">نقاط السؤال</h6>
    </div>
    <div class="col-sm-9 text-secondary">
        <input id="qu_points" name="qu_points" type="text" class="form-control" value="{{ old('qu_points') }}" />
        @error('qu_points') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
</div>

<!-- نقاط السؤال Online -->
<div class="row mb-3">
    <div class="col-sm-3">
        <h6 class="mb-0">نقاط السؤال OnLine</h6>
    </div>
    <div class="col-sm-9 text-secondary">
        <input id="qu_points_online" name="qu_points_online" type="text" class="form-control" value="{{ old('qu_points_online') }}" />
        @error('qu_points_online') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
</div> --}}


                            <!-- Question counter -->

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">توقيت السؤال</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input name="time_counter" type="number" class="form-control" min="1" step="1" value="{{ old('time_counter') }}" />
                                    @error('time_counter')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        ملاحظة : اذا تم ترك الحقل فارغ سيتم عدم حساب توقيت للسؤال ومعيار الحساب دقائق يعني اذا تم ادخال 1 تعني دقيقة واحدة
                                    </small>
                                </div>
                            </div>


                                  <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">توقيت السؤال OnLine</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input name="time_counter_online" type="number" class="form-control" min="1" step="1" value="{{ old('time_counter_online') }}" />
                                    @error('time_counter_online')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        ملاحظة : اذا تم ترك الحقل فارغ سيتم حساب توقيت للسؤال 10 ثواني ومعيار الحساب ثواني
                                    </small>
                                </div>
                            </div>

                            <!-- Question Type -->
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">نوع السؤال</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <label>
                                        <input type="radio" name="questions_type" value="text" checked /> نصي
                                    </label>
                                    <label class="ms-3">
                                        <input type="radio" name="questions_type" value="image" /> صورة
                                    </label>
                                    <label class="ms-3">
                                        <input type="radio" name="questions_type" value="sound" /> ملف صوتي
                                    </label>
                                    <label class="ms-3">
                                        <input type="radio" name="questions_type" value="video" /> فيديو
                                    </label>
                                    @error('questions_type') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                @error('answerـfile') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <!-- Question File Upload (Initially Hidden) -->
                            <div class="row mb-3" id="question_file_input" style="display: none;">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">الملف</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input name="questionsـfile" type="file" class="form-control" id="questionFile" />
                                    <div id="questionPreview" class="mt-2"></div> <!-- Preview Area -->
                                </div>
                            </div>

                        </div>
                    </div>

            </div>
        </div>

        <!-- Answer Section -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">

                اضافة الاجابة

                Local

            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">

                <div class="card">
                    <div class="card-body">

                        <!-- Answer Title -->
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">الاجابة</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input id="answer_title" name="answer_title" type="text" class="form-control" value="{{ old('answer_title') }}" />
                                @error('answer_title') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                            <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Answer</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input name="answer_title_en" id="answer_title_en" type="text" class="form-control" value="{{ old('answer_title_en') }}" />
                                @error('answer_title_en') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>


                        </div>

                        <!-- Answer Type -->
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">نوع الاجابة</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <label>
                                    <input type="radio" name="answer_type" value="text" checked /> نصي
                                </label>
                                <label class="ms-3">
                                    <input type="radio" name="answer_type" value="image" /> صورة
                                </label>
                                <label class="ms-3">
                                    <input type="radio" name="answer_type" value="sound" /> ملف صوتي
                                </label>
                                <label class="ms-3">
                                    <input type="radio" name="answer_type" value="video" /> فيديو
                                </label>
                                @error('answer_type') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Answer File Upload (Initially Hidden) -->
                        <div class="mb-3">
                            @error('answerـfile') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="row mb-3" id="answer_file_input" style="display: none;">

                            <div class="col-sm-3">
                                <h6 class="mb-0">الملف</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input name="answerـfile" type="file" class="form-control" id="answerFile" />
                                <div id="answerPreview" class="mt-2"></div> <!-- Preview Area -->
                            </div>
                        </div>

                        <!-- Submit Button -->
                        {{-- <div class="row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-9 text-secondary">
                                <input type="submit" class="btn btn-primary px-4" value="اضافة سؤال جديد" />
                            </div>
                        </div> --}}

                    </div>
                </div>


 <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">

                اضافة الاجابات

                OnLine

            </div>
        </div>
                  <div class="card">
                    <div class="card-body">

                        <!-- Answer Title -->
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">الاجابة1 الصحيحة</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input name="answer_title_one" id="answer_title_one" type="text" class="form-control" value="{{ old('answer_title_one') }}" />
                                @error('answer_title_one') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>


                        <!-- Answer 2 Online -->
<div class="row mb-3">
    <div class="col-sm-3">
        <h6 class="mb-0">الاجابة2</h6>
    </div>
    <div class="col-sm-9 text-secondary">
        <input name="answer_title_two" type="text" class="form-control" value="{{ old('answer_title_two') }}" />
        @error('answer_title_two') <span class="text-danger">{{ $message }}</span> @enderror
        <!-- Answer Type -->
        <div class="mt-2">
            <label><input type="radio" name="answer_type_two" value="text" checked> نصي</label>
            <label class="ms-3"><input type="radio" name="answer_type_two" value="image"> صورة</label>
            <label class="ms-3"><input type="radio" name="answer_type_two" value="sound"> ملف صوتي</label>
            <label class="ms-3"><input type="radio" name="answer_type_two" value="video"> فيديو</label>
        </div>
        <!-- File Upload -->
        <div class="mt-2" id="answer_two_file_input" style="display: none;">
            <input name="answer_file_two" type="file" class="form-control" id="answerFileTwo">
            <div id="answerPreviewTwo" class="mt-2"></div>
        </div>
    </div>
</div>

<!-- Answer 3 Online -->
<div class="row mb-3">
    <div class="col-sm-3">
        <h6 class="mb-0">الاجابة3</h6>
    </div>
    <div class="col-sm-9 text-secondary">
        <input name="answer_title_three" type="text" class="form-control" value="{{ old('answer_title_three') }}" />
        @error('answer_title_three') <span class="text-danger">{{ $message }}</span> @enderror
        <div class="mt-2">
            <label><input type="radio" name="answer_type_three" value="text" checked> نصي</label>
            <label class="ms-3"><input type="radio" name="answer_type_three" value="image"> صورة</label>
            <label class="ms-3"><input type="radio" name="answer_type_three" value="sound"> ملف صوتي</label>
            <label class="ms-3"><input type="radio" name="answer_type_three" value="video"> فيديو</label>
        </div>
        <div class="mt-2" id="answer_three_file_input" style="display: none;">
            <input name="answer_file_three" type="file" class="form-control" id="answerFileThree">
            <div id="answerPreviewThree" class="mt-2"></div>
        </div>
    </div>
</div>

<!-- Answer 4 Online -->
<div class="row mb-3">
    <div class="col-sm-3">
        <h6 class="mb-0">الاجابة4</h6>
    </div>
    <div class="col-sm-9 text-secondary">
        <input name="answer_title_four" type="text" class="form-control" value="{{ old('answer_title_four') }}" />
        @error('answer_title_four') <span class="text-danger">{{ $message }}</span> @enderror
        <div class="mt-2">
            <label><input type="radio" name="answer_type_four" value="text" checked> نصي</label>
            <label class="ms-3"><input type="radio" name="answer_type_four" value="image"> صورة</label>
            <label class="ms-3"><input type="radio" name="answer_type_four" value="sound"> ملف صوتي</label>
            <label class="ms-3"><input type="radio" name="answer_type_four" value="video"> فيديو</label>
        </div>
        <div class="mt-2" id="answer_four_file_input" style="display: none;">
            <input name="answer_file_four" type="file" class="form-control" id="answerFileFour">
            <div id="answerPreviewFour" class="mt-2"></div>
        </div>
    </div>
</div>





                        <hr>

                           <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Answer 1 (correct)</h6>

                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input name="answer_title_one_en" id="answer_title_one_en" type="text" class="form-control" value="{{ old('answer_title_one_en') }}" />
                                @error('answer_title_one_en') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                         <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Answer 2</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input name="answer_title_two_en" type="text" class="form-control" value="{{ old('answer_title_two_en') }}" />
                                @error('answer_title_two_en') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Answer 3</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input name="answer_title_three_en" type="text" class="form-control" value="{{ old('answer_title_three_en') }}" />
                                @error('answer_title_three_en') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>


                     <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Answer 4</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input name="answer_title_four_en" type="text" class="form-control" value="{{ old('answer_title_four_en') }}" />
                                @error('answer_title_four_en') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>



                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-9 text-secondary">
                                <input type="submit" class="btn btn-primary px-4" value="اضافة سؤال جديد" />
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
        'الرجاء إختيار الفئة الرئيسية'
    );

    // main_category_id → category_id (subcategories)
    loadOptions(
        'select[name="main_category_id"]',
        'select[name="category_id"]',
        '/get-sub-categories/',
        'id',
        'category_name',
        'الرجاء إختيار الفئة الفرعية'
    );

    // 👉 Reset only category_id when game_type changes (NOT main_category)
    $('select[name="game_type_id"]').on('change', function(){
        resetSelect('select[name="category_id"]', 'الرجاء إختيار الفئة الفرعية');
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
