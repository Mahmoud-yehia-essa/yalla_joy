@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">إدارة الـ QR Code</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('all.qr.code') }}"><i class="bx bx-qr-scan"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">تعديل الـ QR Code</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="{{ route('all.qr.code') }}" class="btn btn-outline-secondary px-3">
            <i class="fa-solid fa-arrow-right me-1"></i> العودة للقائمة
        </a>
    </div>
</div>
<!--end breadcrumb-->

<div class="row">
    <div class="col-xl-8 col-lg-10 mx-auto">
        <div class="card border-top border-0 border-4 border-info shadow-sm">
            <div class="card-body p-4 p-md-5">
                <div class="card-title d-flex align-items-center mb-4">
                    <div><i class="bx bx-edit-alt me-2 font-24 text-info"></i></div>
                    <div>
                        <h4 class="mb-0 text-info fw-bold">تعديل بيانات الـ QR Code ({{ $qrCode->code }})</h4>
                        <p class="text-muted small mb-0 mt-1">يمكنك تحديث العنوان، نص العرض، أو استبدال الصورة/الفيديو المرفق</p>
                    </div>
                </div>
                <hr/>

                @if ($errors->any())
                    <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show py-2">
                        <div class="d-flex align-items-center">
                            <div class="font-35 text-white"><i class='bx bxs-message-square-x'></i></div>
                            <div class="ms-3">
                                <h6 class="mb-0 text-white fw-bold">يرجى تصحيح الأخطاء التالية:</h6>
                                <ul class="text-white mb-0 mt-1" style="font-size: 13px;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('update.qr.code', $qrCode->id) }}" enctype="multipart/form-data" id="qrCodeEditForm">
                    @csrf

                    <!-- Title -->
                    <div class="mb-4">
                        <label class="form-label fw-bold"><i class="fa-solid fa-heading text-primary me-1"></i> العنوان / السؤال <span class="text-muted fw-normal">(اختياري)</span></label>
                        <input type="text" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror" placeholder="السؤال مثلا" value="{{ old('title', $qrCode->title) }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Text Content -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-bold mb-0">
                                <i class="fa-solid fa-paragraph text-primary me-1"></i> النص المراد ظهوره في صفحة الرابط <span class="text-danger">*</span>
                            </label>
                            <span class="badge bg-light text-dark border" id="charCounter">0 حرف</span>
                        </div>
                        <textarea name="text_content" id="textContentInput" rows="6" class="form-control @error('text_content') is-invalid @enderror" required>{{ old('text_content', $qrCode->text_content) }}</textarea>
                        @error('text_content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Media Type Selector -->
                    @php
                        $currentMediaType = old('media_type', $qrCode->media_type);
                    @endphp
                    <div class="mb-4">
                        <label class="form-label fw-bold d-block"><i class="fa-solid fa-photo-film text-primary me-1"></i> نوع المرفق أسفل النص <span class="text-danger">*</span></label>
                        
                        <div class="row g-3 mt-1">
                            <div class="col-md-4">
                                <label class="media-type-card w-100 p-3 rounded-3 border text-center cursor-pointer transition-all {{ $currentMediaType === 'none' ? 'active-media-type' : '' }}" for="media_type_none">
                                    <input type="radio" name="media_type" id="media_type_none" value="none" class="d-none" {{ $currentMediaType === 'none' ? 'checked' : '' }}>
                                    <div class="icon-wrap mb-2 font-30 text-secondary"><i class="fa-solid fa-font"></i></div>
                                    <div class="fw-bold">نص فقط</div>
                                    <div class="text-muted small">بدون مرفقات</div>
                                </label>
                            </div>

                            <div class="col-md-4">
                                <label class="media-type-card w-100 p-3 rounded-3 border text-center cursor-pointer transition-all {{ $currentMediaType === 'image' ? 'active-media-type' : '' }}" for="media_type_image">
                                    <input type="radio" name="media_type" id="media_type_image" value="image" class="d-none" {{ $currentMediaType === 'image' ? 'checked' : '' }}>
                                    <div class="icon-wrap mb-2 font-30 text-success"><i class="fa-solid fa-image"></i></div>
                                    <div class="fw-bold">إرفاق صورة</div>
                                    <div class="text-muted small">JPEG, PNG, WEBP, GIF</div>
                                </label>
                            </div>

                            <div class="col-md-4">
                                <label class="media-type-card w-100 p-3 rounded-3 border text-center cursor-pointer transition-all {{ $currentMediaType === 'video' ? 'active-media-type' : '' }}" for="media_type_video">
                                    <input type="radio" name="media_type" id="media_type_video" value="video" class="d-none" {{ $currentMediaType === 'video' ? 'checked' : '' }}>
                                    <div class="icon-wrap mb-2 font-30 text-danger"><i class="fa-solid fa-video"></i></div>
                                    <div class="fw-bold">إرفاق فيديو</div>
                                    <div class="text-muted small">MP4, WEBM, MOV</div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Image Upload Section -->
                    <div id="imageUploadSection" class="mb-4 p-3 bg-light rounded-3 border {{ $currentMediaType === 'image' ? '' : 'd-none' }}">
                        <label class="form-label fw-bold"><i class="fa-solid fa-upload text-success me-1"></i> تعديل / تغيير الصورة المرفقة</label>
                        <input type="file" name="image_file" id="imageFileInput" class="form-control @error('image_file') is-invalid @enderror" accept="image/*">
                        <div class="form-text text-muted">اترك هذا الحقل فارغاً إذا كنت لا ترغب بتغيير الصورة الحالية.</div>

                        <!-- Current / Preview Image Box -->
                        <div id="imagePreviewContainer" class="mt-3 text-center {{ ($qrCode->isImage()) ? '' : 'd-none' }}">
                            <div class="position-relative d-inline-block">
                                <img id="imagePreview" src="{{ $qrCode->isImage() ? asset($qrCode->media_path) : '#' }}" alt="معاينة الصورة" class="img-thumbnail shadow-sm rounded-3" style="max-height: 250px; max-width: 100%; object-fit: contain;">
                            </div>
                        </div>
                    </div>

                    <!-- Video Upload Section -->
                    <div id="videoUploadSection" class="mb-4 p-3 bg-light rounded-3 border {{ $currentMediaType === 'video' ? '' : 'd-none' }}">
                        <label class="form-label fw-bold"><i class="fa-solid fa-upload text-danger me-1"></i> تعديل / تغيير الفيديو المرفق</label>
                        <input type="file" name="video_file" id="videoFileInput" class="form-control @error('video_file') is-invalid @enderror" accept="video/mp4,video/webm,video/ogg,video/quicktime">
                        <div class="form-text text-muted">اترك هذا الحقل فارغاً إذا كنت لا ترغب بتغيير الفيديو الحالي.</div>

                        <!-- Current / Preview Video Box -->
                        <div id="videoPreviewContainer" class="mt-3 text-center {{ ($qrCode->isVideo()) ? '' : 'd-none' }}">
                            <div class="position-relative d-inline-block w-100" style="max-width: 450px;">
                                <video id="videoPreview" controls class="w-100 rounded-3 shadow-sm" style="max-height: 280px; background: #000;" src="{{ $qrCode->isVideo() ? asset($qrCode->media_path) : '' }}"></video>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label class="form-label fw-bold"><i class="fa-solid fa-toggle-on text-primary me-1"></i> حالة الـ QR Code</label>
                        <div class="form-check form-switch form-check-lg">
                            <input class="form-check-input" type="checkbox" name="status" value="active" id="statusSwitch" {{ old('status', $qrCode->status) === 'active' ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="statusSwitch" id="statusLabel">
                                {{ old('status', $qrCode->status) === 'active' ? 'مفعل (يمكن للمستخدمين مسح الرمز وعرض الصفحة)' : 'معطل (الصفحة ستكون غير متاحة للزوار مؤقتاً)' }}
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('all.qr.code') }}" class="btn btn-secondary px-4">إلغاء</a>
                        <button type="submit" class="btn btn-info text-white px-5 fw-bold" id="submitBtn">
                            <i class="fa-solid fa-check me-1"></i> حفظ التعديلات
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<style>
    .cursor-pointer {
        cursor: pointer;
    }
    .media-type-card {
        background-color: #fff;
        border: 2px solid #e2e8f0;
        transition: all 0.25s ease-in-out;
    }
    .media-type-card:hover {
        border-color: #696cff;
        background-color: #f8f9ff;
        transform: translateY(-2px);
    }
    .media-type-card.active-media-type {
        border-color: #696cff;
        background-color: #f0f2ff;
        box-shadow: 0 4px 12px rgba(105, 108, 255, 0.15);
    }
    .media-type-card.active-media-type .icon-wrap {
        transform: scale(1.1);
    }
    .transition-all {
        transition: all 0.25s ease;
    }
</style>

<script>
    $(document).ready(function() {
        // Character counter for text content
        function updateCharCounter() {
            const count = $('#textContentInput').val().length;
            $('#charCounter').text(count + ' حرف');
        }
        $('#textContentInput').on('input', updateCharCounter);
        updateCharCounter();

        // Switch Media Type Cards
        $('input[name="media_type"]').on('change', function() {
            $('.media-type-card').removeClass('active-media-type');
            $(this).closest('.media-type-card').addClass('active-media-type');

            const val = $(this).val();
            if (val === 'image') {
                $('#imageUploadSection').removeClass('d-none');
                $('#videoUploadSection').addClass('d-none');
            } else if (val === 'video') {
                $('#videoUploadSection').removeClass('d-none');
                $('#imageUploadSection').addClass('d-none');
            } else {
                $('#imageUploadSection').addClass('d-none');
                $('#videoUploadSection').addClass('d-none');
            }
        });

        // Image Live Preview
        $('#imageFileInput').on('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    $('#imagePreview').attr('src', event.target.result);
                    $('#imagePreviewContainer').removeClass('d-none');
                }
                reader.readAsDataURL(file);
            }
        });

        // Video Live Preview
        $('#videoFileInput').on('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const videoURL = URL.createObjectURL(file);
                const videoEl = document.getElementById('videoPreview');
                videoEl.src = videoURL;
                $('#videoPreviewContainer').removeClass('d-none');
            }
        });

        // Status switch label
        $('#statusSwitch').on('change', function() {
            if ($(this).is(':checked')) {
                $('#statusLabel').text('مفعل (يمكن للمستخدمين مسح الرمز وعرض الصفحة)');
            } else {
                $('#statusLabel').text('معطل (الصفحة ستكون غير متاحة للزوار مؤقتاً)');
            }
        });

        // Form Submit loading state
        $('#qrCodeEditForm').on('submit', function() {
            $('#submitBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> جاري حفظ التعديلات...');
        });
    });
</script>
@endsection
