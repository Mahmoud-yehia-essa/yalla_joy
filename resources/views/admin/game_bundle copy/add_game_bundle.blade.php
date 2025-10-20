@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">إضافة حزمة جديدة</div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                <form method="post" action="{{ route('store.game.bundle') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Name Arabic/English -->
                    <div class="row mb-3">
                        <div class="col-sm-3"><h6>اسم الحزمة</h6></div>
                        <div class="col-sm-9"><input type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3"><h6>Bundle Name</h6></div>
                        <div class="col-sm-9"><input type="text" name="name_en" class="form-control" value="{{ old('name_en') }}" required></div>
                    </div>

                    <!-- Description -->
                    <div class="row mb-3">
                        <div class="col-sm-3"><h6>وصف الحزمة</h6></div>
                        <div class="col-sm-9"><input type="text" name="description" class="form-control" value="{{ old('description') }}"></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3"><h6>Description</h6></div>
                        <div class="col-sm-9"><input type="text" name="description_en" class="form-control" value="{{ old('description_en') }}"></div>
                    </div>

                    <!-- Hint -->
                    <div class="row mb-3">
                        <div class="col-sm-3"><h6>تلميح</h6></div>
                        <div class="col-sm-9"><input type="text" name="hint" class="form-control" value="{{ old('hint') }}"></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3"><h6>Hint</h6></div>
                        <div class="col-sm-9"><input type="text" name="hint_en" class="form-control" value="{{ old('hint_en') }}"></div>
                    </div>

                    <!-- Bundle Type -->
                    <div class="row mb-3">
                        <div class="col-sm-3"><h6>نوع الحزمة</h6></div>
                        <div class="col-sm-9">
                            <select name="bundle_type" class="form-select" required>
                                <option value="starter">حزمة البداية</option>
                                <option value="daily">حزمة يومية</option>
                            </select>
                        </div>
                    </div>

                    <!-- Photo -->
                    <div class="row mb-3">
                        <div class="col-sm-3"><h6>صورة الحزمة</h6></div>
                        <div class="col-sm-9">
                            <input type="file" name="photo" class="form-control" id="image" required>
                            <img id="showImage" src="{{ url('upload/no_image.jpg') }}" style="width:100px; height:100px; margin-top:10px;">
                        </div>
                    </div>

                    <!-- Coins Table -->
                    <h5>العملات</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>اختر</th>
                                <th>اسم العملة</th>
                                <th>عدد العملات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($coins as $coin)
                            <tr>
                                <td><input type="checkbox" name="coin_id[]" value="{{ $coin->id }}"></td>
                                <td>{{ $coin->name }} / {{ $coin->name_en }}</td>
                                <td><input type="number" name="coin_number[]" class="form-control" min="0" value="0"></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Items Table -->
                    <h5>عناصر اللعبة</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>اختر</th>
                                <th>اسم العنصر</th>
                                <th>العدد</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                <td><input type="checkbox" name="item_id[]" value="{{ $item->id }}"></td>
                                <td>{{ $item->name }} / {{ $item->name_en }}</td>
                                <td><input type="number" name="item_number[]" class="form-control" min="0" value="0"></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Helpers Table -->
                    <h5>عناصر المساعدة</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>اختر</th>
                                <th>اسم العنصر</th>
                                <th>العدد</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($helpers as $helper)
                            <tr>
                                <td><input type="checkbox" name="helper_id[]" value="{{ $helper->id }}"></td>
                                <td>{{ $helper->name }} / {{ $helper->name_en }}</td>
                                <td><input type="number" name="helper_number[]" class="form-control" min="0" value="0"></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Submit -->
                    <div class="row">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9 text-secondary">
                            <input type="submit" class="btn btn-primary px-4" value="إضافة الحزمة">
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
                    });
                </script>

            </div>
        </div>
    </div>
</div>
@endsection
