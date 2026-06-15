@extends('admin.master_admin')
@section('admin')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">إدارة الأفاتار</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('all.avatar.item') }}"><i class="bx bx-home-alt"></i> كل العناصر</a></li>
                <li class="breadcrumb-item active" aria-current="page">مشتري الأفاتار</li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->

<hr/>

<!-- Avatar Info Details Card -->
<div class="card mb-4 border-top border-0 border-4 border-warning shadow-sm">
    <div class="card-body">
        <div class="d-flex align-items-center gap-3">
            <div>
                <img src="{{ $avatarItem->image ? asset($avatarItem->image) : url('upload/no_image.jpg') }}" 
                     class="rounded border shadow-sm p-1 bg-white" 
                     style="width: 80px; height: 80px; object-fit: contain;">
            </div>
            <div>
                <h5 class="mb-1" style="font-weight: bold; color: #32296a;">{{ $avatarItem->name }}</h5>
                <p class="mb-0 text-secondary">
                    <strong>التصنيف:</strong> {{ $avatarItem->category->name ?? '---' }} | 
                    <strong>النوع:</strong> 
                    @if($avatarItem->gender == 'boy')
                        <span class="badge bg-primary">ولد</span>
                    @elseif($avatarItem->gender == 'girl')
                        <span class="badge bg-danger">بنت</span>
                    @else
                        <span class="badge bg-secondary">ولد</span>
                    @endif
                     | 
                    <strong>السعر:</strong> 
                    @if($avatarItem->is_free)
                        <span class="badge bg-success">مجاني</span>
                    @else
                        <span class="badge bg-warning text-dark">{{ $avatarItem->coins_number ?? 0 }} {{ $avatarItem->coin->name ?? '' }}</span>
                    @endif
                </p>
            </div>
            <div class="ms-auto">
                <span class="badge bg-dark fs-6">إجمالي المشترين: {{ $users->count() }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Users Table Card -->
<div class="card shadow-sm">
    <div class="card-body">
        <h6 class="mb-3 text-uppercase" style="font-weight: bold; color: #32296a;"><i class="bx bx-group"></i> قائمة المستخدمين الذين اشتروا هذا الأفاتار</h6>
        <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>الرقم</th>
                        <th>الصورة</th>
                        <th>الاسم الأول</th>
                        <th>اسم العائلة</th>
                        <th>البريد الإلكتروني</th>
                        <th>تاريخ الميلاد</th>
                        <th>طريقة التسجيل</th>
                        <th>تاريخ الشراء</th>
                        <th>الاجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>
                            <img onclick="showImageModal(this.src)"
                                 class="rounded-circle"
                                 src="{{ (!empty($user->photo) && $user->photo != 'non') ? url('upload/user_images/'.$user->photo) : url('upload/no_image.jpg') }}"
                                 style="width: 50px; height: 50px; border: 2px solid #0aa2dd; cursor: pointer; object-fit: cover;">
                        </td>
                        <td>{{ $user->fname }}</td>
                        <td>{{ $user->lname }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->date_of_birth)
                                {{ $user->date_of_birth }} ({{ \Carbon\Carbon::parse($user->date_of_birth)->age }} سنة)
                            @else
                                لم يتم التحديد
                            @endif
                        </td>
                        <td class="text-center">
                            @if($user->register_type == 'normal')
                                <i class="fa-solid fa-envelope fa-lg" title="Email"></i>
                            @elseif($user->register_type == 'google')
                                <i class="fa-brands fa-google fa-lg" style="color:#DB4437;" title="Google"></i>
                            @elseif($user->register_type == 'facebook')
                                <i class="fa-brands fa-facebook fa-lg" style="color:#1877F2;" title="Facebook"></i>
                            @elseif($user->register_type == 'apple')
                                <i class="fa-brands fa-apple fa-lg" style="color:#000;" title="Apple"></i>
                            @else
                                <i class="fa-solid fa-question fa-lg" title="Unknown"></i>
                            @endif
                        </td>
                        <td>
                            {{ $user->pivot->created_at ? $user->pivot->created_at->format('Y-m-d H:i') : '---' }}
                        </td>
                        <td>
                            <a href="{{ route('edit.user', $user->id) }}" class="btn btn-info btn-sm" title="تعديل بيانات المستخدم">
                                <i class="fa fa-pencil"></i> تعديل
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>الرقم</th>
                        <th>الصورة</th>
                        <th>الاسم الأول</th>
                        <th>اسم العائلة</th>
                        <th>البريد الإلكتروني</th>
                        <th>تاريخ الميلاد</th>
                        <th>طريقة التسجيل</th>
                        <th>تاريخ الشراء</th>
                        <th>الاجراء</th>
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
            <!-- Rectangular Close Button -->
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
