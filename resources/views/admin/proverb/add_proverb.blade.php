@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">اضافة عبارة جديدة</div>
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

                            <form method="post" action="{{ route('add.proverb.store') }}" enctype="multipart/form-data">
                                @csrf

                                <!-- Title (Arabic) -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">العبارة (عربي)</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" />
                                        @error('title')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Title (English) -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">العبارة ( انجليزي )</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" dir="ltr" name="title_en" class="form-control"  value="{{ old('title_en') }}" />
                                        @error('title_en')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Type -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">نوع العبارة</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <select name="type" class="form-select mb-3" aria-label="Default select example">
                                            <option value="positive" {{ old('type') == 'positive' ? 'selected' : '' }}>ايجابية</option>
                                            <option value="negative" {{ old('type') == 'negative' ? 'selected' : '' }}>سلبية</option>
                                        </select>
                                        @error('type')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">الرتبة</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <select name="ranking_new_id" class="form-select mb-3" aria-label="Default select example">
                                            <option selected="" value="">اختر الرتبة</option>
                                            @foreach($rankings as $ranking)
                                                <option value="{{ $ranking->id }}">{{ $ranking->rank_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('ranking_new_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">الصوت (عربي)</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="file" name="audio_ar" class="form-control" id="audioArInput" accept="audio/*" />
                                        @error('audio_ar')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <div class="mt-2" id="audioArPreviewContainer" style="display: none;">
                                            <audio id="audioArPreview" controls style="width: 100%;">
                                                <source src="" id="audioArSource">
                                            </audio>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">الصوت (انجليزي)</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="file" name="audio_en" class="form-control" id="audioEnInput" accept="audio/*" />
                                        @error('audio_en')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <div class="mt-2" id="audioEnPreviewContainer" style="display: none;">
                                            <audio id="audioEnPreview" controls style="width: 100%;">
                                                <source src="" id="audioEnSource">
                                            </audio>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="submit" class="btn btn-primary px-4" value="اضافة عبارة" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        // Audio Preview for Arabic
        $('#audioArInput').change(function(e){
            var file = e.target.files[0];
            if(file) {
                var objectUrl = URL.createObjectURL(file);
                $('#audioArPreview').attr('src', objectUrl);
                $('#audioArPreviewContainer').show();
            } else {
                $('#audioArPreviewContainer').hide();
                $('#audioArPreview').attr('src', '');
            }
        });

        // Audio Preview for English
        $('#audioEnInput').change(function(e){
            var file = e.target.files[0];
            if(file) {
                var objectUrl = URL.createObjectURL(file);
                $('#audioEnPreview').attr('src', objectUrl);
                $('#audioEnPreviewContainer').show();
            } else {
                $('#audioEnPreviewContainer').hide();
                $('#audioEnPreview').attr('src', '');
            }
        });
    });
</script>
@endsection
