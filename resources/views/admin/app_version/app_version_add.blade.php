@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo&family=Tajawal&family=Amiri&family=Roboto&display=swap" rel="stylesheet">

<div class="col-lg-16">
    <div class="card">
        <div class="card-body">

            {{-- Display Success Message --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Display Validation Errors --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('update.versions.store') }}">
                @csrf



                 <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">اسم اللعبة</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <input type="text" class="form-control @error('app_name') is-invalid @enderror"
                               name="app_name" value="{{ old('app_name', $appVersion->app_name) }}">
                        @error('ios')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">اصدار اللعبة الحالي</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <input type="text" class="form-control @error('version') is-invalid @enderror"
                               name="version" value="{{ old('version', $appVersion->version) }}">
                        @error('version')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">App Store رابط اللعبة على</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <input type="text" class="form-control @error('ios') is-invalid @enderror"
                               name="ios" value="{{ old('ios', $appVersion->ios) }}">
                        @error('ios')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Google Play  رابط اللعبة على</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <input type="text" class="form-control @error('android') is-invalid @enderror"
                               name="android" value="{{ old('android', $appVersion->android) }}">
                        @error('android')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">الوصف</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <textarea name="des" class="form-control @error('des') is-invalid @enderror"
                                  id="input11" placeholder="Description ..." rows="3">{{ old('des', $appVersion->des) }}</textarea>
                        @error('des')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">رقم الواتساب للتواصل (مع كود الدولة)</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <input type="text" class="form-control @error('whatsapp_number') is-invalid @enderror"
                               name="whatsapp_number" value="{{ old('whatsapp_number', $appVersion->whatsapp_number) }}"
                               placeholder="مثال: +966500000000 أو 966500000000">
                        @error('whatsapp_number')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">البريد الإلكتروني للتواصل</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <input type="email" class="form-control @error('contact_email') is-invalid @enderror"
                               name="contact_email" value="{{ old('contact_email', $appVersion->contact_email) }}"
                               placeholder="مثال: support@fik-tahadi.com">
                        @error('contact_email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>



                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">التحديث في اللعبة الزامي ؟</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">


                        <select  name="update_required" class="form-select" aria-label="Default select example">



                            <option value="yes" {{ old('update_required',$appVersion->update_required) == 'yes' ? 'selected' : '' }} >نعم</option>

                            <option value="no" {{ old('update_required',$appVersion->update_required) == 'no' ? 'selected' : '' }} >لا</option>




                        </select>

                        @error('special') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>


                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">العناصر في اللعبة ؟</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">


                        <select  name="app_type" class="form-select" aria-label="Default select example">



                            <option value="free" {{ old('app_type',$appVersion->app_type) == 'free' ? 'selected' : '' }} >مجانية</option>

                            <option value="paid" {{ old('app_type',$appVersion->app_type) == 'paid' ? 'selected' : '' }} >مدفوعة</option>




                        </select>

                        @error('special') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>





                  <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">نوع الخط</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">


                        <select  id="fontSelect" name="font_family_id" class="form-select" aria-label="Default select example">


 <option value="">اختر نوع الخط</option>
        @foreach($fontFamilies as $font)
                                                <option value="{{ $font->id }}" {{ old('font_family_id',$appVersion->font_family_id) == $font->id ? 'selected' : '' }} style="font-family: '{{ $font->font_family_name }}';">


            {{-- <option value="{{ $font->id }}"   style="font-family: '{{ $font->font_family_name }}';"> --}}
                {{ $font->font_family_name }}
            </option>
        @endforeach


                        </select>

                        @error('font_family_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>




{{-- <!-- عرض المعاينة -->
<div class="mt-2">
    <label>معاينة الخط:</label>
    <p id="fontPreview" style="font-size: 20px;">هذا نص تجريبي للمعاينة</p>
</div> --}}


                  <div class="mb-3">
            <label class="form-label">لون الخلفية</label>
            <input type="color" name="primary_color" value="{{ $appVersion->primary_color ?? '#ED7032' }}" class="form-control form-control-color">
        </div>

                   <div class="mb-3">
            <label class="form-label">لون النصوص</label>
            <input type="color" name="font_color_normal" value="{{ $appVersion->font_color_normal ?? '#ED7032' }}" class="form-control form-control-color">
        </div>

                <div class="row">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-9 text-secondary">
                        <input type="submit" class="btn btn-primary px-4" value="تحديث">
                    </div>
                </div>



            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('fontSelect');
    const preview = document.getElementById('fontPreview');

    select.addEventListener('change', function() {
        const selectedOption = select.options[select.selectedIndex];
        const fontName = selectedOption.textContent.trim();
        preview.style.fontFamily = fontName;
    });
});
</script>

@endsection
