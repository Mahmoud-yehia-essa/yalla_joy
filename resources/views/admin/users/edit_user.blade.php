@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">تعديل المستخدم </div>
</div>
<!--end breadcrumb-->
<div class="container">
    <div class="main-body">
        <div class="row">
            <div class="col-lg-8">
                <form action="{{ route('edit.user.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf


                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <input type="hidden" name="old_image" value="{{ $user->photo }}">
                    <input type="hidden" name="old_email" value="{{ $user->email }}">

                    <div class="card">
                        <div class="card-body">
                            <!-- First Name -->
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">الاسم الأول</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input name="fname" value="{{$user->fname}}" type="text" class="form-control" value="{{ old('fname') }}" />
                                    @error('fname') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Last Name -->
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">اسم العائلة</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input name="lname" type="text" value="{{$user->lname}}" class="form-control" value="{{ old('lname') }}" />
                                    @error('lname') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">البريد الإلكتروني</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input name="email" type="email" value="{{$user->email}}" class="form-control" value="{{ old('email') }}" />
                                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">كلمة المرور</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input name="password" type="password" class="form-control" autocomplete="new-password"/>
                                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">تأكيد كلمة المرور</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input name="password_confirmation" type="password" class="form-control"  autocomplete="new-password"/>
                                    @error('password_confirmation') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">رقم الهاتف</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input name="phone" type="text" value="{{$user->phone}}" class="form-control" value="{{ old('phone') }}" />
                                    @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">العنوان</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input name="address" type="text" value="{{$user->address}}" class="form-control" value="{{ old('address') }}" />
                                    @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">الألعاب لهذا المستخدم ؟</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">


                        <select  name="is_game_free" class="form-select" aria-label="Default select example">



                            <option value="free" {{ old('app_type',$user->is_game_free) == 'free' ? 'selected' : '' }} >مجانية</option>

                            <option value="paid" {{ old('app_type',$user->is_game_free) == 'paid' ? 'selected' : '' }} >مدفوعة</option>




                        </select>

                        @error('special') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                            <!-- Profile Picture -->
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">الصورة</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input name="photo" type="file" id="image" class="form-control" />
                                    @error('photo') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Profile Picture Preview -->
                            <div class="row mb-3">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-9 text-secondary">
                                    <img id="showImage" src="{{ (!empty($user->photo) && $user->photo != 'non' ) ? url('upload/user_images/'.$user->photo):url('upload/no_image.jpg') }}" alt="Admin" width="110">


                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="submit" class="btn btn-primary px-4" value="تعديل المستخدم" />
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#image').change(function(e){
            var reader = new FileReader();
            reader.onload = function(e){
                $('#showImage').attr('src',e.target.result);
            }
            reader.readAsDataURL(e.target.files['0']);
        });
    });
</script>
@endsection
