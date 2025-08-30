@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">اضافة تكلفة عنصر جديد </div>
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

                            <form method="post" action="{{ route('add.title.position.store') }}" enctype="multipart/form-data">
                                @csrf



                                    <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">اختر نوع العنصر المضاف</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">


                                        <select  name="type" class="form-select" aria-label="Default select example">
                                            <option selected="" value="non">الرجاء إختيار نوع العنصر</option>

                                            <option value="game" >لعبة</option>
                                             <option value="positions" > لقب</option>
                                             {{-- <option value="clothe" >ملابس</option> --}}
                                            <option value="accessorie" >إكسسوار</option>




                                        </select>

                                        @error('type') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Name -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">الاسم</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" />
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Name English -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Name (English)</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" dir="ltr" name="name_en" class="form-control" value="{{ old('name_en') }}" />
                                        @error('name_en')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Coins -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">السعر بعملة اللعبة</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="number" name="coins" class="form-control" value="{{ old('coins') }}" />
                                        @error('coins')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                                                            <small>اتركة فارغا اذا كانت بدون سعر</small>

                                    </div>
                                </div>

                                <!-- Points -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">النقاط</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="number" name="points" class="form-control" value="{{ old('points') }}" />
                                        @error('points')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                             <small>النقاط المطلوبة للوصول الى العنصر</small>
                             <br>
                             <small>اتركة فاغا اذا كان بدون نقاط</small>

                                    </div>

                                </div>

                                <!-- Photo -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">الصورة</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="file" name="photo" class="form-control" id="image" />
                                        @error('photo')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Image Preview -->
                                <div class="row mb-3">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <img id="showImage" src="{{ url('upload/no_image.jpg') }}" alt="Preview" style="width:100px; height: 100px;">
                                    </div>
                                </div>

                                <!-- Submit -->
                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="submit" class="btn btn-primary px-4" value="اضافة عنصر جديد" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- jQuery for Image Preview -->
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
