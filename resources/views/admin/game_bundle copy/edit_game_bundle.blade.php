@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">تعديل حزمة اللعبة</div>
    </div>
    <!--end breadcrumb-->

    <div class="container">
        <div class="main-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <!-- Errors -->
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                                </div>
                            @endif

                            <form method="post" action="{{ route('update.game.bundle') }}" enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="id" value="{{ $bundle->id }}">
                                <input type="hidden" name="old_image" value="{{ $bundle->photo }}">

                                <!-- Arabic / English names -->
                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">اسم الحزمة</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="name" class="form-control" value="{{ $bundle->name }}" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">Bundle Name</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="name_en" dir="ltr" class="form-control" value="{{ $bundle->name_en }}" />
                                    </div>
                                </div>

                                <!-- Descriptions -->
                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">الوصف</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <textarea name="description" class="form-control">{{ $bundle->description }}</textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">Description</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <textarea name="description_en" dir="ltr" class="form-control">{{ $bundle->description_en }}</textarea>
                                    </div>
                                </div>

                                <!-- Hints -->
                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">تلميح</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <textarea name="hint" class="form-control">{{ $bundle->hint }}</textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">Hint</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <textarea name="hint_en" dir="ltr" class="form-control">{{ $bundle->hint_en }}</textarea>
                                    </div>
                                </div>

                                <!-- Bundle Type -->
                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">نوع الحزمة</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <select name="bundle_type" class="form-select">
                                            <option value="start" {{ $bundle->bundle_type == 'start' ? 'selected' : '' }}>حزمة البداية</option>
                                            <option value="daily" {{ $bundle->bundle_type == 'daily' ? 'selected' : '' }}>حزمة يومية</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Photo -->
                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">الصورة</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="file" name="photo" class="form-control" id="image" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <img id="showImage" src="{{ url($bundle->photo ?? 'upload/no_image.jpg') }}" style="width:100px; height:100px;">
                                    </div>
                                </div>

                                <hr>

                                <!-- Coins -->
                                <h5>العملات</h5>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>اختيار</th>
                                            <th>العملة</th>
                                            <th>العدد</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($coins as $coin)
                                            @php
                                                $bundleCoin = $bundle->bundleCoins->where('game_coin_id', $coin->id)->first();
                                            @endphp
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="coins_id[]" value="{{ $coin->id }}" {{ $bundleCoin ? 'checked' : '' }}>
                                                </td>
                                                <td>{{ $coin->name }}</td>
                                                <td>
                                                    <input type="number" name="coins_number[{{ $coin->id }}]" value="{{ $bundleCoin->number ?? 0 }}" class="form-control">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <!-- Items -->
                                <h5>عناصر اللعبة</h5>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>اختيار</th>
                                            <th>العنصر</th>
                                            <th>العدد</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $item)
                                            @php
                                                $bundleItem = $bundle->bundleItems->where('game_item_id', $item->id)->first();
                                            @endphp
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="items_id[]" value="{{ $item->id }}" {{ $bundleItem ? 'checked' : '' }}>
                                                </td>
                                                <td>{{ $item->name }}</td>
                                                <td>
                                                    <input type="number" name="items_number[{{ $item->id }}]" value="{{ $bundleItem->number ?? 0 }}" class="form-control">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <!-- Helpers -->
                                <h5>مساعدات اللعبة</h5>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>اختيار</th>
                                            <th>المساعدة</th>
                                            <th>العدد</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($helpers as $helper)
                                            @php
                                                $bundleHelper = $bundle->bundleHelpers->where('game_helper_id', $helper->id)->first();
                                            @endphp
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="helpers_id[]" value="{{ $helper->id }}" {{ $bundleHelper ? 'checked' : '' }}>
                                                </td>
                                                <td>{{ $helper->name }}</td>
                                                <td>
                                                    <input type="number" name="helpers_number[{{ $helper->id }}]" value="{{ $bundleHelper->number ?? 0 }}" class="form-control">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <hr>

                                <!-- Submit -->
                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="submit" class="btn btn-primary px-4" value="تعديل الحزمة" />
                                    </div>
                                </div>

                            </form>

                            <!-- Preview JS -->
                            <script>
                                $(document).ready(function(){
                                    $('#image').change(function(e){
                                        var reader = new FileReader();
                                        reader.onload = function(e){
                                            $('#showImage').attr('src', e.target.result);
                                        }
                                        reader.readAsDataURL(e.target.files[0]);
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
