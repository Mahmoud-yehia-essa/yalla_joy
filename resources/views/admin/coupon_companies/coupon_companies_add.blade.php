@extends('admin.master_admin')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">إضافة كوبون شركة جديد</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">إضافة كوبون</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="container">
        <div class="main-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="myForm" method="post" action="{{ route('store.coupon_companies') }}">
                                @csrf

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">اسم الكوبون (بالعربي)</h6>
                                    </div>
                                    <div class="form-group col-sm-9 text-secondary">
                                        <input type="text" name="coupon_name" class="form-control" value="{{ old('coupon_name') }}" />
                                        @error('coupon_name')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">اسم الكوبون (EN)</h6>
                                    </div>
                                    <div class="form-group col-sm-9 text-secondary">
                                        <input type="text" name="coupon_name_en" class="form-control" value="{{ old('coupon_name_en') }}" />
                                        @error('coupon_name_en')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">الشركة الراعية</h6>
                                    </div>
                                    <div class="form-group col-sm-9 text-secondary">
                                        <select name="sponsor_id" class="form-select">
                                            <option value="">اختر الشركة</option>
                                            @foreach($sponsors as $sponsor)
                                            <option value="{{ $sponsor->id }}" {{ old('sponsor_id') == $sponsor->id ? 'selected' : '' }}>{{ $sponsor->title }}</option>
                                            @endforeach
                                        </select>
                                        @error('sponsor_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">تاريخ ووقت الانتهاء</h6>
                                    </div>
                                    <div class="form-group col-sm-9 text-secondary">
                                        <input type="datetime-local" name="valid_until" class="form-control" value="{{ old('valid_until') }}" />
                                        @error('valid_until')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">عدد الكوبونات المتاحة</h6>
                                    </div>
                                    <div class="form-group col-sm-9 text-secondary">
                                        <input type="number" name="coupons_count" class="form-control" min="1" placeholder="أدخل عدد الكوبونات الكلي المتاح (أتركه فارغاً لعدد غير محدود)" value="{{ old('coupons_count') }}" />
                                        <small class="text-muted">إذا تركت هذا الحقل فارغاً، سيكون عدد الكوبونات المتاحة غير محدود.</small>
                                        @error('coupons_count')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">وصف الكوبون (بالعربي)</h6>
                                    </div>
                                    <div class="form-group col-sm-9 text-secondary">
                                        <textarea name="coupon_description" class="form-control" rows="3">{{ old('coupon_description') }}</textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">وصف الكوبون (EN)</h6>
                                    </div>
                                    <div class="form-group col-sm-9 text-secondary">
                                        <textarea name="coupon_description_en" class="form-control" rows="3">{{ old('coupon_description_en') }}</textarea>
                                    </div>
                                </div>

                                <hr>
                                <h5 class="mb-3">سعر شراء الكوبون</h5>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">نوع العملة المطلوبة</h6>
                                    </div>
                                    <div class="form-group col-sm-9 text-secondary">
                                        <select name="game_coin_id" class="form-select">
                                            <option value="">لا يتطلب عملات</option>
                                            @foreach($gameCoins as $coin)
                                            <option value="{{ $coin->id }}" {{ old('game_coin_id') == $coin->id ? 'selected' : '' }}>{{ $coin->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">عدد العملات المطلوبة</h6>
                                    </div>
                                    <div class="form-group col-sm-9 text-secondary">
                                        <input type="number" name="game_coins_count" class="form-control" value="{{ old('game_coins_count', 0) }}" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">كوبون قشط؟</h6>
                                    </div>
                                    <div class="form-group col-sm-9 text-secondary">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_scratch_coupon" id="is_scratch_coupon" value="1">
                                            <label class="form-check-label" for="is_scratch_coupon">تفعيل ككوبون قشط</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">كوبون خاص؟</h6>
                                    </div>
                                    <div class="form-group col-sm-9 text-secondary">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_special_coupon" id="is_special_coupon" value="1">
                                            <label class="form-check-label" for="is_special_coupon">تفعيل ككوبون خاص</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="submit" class="btn btn-primary px-4" value="حفظ الكوبون" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function (){
        $('#myForm').validate({
            rules: {
                coupon_name: { required : true },
                sponsor_id: { required : true },
            },
            messages :{
                coupon_name: { required : 'الرجاء ادخال اسم الكوبون' },
                sponsor_id: { required : 'الرجاء اختيار الشركة' },
            },
            errorElement : 'span', 
            errorPlacement: function (error,element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight : function(element, errorClass, validClass){
                $(element).addClass('is-invalid');
            },
            unhighlight : function(element, errorClass, validClass){
                $(element).removeClass('is-invalid');
            },
        });
    });
</script>

@endsection
