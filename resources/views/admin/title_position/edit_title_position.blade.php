@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">تعديل المركز</div>
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

                            <form method="post" action="{{ route('edit.title.position.store') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{ $titlePosition->id }}">
                                <input type="hidden" name="old_image" value="{{ $titlePosition->photo }}">


                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">اختر نوع العنصر</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">


                                        <select  name="type" class="form-select" aria-label="Default select example">
                                            <option selected="" value="non">الرجاء إختيار نوع العنصر</option>

                                            <option value="game" {{$titlePosition->type == "game" ? 'selected' : ''}} >لعبة</option>
                                             <option value="positions" {{$titlePosition->type == "positions" ? 'selected' : ''}} >منصب أو لقب</option>
                                             <option value="clothe" {{$titlePosition->type == "clothe" ? 'selected' : ''}} > ملابس</option>
                                             <option value="accessorie" {{$titlePosition->type == "accessorie" ? 'selected' : ''}} > إكسسوار</option>

                                             {{-- <option value="positions" >منصب أو لقب</option>
                                             <option value="clothe" >ملابس</option>
                                            <option value="accessorie" >إكسسوار</option> --}}




                                        </select>

                                        @error('type') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- name -->
                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">الاسم</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="name" value="{{ old('name', $titlePosition->name) }}" class="form-control" />
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- name_en -->
                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">Name (EN)</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="name_en" value="{{ old('name_en', $titlePosition->name_en) }}" class="form-control" />
                                        @error('name_en')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- coins -->
                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">Coins</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="number" name="coins" value="{{ old('coins', $titlePosition->coins) }}" class="form-control" />
                                        @error('coins')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- points -->
                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">Points</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="number" name="points" value="{{ old('points', $titlePosition->points) }}" class="form-control" />
                                        @error('points')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- photo -->
                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">الصورة</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="file" name="photo" class="form-control" id="image" />
                                        @error('photo')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- preview -->
                                <div class="row mb-3">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <img id="showImage" src="{{ url($titlePosition->photo) }}" alt="Preview" style="width:100px; height:100px;">
                                    </div>
                                </div>

                                <!-- submit -->
                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="submit" class="btn btn-primary px-4" value="تعديل المركز" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- jQuery Preview -->
                    <script type="text/javascript">
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
@endsection
