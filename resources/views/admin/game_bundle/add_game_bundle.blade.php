@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">إضافة حزمة لعبة جديدة</div>
    </div>

    <div class="container">
        <div class="main-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                                </div>
                            @endif

                            <form method="post" action="{{ route('store.game.bundle') }}" enctype="multipart/form-data">
                                @csrf

                                <!-- اسم الحزمة -->
                                <div class="row mb-3">
                                    <div class="col-sm-2"><h6 class="mb-0">اسم الحزمة عربي</h6></div>
                                    <div class="col-sm-10"><input type="text" name="name" class="form-control" value="{{ old('name') }}" /></div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-2"><h6 class="mb-0">اسم الحزمة انجليزي</h6></div>
                                    <div class="col-sm-10"><input type="text" name="name_en" dir="ltr" class="form-control" value="{{ old('name_en') }}" /></div>
                                </div>

                                <!-- الوصف -->
                                <div class="row mb-3">
                                    <div class="col-sm-2"><h6 class="mb-0">الوصف عربي</h6></div>
                                    <div class="col-sm-10"><textarea name="description" class="form-control">{{ old('description') }}</textarea></div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-2"><h6 class="mb-0">الوصف انجليزي</h6></div>
                                    <div class="col-sm-10"><textarea name="description_en" dir="ltr" class="form-control">{{ old('description_en') }}</textarea></div>
                                </div>

                                <!-- التلميح -->
                                <div class="row mb-3">
                                    <div class="col-sm-2"><h6 class="mb-0">تلميح عربي</h6></div>
                                    <div class="col-sm-10"><input type="text" name="hint" class="form-control" value="{{ old('hint') }}" /></div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-2"><h6 class="mb-0">تلميح انجليزي</h6></div>
                                    <div class="col-sm-10"><input type="text" name="hint_en" dir="ltr" class="form-control" value="{{ old('hint_en') }}" /></div>
                                </div>

                                <!-- نوع الحزمة -->
                                <div class="row mb-3">
                                    <div class="col-sm-2"><h6 class="mb-0">نوع الحزمة</h6></div>
                                    <div class="col-sm-10">
                                        <select name="bundle_type" class="form-select">
                                            <option value="start_bundle">حزمة البداية</option>
                                            <option value="daily_bundle">حزمة يومية</option>
                                            <option value="ranking_bundle">حزمة الرتب عند الوصول الى رتبة معينة</option>
                                        </select>
                                    </div>
                                </div>

                                  <!-- نوع الرتبة -->
                                <div class="row mb-3">
                                    <div class="col-sm-2"><h6 class="mb-0">الرتبة المطلوبة</h6></div>
                                    <div class="col-sm-10">
                                        <select name="ranking_id" class="form-select">
                                            <option value="">بدون رتبة</option>
                                              @foreach($ranking as $itme)
                                                <option value="{{ $itme->id }}" {{ old('ranking_id') == $itme->id ? 'selected' : '' }}>
                                                    {{ $itme->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small>سوف تظهر عند اختيار نوع الحزمة الرتب</small>
                                    </div>
                                </div>

                                <!-- الصورة -->
                                <div class="row mb-3">
                                    <div class="col-sm-2"><h6 class="mb-0">الصورة</h6></div>
                                    <div class="col-sm-10"><input type="file" name="photo" class="form-control" id="image" /></div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-10">
                                        <img id="showImage" src="{{ url('upload/no_image.jpg') }}" style="width:100px; height:100px;">
                                    </div>
                                </div>

                                <!-- جدول العملات -->
                                <h5>العملات</h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>اختيار</th>
                                            <th>اسم العملة</th>
                                            <th>عدد العملات في الحزمة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($gameCoins as $coin)
                                            <tr>
                                                <td>
                                                    <!-- اسم جديد مرتبط بالـ id -->
                                                    <input type="checkbox" name="coins[{{ $coin->id }}][selected]" value="1" class="coin-checkbox">
                                                </td>
                                                <td>{{ $coin->name }} / {{ $coin->name_en }}</td>
                                                <td>
                                                    <input type="number" name="coins[{{ $coin->id }}][number]" class="form-control coin-number" min="0" value="{{ old('coins.' . $coin->id . '.number', '') }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <!-- جدول عناصر اللعبة -->
                                <h5>عناصر اللعبة</h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>اختيار</th>
                                            <th>اسم العنصر</th>
                                            <th>عدد العناصر في الحزمة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($gameItems as $item)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="items[{{ $item->id }}][selected]" value="1" class="item-checkbox">
                                                </td>
                                                <td>{{ $item->name }} / {{ $item->name_en }}</td>
                                                <td>
                                                    <input type="number" name="items[{{ $item->id }}][number]" class="form-control item-number" min="0" value="{{ old('items.' . $item->id . '.number', '') }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <!-- جدول عناصر المساعدة -->
                                <h5>عناصر المساعدة</h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>اختيار</th>
                                            <th>اسم العنصر</th>
                                            <th>عدد العناصر في الجزمة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($gameHelpers as $helper)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="helpers[{{ $helper->id }}][selected]" value="1" class="helper-checkbox">
                                                </td>
                                                <td>{{ $helper->name }} / {{ $helper->name_en }}</td>
                                                <td>
                                                    <input type="number" name="helpers[{{ $helper->id }}][number]" class="form-control helper-number" min="0" value="{{ old('helpers.' . $helper->id . '.number', '') }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="row mt-3">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-10">
                                        <input type="submit" class="btn btn-primary px-4" value="إضافة الحزمة" />
                                    </div>
                                </div>

                            </form>

                            <!-- Script Preview الصورة -->
                            <script>
                                $(document).ready(function(){
                                    $('#image').change(function(e){
                                        var reader = new FileReader();
                                        reader.onload = function(e){
                                            $('#showImage').attr('src', e.target.result);
                                        }
                                        reader.readAsDataURL(e.target.files[0]);
                                    });

                                    // عند إدخال رقم، شغل checkbox الخاص بنفس الصف إذا كان الرقم > 0
                                    $('.coin-number').on('input', function(){
                                        var val = parseInt($(this).val());
                                        $(this).closest('tr').find('.coin-checkbox').prop('checked', !isNaN(val) && val > 0);
                                    });
                                    $('.item-number').on('input', function(){
                                        var val = parseInt($(this).val());
                                        $(this).closest('tr').find('.item-checkbox').prop('checked', !isNaN(val) && val > 0);
                                    });
                                    $('.helper-number').on('input', function(){
                                        var val = parseInt($(this).val());
                                        $(this).closest('tr').find('.helper-checkbox').prop('checked', !isNaN(val) && val > 0);
                                    });

                                    // إذا شغل المستخدم الـ checkbox بدون رقم، اتركه؛ في السيرفر سنأخذ number = 0 إن لم يدخل رقم
                                    // يمكن أيضاً مسح الرقم لو ألغى الاختيار
                                    $('.coin-checkbox').on('change', function(){
                                        if(!$(this).is(':checked')){
                                            $(this).closest('tr').find('.coin-number').val('');
                                        }
                                    });
                                    $('.item-checkbox').on('change', function(){
                                        if(!$(this).is(':checked')){
                                            $(this).closest('tr').find('.item-number').val('');
                                        }
                                    });
                                    $('.helper-checkbox').on('change', function(){
                                        if(!$(this).is(':checked')){
                                            $(this).closest('tr').find('.helper-number').val('');
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
