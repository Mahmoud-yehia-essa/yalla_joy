@extends('admin.master_admin')
@section('admin')

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">تعديل بند الشروط والأحكام</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('all.terms.and.conditions') }}">الشروط والأحكام</a></li>
                    <li class="breadcrumb-item active" aria-current="page">تعديل البند</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="container">
        <div class="main-body">
            <div class="row">
                <div class="col-lg-10">
                    <div class="card">
                        <div class="card-body p-4">
                            <!-- Display Validation Errors -->
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="post" action="{{ route('edit.terms.and.conditions.store') }}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $term->id }}">

                                <!-- Title (Arabic) -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0 fw-bold">عنوان القسم (عربي) <span class="text-danger">*</span></h6>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" name="title" class="form-control" value="{{ old('title', $term->title) }}" placeholder="مثال: الشروط والأحكام لتطبيق فيك تحدي ؟" />
                                        @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Title (English) -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">عنوان القسم (English)</h6>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" dir="ltr" name="title_en" class="form-control" value="{{ old('title_en', $term->title_en) }}" placeholder="e.g. Terms and Conditions for Feek Tahadi?" />
                                        @error('title_en') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Content (Arabic) -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0 fw-bold">المحتوى والبنود (عربي) <span class="text-danger">*</span></h6>
                                    </div>
                                    <div class="col-sm-9">
                                        <textarea name="content" rows="8" class="form-control" placeholder="اكتب نص وبنود الشروط والأحكام بالعربية">{{ old('content', $term->content) }}</textarea>
                                        <small class="text-muted">💡 يمكنك كتابة الفقرات والنقاط والأسطر بحرية، وسيتم عرضها بنفس التنسيق تماماً داخل التطبيق.</small>
                                        @error('content') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Content (English) -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">المحتوى والبنود (English)</h6>
                                    </div>
                                    <div class="col-sm-9">
                                        <textarea dir="ltr" name="content_en" rows="8" class="form-control" placeholder="Write terms and conditions content in English">{{ old('content_en', $term->content_en) }}</textarea>
                                        @error('content_en') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Order By -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">ترتيب الظهور</h6>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="number" name="order_by" class="form-control" value="{{ old('order_by', $term->order_by) }}" min="1" />
                                        @error('order_by') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9">
                                        <input type="submit" class="btn btn-primary px-4" value="حفظ التعديلات" />
                                        <a href="{{ route('all.terms.and.conditions') }}" class="btn btn-secondary px-4 ms-2">إلغاء</a>
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

@endsection
