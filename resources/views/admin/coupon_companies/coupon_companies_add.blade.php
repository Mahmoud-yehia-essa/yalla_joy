@extends('admin.master_admin')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">إضافة كوبون شركة جديد</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">إضافة كوبون</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="container">
        <div class="main-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="myForm" method="post" action="{{ route('store.coupon_companies') }}">
                                @csrf

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">اسم الكوبون (بالعربي)</h6>
                                    </div>
                                    <div class="form-group col-sm-9 text-secondary">
                                        <input type="text" name="coupon_name" class="form-control" value="{{ old('coupon_name') }}" />
                                        @error('coupon_name')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">اسم الكوبون (EN)</h6>
                                    </div>
                                    <div class="form-group col-sm-9 text-secondary">
                                        <input type="text" name="coupon_name_en" class="form-control" value="{{ old('coupon_name_en') }}" />
                                        @error('coupon_name_en')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">الشركة الراعية</h6>
                                    </div>
                                    <div class="form-group col-sm-9 text-secondary">
                                        <select name="sponsor_id" class="form-select">
                                            <option value="">اختر الشركة</option>
                                            @foreach($sponsors as $sponsor)
                                            <option value="{{ $sponsor->id }}" {{ old('sponsor_id') == $sponsor->id ? 'selected' : '' }}>{{ $sponsor->title }}</option>
                                            @endforeach
                                        </select>
                                        @error('sponsor_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">تاريخ ووقت الانتهاء</h6>
                                    </div>
                                    <div class="form-group col-sm-9 text-secondary">
                                        <input type="datetime-local" name="valid_until" class="form-control" value="{{ old('valid_until') }}" />
                                        @error('valid_until')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">عدد الكوبونات المتاحة</h6>
                                    </div>
                                    <div class="form-group col-sm-9 text-secondary">
                                        <input type="number" name="coupons_count" class="form-control" min="1" placeholder="أدخل عدد الكوبونات الكلي المتاح (أتركه فارغاً لعدد غير محدود)" value="{{ old('coupons_count') }}" />
                                        <small class="text-muted">إذا تركت هذا الحقل فارغاً، سيكون عدد الكوبونات المتاحة غير محدود.</small>
                                        @error('coupons_count')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">وصف الكوبون (بالعربي)</h6>
                                    </div>
                                    <div class="form-group col-sm-9 text-secondary">
                                        <textarea name="coupon_description" class="form-control" rows="3">{{ old('coupon_description') }}</textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">وصف الكوبون (EN)</h6>
                                    </div>
                                    <div class="form-group col-sm-9 text-secondary">
                                        <textarea name="coupon_description_en" class="form-control" rows="3">{{ old('coupon_description_en') }}</textarea>
                                    </div>
                                </div>

                                <hr>
                                <h5 class="mb-3">سعر استبدال الكوبون</h5>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">نوع العملة المطلوبة</h6>
                                    </div>
                                    <div class="form-group col-sm-9 text-secondary">
                                        <select name="game_coin_id" class="form-select">
                                            <option value="">لا يتطلب عملات</option>
                                            @foreach($gameCoins as $coin)
                                            <option value="{{ $coin->id }}" {{ old('game_coin_id') == $coin->id ? 'selected' : '' }}>{{ $coin->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">عدد العملات المطلوبة</h6>
                                    </div>
                                    <div class="form-group col-sm-9 text-secondary">
                                        <input type="number" name="game_coins_count" class="form-control" value="{{ old('game_coins_count', 0) }}" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">كوبون قشط؟</h6>
                                    </div>
                                    <div class="form-group col-sm-9 text-secondary">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_scratch_coupon" id="is_scratch_coupon" value="1">
                                            <label class="form-check-label" for="is_scratch_coupon">تفعيل ككوبون قشط</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">كوبون خاص؟</h6>
                                    </div>
                                    <div class="form-group col-sm-9 text-secondary">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_special_coupon" id="is_special_coupon" value="1" {{ old('is_special_coupon') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_special_coupon">تفعيل ككوبون خاص</label>
                                        </div>
                                    </div>
                                </div>

@php
$defaultEmailTemplate = '<div style="text-align: center;">
    <h2 style="color: #ffd700; font-size: 24px; margin-bottom: 12px;">مبروك! قسيمتك المميزة جاهزة 🌟</h2>
    <p style="font-size: 18px; color: #ffffff; margin-bottom: 15px; font-weight: bold;">مرحباً يا {user_name}،</p>
    <p style="font-size: 15px; color: #a4b3d6; line-height: 1.7; margin-bottom: 25px;">
        لقد قمت بنجاح باستبدال عملاتك الافتراضية للحصول على القسيمة المميزة التالية من <b>{sponsor_name}</b>:
    </p>

    <!-- بطاقة القسيمة -->
    <div style="background: linear-gradient(145deg, #1a2a47, #101c33); border: 2px dashed #daa520; border-radius: 16px; padding: 25px; margin: 25px auto; max-width: 480px;">
        <h3 style="color: #ffd700; font-size: 20px; margin: 0 0 10px 0;">{coupon_name}</h3>
        
        <div style="background-color: #0c1424; border: 1px solid rgba(218, 165, 32, 0.4); border-radius: 10px; padding: 15px; margin: 15px auto; display: inline-block; min-width: 220px;">
            <div style="font-size: 12px; color: #a4b3d6; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px;">رمز القسيمة الخاص بك</div>
            <div style="font-size: 28px; font-weight: 900; color: #ffd700; letter-spacing: 2px; margin: 0;">{coupon_code}</div>
        </div>

        <div style="font-size: 13px; color: #a4b3d6; margin-top: 15px;">
            صلاحية القسيمة حتى: <span style="color: #ffd700; font-weight: bold;">{valid_until}</span>
        </div>
    </div>

    <!-- ملاحظات الاستفادة -->
    <div style="background-color: rgba(218, 165, 32, 0.1); border-right: 4px solid #daa520; border-radius: 6px; padding: 15px; margin-top: 25px; text-align: center; color: #ffd700; font-size: 14px; font-weight: bold;">
        💡 للاستفادة من الكوبون الرجاء زيارة مقر الإدارة لتعبئة استمارة / كوبون الجائزة
    </div>
</div>';
@endphp

                                <div class="row mb-3" id="special_coupon_message_container" style="display: none;">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">محتوى البريد الإلكتروني (HTML)</h6>
                                        <small class="text-muted d-block mt-1">يصل هذا المحتوى كاملاً للمستخدم في البريد (بين الهيدر والفوتر). يمكنك تعديل النصوص أو إضافة صور أو فيديو أو إعادة تصميم القالب بالكامل.</small>
                                    </div>
                                    <div class="form-group col-sm-9 text-secondary">
                                        <div class="alert alert-info border-0 bg-info alert-dismissible fade show py-2 text-white">
                                            <div class="d-flex align-items-center">
                                                <div class="font-35 text-white"><i class="bx bx-info-circle"></i></div>
                                                <div class="ms-3">
                                                    <h6 class="mb-0 text-white font-weight-bold">المتغيرات التلقائية المتاحة:</h6>
                                                    <small class="text-white">يمكنك استخدام المتغيرات التالية وسيتم استبدالها تلقائياً عند الإرسال: <code>{user_name}</code> لاسم المستخدم، <code>{coupon_code}</code> لرمز القسيمة، <code>{sponsor_name}</code> لاسم الشركة، <code>{coupon_name}</code> لاسم الكوبون، <code>{valid_until}</code> لتاريخ الصلاحية.</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted small">محرر البريد الإلكتروني المتكامل:</span>
                                            <button type="button" class="btn btn-sm btn-outline-primary" id="btnLoadDefaultTemplate">
                                                <i class="bx bx-refresh"></i> استعادة القالب النموذجي الافتراضي
                                            </button>
                                        </div>

                                        <div id="default_template_holder" style="display:none;">{!! $defaultEmailTemplate !!}</div>

                                        <textarea id="special_coupon_message" name="special_coupon_message" class="form-control" rows="10">{{ old('special_coupon_message', $defaultEmailTemplate) }}</textarea>
                                        @error('special_coupon_message')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="submit" class="btn btn-primary px-4" value="حفظ الكوبون" />
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
    $(document).ready(function (){
        let specialCouponEditorInitialized = false;

        function initSpecialCouponEditor() {
            if (!specialCouponEditorInitialized && $('#special_coupon_message').length) {
                tinymce.init({
                    selector: 'textarea#special_coupon_message',
                    height: 380,
                    directionality: 'rtl',
                    plugins: [
                        "advlist autolink link image lists charmap print preview hr anchor pagebreak",
                        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                        "save table directionality emoticons paste textcolor"
                    ],
                    toolbar: "undo redo | formatselect fontselect fontsizeselect | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | ltr rtl | bullist numlist outdent indent | link image media | table hr | removeformat | code fullscreen",
                    content_style: "body { font-family: 'Segoe UI', Tahoma, Arial, sans-serif; font-size: 15px; direction: rtl; text-align: right; } img { max-width: 100%; height: auto; }",
                    relative_urls: false,
                    remove_script_host: false,
                    convert_urls: false,
                    images_upload_handler: function (blobInfo, success, failure) {
                        let xhr = new XMLHttpRequest();
                        xhr.withCredentials = true;
                        xhr.open('POST', "{{ route('coupon_companies.upload_media') }}");
                        xhr.setRequestHeader('X-CSRF-TOKEN', "{{ csrf_token() }}");

                        xhr.onload = function() {
                            if (xhr.status != 200) {
                                failure('HTTP Error: ' + xhr.status);
                                return;
                            }
                            let json;
                            try {
                                json = JSON.parse(xhr.responseText);
                            } catch(e) {
                                failure('Invalid JSON: ' + xhr.responseText);
                                return;
                            }
                            if (!json || typeof json.location != 'string') {
                                failure('Invalid response from server');
                                return;
                            }
                            success(json.location);
                        };

                        xhr.onerror = function () {
                            failure('Image upload failed due to a network error.');
                        };

                        let formData = new FormData();
                        formData.append('file', blobInfo.blob(), blobInfo.filename());
                        xhr.send(formData);
                    },
                    setup: function (editor) {
                        editor.on('change keyup NodeChange', function () {
                            editor.save();
                        });
                    }
                });
                specialCouponEditorInitialized = true;
            }
        }

        function toggleSpecialCouponContainer() {
            if ($('#is_special_coupon').is(':checked')) {
                $('#special_coupon_message_container').show();
                initSpecialCouponEditor();
            } else {
                $('#special_coupon_message_container').hide();
            }
        }

        $('#is_special_coupon').on('change', function () {
            toggleSpecialCouponContainer();
        });

        // Initialize state on page load
        if ($('#is_special_coupon').is(':checked')) {
            toggleSpecialCouponContainer();
        }

        $('#btnLoadDefaultTemplate').on('click', function() {
            let defaultContent = $('#default_template_holder').html();
            if (typeof tinymce !== 'undefined' && tinymce.get('special_coupon_message')) {
                tinymce.get('special_coupon_message').setContent(defaultContent);
                tinymce.get('special_coupon_message').save();
            } else {
                $('#special_coupon_message').val(defaultContent);
            }
        });

        $('#myForm').on('submit', function() {
            if (typeof tinymce !== 'undefined') {
                tinymce.triggerSave();
            }
        });

        $('#myForm').validate({
            rules: {
                coupon_name: { required : true },
                sponsor_id: { required : true },
            },
            messages :{
                coupon_name: { required : 'الرجاء ادخال اسم الكوبون' },
                sponsor_id: { required : 'الرجاء اختيار الشركة' },
            },
            errorElement : 'span', 
            errorPlacement: function (error,element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight : function(element, errorClass, validClass){
                $(element).addClass('is-invalid');
            },
            unhighlight : function(element, errorClass, validClass){
                $(element).removeClass('is-invalid');
            },
        });
    });
</script>

@endsection
