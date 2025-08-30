@extends('admin.master_admin')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3"> تعديل سؤال </div>
</div>
<!--end breadcrumb-->
<div class="container">
    <div class="main-body">
        <div class="row">
            <div class="col-lg-8">
                <form action="{{ route('edit.question.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="question_id" value="{{$question->id}}"/>
                    <input type="hidden" name="old_question_image" value="{{$question->qu_image}}"/>
                    <input type="hidden" name="old_question_sound" value="{{$question->qu_sound}}"/>
                    <input type="hidden" name="old_question_video" value="{{$question->qu_video}}"/>

                    <input type="hidden" name="answer_id" value="{{ $question->answers->first()->id}}"/>


                {{-- four answer id  --}}
                <input type="hidden" name="answer_id_one" value="{{ $question->answerQuestionOnlines[0]->id}}"/>
                <input type="hidden" name="answer_id_two" value="{{ $question->answerQuestionOnlines[1]->id}}"/>
                <input type="hidden" name="answer_id_three" value="{{ $question->answerQuestionOnlines[2]->id}}"/>
                <input type="hidden" name="answer_id_four" value="{{ $question->answerQuestionOnlines[3]->id}}"/>

                 {{--end four answer id  --}}

                    <input type="hidden" name="old_answer_image" value="{{ $question->answers->first()->answer_image}}"/>
                    <input type="hidden" name="old_answer_sound" value="{{ $question->answers->first()->answer_sound}}"/>
                    <input type="hidden" name="old_answer_video" value="{{ $question->answers->first()->answer_video}}"/>

                    <div class="card">
                        <div class="card-body">



                              <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">اختر نوع اللعبة</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">


                                        <select  name="game_type_id" class="form-select" aria-label="Default select example">
                                            <option selected="" value="non">الرجاء إختيار نوع اللعبة</option>

                                            @foreach ($gameType as $item )
                                            <option value="{{$item->id}}" {{ $question->game_type_id == $item->id ? 'selected' : '' }} >{{$item->type_name}}</option>

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
                                                                                        <option selected="" value="non">الرجاء إختيار الفئة الرئيسية</option>

                                         @foreach ($main_category as $item )
                                            <option value="{{$item->id}}" {{ $question->main_category_id == $item->id ? 'selected' : '' }} >{{$item->main_category_name}}</option>
                                            {{-- <option selected="" value="non">الرجاء إختيار الفئة الرئيسية</option> --}}

                                            @endforeach
                                        </select>

                                        @error('main_category_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>


                            <!-- Question Category -->
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                        <h6 class="mb-0">اختر الفئة الفرعية</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <select  name="category_id" class="form-select" aria-label="Default select example">
                                            <option selected="" value="non">الرجاء إختيار الفئة الفرعية</option>
                                        @foreach ($category as $item )
                                            <option value="{{$item->id}}" {{ $question->category_id == $item->id ? 'selected' : '' }} >{{$item->category_name}}</option>
                                            {{-- <option selected="" value="non">الرجاء إختيار الفئة الرئيسية</option> --}}

                                            @endforeach
                                    </select>
                                    @error('category_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Question Title -->
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">السؤال</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input name="qu_title" type="text" class="form-control" value="{{ old('qu_title', $question->qu_title) }}" />
                                    @error('qu_title') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>



                                 <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Question</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input name="qu_title_en" type="text" class="form-control"  value="{{ old('qu_title_en', $question->qu_title_en) }}" />
                                    @error('qu_title_en') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Question Points -->
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">نقاط السؤال</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input name="qu_points" type="text" class="form-control" value="{{ old('qu_points', $question->qu_points) }}" />
                                    @error('qu_points') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>


                            <!-- نقاط السؤال Online -->
<div class="row mb-3">
    <div class="col-sm-3">
        <h6 class="mb-0">نقاط السؤال OnLine</h6>
    </div>
    <div class="col-sm-9 text-secondary">
        <input id="qu_points_online" name="qu_points_online" type="text" class="form-control"   value="{{ old('qu_points_online', $question->qu_points_online) }}" />
        @error('qu_points_online') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
</div>


                               <!-- Question counter -->

                               <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">توقيت السؤال</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input name="time_counter" type="number" class="form-control" min="1" step="1" value="{{ old('time_counter', $question->time_counter) }}" />
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
                                    <input name="time_counter_online" type="number" class="form-control" min="1" step="1" value="{{ old('time_counter_online', $question->time_counter_online) }}" />
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
                                        <input type="radio" name="questions_type" value="text" {{ $question->questions_type == 'text' ? 'checked' : '' }} /> نصي
                                    </label>
                                    <label class="ms-3">
                                        <input type="radio" name="questions_type" value="image" {{ $question->questions_type == 'image' ? 'checked' : '' }} /> صورة
                                    </label>
                                    <label class="ms-3">
                                        <input type="radio" name="questions_type" value="sound" {{ $question->questions_type == 'sound' ? 'checked' : '' }} /> ملف صوتي
                                    </label>
                                    <label class="ms-3">
                                        <input type="radio" name="questions_type" value="video" {{ $question->questions_type == 'video' ? 'checked' : '' }} /> ملف فيديو
                                    </label>
                                    @error('questions_type') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Question File Upload -->
                            <div class="row mb-3" id="question_file_input" style="display: none;">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">الملف</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input name="questionsـfile" type="file" class="form-control" id="questionFileInput" />
                                </div>
                            </div>

                            <!-- Preview for Question File -->
                            <div id="questionPreview" class="mt-2"></div>

                            @if ($question->questions_type == 'image')
                                <img id="showQuestionImage" src="{{ url('upload/questions/images/' . $question->qu_image) }}" alt="Admin" width="110" class="mt-2">
                            @elseif ($question->questions_type == 'video')
                                <video width="400px" controls><source src="{{ url('upload/questions/videos/' . $question->qu_video) }}" type="video/mp4"></video>
                            @elseif ($question->questions_type == 'sound')
                                <audio controls id="showQuestionAudio">
                                    <source src="{{ url('upload/questions/sounds/' . $question->qu_sound) }}" type="audio/mpeg">
                                    Your browser does not support the audio tag.
                                </audio>
                            @endif

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
                                <input name="answer_title" type="text" class="form-control" value="{{ old('answer_title', $question->answers->first()->answer_title) }}" />
                                @error('answer_title') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                          <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Answer</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input name="answer_title_en" id="answer_title_en" type="text" class="form-control" value="{{ old('answer_title_en', $question->answers->first()->answer_title_en) }}" />
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
                                    <input type="radio" name="answer_type" value="text" {{ $question->answers->first()->answer_type == 'text' ? 'checked' : '' }} /> نصي
                                </label>
                                <label class="ms-3">
                                    <input type="radio" name="answer_type" value="image" {{ $question->answers->first()->answer_type == 'image' ? 'checked' : '' }} /> صورة
                                </label>
                                <label class="ms-3">
                                    <input type="radio" name="answer_type" value="sound" {{ $question->answers->first()->answer_type == 'sound' ? 'checked' : '' }} /> ملف صوتي
                                </label>
                                <label class="ms-3">
                                    <input type="radio" name="answer_type" value="video" {{ $question->answers->first()->answer_type == 'video' ? 'checked' : '' }} /> ملف فيديو
                                </label>
                                @error('answer_type') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Answer File Upload -->
                        <div class="row mb-3" id="answer_file_input" style="display: none;">
                            <div class="col-sm-3">
                                <h6 class="mb-0">الملف</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input name="answerـfile" type="file" class="form-control" id="answerFileInput" />
                            </div>
                        </div>

                        <!-- Preview for Answer File -->
                        <div id="answerPreview" class="mt-2"></div>

                        @if ($question->answers->first()->answer_type == 'image')
                            <img id="showAnswerImage" src="{{ url('upload/answers/images/' . $question->answers->first()->answer_image) }}" alt="Admin" width="110" class="mt-2">
                        @elseif ($question->answers->first()->answer_type == 'video')
                            <video width="400px" controls><source src="{{ url('upload/answers/videos/' . $question->answers->first()->answer_video) }}" type="video/mp4"></video>
                        @elseif ($question->answers->first()->answer_type == 'sound')
                            <audio controls id="showAnswerAudio">
                                <source src="{{ url('upload/answers/sounds/' . $question->answers->first()->answer_sound) }}" type="audio/mpeg">
                                Your browser does not support the audio tag.
                            </audio>
                        @endif

                        <!-- Submit Button -->
                        {{-- <div class="row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-9 text-secondary">
                                <input type="submit" class="btn btn-primary px-4" value="تعديل السؤال" />
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
                                <input name="answer_title_one" id="answer_title_one" type="text" class="form-control"  value="{{ old('answer_title_one', $question->answerQuestionOnlines[0]->answer_title) }}" />
                                @error('answer_title_one') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                         <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">الاجابة2</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input name="answer_title_two" type="text" class="form-control"  value="{{ old('answer_title_two', $question->answerQuestionOnlines[1]->answer_title) }}"  />
                                @error('answer_title_two') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">الاجابة3</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input name="answer_title_three" type="text" class="form-control"  value="{{ old('answer_title_three', $question->answerQuestionOnlines[2]->answer_title) }}" />
                                @error('answer_title_three') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>


                     <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">الاجابة4</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input name="answer_title_four" type="text" class="form-control"  value="{{ old('answer_title_four', $question->answerQuestionOnlines[3]->answer_title) }}" />
                                @error('answer_title_four') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>








                        <hr>

                           <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Answer 1 (correct)</h6>

                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input name="answer_title_one_en" id="answer_title_one_en" type="text" class="form-control" value="{{ old('answer_title_one_en', $question->answerQuestionOnlines[0]->answer_title_en) }}" />
                                @error('answer_title_one_en') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                         <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Answer 2</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input name="answer_title_two_en" type="text" class="form-control" value="{{ old('answer_title_two_en', $question->answerQuestionOnlines[1]->answer_title_en) }}" />
                                @error('answer_title_two_en') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Answer 3</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input name="answer_title_three_en" type="text" class="form-control" value="{{ old('answer_title_three_en', $question->answerQuestionOnlines[2]->answer_title_en) }}" />
                                @error('answer_title_three_en') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>


                     <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Answer 4</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <input name="answer_title_four_en" type="text" class="form-control" value="{{ old('answer_title_four_en', $question->answerQuestionOnlines[3]->answer_title_en) }}" />
                                @error('answer_title_four_en') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>



                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-9 text-secondary">
                                <input type="submit" class="btn btn-primary px-4" value="تعديل السؤال" />
                            </div>
                        </div>

                    </div>







                </div>

                </form>
            </div>
        </div>
    </div>
</div>

<!-- jQuery to Show/Hide File Inputs and Preview -->
<script type="text/javascript">

    $(document).ready(function(){
        // Initially hide file inputs and previews
        $('#question_file_input').hide();
        $('#answer_file_input').hide();
        $('#questionPreview').hide();
        $('#answerPreview').hide();

        // Function to show/hide file inputs based on selection
        function toggleFileInput(typeSelector, fileInputSelector, previewSelector) {
            $(typeSelector).change(function() {
                $(fileInputSelector).hide(); // Hide the file input initially
                $(previewSelector).hide(); // Hide the preview initially
                if ($(this).val() == "image" || $(this).val() == "sound" || $(this).val() == "video") {
                    $(fileInputSelector).show(); // Show file input if type is image, sound or video
                }
            });
        }

        // Apply function to question and answer types
        toggleFileInput('input[name="questions_type"]', '#question_file_input', '#questionPreview');
        toggleFileInput('input[name="answer_type"]', '#answer_file_input', '#answerPreview');

        // Check the initial state on page load
        $('input[name="questions_type"]:checked').trigger('change');
        $('input[name="answer_type"]:checked').trigger('change');

        // Preview new file for Question
        $('#questionFileInput').on('change', function() {
            const file = this.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#showQuestionImage').hide(); // Hide old preview if it's an image
                $('#showQuestionAudio').hide(); // Hide old preview if it's an audio
                $('#questionPreview').show();
                const fileType = file.type;
                if (fileType.startsWith('image/')) {
                    $('#questionPreview').html('<img src="' + e.target.result + '" alt="New Image" width="110" class="mt-2">');
                } else if (fileType.startsWith('audio/')) {
                    $('#questionPreview').html('<audio controls><source src="' + e.target.result + '" type="' + fileType + '">Your browser does not support the audio tag.</audio>');
                } else if (fileType.startsWith('video/')) {
                    $('#questionPreview').html('<video width="400px" controls><source src="' + e.target.result + '" type="video/mp4"></video>');
                }
            };
            reader.readAsDataURL(file);
        });

        // Preview new file for Answer
        $('#answerFileInput').on('change', function() {
            const file = this.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#showAnswerImage').hide(); // Hide old preview if it's an image
                $('#showAnswerAudio').hide(); // Hide old preview if it's an audio
                $('#answerPreview').show();
                const fileType = file.type;
                if (fileType.startsWith('image/')) {
                    $('#answerPreview').html('<img src="' + e.target.result + '" alt="New Image" width="110" class="mt-2">');
                } else if (fileType.startsWith('audio/')) {
                    $('#answerPreview').html('<audio controls><source src="' + e.target.result + '" type="' + fileType + '">Your browser does not support the audio tag.</audio>');
                } else if (fileType.startsWith('video/')) {
                    $('#answerPreview').html('<video width="400px" controls><source src="' + e.target.result + '" type="video/mp4"></video>');
                }
            };
            reader.readAsDataURL(file);
        });
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


@endsection
