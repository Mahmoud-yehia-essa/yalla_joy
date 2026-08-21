@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">تعديل وسيلة المساعدة</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('all.game.helper') }}">وسائل المساعدة</a></li>
                    <li class="breadcrumb-item active" aria-current="page">تعديل: {{ $gameHelper->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container">
        <div class="main-body">
            <div class="row">
                <div class="col-lg-10">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                                </div>
                            @endif

                            <form method="post" action="{{ route('update.game.helper') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{ $gameHelper->id }}" />

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0 font-weight-bold">اسم وسيلة المساعدة <span class="text-danger">*</span></h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="name" class="form-control" value="{{ old('name', $gameHelper->name) }}" required />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">الاسم بالإنجليزية</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="name_en" dir="ltr" class="form-control" value="{{ old('name_en', $gameHelper->name_en) }}" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">الوصف (يظهر عند الضغط على i)</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <textarea name="description" rows="3" class="form-control">{{ old('description', $gameHelper->description) }}</textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">توقيت الاستخدام <i class="fa-solid fa-lock text-muted" title="مغلق"></i></h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <div class="mt-1">
                                            @if($gameHelper->use_before_question)
                                                <span class="badge bg-info text-dark p-2"><i class="fa-solid fa-clock me-1"></i> تستخدم قبل اختيار السؤال</span>
                                            @else
                                                <span class="badge bg-light text-dark border p-2"><i class="fa-solid fa-clock me-1"></i> تستخدم أثناء السؤال</span>
                                            @endif
                                            <div class="mt-1"><small class="text-muted"><i class="fa-solid fa-lock"></i> توقيت استخدام الوسيلة ثابت برمجياً داخل اللعبة ولا يمكن تغييره.</small></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">الترتيب</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="number" id="order_num_input" name="order_num" class="form-control" value="{{ old('order_num', $gameHelper->order_num) }}" style="width: 130px;" min="0" />
                                            <span class="badge bg-secondary">الترتيب الحالي: {{ $gameHelper->order_num ?? 0 }}</span>
                                        </div>

                                        <!-- صندوق تنبيه عند تشابه الترتيب -->
                                        <div id="order_conflict_box" class="alert alert-warning mt-3 p-3 shadow-sm border-0 d-none" style="border-radius: 10px; background-color: #fff3cd; border-right: 4px solid #ffc107 !important;">
                                            <div class="d-flex align-items-start">
                                                <i class="fa-solid fa-triangle-exclamation fs-4 me-2 mt-1 text-warning"></i>
                                                <div class="flex-grow-1">
                                                    <div id="order_conflict_message" class="text-dark fw-bold mb-2"></div>
                                                    <div class="form-check form-switch mt-2">
                                                        <input class="form-check-input" type="checkbox" name="swap_order" id="swapOrderCheck" value="1" checked>
                                                        <label class="form-check-label text-dark fw-bold" for="swapOrderCheck" style="cursor: pointer;">
                                                            🔄 تبديل الترتيب تلقائياً (ستحصل الوسيلة الأخرى على الترتيب السابق: <span class="badge bg-dark">{{ $gameHelper->order_num ?? 0 }}</span>)
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">تغيير الأيقونة / الصورة</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="file" name="photo" class="form-control" id="image" accept="image/*" />
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-sm-3"><h6 class="mb-0">الأيقونة الحالية</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <div class="p-2 border rounded d-inline-block bg-light">
                                            <img id="showImage" src="{{ $gameHelper->photo ? asset($gameHelper->photo) : url('upload/no_image.jpg') }}" style="width:100px; height:100px; object-fit: contain;">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="submit" class="btn btn-primary px-4" value="حفظ التعديلات" />
                                        <a href="{{ route('all.game.helper') }}" class="btn btn-outline-secondary px-4 ms-2">إلغاء</a>
                                    </div>
                                </div>
                            </form>

                            <script>
                                $(document).ready(function(){
                                    // معاينة الصورة
                                    $('#image').change(function(e){
                                        var reader = new FileReader();
                                        reader.onload = function(e){
                                            $('#showImage').attr('src', e.target.result);
                                        }
                                        if (e.target.files[0]) {
                                            reader.readAsDataURL(e.target.files[0]);
                                        }
                                    });

                                    // فحص تضارب الترتيب مع وسائل المساعدة الأخرى
                                    const otherHelpers = @json($allHelpers ?? []);
                                    const initialOrder = parseInt("{{ $gameHelper->order_num ?? 0 }}");

                                    function checkOrderConflict() {
                                        const currentVal = $('#order_num_input').val().trim();
                                        if (currentVal === '') {
                                            $('#order_conflict_box').addClass('d-none');
                                            return;
                                        }

                                        const newOrder = parseInt(currentVal);
                                        if (isNaN(newOrder) || newOrder === initialOrder) {
                                            $('#order_conflict_box').addClass('d-none');
                                            return;
                                        }

                                        const conflicting = otherHelpers.find(h => parseInt(h.order_num) === newOrder);
                                        if (conflicting) {
                                            $('#order_conflict_message').html(
                                                `⚠️ تنبيه: توجد وسيلة مساعدة أخرى بنفس الترتيب (<strong>${newOrder}</strong>) وهي: <span class="text-primary font-weight-bold">"${conflicting.name}"</span>.`
                                            );
                                            $('#order_conflict_box').removeClass('d-none');
                                        } else {
                                            $('#order_conflict_box').addClass('d-none');
                                        }
                                    }

                                    $('#order_num_input').on('input change keyup', checkOrderConflict);
                                    checkOrderConflict();
                                });
                            </script>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
