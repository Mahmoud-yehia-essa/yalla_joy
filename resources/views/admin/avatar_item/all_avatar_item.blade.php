@extends('admin.master_admin')
@section('admin')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">كل عناصر الأفاتار</div>
    <div class="ms-auto">
        <div class="btn-group">
            <a href="{{ route('export.avatar.item', request()->query()) }}" class="btn btn-success px-3 d-flex align-items-center justify-content-center gap-1">
                <i class="bx bx-download"></i> تصدير إلى Excel
            </a>
        </div>
    </div>
</div>
<!--end breadcrumb-->

<hr/>

<!-- Filter Section -->
<div class="card mb-3 shadow-sm border-top border-0 border-4 border-primary">
    <div class="card-body p-3">
        <h6 class="mb-3 text-uppercase" style="font-weight: bold; color: #32296a;"><i class="bx bx-filter-alt"></i> تصفية وتصفح عناصر الأفاتار</h6>
        <form method="GET" action="{{ route('all.avatar.item') }}" class="row g-3 align-items-end">
            <!-- Gender Filter -->
            <div class="col-md-3">
                <label class="form-label" style="font-weight: 600; font-size: 0.9rem;">النوع</label>
                <select name="gender" class="form-select border-2">
                    <option value="">كل الأنواع (ولد وبنت)</option>
                    <option value="boy" {{ request('gender') == 'boy' ? 'selected' : '' }}>ولد (Boy)</option>
                    <option value="girl" {{ request('gender') == 'girl' ? 'selected' : '' }}>بنت (Girl)</option>
                </select>
            </div>

            <!-- Category Filter -->
            <div class="col-md-3">
                <label class="form-label" style="font-weight: 600; font-size: 0.9rem;">التصنيف</label>
                <select name="category_id" class="form-select border-2">
                    <option value="">كل التصنيفات</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Price Filter -->
            <div class="col-md-3">
                <label class="form-label" style="font-weight: 600; font-size: 0.9rem;">حالة السعر</label>
                <select name="price_type" class="form-select border-2">
                    <option value="">كل الحالات</option>
                    <option value="free" {{ request('price_type') == 'free' ? 'selected' : '' }}>مجاني</option>
                    <option value="paid" {{ request('price_type') == 'paid' ? 'selected' : '' }}>مدفوع</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-3 w-100 d-flex align-items-center justify-content-center gap-1" style="height: 38px;">
                    <i class="bx bx-filter-alt"></i> تصفية
                </button>
                <a href="{{ route('all.avatar.item') }}" class="btn btn-secondary px-3 w-100 d-flex align-items-center justify-content-center gap-1" style="height: 38px;">
                    <i class="bx bx-refresh"></i> إعادة تعيين
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>الرقم</th>
                        <th>اسم العنصر</th>
                        <th>الصورة</th>
                        <th>التصنيف</th>
                        <th>النوع</th>
                        <th>نوع العملة</th>
                        <th>السعر (العملات)</th>
                        <th>الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($avatarItems as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->name ?? '---' }}</td>
                        <td>
                            <img onclick="showImageModal(this.src)"
                                 src="{{ $item->image ? asset($item->image) : url('upload/no_image.jpg') }}"
                                 style="width: 60px; height: 60px; cursor: pointer; object-fit: contain; background-color: #f8f9fa; border: 1px solid #e3e6f0; border-radius: 8px; padding: 2px;">
                        </td>
                        <td>{{ $item->category->name ?? '---' }}</td>
                        <td>
                            @if($item->gender == 'boy')
                                <span class="badge bg-primary">ولد</span>
                            @elseif($item->gender == 'girl')
                                <span class="badge bg-danger">بنت</span>
                            @else
                                <span class="badge bg-secondary">ولد</span>
                            @endif
                        </td>
                        <td>
                            @if($item->is_free)
                                <span class="badge bg-success">مجاني</span>
                            @else
                                {{ $item->coin->name ?? '---' }}
                            @endif
                        </td>
                        <td>
                            @if($item->is_free)
                                <span class="badge bg-success">مجاني</span>
                            @else
                                {{ $item->coins_number ?? 0 }}
                            @endif
                        </td>
                        <td>
                            @if($item->status == 'active')
                                <a href="{{ route('inactive.avatar.item', $item->id) }}" class="btn btn-primary" title="اخفاء">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            @else
                                <a href="{{ route('active.avatar.item', $item->id) }}" class="btn btn-primary" title="اظهار">
                                    <i class="fa-solid fa-eye-slash"></i>
                                </a>
                            @endif
                            <a href="{{ route('edit.avatar.item', $item->id) }}" class="btn btn-info">تعديل</a>
                            <a href="{{ route('avatar.item.purchased.users', $item->id) }}" class="btn btn-warning">المشترون</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>الرقم</th>
                        <th>اسم العنصر</th>
                        <th>الصورة</th>
                        <th>التصنيف</th>
                        <th>النوع</th>
                        <th>نوع العملة</th>
                        <th>السعر (العملات)</th>
                        <th>الإجراء</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content position-relative bg-transparent border-0">

            <!-- Close Button -->
            <button type="button"
                    class="btn text-white"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                    style="
                        position: absolute;
                        top: 15px;
                        right: 15px;
                        background-color: black;
                        font-size: 30px;
                        padding: 1px 10px;
                        border-radius: 8px;
                        z-index: 1055;
                    ">
                &times;
            </button>

            <!-- Image -->
            <img id="modalImage" src="" class="img-fluid rounded shadow" alt="image">
        </div>
    </div>
</div>

<script>
    function showImageModal(src) {
        document.getElementById('modalImage').src = src;
        var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
        myModal.show();
    }
</script>

@endsection
