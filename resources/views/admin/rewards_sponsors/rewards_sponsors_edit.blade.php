@extends('admin.master_admin')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">تعديل مكافأت مع الرعاة</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">

						</nav>
					</div>
					<div class="ms-auto">

					</div>
				</div>
				<!--end breadcrumb-->
				<div class="container">
					<div class="main-body">
						<div class="row">

<div class="col-lg-10">
	<div class="card">
		<div class="card-body">

 <form id="myForm" method="post" action="{{ route('update.rewards.sponsors') }}" enctype="multipart/form-data"   >
			@csrf
      <!-- Question  category-->


      <input type="hidden" name="id" value="{{ $rewardsSponsor->id }}">
     <input type="hidden" name="old_image" value="{{ $rewardsSponsor->photo }}">
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">اختر الراعي</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">


                                        <select  name="sponsor_id" class="form-select" aria-label="Default select example">
                                            <option selected="" value="non">الرجاء إختيار الراعي</option>

                                            @foreach ($sponsor as $item )
                                            {{-- <option value="{{$item->id}}" {{ old('sponsor_id') == $item->id ? 'selected' : '' }}>{{$item->title}}</option> --}}
                                            <option value="{{$item->id}}" {{ old('sponsor_id',$rewardsSponsor->sponsor_id) == $item->id ? 'selected' : '' }}>{{$item->title}} - {{$item->title_en}}</option>

                                            @endforeach

                                        </select>

                                        @error('sponsor_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>



          <div class="row mb-3">
				<div class="col-sm-3">
					<h6 class="mb-0">عنوان المكافأة</h6>
				</div>
				<div class="form-group col-sm-9 text-secondary">
					<input type="text" name="title" class="form-control" value="{{old('title',$rewardsSponsor->title)}}"  />
                      @error('title')
                        <span class="text-danger">{{ $message }}</span>
                      @enderror
				</div>
			</div>


              <div class="row mb-3">
				<div class="col-sm-3">
					<h6 class="mb-0">Title</h6>
				</div>
				<div class="form-group col-sm-9 text-secondary">
					<input type="text" name="title_en" class="form-control"  value="{{old('title_en',$rewardsSponsor->title_en)}}" />
                      @error('title_en')
                        <span class="text-danger">{{ $message }}</span>
                      @enderror
				</div>
			</div>


              <div class="row mb-3">
				<div class="col-sm-3">
					<h6 class="mb-0">عدد نقاط المكافأة</h6>
				</div>
				<div class="form-group col-sm-9 text-secondary">
					<input type="number" name="number_of_points" class="form-control"  value="{{old('number_of_points',$rewardsSponsor->number_of_points)}}" />
                      @error('number_of_points')
                        <span class="text-danger">{{ $message }}</span>
                      @enderror
				</div>
			</div>



           <div class="row mb-3">
				<div class="col-sm-3">
					<h6 class="mb-0">اسم الكوبون</h6>
				</div>
				<div class="form-group col-sm-9 text-secondary">
					<input type="text" name="coupon_name" class="form-control" value="{{old('coupon_name',$rewardsSponsor->coupon_name)}}"   />
                      @error('coupon_name')
                        <span class="text-danger">{{ $message }}</span>
                      @enderror
				</div>
			</div>

            {{-- <div class="row mb-3">
				<div class="col-sm-3">
					<h6 class="mb-0">الخصم(%)</h6>
                    <small>الرجاء اضافة الرقم فقط بدون نسبة</small>
				</div>
				<div class="form-group col-sm-9 text-secondary">
					<input  type="number"  name="coupon_discount" class="form-control"   />
                      @error('coupon_discount')
                        <span class="text-danger">{{ $message }}</span>
                      @enderror
				</div>
			</div> --}}


              <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">الوصف</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="des" class="form-control" value="{{old('des',$rewardsSponsor->des)}}" />
                                        @error('des')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>


                                 <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Description</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" dir="ltr" name="des_en" class="form-control" value="{{old('des_en',$rewardsSponsor->des_en)}}" />
                                        @error('des_en')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>


            <div class="row mb-3">
				<div class="col-sm-3">
					<h6 class="mb-0">حدد تاريخ صلاحية المكافأة</h6>
				</div>
				<div class="form-group col-sm-9 text-secondary">

					{{-- <input type="date" min="{{Carbon\Carbon::now()}}" name="coupon_validity" class="form-control"   /> --}}
                    <input type="date" min="{{ Carbon\Carbon::now()->format('Y-m-d') }}" name="coupon_validity" value="{{old('coupon_validity',$rewardsSponsor->coupon_validity)}}" class="form-control"   />

                      @error('coupon_validity')
                        <span class="text-danger">{{ $message }}</span>
                      @enderror
				</div>
			</div>




                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Latitude</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input name="latitude" type="text" class="form-control" value="{{old('latitude',$rewardsSponsor->latitude)}}" />
                                    @error('latitude') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>


                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Longitude</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input name="longitude" type="text" class="form-control" value="{{old('longitude',$rewardsSponsor->longitude)}}" />
                                    @error('longitude') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>



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
                                        <img id="showImage" src="{{$rewardsSponsor->photo == null ? url('upload/no_image.jpg'): url($rewardsSponsor->photo) }}" alt="Preview" style="width:100px; height: 100px;">
                                    </div>
                                </div>



			<div class="row">
				<div class="col-sm-3"></div>
				<div class="col-sm-9 text-secondary">
					<input type="submit" class="btn btn-primary px-4" value="تعديل" />
				</div>
			</div>
		</div>

		</form>



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
                subcategory_name: {
                    required : true,
                },
            },
            messages :{
                subcategory_name: {
                    required : 'Please Enter SubCategory Name',
                },
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




@endsection
