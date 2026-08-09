@extends('admin.master_admin')
@section('admin')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">إدارة التحديات</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">عرض التحديات</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="{{ route('export.challenges', request()->query()) }}" class="btn btn-success px-3 d-flex align-items-center gap-1 shadow-sm">
            <i class="bx bx-download"></i> تصدير إلى Excel
        </a>
    </div>
</div>
<!--end breadcrumb-->

<hr/>

<!-- Stats Summary Cards -->
<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-3 mb-4">
    <div class="col">
        <div class="card radius-10 border-start border-0 border-3 border-primary shadow-sm h-100 mb-0">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary font-14">إجمالي التحديات</p>
                        <h4 class="my-1 text-primary fw-bold">{{ number_format($totalChallengesCount) }}</h4>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                        <i class='bx bx-trophy'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card radius-10 border-start border-0 border-3 border-warning shadow-sm h-100 mb-0">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary font-14">تحديات معلقة</p>
                        <h4 class="my-1 text-warning fw-bold">{{ number_format($pendingCount) }}</h4>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-bnh text-white ms-auto">
                        <i class='bx bx-time-five'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card radius-10 border-start border-0 border-3 border-info shadow-sm h-100 mb-0">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary font-14">تحديات مقبولة</p>
                        <h4 class="my-1 text-info fw-bold">{{ number_format($acceptedCount) }}</h4>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto">
                        <i class='bx bx-check-circle'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card radius-10 border-start border-0 border-3 border-success shadow-sm h-100 mb-0">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary font-14">تحديات مكتملة</p>
                        <h4 class="my-1 text-success fw-bold">{{ number_format($completedCount) }}</h4>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-ohht text-white ms-auto">
                        <i class='bx bx-flag'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <!-- Filter Form -->
        <form method="GET" action="{{ route('all.challenges') }}" class="row g-3 mb-4 align-items-end">
            <!-- 1. Search -->
            <div class="col-lg-4 col-md-6 col-sm-12">
                <label for="search" class="form-label fw-bold" style="font-size: 14px; color: #555;">بحث (كود اللعبة / اسم أو بريد المستخدم):</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bx bx-search"></i></span>
                    <input type="text" name="search" id="search" class="form-control" placeholder="أدخل رمز اللعبة أو اسم المستخدم..." value="{{ request('search') }}">
                </div>
            </div>

            <!-- 2. Status Filter -->
            <div class="col-lg-3 col-md-6 col-sm-12">
                <label for="invitation_statue" class="form-label fw-bold" style="font-size: 14px; color: #555;">حالة الدعوة:</label>
                <select name="invitation_statue" id="invitation_statue" class="form-select" onchange="this.form.submit()">
                    <option value="all" {{ request('invitation_statue') == 'all' || !request('invitation_statue') ? 'selected' : '' }}>جميع الحالات</option>
                    <option value="pending" {{ request('invitation_statue') == 'pending' ? 'selected' : '' }}>معلقة (Pending)</option>
                    <option value="accepted" {{ request('invitation_statue') == 'accepted' ? 'selected' : '' }}>مقبولة (Accepted)</option>
                    <option value="rejected" {{ request('invitation_statue') == 'rejected' ? 'selected' : '' }}>مرفوضة (Rejected)</option>
                    <option value="completed" {{ request('invitation_statue') == 'completed' ? 'selected' : '' }}>مكتملة (Completed)</option>
                    <option value="canceled" {{ request('invitation_statue') == 'canceled' ? 'selected' : '' }}>ملغاة (Canceled)</option>
                </select>
            </div>

            <!-- 3. Sort Order -->
            <div class="col-lg-2 col-md-6 col-sm-12">
                <label for="sort_by" class="form-label fw-bold" style="font-size: 14px; color: #555;">الترتيب:</label>
                <select name="sort_by" id="sort_by" class="form-select" onchange="this.form.submit()">
                    <option value="latest" {{ request('sort_by') == 'latest' || !request('sort_by') ? 'selected' : '' }}>الأحدث أولاً</option>
                    <option value="oldest" {{ request('sort_by') == 'oldest' ? 'selected' : '' }}>الأقدم أولاً</option>
                </select>
            </div>

            <!-- 4. Action Buttons -->
            <div class="col-lg-3 col-md-6 col-sm-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4 d-flex align-items-center justify-content-center gap-1 w-100">
                    <i class="bx bx-filter-alt"></i> تصفية
                </button>
                @if(request()->anyFilled(['search', 'invitation_statue', 'sort_by']))
                    <a href="{{ route('all.challenges') }}" class="btn btn-outline-secondary px-3 d-flex align-items-center justify-content-center w-100">
                        إعادة تعيين
                    </a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered align-middle" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">#</th>
                        <th>مرسل الدعوة</th>
                        <th>مستقبل الدعوة</th>
                        <th class="text-center">رمز اللعبة</th>
                        <th class="text-center">تاريخ التحدي</th>
                        <th class="text-center">فترة الانضمام</th>
                        <th class="text-center">حالة الدعوة</th>
                        <th class="text-center">الفائز</th>
                        <th class="text-center">النقاط / النتيجة</th>
                        <th class="text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($challenges as $key => $item)
                        @php
                            // Sender photo
                            $senderUser = $item->sender;
                            $senderName = $senderUser ? (trim(($senderUser->fname ?? '') . ' ' . ($senderUser->lname ?? '')) ?: ($senderUser->user_name ?? $senderUser->name ?? $senderUser->email ?? 'مستخدم محذوف')) : 'مستخدم محذوف';
                            $senderPhoto = asset('upload/no_image.jpg');
                            if ($senderUser && !empty($senderUser->photo) && $senderUser->photo !== 'non') {
                                $sp = $senderUser->photo;
                                if (str_starts_with($sp, 'http://') || str_starts_with($sp, 'https://')) {
                                    $senderPhoto = $sp;
                                } elseif (str_starts_with($sp, 'upload/user_images/')) {
                                    if (file_exists(public_path($sp))) { $senderPhoto = asset($sp); }
                                } else {
                                    if (file_exists(public_path('upload/user_images/' . $sp))) { $senderPhoto = asset('upload/user_images/' . $sp); }
                                }
                            }

                            // Receiver photo
                            $receiverUser = $item->receiver;
                            $receiverName = $receiverUser ? (trim(($receiverUser->fname ?? '') . ' ' . ($receiverUser->lname ?? '')) ?: ($receiverUser->user_name ?? $receiverUser->name ?? $receiverUser->email ?? 'مستخدم محذوف')) : 'مستخدم محذوف';
                            $receiverPhoto = asset('upload/no_image.jpg');
                            if ($receiverUser && !empty($receiverUser->photo) && $receiverUser->photo !== 'non') {
                                $rp = $receiverUser->photo;
                                if (str_starts_with($rp, 'http://') || str_starts_with($rp, 'https://')) {
                                    $receiverPhoto = $rp;
                                } elseif (str_starts_with($rp, 'upload/user_images/')) {
                                    if (file_exists(public_path($rp))) { $receiverPhoto = asset($rp); }
                                } else {
                                    if (file_exists(public_path('upload/user_images/' . $rp))) { $receiverPhoto = asset('upload/user_images/' . $rp); }
                                }
                            }

                            // Winner
                            $winnerUser = $item->winner;
                            $winnerName = $winnerUser ? (trim(($winnerUser->fname ?? '') . ' ' . ($winnerUser->lname ?? '')) ?: ($winnerUser->user_name ?? $winnerUser->name ?? $winnerUser->email ?? 'غير محدد')) : null;
                        @endphp
                        <tr>
                            <td class="text-center fw-bold">{{ $key + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $senderPhoto }}" class="rounded-circle border shadow-sm" style="width: 40px; height: 40px; object-fit: cover;" alt="Sender Avatar">
                                    <div>
                                        <div class="fw-bold text-dark">{{ $senderName }}</div>
                                        @if($senderUser && $senderUser->email)
                                            <small class="text-muted" style="font-size: 11px;">{{ $senderUser->email }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $receiverPhoto }}" class="rounded-circle border shadow-sm" style="width: 40px; height: 40px; object-fit: cover;" alt="Receiver Avatar">
                                    <div>
                                        <div class="fw-bold text-dark">{{ $receiverName }}</div>
                                        @if($receiverUser && $receiverUser->email)
                                            <small class="text-muted" style="font-size: 11px;">{{ $receiverUser->email }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light-primary text-primary border border-primary px-2 py-1 font-13 fw-bold">
                                    <i class="bx bx-code-alt me-1"></i>{{ $item->game_code }}
                                </span>
                            </td>
                            <td class="text-center text-nowrap">
                                @if($item->date)
                                    <div>{{ $item->date->format('Y-m-d') }}</div>
                                    <small class="text-muted" style="font-size: 11px;">{{ $item->date->format('h:i A') }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center text-nowrap">
                                @if($item->join_start_at || $item->join_end_at)
                                    <div style="font-size: 11px;">
                                        <span class="text-success fw-bold"><i class="bx bx-play-circle me-1"></i>{{ $item->join_start_at ? $item->join_start_at->format('Y-m-d H:i') : '-' }}</span>
                                        <br>
                                        <span class="text-danger fw-bold"><i class="bx bx-stop-circle me-1"></i>{{ $item->join_end_at ? $item->join_end_at->format('Y-m-d H:i') : '-' }}</span>
                                    </div>
                                @else
                                    <span class="text-muted font-12">غير محدد</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @switch($item->invitation_statue)
                                    @case('pending')
                                        <span class="badge bg-warning text-dark px-3 py-1 font-12"><i class="bx bx-time-five me-1"></i>معلقة</span>
                                        @break
                                    @case('accepted')
                                        <span class="badge bg-info px-3 py-1 font-12"><i class="bx bx-check me-1"></i>مقبولة</span>
                                        @break
                                    @case('rejected')
                                        <span class="badge bg-danger px-3 py-1 font-12"><i class="bx bx-x me-1"></i>مرفوضة</span>
                                        @break
                                    @case('completed')
                                    @case('finished')
                                        <span class="badge bg-success px-3 py-1 font-12"><i class="bx bx-check-double me-1"></i>مكتملة</span>
                                        @break
                                    @case('canceled')
                                    @case('cancelled')
                                        <span class="badge bg-secondary px-3 py-1 font-12"><i class="bx bx-block me-1"></i>ملغاة</span>
                                        @break
                                    @default
                                        <span class="badge bg-dark px-3 py-1 font-12">{{ $item->invitation_statue }}</span>
                                @endswitch
                            </td>
                            <td class="text-center">
                                @if($winnerName)
                                    <span class="badge bg-light-success text-success border border-success px-2 py-1 fw-bold font-12">
                                        <i class="bx bx-crown me-1"></i>{{ $winnerName }}
                                    </span>
                                @else
                                    <span class="text-muted font-12">غير محدد</span>
                                @endif
                            </td>
                            <td class="text-center fw-bold">
                                @if($item->score_get !== null)
                                    <span class="badge bg-light-warning text-dark border border-warning px-2 py-1 font-13">
                                        {{ number_format($item->score_get) }} نقطة
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center text-nowrap">
                                <button type="button" class="btn btn-sm btn-info text-white me-1 px-2" onclick="showChallengeDetails({{ $item->id }})" title="عرض التفاصيل">
                                    <i class="bx bx-show font-16 align-middle"></i> تفاصيل
                                </button>

                                @if(Auth::user()->role === 'admin' || Auth::user()->can('حذف التحديات'))
                                    <a href="{{ route('delete.challenge', $item->id) }}" class="btn btn-sm btn-danger px-2" id="delete" title="حذف التحدي">
                                        <i class="bx bx-trash font-16 align-middle"></i> حذف
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th class="text-center">#</th>
                        <th>مرسل الدعوة</th>
                        <th>مستقبل الدعوة</th>
                        <th class="text-center">رمز اللعبة</th>
                        <th class="text-center">تاريخ التحدي</th>
                        <th class="text-center">فترة الانضمام</th>
                        <th class="text-center">حالة الدعوة</th>
                        <th class="text-center">الفائز</th>
                        <th class="text-center">النقاط / النتيجة</th>
                        <th class="text-center">الإجراءات</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Modal Details -->
<div class="modal fade" id="challengeDetailsModal" tabindex="-1" aria-labelledby="challengeDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title d-flex align-items-center gap-2" id="challengeDetailsModalLabel">
                    <i class="bx bx-trophy font-22"></i>
                    <span>تفاصيل التحدي رقم <span id="modalChallengeId"></span></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Loader -->
                <div id="modalLoader" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">جاري التحميل...</span>
                    </div>
                    <p class="mt-2 text-muted">جاري تحميل تفاصيل التحدي...</p>
                </div>

                <!-- Content Container -->
                <div id="modalDetailsContent" style="display: none;">
                    <div class="row g-3 mb-4">
                        <!-- Sender Card -->
                        <div class="col-md-6">
                            <div class="card border border-primary h-100 mb-0 shadow-sm">
                                <div class="card-header bg-light-primary fw-bold text-primary">
                                    <i class="bx bx-paper-plane me-1"></i> مرسل الدعوة
                                </div>
                                <div class="card-body text-center">
                                    <img id="modalSenderPhoto" src="" class="rounded-circle border border-primary p-1 mb-2 shadow-sm" style="width: 75px; height: 75px; object-fit: cover;" alt="Sender">
                                    <h6 id="modalSenderName" class="fw-bold mb-1"></h6>
                                    <p id="modalSenderEmail" class="text-muted font-13 mb-1"></p>
                                    <p id="modalSenderPhone" class="text-muted font-12 mb-0"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Receiver Card -->
                        <div class="col-md-6">
                            <div class="card border border-info h-100 mb-0 shadow-sm">
                                <div class="card-header bg-light-info fw-bold text-info">
                                    <i class="bx bx-envelope me-1"></i> مستقبل الدعوة
                                </div>
                                <div class="card-body text-center">
                                    <img id="modalReceiverPhoto" src="" class="rounded-circle border border-info p-1 mb-2 shadow-sm" style="width: 75px; height: 75px; object-fit: cover;" alt="Receiver">
                                    <h6 id="modalReceiverName" class="fw-bold mb-1"></h6>
                                    <p id="modalReceiverEmail" class="text-muted font-13 mb-1"></p>
                                    <p id="modalReceiverPhone" class="text-muted font-12 mb-0"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Challenge Data Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <tbody>
                                <tr>
                                    <th style="width: 35%;" class="bg-light">رمز اللعبة (Game Code)</th>
                                    <td><span id="modalGameCode" class="badge bg-primary font-14"></span></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">تاريخ التحدي</th>
                                    <td id="modalDate"></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">تاريخ ووقت فتح باب الانضمام</th>
                                    <td id="modalJoinStartAt" class="text-success fw-bold"></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">تاريخ ووقت غلق باب الانضمام</th>
                                    <td id="modalJoinEndAt" class="text-danger fw-bold"></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">حالة الدعوة</th>
                                    <td id="modalStatus"></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">المستخدم الفائز</th>
                                    <td id="modalWinner"></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">النقاط المحققة</th>
                                    <td id="modalScore"></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">تاريخ الإنشاء</th>
                                    <td id="modalCreatedAt"></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">آخر تحديث</th>
                                    <td id="modalUpdatedAt"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

<script>
function showChallengeDetails(challengeId) {
    var modal = new bootstrap.Modal(document.getElementById('challengeDetailsModal'));
    document.getElementById('modalChallengeId').innerText = challengeId;
    document.getElementById('modalLoader').style.display = 'block';
    document.getElementById('modalDetailsContent').style.display = 'none';
    modal.show();

    fetch("{{ url('/challenge/details') }}/" + challengeId)
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                var data = res.data;

                // Sender
                if (data.sender) {
                    document.getElementById('modalSenderPhoto').src = data.sender.photo;
                    document.getElementById('modalSenderName').innerText = data.sender.name;
                    document.getElementById('modalSenderEmail').innerText = data.sender.email;
                    document.getElementById('modalSenderPhone').innerText = 'الهاتف: ' + data.sender.phone;
                } else {
                    document.getElementById('modalSenderPhoto').src = "{{ asset('upload/no_image.jpg') }}";
                    document.getElementById('modalSenderName').innerText = 'مستخدم محذوف';
                    document.getElementById('modalSenderEmail').innerText = '-';
                    document.getElementById('modalSenderPhone').innerText = '-';
                }

                // Receiver
                if (data.receiver) {
                    document.getElementById('modalReceiverPhoto').src = data.receiver.photo;
                    document.getElementById('modalReceiverName').innerText = data.receiver.name;
                    document.getElementById('modalReceiverEmail').innerText = data.receiver.email;
                    document.getElementById('modalReceiverPhone').innerText = 'الهاتف: ' + data.receiver.phone;
                } else {
                    document.getElementById('modalReceiverPhoto').src = "{{ asset('upload/no_image.jpg') }}";
                    document.getElementById('modalReceiverName').innerText = 'مستخدم محذوف';
                    document.getElementById('modalReceiverEmail').innerText = '-';
                    document.getElementById('modalReceiverPhone').innerText = '-';
                }

                // Game Code & Dates
                document.getElementById('modalGameCode').innerText = data.game_code;
                document.getElementById('modalDate').innerText = data.date;
                document.getElementById('modalJoinStartAt').innerText = data.join_start_at || '-';
                document.getElementById('modalJoinEndAt').innerText = data.join_end_at || '-';
                document.getElementById('modalCreatedAt').innerText = data.created_at;
                document.getElementById('modalUpdatedAt').innerText = data.updated_at;

                // Status Badge
                var statusHtml = '';
                switch (data.invitation_statue) {
                    case 'pending':
                        statusHtml = '<span class="badge bg-warning text-dark px-3 py-1 font-13"><i class="bx bx-time-five me-1"></i>معلقة (Pending)</span>';
                        break;
                    case 'accepted':
                        statusHtml = '<span class="badge bg-info px-3 py-1 font-13"><i class="bx bx-check me-1"></i>مقبولة (Accepted)</span>';
                        break;
                    case 'rejected':
                        statusHtml = '<span class="badge bg-danger px-3 py-1 font-13"><i class="bx bx-x me-1"></i>مرفوضة (Rejected)</span>';
                        break;
                    case 'completed':
                    case 'finished':
                        statusHtml = '<span class="badge bg-success px-3 py-1 font-13"><i class="bx bx-check-double me-1"></i>مكتملة (Completed)</span>';
                        break;
                    case 'canceled':
                    case 'cancelled':
                        statusHtml = '<span class="badge bg-secondary px-3 py-1 font-13"><i class="bx bx-block me-1"></i>ملغاة (Canceled)</span>';
                        break;
                    default:
                        statusHtml = '<span class="badge bg-dark px-3 py-1 font-13">' + data.invitation_statue + '</span>';
                }
                document.getElementById('modalStatus').innerHTML = statusHtml;

                // Winner
                if (data.winner) {
                    document.getElementById('modalWinner').innerHTML = '<div class="d-flex align-items-center gap-2"><img src="' + data.winner.photo + '" class="rounded-circle border" style="width: 30px; height: 30px; object-fit: cover;"><span class="fw-bold text-success">' + data.winner.name + '</span></div>';
                } else {
                    document.getElementById('modalWinner').innerHTML = '<span class="text-muted">لم يحدد بعد</span>';
                }

                // Score
                document.getElementById('modalScore').innerHTML = data.score_get !== 'غير محدد' ? '<span class="badge bg-warning text-dark font-13">' + data.score_get + ' نقطة</span>' : '<span class="text-muted">غير محدد</span>';

                document.getElementById('modalLoader').style.display = 'none';
                document.getElementById('modalDetailsContent').style.display = 'block';
            }
        })
        .catch(err => {
            console.error('Error fetching challenge details:', err);
            document.getElementById('modalLoader').innerHTML = '<div class="alert alert-danger mb-0">حدث خطأ أثناء تحميل البيانات. يرجى المحاولة لاحقاً.</div>';
        });
}
</script>

@endsection
