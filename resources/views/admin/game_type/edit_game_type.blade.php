@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">تعديل نوع اللعبة</div>
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

                            <form method="post" action="{{ route('edit.game.type.store') }}" enctype="multipart/form-data">
                                @csrf


                                <input type="hidden" name="id" value="{{ $gameType->id }}">
                                <input type="hidden" name="old_image" value="{{ $gameType->type_photo }}">
                                <!-- Category Name -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">نوع اللعبة</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" value="{{$gameType->type_name}}" name="game_type_name" class="form-control" value="{{ old('game_type_name') }}" />
                                        @error('game_type_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>


                                 <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Game Type</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" value="{{$gameType->type_name_en}}" name="game_type_name_en" class="form-control" value="{{ old('game_type_name_en') }}" />
                                        @error('game_type_name_en')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>






                                <!-- Category Description -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">الوصف</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="game_type_description" value="{{$gameType->type_description}}" class="form-control" value="{{ old('game_type_description') }}" />
                                        @error('game_type_description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>



                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Description</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="game_type_description_en" value="{{$gameType->type_description_en}}" class="form-control" value="{{ old('game_type_description_en') }}" />
                                        @error('game_type_description_en')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Category Photo -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">الصورة</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="file" name="game_type_photo" class="form-control" id="image" />
                                        @error('game_type_photo')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Image Preview -->
                                <div class="row mb-3">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <img id="showImage" src="{{ url($gameType->type_photo) }}" alt="Preview" style="width:100px; height: 100px;">
                                    </div>
                                </div>





                                <!-- category is_kids -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">هل هذا النوع خاص بالأطفال؟</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <div class="form-check form-switch">
                                            <input type="hidden" name="is_kids" value="0">
                                            <input class="form-check-input" type="checkbox" name="is_kids" value="1" id="isKidsSwitch" {{ old('is_kids', $gameType->is_kids) == 1 ? 'checked' : '' }} style="transform: scale(1.5); margin-right: 10px;">
                                            <label class="form-check-label" for="isKidsSwitch" style="margin-right: 10px;">نعم / لا</label>
                                        </div>
                                        @error('is_kids') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- category is_term -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">هل يوجد ترم اخر ؟</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <div class="form-check form-switch">
                                            <input type="hidden" name="is_term" value="0">
                                            <input class="form-check-input" type="checkbox" name="is_term" value="1" id="isTermSwitch" {{ old('is_term', $gameType->is_term) == 1 ? 'checked' : '' }} style="transform: scale(1.5); margin-right: 10px;">
                                            <label class="form-check-label" for="isTermSwitch" style="margin-right: 10px;">نعم / لا</label>
                                        </div>
                                        @error('is_term') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="submit" class="btn btn-primary px-4" value="تعديل نوع اللعبة" />
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
