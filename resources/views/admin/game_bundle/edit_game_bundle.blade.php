@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">تعديل الحزمة</div>
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

                            <form method="post" action="{{ route('update.game.bundle') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{ $bundle->id }}">
                                <input type="hidden" name="old_image" value="{{ $bundle->photo }}">

                                <!-- اسم الحزمة -->
                                <div class="row mb-3">
                                    <div class="col-sm-2"><h6 class="mb-0">اسم الحزمة عربي</h6></div>
                                    <div class="col-sm-10"><input type="text" name="name" class="form-control" value="{{ $bundle->name }}" /></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-2"><h6 class="mb-0">اسم الحزمة انجليزي</h6></div>
                                    <div class="col-sm-10"><input type="text" name="name_en" dir="ltr" class="form-control" value="{{ $bundle->name_en }}" /></div>
                                </div>

                                <!-- الوصف -->
                                <div class="row mb-3">
                                    <div class="col-sm-2"><h6 class="mb-0">الوصف عربي</h6></div>
                                    <div class="col-sm-10"><textarea name="description" class="form-control">{{ $bundle->description }}</textarea></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-2"><h6 class="mb-0">الوصف انجليزي</h6></div>
                                    <div class="col-sm-10"><textarea name="description_en" dir="ltr" class="form-control">{{ $bundle->description_en }}</textarea></div>
                                </div>

                                <!-- التلميح -->
                                <div class="row mb-3">
                                    <div class="col-sm-2"><h6 class="mb-0">تلميح عربي</h6></div>
                                    <div class="col-sm-10"><input type="text" name="hint" class="form-control" value="{{ $bundle->hint }}" /></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-2"><h6 class="mb-0">تلميح انجليزي</h6></div>
                                    <div class="col-sm-10"><input type="text" name="hint_en" dir="ltr" class="form-control" value="{{ $bundle->hint_en }}" /></div>
                                </div>

                                <!-- نوع الحزمة -->
                                <div class="row mb-3">
                                    <div class="col-sm-2"><h6 class="mb-0">نوع الحزمة</h6></div>
                                    <div class="col-sm-10">
                                        <select name="bundle_type" class="form-select">
                                            <option value="start_bundle" {{ $bundle->bundle_type == 'start_bundle' ? 'selected' : '' }}>حزمة البداية</option>
                                            <option value="daily_bundle" {{ $bundle->bundle_type == 'daily_bundle' ? 'selected' : '' }}>حزمة يومية</option>
                                            <option value="ranking_bundle" {{ $bundle->bundle_type == 'ranking_bundle' ? 'selected' : '' }}>حزمة الرتب عند الوصول الى رتبة معينة</option>
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
                                                <option value="{{ $itme->id }}" {{ $bundle->ranking_id == $itme->id ? 'selected' : '' }} >
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
                                        <img id="showImage" src="{{ url($bundle->photo ?? 'upload/no_image.jpg') }}" style="width:100px; height:100px;">
                                    </div>
                                </div>

                                <!-- جدول العملات -->
                                <h5>العملات</h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>اختيار</th>
                                            <th>اسم العملة</th>
                                            <th>عدد العملات المطلوبة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($gameCoins as $coin)
                                            @php $selectedCoin = $bundle->bundleCoins->firstWhere('game_coin_id', $coin->id); @endphp
                                            <tr>
                                                <td><input type="checkbox" name="coins[{{ $coin->id }}][id]" value="{{ $coin->id }}" class="coin-checkbox" {{ $selectedCoin ? 'checked' : '' }}></td>
                                                <td>{{ $coin->name }} / {{ $coin->name_en }}</td>
                                                <td><input type="number" name="coins[{{ $coin->id }}][number]" class="form-control coin-number" min="0" value="{{ $selectedCoin->number ?? '' }}"></td>
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
                                            <th>عدد العناصر المطلوبة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($gameItems as $item)
                                            @php $selectedItem = $bundle->bundleItems->firstWhere('game_item_id', $item->id); @endphp
                                            <tr>
                                                <td><input type="checkbox" name="items[{{ $item->id }}][id]" value="{{ $item->id }}" class="item-checkbox" {{ $selectedItem ? 'checked' : '' }}></td>
                                                <td>{{ $item->name }} / {{ $item->name_en }}</td>
                                                <td><input type="number" name="items[{{ $item->id }}][number]" class="form-control item-number" min="0" value="{{ $selectedItem->number ?? '' }}"></td>
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
                                            <th>عدد العناصر المطلوبة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($gameHelpers as $helper)
                                            @php $selectedHelper = $bundle->bundleHelpers->firstWhere('game_helper_id', $helper->id); @endphp
                                            <tr>
                                                <td><input type="checkbox" name="helpers[{{ $helper->id }}][id]" value="{{ $helper->id }}" class="helper-checkbox" {{ $selectedHelper ? 'checked' : '' }}></td>
                                                <td>{{ $helper->name }} / {{ $helper->name_en }}</td>
                                                <td><input type="number" name="helpers[{{ $helper->id }}][number]" class="form-control helper-number" min="0" value="{{ $selectedHelper->number ?? '' }}"></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="row mt-3">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-10">
                                        <input type="submit" class="btn btn-primary px-4" value="تعديل الحزمة" />
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
                                        reader.readAsDataURL(e.target.files[0]);
                                    });

                                    $('.coin-number').on('input', function(){
                                        $(this).closest('tr').find('.coin-checkbox').prop('checked', $(this).val() > 0);
                                    });
                                    $('.item-number').on('input', function(){
                                        $(this).closest('tr').find('.item-checkbox').prop('checked', $(this).val() > 0);
                                    });
                                    $('.helper-number').on('input', function(){
                                        $(this).closest('tr').find('.helper-checkbox').prop('checked', $(this).val() > 0);
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
