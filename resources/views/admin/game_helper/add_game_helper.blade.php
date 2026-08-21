@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">إضافة وسيلة مساعدة جديدة</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('all.game.helper') }}">وسائل المساعدة</a></li>
                    <li class="breadcrumb-item active" aria-current="page">إضافة جديدة</li>
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

                            <form method="post" action="{{ route('store.game.helper') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0 font-weight-bold">اسم وسيلة المساعدة <span class="text-danger">*</span></h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="name" class="form-control" placeholder="مثال: وهقة، إكلها، دوبلها..." value="{{ old('name') }}" required />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">الاسم بالإنجليزية</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="name_en" dir="ltr" class="form-control" placeholder="e.g. Wahqa, Double, Skip..." value="{{ old('name_en') }}" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">المفتاح البرمجي (Tool Key)</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="tool_key" dir="ltr" class="form-control font-monospace" placeholder="e.g. wahqa, akilha, eqlebha, dablha, dayeqha, awelha, sakrha" value="{{ old('tool_key') }}" />
                                        <small class="text-muted">المفتاح الذي يحدد تصرف الوسيلة في اللعبة (wahqa, akilha, eqlebha, dablha, dayeqha, awelha, sakrha, darbak_khodr, checkmate)</small>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">الوصف (يظهر عند الضغط على i)</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <textarea name="description" rows="3" class="form-control" placeholder="شرح طريقة عمل وسيلة المساعدة الذي يظهر للمستخدم عند الضغط على أيقونة (i)...">{{ old('description') }}</textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">ملاحظة أسفل العنوان</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="note" class="form-control" placeholder="مثال: ملاحظة : تستخدم قبل اختيار السؤال" value="{{ old('note') }}" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">توقيت الاستخدام</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <div class="form-check form-switch mt-1">
                                            <input class="form-check-input" type="checkbox" name="use_before_question" id="useBeforeQuestion" value="1" {{ old('use_before_question') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="useBeforeQuestion">تستخدم قبل اختيار السؤال</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">الترتيب</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="number" name="order_num" class="form-control" value="{{ old('order_num', 0) }}" style="width: 120px;" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">أيقونة / صورة الوسيلة <span class="text-danger">*</span></h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="file" name="photo" class="form-control" id="image" accept="image/*" required />
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-sm-3"><h6 class="mb-0">معاينة الأيقونة</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <div class="p-2 border rounded d-inline-block bg-light">
                                            <img id="showImage" src="{{ url('upload/no_image.jpg') }}" style="width:100px; height:100px; object-fit: contain;">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="submit" class="btn btn-primary px-4" value="حفظ وسيلة المساعدة" />
                                        <a href="{{ route('all.game.helper') }}" class="btn btn-outline-secondary px-4 ms-2">إلغاء</a>
                                    </div>
                                </div>
                            </form>

                            <script>
                                $(document).ready(function(){
                                    $('#image').change(function(e){
                                        var reader = new FileReader();
                                        reader.onload = function(e){
                                            $('#showImage').attr('src', e.target.result);
                                        }
                                        if (e.target.files[0]) {
                                            reader.readAsDataURL(e.target.files[0]);
                                        }
                                    });
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
