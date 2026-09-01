@extends('admin.master_admin')
@section('admin')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">كل المستخدمين</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">

        </nav>
    </div>
    <div class="ms-auto">
        <div class="d-flex gap-2">
            <a href="{{ route('export.users') }}" class="btn btn-success px-3 d-flex align-items-center gap-1">
                <i class="bx bx-download"></i> تصدير إلى Excel
            </a>
            <a href="{{ route('add.user') }}" class="btn btn-primary px-3 d-flex align-items-center gap-1">
                <i class="bx bx-plus"></i> اضافة مستخدم
            </a>
        </div>
    </div>
</div>
<!--end breadcrumb-->

<hr/>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered align-middle" style="width:100%; font-size: 13px;">
                <thead>
                    <tr class="text-center align-middle">
                        <th>#</th>
                        <th>اسم المستخدم</th>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>تاريخ الميلاد</th>
                        <th>تاريخ التسجيل</th>
                        <th>طريقة التسجيل</th>
                        <th>نقاط الأونلاين (الكلي)</th>
                        <th>نقاط الأونلاين (المتاحة)</th>
                        <th>الصورة</th>
                        <th>حالة الصورة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($users as $key => $item)
                <tr>
                <td class="text-center"> {{ $key+1 }} </td>
                <td class="fw-bold text-nowrap text-primary">{{ $item->user_name ?? '---' }}</td>
                <td class="fw-bold text-nowrap">{{ $item->fname }} {{ $item->lname }}</td>
                <td>{{ $item->email }}</td>
                <td class="text-nowrap">
                    @if($item->date_of_birth)
                        {{ $item->date_of_birth }} <span class="text-muted">({{ \Carbon\Carbon::parse($item->date_of_birth)->age }} سنة)</span>
                    @else
                        <span class="text-muted">لم يتم التحديد</span>
                    @endif
                </td>
                <td class="text-nowrap">
                    @if($item->created_at)
                        {{ $item->created_at->format('Y-m-d') }}
                        <div class="small text-muted" style="font-size: 11px;">({{ $item->created_at->diffForHumans() }})</div>
                    @else
                        <span class="text-muted">لم يتم التحديد</span>
                    @endif
                </td>

                <td class="text-center">
                    @if($item->register_type == 'normal')
                        <i class="fa-solid fa-envelope fa-lg" title="Email"></i>
                    @elseif($item->register_type == 'google')
                        <i class="fa-brands fa-google fa-lg" style="color:#DB4437;" title="Google"></i>
                    @elseif($item->register_type == 'facebook')
                        <i class="fa-brands fa-facebook fa-lg" style="color:#1877F2;" title="Facebook"></i>
                    @elseif($item->register_type == 'apple')
                        <i class="fa-brands fa-apple fa-lg" style="color:#000;" title="Apple"></i>
                    @else
                        <i class="fa-solid fa-question fa-lg" title="Unknown"></i>
                    @endif
                </td>

                <td class="text-center fw-bold">{{ $item->online_points_fixed }}</td>
                <td class="text-center fw-bold">{{ $item->online_points }}</td>

                <td class="text-center">
                    <img
                        onclick="showImageModal(this.src)"
                        class="rounded-circle"
                        src="{{ (!empty($item->photo) && $item->photo != 'non' && file_exists(public_path('upload/user_images/'.$item->photo))) ? url('upload/user_images/'.$item->photo) : url('upload/no_image.jpg') }}"
                        style="width: 45px; height:45px; border: 2px solid #0aa2dd; cursor: pointer; object-fit: cover;"
                    >
                </td>
                <td class="text-center">
                    @if(!empty($item->photo) && $item->photo != 'non' && file_exists(public_path('upload/user_images/'.$item->photo)))
                        <div class="d-flex flex-column align-items-center gap-1">
                            @if($item->photo_approval_status == 'approved')
                                <span class="badge bg-success">مقبولة</span>
                                <a href="{{ route('user.photo.reject', $item->id) }}" class="btn btn-sm btn-outline-danger mt-1" style="font-size: 10px; padding: 2px 5px;" title="رفض الصورة">
                                    <i class="fa-solid fa-ban"></i> رفض
                                </a>
                            @elseif($item->photo_approval_status == 'rejected')
                                <span class="badge bg-danger">مرفوضة</span>
                                <a href="{{ route('user.photo.approve', $item->id) }}" class="btn btn-sm btn-outline-success mt-1" style="font-size: 10px; padding: 2px 5px;" title="قبول الصورة">
                                    <i class="fa-solid fa-check"></i> قبول
                                </a>
                            @else
                                <span class="badge bg-warning text-dark">قيد الانتظار</span>
                                <div class="d-flex gap-1 mt-1">
                                    <a href="{{ route('user.photo.approve', $item->id) }}" class="btn btn-sm btn-success" style="font-size: 10px; padding: 2px 6px;" title="قبول الصورة">
                                        <i class="fa-solid fa-check"></i>
                                    </a>
                                    <a href="{{ route('user.photo.reject', $item->id) }}" class="btn btn-sm btn-danger" style="font-size: 10px; padding: 2px 6px;" title="رفض الصورة">
                                        <i class="fa-solid fa-ban"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <span class="badge bg-secondary">لا توجد صورة</span>
                    @endif
                </td>
                <td class="text-nowrap text-center">

                @if($item->status == 'active')
                <a href="{{ route('inactive.user',$item->id) }}" class="btn btn-sm btn-primary" title="ايقاف التفعيل"> <i class="fa-solid fa-thumbs-down"></i> </a>
                @else
                <a href="{{ route('active.user',$item->id) }}" class="btn btn-sm btn-primary" title="تفعيل"> <i class="fa-solid fa-thumbs-up"></i> </a>
                @endif
                <a href="{{ route('edit.user',$item->id) }}" class="btn btn-sm btn-info" title="Edit Data"> <i class="fa fa-pencil"></i> </a>

                <a href="{{ route('delete.user',$item->id) }}" class="btn btn-sm btn-danger" id="delete" title="Delete Data" ><i class="fa fa-trash"></i></a>
                <button type="button" class="btn btn-sm btn-warning toggle-coins-btn text-dark" data-user-id="{{ $item->id }}" title="العملات المكتسبة">
                    <i class="fa-solid fa-coins"></i>
                </button>

                </td>
                </tr>

                <!-- Coins Collapse Row -->
                <tr id="user-coins-row-{{ $item->id }}" style="display: none; background-color: #faf9fd;">
                    <td colspan="12" class="p-3">
        <div class="card shadow-none border mb-0" style="overflow: hidden;">
            <div class="card-body p-2 p-md-3">
                <!-- User Game Stats -->
                <h6 class="mb-3 text-primary fw-bold px-2" style="font-size: 14px;">
                    <i class="fa-solid fa-gamepad me-2"></i> معلومات لعبة الميدان
                </h6>
                <div class="row g-2 g-md-3 mb-4 pb-3 border-bottom text-center mx-0">
                    <div class="col-12 col-md-4">
                        <div class="d-flex align-items-center gap-2 p-2 bg-light rounded-3 border">
                            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 16px; flex-shrink: 0;">
                                <i class="fa-solid fa-trophy"></i>
                            </div>
                            <div class="text-end w-100">
                                <span class="text-muted d-block small fw-bold mb-1" style="font-size: 11px;">عدد مرات الفوز المحققة</span>
                                <input type="number" id="stat-wins-{{ $item->id }}" class="form-control form-control-sm text-center fw-bold fs-6 border-2" value="{{ $item->online_game_wins ?? 0 }}" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="d-flex align-items-center gap-2 p-2 bg-light rounded-3 border">
                            <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 16px; flex-shrink: 0;">
                                <i class="fa-solid fa-gamepad"></i>
                            </div>
                            <div class="text-end w-100">
                                <span class="text-muted d-block small fw-bold mb-1" style="font-size: 11px;">عدد مرات اللعب الميدان</span>
                                <input type="number" id="stat-play-{{ $item->id }}" class="form-control form-control-sm text-center fw-bold fs-6 border-2" value="{{ $item->online_play_count ?? 0 }}" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="d-flex align-items-center gap-2 p-2 bg-light rounded-3 border">
                            <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 16px; flex-shrink: 0;">
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <div class="text-end w-100">
                                <span class="text-muted d-block small fw-bold mb-1" style="font-size: 11px;">نقاط لعبة الميدان</span>
                                <input type="number" id="stat-points-{{ $item->id }}" class="form-control form-control-sm text-center fw-bold fs-6 border-2" value="{{ $item->online_points ?? 0 }}" min="0">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Session Game Stats -->
                <h6 class="mb-3 text-primary fw-bold px-2 mt-3" style="font-size: 14px;">
                    <i class="fa-solid fa-users me-2"></i> معلومات لعبة الجلسة
                </h6>
                <div class="row g-2 g-md-3 mb-4 pb-3 border-bottom text-center mx-0">
                    <div class="col-12 col-md-4">
                        <div class="d-flex align-items-center gap-2 p-2 bg-light rounded-3 border">
                            <div class="rounded-circle text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 16px; flex-shrink: 0; background-color: #6f42c1;">
                                <i class="fa-solid fa-gamepad"></i>
                            </div>
                            <div class="text-end w-100">
                                <span class="text-muted d-block small fw-bold mb-1" style="font-size: 11px;">عدد مرات اللعب</span>
                                <input type="number" class="form-control form-control-sm text-center fw-bold fs-6 border-2" value="{{ $item->games ? $item->games->count() : 0 }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3 border-bottom pb-2 gap-2 mx-0 px-2">
                    <h6 class="mb-0 text-primary fw-bold" style="font-size: 14px;">
                        <i class="fa-solid fa-wallet me-2"></i> إجمالي العملات المكتسبة للمستخدم: {{ $item->fname }} {{ $item->lname }}
                    </h6>
                    <button class="btn btn-primary btn-sm view-coin-details-btn d-none align-self-start align-self-md-auto" id="details-btn-{{ $item->id }}" data-user-id="{{ $item->id }}" data-username="{{ $item->fname }} {{ $item->lname }}">
                        <i class="fa-solid fa-list-check me-1"></i> التفاصيل
                    </button>
                </div>
                <!-- loading spinner -->
                <div id="coins-loading-{{ $item->id }}" class="text-center my-3">
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">جاري التحميل...</span>
                    </div>
                </div>
                <!-- container for coins -->
                <div id="coins-list-{{ $item->id }}" class="row g-2 g-md-3 mx-0">
                    <!-- Dynamic coins grid -->
                </div>

                <!-- Save Changes Button -->
                <div class="d-flex justify-content-end align-items-center border-top pt-3 mt-3 px-2">
                    <button class="btn btn-success px-4 d-flex align-items-center justify-content-center gap-1 save-collapse-details-btn w-100 w-md-auto" data-user-id="{{ $item->id }}">
                        <i class="fa-solid fa-floppy-disk"></i> حفظ التغييرات
                    </button>
                </div>
            </div>
        </div>
    </td>
</tr>
@endforeach


</tbody>
<tfoot>
<tr class="text-center align-middle">
    <th>#</th>
    <th>اسم المستخدم</th>
    <th>الاسم</th>
    <th>البريد الإلكتروني</th>
    <th>تاريخ الميلاد</th>
    <th>تاريخ التسجيل</th>
    <th>طريقة التسجيل</th>
    <th>نقاط الأونلاين (الكلي)</th>
    <th>نقاط الأونلاين (المتاحة)</th>
    <th>الصورة</th>
    <th>حالة الصورة</th>
    <th>الإجراءات</th>
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
        <img id="modalImage" src="" class="img-fluid rounded shadow"  alt="image">
      </div>
    </div>
  </div>

<!-- Coins Details Modal -->
<div class="modal fade" id="userCoinsDetailsModal" tabindex="-1" aria-labelledby="userCoinsDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="userCoinsDetailsModalLabel">
                    <i class="fa-solid fa-coins me-2"></i> سجل تفاصيل عملات المستخدم: <span id="modal-username-title" class="fw-bold"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="direction: rtl; text-align: right;">
                <!-- loading spinner -->
                <div id="modal-loading-spinner" class="text-center my-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">جاري تحميل السجل...</span>
                    </div>
                </div>
                <!-- empty/no-data alert -->
                <div id="modal-no-data" class="alert alert-info text-center my-4 d-none">
                    لا يوجد سجل للعملات لهذا المستخدم بعد.
                </div>
                <!-- details table -->
                <div id="modal-table-container" class="table-responsive d-none">
                    <table class="table table-striped table-hover align-middle">
                        <thead>
                            <tr class="table-primary text-center">
                                <th>الرقم</th>
                                <th>صورة العملة</th>
                                <th>اسم العملة</th>
                                <th>الكمية</th>
                                <th>نوع العملية</th>
                                <th>تاريخ العملية</th>
                            </tr>
                        </thead>
                        <tbody id="modal-coins-details-body">
                            <!-- Rows injected dynamically via Javascript -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

 <script>
    function showImageModal(src) {
        document.getElementById('modalImage').src = src;
        var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
        myModal.show();
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Toggle Coins Collapse and Load Summary
        document.querySelectorAll('.toggle-coins-btn').forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.getAttribute('data-user-id');
                const row = document.getElementById(`user-coins-row-${userId}`);
                const listContainer = document.getElementById(`coins-list-${userId}`);
                const spinner = document.getElementById(`coins-loading-${userId}`);
                const detailsBtn = document.getElementById(`details-btn-${userId}`);

                if (row.style.display === 'none') {
                    row.style.display = 'table-row';
                    
                    // Check if already loaded
                    if (row.getAttribute('data-loaded') !== 'true') {
                        spinner.classList.remove('d-none');
                        listContainer.innerHTML = '';
                        
                        // Fetch summary from API
                        fetch('/api/user/coins-summary', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ user_id: userId })
                        })
                        .then(response => response.json())
                        .then(res => {
                            spinner.classList.add('d-none');
                            if (res.status && res.data && res.data.length > 0) {
                                detailsBtn.classList.remove('d-none');
                                let cardsHtml = '';
                                res.data.forEach(item => {
                                    let photoUrl = item.photo ? (item.photo.startsWith('/') ? item.photo : '/' + item.photo) : '/upload/no_image.jpg';
                                    cardsHtml += `
                                        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                                            <div class="card border shadow-sm mb-0 rounded-3 bg-white">
                                                <div class="card-body p-2 p-md-3 d-flex align-items-center gap-2 gap-md-3">
                                                    <div class="avatar-coin rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border: 1.5px solid #eaeaea; overflow: hidden; flex-shrink: 0;">
                                                        <img src="${photoUrl}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='/upload/no_image.jpg'">
                                                    </div>
                                                    <div class="w-100">
                                                        <span class="text-muted d-block small fw-bold" style="font-size: 11px;">${item.name || item.name_en || 'عملة'}</span>
                                                        <input type="number" class="form-control form-control-sm text-center fw-bold mt-1 coin-input-field-${userId}" data-coin-id="${item.game_coin_id}" value="${item.total_coins}" min="0">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                });
                                listContainer.innerHTML = cardsHtml;
                            } else {
                                detailsBtn.classList.add('d-none');
                                listContainer.innerHTML = `
                                    <div class="col-12 text-center text-muted p-3">
                                        <i class="fa-regular fa-folder-open fa-2x mb-2 d-block"></i>
                                        لا توجد أي عملات مكتسبة مسجلة للمستخدم بعد.
                                    </div>
                                `;
                            }
                            row.setAttribute('data-loaded', 'true');
                        })
                        .catch(err => {
                            spinner.classList.add('d-none');
                            listContainer.innerHTML = `
                                <div class="col-12 text-center text-danger p-3">
                                    حدث خطأ أثناء تحميل البيانات. الرجاء المحاولة مرة أخرى.
                                </div>
                            `;
                        });
                    }
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Save Collapse Details
        document.querySelectorAll('.save-collapse-details-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const userId = this.getAttribute('data-user-id');
                const wins = document.getElementById(`stat-wins-${userId}`).value;
                const play = document.getElementById(`stat-play-${userId}`).value;
                const points = document.getElementById(`stat-points-${userId}`).value;

                // Collect coin inputs
                const coins = {};
                document.querySelectorAll(`.coin-input-field-${userId}`).forEach(input => {
                    const coinId = input.getAttribute('data-coin-id');
                    coins[coinId] = input.value;
                });

                // Disable button and show saving indicator
                const saveBtn = this;
                const originalHtml = saveBtn.innerHTML;
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> جاري الحفظ...';

                // Send request
                fetch('/user/update-collapse-details', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        online_game_wins: wins,
                        online_play_count: play,
                        online_points: points,
                        coins: coins
                    })
                })
                .then(response => response.json())
                .then(res => {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = originalHtml;
                    if (res.success) {
                        toastr.success(res.message);
                        
                        // Force reload of summary if user collapses and opens again
                        const row = document.getElementById(`user-coins-row-${userId}`);
                        row.removeAttribute('data-loaded');
                    } else {
                        toastr.error('حدث خطأ أثناء حفظ التغييرات.');
                    }
                })
                .catch(err => {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = originalHtml;
                    toastr.error('حدث خطأ أثناء الاتصال بالخادم.');
                });
            });
        });

        // Load Coin Details in Modal
        document.querySelectorAll('.view-coin-details-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const userId = this.getAttribute('data-user-id');
                const username = this.getAttribute('data-username');
                
                // Show modal and set title
                const modal = new bootstrap.Modal(document.getElementById('userCoinsDetailsModal'));
                document.getElementById('modal-username-title').innerText = username;
                
                // Show spinner, hide table and alert
                document.getElementById('modal-loading-spinner').classList.remove('d-none');
                document.getElementById('modal-table-container').classList.add('d-none');
                document.getElementById('modal-no-data').classList.add('d-none');
                
                modal.show();

                // Fetch details
                fetch('/api/user/coins-details', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ user_id: userId })
                })
                .then(response => response.json())
                .then(res => {
                    document.getElementById('modal-loading-spinner').classList.add('d-none');
                    if (res.status && res.data && res.data.length > 0) {
                        document.getElementById('modal-table-container').classList.remove('d-none');
                        
                        let tbodyHtml = '';
                        res.data.forEach((item, index) => {
                            let photoUrl = item.photo ? (item.photo.startsWith('/') ? item.photo : '/' + item.photo) : '/upload/no_image.jpg';
                            
                            // Style the coin number based on add or withdraw
                            let countBadge = '';
                            if (item.coins_number > 0) {
                                countBadge = `<span class="badge bg-light-success text-success fw-bold p-2" style="font-size: 13px;">+${item.coins_number}</span>`;
                            } else {
                                countBadge = `<span class="badge bg-light-danger text-danger fw-bold p-2" style="font-size: 13px;">${item.coins_number}</span>`;
                            }

                            // Arabic translation for types
                            let typeLabel = '';
                            if (item.type === 'add') {
                                typeLabel = '<span class="badge bg-success text-white">شحن / إضافة</span>';
                            } else if (item.type === 'withdraw') {
                                typeLabel = '<span class="badge bg-danger text-white">سحب / خصم</span>';
                            } else if (item.type === 'buy_animation') {
                                typeLabel = '<span class="badge bg-info text-dark">شراء أنيميشن</span>';
                            } else {
                                typeLabel = `<span class="badge bg-secondary text-white">${item.type || 'N/A'}</span>`;
                            }

                            tbodyHtml += `
                                <tr class="text-center">
                                    <td>${index + 1}</td>
                                    <td>
                                        <div class="avatar-coin rounded-circle bg-light d-flex align-items-center justify-content-center m-auto" style="width: 38px; height: 38px; border: 1px solid #eaeaea; overflow: hidden;">
                                            <img src="${photoUrl}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='/upload/no_image.jpg'">
                                        </div>
                                    </td>
                                    <td><strong>${item.name || item.name_en || 'N/A'}</strong></td>
                                    <td>${countBadge}</td>
                                    <td>${typeLabel}</td>
                                    <td>
                                        <span class="text-muted d-block small">${item.created_at_human}</span>
                                        <span class="text-muted small" style="font-size: 10px;">${new Date(item.created_at).toLocaleString('ar-EG')}</span>
                                    </td>
                                </tr>
                            `;
                        });
                        document.getElementById('modal-coins-details-body').innerHTML = tbodyHtml;
                    } else {
                        document.getElementById('modal-no-data').classList.remove('d-none');
                    }
                })
                .catch(err => {
                    document.getElementById('modal-loading-spinner').classList.add('d-none');
                    document.getElementById('modal-no-data').innerText = 'حدث خطأ في تحميل السجل.';
                    document.getElementById('modal-no-data').classList.remove('d-none');
                });
            });
        });
    });
</script>
@endsection
