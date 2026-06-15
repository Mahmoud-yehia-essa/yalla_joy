@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">إضافة حركة (أنيميشن) جديدة</div>
    </div>

    <div class="container">
        <div class="main-body">
            <div class="row">
                <div class="col-lg-10">
                    <div class="card">
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="post" action="{{ route('add.animation.store') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">اسم الحركة (عربي)</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" />
                                        @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">اسم الحركة (انجليزي)</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" dir="ltr" name="name_en" class="form-control" value="{{ old('name_en') }}" />
                                        @error('name_en')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">وصف الحركة (عربي)</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">وصف الحركة (انجليزي)</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <textarea dir="ltr" name="description_en" class="form-control">{{ old('description_en') }}</textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">نوع الحركة</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <select name="type" class="form-select mb-3" aria-label="Default select example">
                                            <option value="positive" {{ old('type') == 'positive' ? 'selected' : '' }}>إيجابية</option>
                                            <option value="negative" {{ old('type') == 'negative' ? 'selected' : '' }}>سلبية</option>
                                        </select>
                                        @error('type')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">الرتبة الخاصة بالحركة</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <select name="ranking_new_id" class="form-select mb-3" aria-label="Default select example">
                                            <option selected="" value="">اختر الرتبة</option>
                                            @foreach($rankings as $ranking)
                                            <option value="{{ $ranking->id }}">{{ $ranking->rank_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('ranking_new_id')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">هل الحركة مجانية؟</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_free" value="1" id="isFreeSwitch" checked>
                                            <label class="form-check-label" for="isFreeSwitch">نعم</label>
                                        </div>
                                    </div>
                                </div>

                                <div id="coinDetails" style="display: none;">
                                    <div class="row mb-3">
                                        <div class="col-sm-3"><h6 class="mb-0">نوع العملة لشراء الحركة</h6></div>
                                        <div class="col-sm-9 text-secondary">
                                            <select name="coin_id" class="form-select mb-3" aria-label="Default select example">
                                                <option selected="" value="">اختر العملة</option>
                                                @foreach($coins as $coin)
                                                <option value="{{ $coin->id }}">{{ $coin->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-3"><h6 class="mb-0">عدد العملات لشراء الحركة</h6></div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="number" name="coin_amount" class="form-control" value="{{ old('coin_amount') }}" />
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">ملف الحركة (JSON)</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="file" name="file_path" class="form-control" id="animationFile" accept=".json" />
                                        @error('file_path')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                </div>

                                <div class="row mb-3" id="previewContainer" style="display: none;">
                                    <div class="col-sm-3"><h6 class="mb-0">معاينة الحركة</h6></div>
                                    <div class="col-sm-9 text-secondary" id="lottiePreview">
                                        <!-- Lottie Player will be injected here -->
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">صوت الحركة (اختياري)</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="file" name="audio" class="form-control" id="audioInput" accept="audio/*" />
                                        @error('audio')<span class="text-danger">{{ $message }}</span>@enderror
                                        <div class="mt-2" id="audioPreviewContainer" style="display: none;">
                                            <audio id="audioPreview" controls style="width: 100%;">
                                                <source src="" id="audioSource">
                                            </audio>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="submit" class="btn btn-primary px-4" value="اضافة الحركة" />
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

<script type="text/javascript">
    $(document).ready(function(){
        // Handle switch toggle
        $('#isFreeSwitch').change(function() {
            if($(this).is(':checked')) {
                $('#coinDetails').hide();
            } else {
                $('#coinDetails').show();
            }
        });

        // Trigger change on load if old value exists
        if(!$('#isFreeSwitch').is(':checked')) {
            $('#coinDetails').show();
        }

        // Handle file preview
        $('#animationFile').change(function(e){
            var file = e.target.files[0];
            if(file && file.name.endsWith('.json')) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var jsonContent = e.target.result;
                    var playerHtml = '<lottie-player src="' + jsonContent + '" background="transparent" speed="1" style="width: 200px; height: 200px;" loop autoplay></lottie-player>';
                    
                    // We can't directly put raw json text into src for lottie-player usually, it needs a URL.
                    // But lottie-player supports Data URLs!
                    var dataUrl = 'data:application/json;base64,' + btoa(unescape(encodeURIComponent(jsonContent)));
                    var safePlayerHtml = '<lottie-player src="' + dataUrl + '" background="transparent" speed="1" style="width: 200px; height: 200px;" loop autoplay></lottie-player>';
                    
                    $('#lottiePreview').html(safePlayerHtml);
                    $('#previewContainer').show();
                };
                reader.readAsText(file);
            } else {
                $('#previewContainer').hide();
            }
        });

        // Audio Preview
        $('#audioInput').change(function(e){
            var file = e.target.files[0];
            if(file) {
                var objectUrl = URL.createObjectURL(file);
                $('#audioPreview').attr('src', objectUrl);
                $('#audioPreviewContainer').show();
            } else {
                $('#audioPreviewContainer').hide();
                $('#audioPreview').attr('src', '');
            }
        });
    });
</script>
@endsection
