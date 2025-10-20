@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">تعديل الصلاحية</div>
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

                            <form method="post" action="{{ route('edit.permission.store') }}" enctype="multipart/form-data">
                                @csrf


                                <input type="hidden" name="id" value="{{ $permission->id }}">

                                      <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">اسم الصلاحية</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="name" class="form-control" value="{{ old('name',$permission->name) }}" />
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>




                                 <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">اختر المجموعة</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">


                                  <select name="group_name" class="form-select" aria-label="Default select example">
    <option value="non">الرجاء إختيار المجموعة</option>
    <option value="أنواع الألعاب" {{ $permission->group_name == "أنواع الألعاب" ? 'selected' : '' }}>أنواع الألعاب</option>
    <option value="الفئات الرئيسية" {{ $permission->group_name == "الفئات الرئيسية" ? 'selected' : '' }}>الفئات الرئيسية</option>
    <option value="الفئات الفرعية" {{ $permission->group_name == "الفئات الفرعية" ? 'selected' : '' }}>الفئات الفرعية</option>
    <option value="إدارة المستخدمين" {{ $permission->group_name == "إدارة المستخدمين" ? 'selected' : '' }}>إدارة المستخدمين</option>
    <option value="التحكم في الأسئلة" {{ $permission->group_name == "التحكم في الأسئلة" ? 'selected' : '' }}>التحكم في الأسئلة</option>
    <option value="الأسئلة بإستخدام AI" {{ $permission->group_name == "الأسئلة بإستخدام AI" ? 'selected' : '' }}>الأسئلة بإستخدام AI</option>
    <option value="الألعاب المسجلة" {{ $permission->group_name == "الألعاب المسجلة" ? 'selected' : '' }}>الألعاب المسجلة</option>
    <option value="الاحصائيات" {{ $permission->group_name == "الاحصائيات" ? 'selected' : '' }}>الاحصائيات</option>
    <option value="إدارة الإشعارات" {{ $permission->group_name == "إدارة الإشعارات" ? 'selected' : '' }}>إدارة الإشعارات</option>
    <option value="التحكم في الأسعار" {{ $permission->group_name == "التحكم في الأسعار" ? 'selected' : '' }}>التحكم في الأسعار</option>
    <option value="إدارة الكوبونات" {{ $permission->group_name == "إدارة الكوبونات" ? 'selected' : '' }}>إدارة الكوبونات</option>
    <option value="إدارة المستويات" {{ $permission->group_name == "إدارة المستويات" ? 'selected' : '' }}>إدارة المستويات</option>
    <option value="إدارة الرتب" {{ $permission->group_name == "إدارة الرتب" ? 'selected' : '' }}>إدارة الرتب</option>
    <option value="إدارة نوع عنصر اللعبة" {{ $permission->group_name == "إدارة نوع عنصر اللعبة" ? 'selected' : '' }}>إدارة نوع عنصر اللعبة</option>
    <option value="إدارة عناصر اللعبة" {{ $permission->group_name == "إدارة عناصر اللعبة" ? 'selected' : '' }}>إدارة عناصر اللعبة</option>
    <option value="إدارة مساعدات اللعبة" {{ $permission->group_name == "إدارة مساعدات اللعبة" ? 'selected' : '' }}>إدارة مساعدات اللعبة</option>
    <option value="إدارة حزم اللعبة" {{ $permission->group_name == "إدارة حزم اللعبة" ? 'selected' : '' }}>إدارة حزم اللعبة</option>
    <option value="إدارة صلاحيات اللعبة" {{ $permission->group_name == "إدارة صلاحيات اللعبة" ? 'selected' : '' }}>إدارة صلاحيات اللعبة</option>
    <option value="إدارة المديرين" {{ $permission->group_name == "إدارة المديرين" ? 'selected' : '' }}>إدارة المديرين</option>


    <option value="إدارة ألعاب المستخدمين" {{ $permission->group_name == "إدارة ألعاب المستخدمين" ? 'selected' : '' }}>إدارة ألعاب المستخدمين</option>
    <option value="إدارة دليل اللعبة" {{ $permission->group_name == "إدارة دليل اللعبة" ? 'selected' : '' }}>إدارة دليل اللعبة</option>
    <option value="إدارة عملة اللعبة" {{ $permission->group_name == "إدارة عملة اللعبة" ? 'selected' : '' }}>إدارة عملة اللعبة</option>
    <option value="إدارة اللعبة" {{ $permission->group_name == "إدارة اللعبة" ? 'selected' : '' }}>إدارة اللعبة</option>
    <option value="إدارة الرعاة" {{ $permission->group_name == "إدارة الرعاة" ? 'selected' : '' }}>إدارة الرعاة</option>
    <option value="إدارة الرعاة مع المكافآت" {{ $permission->group_name == "إدارة الرعاة مع المكافآت" ? 'selected' : '' }}>إدارة الرعاة مع المكافآت</option>
</select>


                                        @error('group_name') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>








                                <!-- Submit Button -->
                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="submit" class="btn btn-primary px-4" value="تعديل الفئة" />
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
