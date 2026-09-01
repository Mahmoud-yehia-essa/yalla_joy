@extends('admin.master_admin')
@section('admin')

<style>
    .stat-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .stat-icon-circle {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
    }
    .user-avatar-sm {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e2e8f0;
    }
    .action-btn-pill {
        border-radius: 8px;
        padding: 5px 9px;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.15s ease;
    }
    .action-btn-pill:hover {
        transform: scale(1.05);
    }
    .package-tag {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 4px 10px;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }
</style>

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">التقارير المالية</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">التقارير المالية وعمليات الشراء</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="{{ route('export.transactions', request()->query()) }}" class="btn btn-success px-3 d-flex align-items-center gap-1 shadow-sm">
            <i class="bx bx-download fs-5"></i> <span>تصدير تقرير إكسيل (Excel)</span>
        </a>
    </div>
</div>
<!--end breadcrumb-->

<!-- ═══════════════════════════════════════════════════════════════════════ -->
<!-- TOP STATS CARDS -->
<!-- ═══════════════════════════════════════════════════════════════════════ -->
<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-3 mb-4">
    <!-- 1. Total Revenue -->
    <div class="col">
        <div class="card stat-card bg-gradient-cosmic text-white h-100" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="mb-1 text-white-50" style="font-size: 13px;">إجمالي الإيرادات الناجحة</h6>
                        <h3 class="mb-0 text-white fw-bold">{{ number_format($stats['total_revenue'], 3) }} <span style="font-size: 15px;">د.ك</span></h3>
                    </div>
                    <div class="stat-icon-circle bg-white bg-opacity-25 text-white">
                        <i class="bx bx-wallet"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Successful Transactions -->
    <div class="col">
        <div class="card stat-card h-100" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
            <div class="card-body p-3 text-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="mb-1 text-white-50" style="font-size: 13px;">العمليات الناجحة (مدفوعة)</h6>
                        <h3 class="mb-0 text-white fw-bold">{{ $stats['success_count'] }} <span style="font-size: 14px;">عملية</span></h3>
                    </div>
                    <div class="stat-icon-circle bg-white bg-opacity-25 text-white">
                        <i class="bx bx-check-shield"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Failed/Cancelled Transactions -->
    <div class="col">
        <div class="card stat-card h-100" style="background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);">
            <div class="card-body p-3 text-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="mb-1 text-white-50" style="font-size: 13px;">العمليات الفاشلة / ملغاة</h6>
                        <h3 class="mb-0 text-white fw-bold">{{ $stats['failed_count'] }} <span style="font-size: 14px;">عملية</span></h3>
                    </div>
                    <div class="stat-icon-circle bg-white bg-opacity-25 text-white">
                        <i class="bx bx-x-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Total Games/Items Purchased -->
    <div class="col">
        <div class="card stat-card h-100" style="background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);">
            <div class="card-body p-3 text-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="mb-1 text-white-50" style="font-size: 13px;">الألعاب والعملات المشتراة</h6>
                        <h4 class="mb-0 text-white fw-bold">
                            {{ $stats['total_games_purchased'] }} <span style="font-size: 13px;">لعبة</span> 
                            @if($stats['total_coins_purchased'] > 0)
                            + {{ $stats['total_coins_purchased'] }} <span style="font-size: 13px;">عملة</span>
                            @endif
                        </h4>
                    </div>
                    <div class="stat-icon-circle bg-white bg-opacity-25 text-white">
                        <i class="bx bx-package"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════════════════ -->
<!-- FILTERS CARD -->
<!-- ═══════════════════════════════════════════════════════════════════════ -->
<div class="card mb-4 border-0 shadow-sm" style="border-radius: 14px;">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('all.transactions') }}" class="row g-2 align-items-end">
            <!-- 1. Search -->
            <div class="col-lg-3 col-md-6">
                <label class="form-label fw-bold text-muted small mb-1">بحث برقم الطلب، العميل، أو المعرف:</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bx bx-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="رقم الطلب، المعرف، الاسم، الهاتف..." value="{{ request('search') }}">
                </div>
            </div>

            <!-- 2. Status -->
            <div class="col-lg-2 col-md-4 col-sm-6">
                <label class="form-label fw-bold text-muted small mb-1">حالة العملية:</label>
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>جميع الحالات</option>
                    <option value="paid" {{ request('status') == 'paid' || request('status') == 'success' ? 'selected' : '' }}>ناجحة (تم الدفع)</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>فشلت العملية</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغاة</option>
                </select>
            </div>

            <!-- 3. Package Type -->
            <div class="col-lg-2 col-md-4 col-sm-6">
                <label class="form-label fw-bold text-muted small mb-1">نوع المشتريات:</label>
                <select name="package_type" class="form-select" onchange="this.form.submit()">
                    <option value="all" {{ request('package_type') == 'all' || !request('package_type') ? 'selected' : '' }}>الكل</option>
                    <option value="games" {{ request('package_type') == 'games' ? 'selected' : '' }}>شراء ألعاب</option>
                    <option value="coins" {{ request('package_type') == 'coins' ? 'selected' : '' }}>شراء عملات</option>
                </select>
            </div>

            <!-- 4. Date From -->
            <div class="col-lg-2 col-md-4 col-sm-6">
                <label class="form-label fw-bold text-muted small mb-1">من تاريخ:</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>

            <!-- 5. Date To -->
            <div class="col-lg-2 col-md-4 col-sm-6">
                <label class="form-label fw-bold text-muted small mb-1">إلى تاريخ:</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>

            <!-- Buttons -->
            <div class="col-lg-1 col-md-4 d-flex gap-1">
                <button type="submit" class="btn btn-primary w-100" title="تصفية"><i class="bx bx-filter-alt"></i></button>
                @if(request()->hasAny(['search', 'status', 'package_type', 'date_from', 'date_to']))
                    <a href="{{ route('all.transactions') }}" class="btn btn-outline-secondary" title="إلغاء الفلاتر"><i class="bx bx-reset"></i></a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════════════════ -->
<!-- DATA TABLE CARD -->
<!-- ═══════════════════════════════════════════════════════════════════════ -->
<div class="card border-0 shadow-sm" style="border-radius: 14px;">
    <div class="card-body p-3">
        <div class="table-responsive">
            <table id="transactionsTable" class="table table-hover align-middle mb-0" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">#</th>
                        <th>رقم الطلب والمعرّف (Ottu)</th>
                        <th>العميل / المستخدم</th>
                        <th>تفاصيل الباقة</th>
                        <th>المبلغ</th>
                        <th>وسيلة الدفع ومراجع البوابة</th>
                        <th class="text-center">الحالة</th>
                        <th>تاريخ العملية</th>
                        <th class="text-center" style="min-width: 170px;">الإجراءات والتواصل</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $index => $item)
                    @php
                        $user = $item->user;
                        $customerName = $user ? trim(($user->fname ?? '') . ' ' . ($user->lname ?? '')) : ($item->customer_name ?? 'زائر');
                        if (empty($customerName)) {
                            $customerName = $user->user_name ?? 'مستخدم';
                        }
                        $customerEmail = $user->email ?? ($item->customer_email ?? '');
                        $customerPhone = $user->phone ?? ($item->customer_phone ?? '');
                        $cleanPhone = preg_replace('/[^0-9]/', '', $customerPhone);
                        $statusLower = strtolower($item->status ?? '');
                        $isPaid = in_array($statusLower, ['paid', 'success', 'captured', 'completed', 'approved', 'processed']);
                        $isFailed = in_array($statusLower, ['failed', 'error']);
                        $isCancelled = in_array($statusLower, ['cancelled', 'canceled']);
                        $paymentRef = $item->gateway_payment_id ?: ($item->gateway_ref_number ?: '');
                    @endphp
                    <tr>
                        <!-- # -->
                        <td class="text-center text-muted fw-bold">{{ $index + 1 }}</td>

                        <!-- Order No & Ottu Session ID -->
                        <td>
                            <div class="fw-bold text-dark font-monospace" style="font-size: 13.5px;">
                                {{ $item->order_no }}
                            </div>
                            @if($item->session_id)
                            <div class="d-flex align-items-center gap-1 text-muted mt-1" style="font-size: 11px;">
                                <span class="badge bg-light text-secondary border font-monospace" title="Ottu Session ID">
                                    Ottu: {{ Str::limit($item->session_id, 14) }}
                                </span>
                                <button type="button" class="btn btn-link btn-sm p-0 text-secondary" 
                                        onclick="copyText('{{ $item->session_id }}', 'تم نسخ Session ID بنجاح')" 
                                        title="نسخ Session ID">
                                    <i class="bx bx-copy"></i>
                                </button>
                            </div>
                            @endif
                        </td>

                        <!-- Customer / User Info -->
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($user && $user->photo)
                                    <img src="{{ asset('upload/user_images/' . $user->photo) }}" class="user-avatar-sm" alt="user" onerror="this.src='{{ asset('upload/no_image.jpg') }}'">
                                @else
                                    <div class="user-avatar-sm bg-light d-flex align-items-center justify-content-center text-secondary fw-bold">
                                        <i class="bx bx-user fs-4"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-bold text-dark" style="font-size: 13.5px;">
                                        @if($user)
                                            <a href="{{ route('edit.user', $user->id) }}" class="text-primary text-decoration-none">
                                                {{ $customerName }}
                                            </a>
                                        @else
                                            {{ $customerName }}
                                        @endif
                                    </div>
                                    @if($customerEmail)
                                        <div class="text-muted small" style="font-size: 11.5px;">
                                            <i class="bx bx-envelope"></i> {{ $customerEmail }}
                                        </div>
                                    @endif
                                    @if($customerPhone)
                                        <div class="text-muted small" style="font-size: 11.5px;">
                                            <i class="bx bx-phone"></i> {{ $customerPhone }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Package details -->
                        <td>
                            <span class="package-tag">
                                @if($item->package_type === 'coins')
                                    <i class="bx bx-coin-stack text-warning me-1"></i>
                                @else
                                    <i class="bx bx-joystick text-primary me-1"></i>
                                @endif
                                {{ $item->package_title ?? 'باقة ألعاب' }}
                            </span>
                            <div class="mt-1 small text-muted" style="font-size: 12px;">
                                @if($item->games_count > 0)
                                    <span class="badge bg-light text-dark border"><i class="bx bx-game"></i> {{ $item->games_count }} ألعاب</span>
                                @endif
                                @if($item->coins_count > 0)
                                    <span class="badge bg-light text-warning border"><i class="bx bx-coin"></i> +{{ $item->coins_count }} عملة</span>
                                @endif
                            </div>
                        </td>

                        <!-- Amount -->
                        <td>
                            <div class="fw-bold text-success" style="font-size: 15px;">
                                {{ number_format((float)$item->amount, 3) }} <span class="small text-muted" style="font-size: 11px;">د.ك</span>
                            </div>
                        </td>

                        <!-- Payment Method & Gateway Reference -->
                        <td>
                            <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 12px; font-weight: 600;">
                                <i class="bx bx-credit-card me-1 text-primary"></i>{{ $item->gateway_method_name }}
                            </span>
                            @if(!empty($paymentRef))
                            <div class="mt-1 small font-monospace text-muted" style="font-size: 11px;">
                                <span class="badge bg-light text-secondary border" title="Payment / Reference ID">
                                    Ref: {{ Str::limit($paymentRef, 12) }}
                                </span>
                            </div>
                            @endif
                        </td>

                        <!-- Status -->
                        <td class="text-center">
                            @if($isPaid)
                                <span class="badge bg-success text-white px-2 py-2" style="font-size: 12px; border-radius: 8px;">
                                    <i class="bx bx-check-circle me-1"></i> ناجحة (تم الدفع)
                                </span>
                            @elseif($isFailed)
                                <span class="badge bg-danger text-white px-2 py-2" style="font-size: 12px; border-radius: 8px;">
                                    <i class="bx bx-x-circle me-1"></i> فشلت العملية
                                </span>
                            @elseif($isCancelled)
                                <span class="badge bg-secondary text-white px-2 py-2" style="font-size: 12px; border-radius: 8px;">
                                    <i class="bx bx-block me-1"></i> ملغاة
                                </span>
                            @else
                                <span class="badge bg-warning text-dark px-2 py-2" style="font-size: 12px; border-radius: 8px;">
                                    <i class="bx bx-time me-1"></i> قيد الانتظار
                                </span>
                            @endif
                        </td>

                        <!-- Date & Time -->
                        <td>
                            <div class="text-dark small fw-semibold" style="font-size: 12px;">
                                {{ $item->created_at ? $item->created_at->format('Y-m-d') : '---' }}
                            </div>
                            <div class="text-muted" style="font-size: 11px;">
                                {{ $item->created_at ? $item->created_at->format('h:i A') : '' }}
                            </div>
                        </td>

                        <!-- Actions & Communication Buttons -->
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center gap-1 flex-wrap">
                                <!-- 1. Send Push Notification Button -->
                                @if($user)
                                <button type="button" class="btn btn-sm btn-outline-primary action-btn-pill" 
                                        onclick="openNotificationModal({{ $user->id }}, '{{ addslashes($customerName) }}', '{{ $item->order_no }}', '{{ $item->status_arabic }}')"
                                        title="إرسال إشعار للمستخدم في التطبيق">
                                    <i class="bx bx-bell"></i> <span>إشعار</span>
                                </button>
                                @endif

                                <!-- 2. WhatsApp Button -->
                                @if(!empty($cleanPhone))
                                @php
                                    $waMessage = urlencode("مرحباً {$customerName}، بخصوص طلبكم رقم ({$item->order_no}) لشراء ({$item->package_title}) في تطبيق فيك تحدي...");
                                @endphp
                                <a href="https://wa.me/{{ $cleanPhone }}?text={{ $waMessage }}" target="_blank" 
                                   class="btn btn-sm btn-outline-success action-btn-pill" 
                                   title="تواصل عبر الواتساب">
                                    <i class="bx bxl-whatsapp fs-6"></i> <span>واتساب</span>
                                </a>
                                @endif

                                <!-- 3. Email Button -->
                                @if(!empty($customerEmail))
                                @php
                                    $mailSubject = rawurlencode("تطبيق فيك تحدي - بخصوص طلب الشراء رقم {$item->order_no}");
                                    $mailBody = rawurlencode("عزيزي {$customerName}،\n\nتحية طيبة وبعد،\nبخصوص طلبكم رقم {$item->order_no} الخاصة بـ {$item->package_title} بقيمة {$item->amount} د.ك...\n\nمع تحيات فريق تطبيق فيك تحدي.");
                                @endphp
                                <a href="mailto:{{ $customerEmail }}?subject={{ $mailSubject }}&body={{ $mailBody }}" 
                                   class="btn btn-sm btn-outline-info action-btn-pill" 
                                   title="إرسال بريد إلكتروني">
                                    <i class="bx bx-envelope fs-6"></i>
                                </a>
                                @endif

                                <!-- 4. Call Phone Button -->
                                @if(!empty($customerPhone))
                                <a href="tel:{{ $customerPhone }}" 
                                   class="btn btn-sm btn-outline-secondary action-btn-pill" 
                                   title="اتصال هاتفي">
                                    <i class="bx bx-phone fs-6"></i>
                                </a>
                                @endif

                                <!-- 5. View Details Button -->
                                <button type="button" class="btn btn-sm btn-dark action-btn-pill" 
                                        onclick="viewTransactionDetails({{ $item->id }})" 
                                        title="تفاصيل العملية الكاملة وتفاصيل بوابة Ottu">
                                    <i class="bx bx-show fs-6"></i> <span>تفاصيل</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            <i class="bx bx-receipt fs-1 d-block mb-2 text-secondary"></i>
                            لا توجد أي عمليات شراء مسجلة تطابق الفلاتر المحددة.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════════════════ -->
<!-- MODAL 1: SEND NOTIFICATION TO USER -->
<!-- ═══════════════════════════════════════════════════════════════════════ -->
<div class="modal fade" id="sendNotificationModal" tabindex="-1" aria-labelledby="sendNotificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header bg-primary text-white" style="border-radius: 16px 16px 0 0;">
                <h5 class="modal-title d-flex align-items-center gap-2" id="sendNotificationModalLabel">
                    <i class="bx bx-bell fs-4"></i> إرسال إشعار للمستخدم
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="sendNotificationForm">
                @csrf
                <div class="modal-body p-4">
                    <input type="hidden" name="user_id" id="modalUserId">

                    <div class="alert alert-info d-flex align-items-center gap-2 py-2 mb-3" style="border-radius: 10px;">
                        <i class="bx bx-user fs-4 text-primary"></i>
                        <div>
                            <span class="small text-muted">المستلم: </span>
                            <strong id="modalUserName" class="text-dark">---</strong>
                            <span class="badge bg-secondary ms-2" id="modalOrderTag"></span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">عنوان الإشعار <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="modalNotifTitle" class="form-control" placeholder="مثال: تحديث بخصوص طلب الشراء الخاص بك" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">نص الإشعار <span class="text-danger">*</span></label>
                        <textarea name="description" id="modalNotifBody" class="form-control" rows="4" placeholder="اكتب نص الإشعار هنا..." required></textarea>
                    </div>

                    <div id="notifAlertBox" style="display: none;" class="alert mb-0"></div>
                </div>
                <div class="modal-footer bg-light" style="border-radius: 0 0 16px 16px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" id="btnSubmitNotification" class="btn btn-primary d-flex align-items-center gap-1">
                        <i class="bx bx-send"></i> <span>إرسال الإشعار الآن</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════════════════ -->
<!-- MODAL 2: TRANSACTION FULL DETAILS & OTTU GATEWAY REFERENCE -->
<!-- ═══════════════════════════════════════════════════════════════════════ -->
<div class="modal fade" id="transactionDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header px-4 py-3" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important; border-bottom: 1px solid rgba(255,255,255,0.1);">
                <h5 class="modal-title d-flex align-items-center gap-2 m-0" style="color: #ffffff !important; font-weight: 700; font-size: 1.15rem;">
                    <i class="bx bx-receipt fs-4 text-warning"></i> <span style="color: #ffffff !important;">تفاصيل عملية الشراء والدفع ومراجع Ottu</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="transactionDetailsBody">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <div class="mt-2 text-muted">جاري تحميل تفاصيل العملية...</div>
                </div>
            </div>
            <div class="modal-footer bg-light px-4 py-2" style="border-top: 1px solid #e2e8f0;">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // ── Helper Copy Function ──
    function copyText(text, successMsg) {
        if (!text) return;
        navigator.clipboard.writeText(text).then(function() {
            if (typeof toastr !== 'undefined') {
                toastr.success(successMsg || 'تم النسخ بنجاح');
            } else {
                alert(successMsg || 'تم النسخ بنجاح: ' + text);
            }
        });
    }

    // ── Helper Date Formatter ──
    function formatDateTime(dateStr) {
        if (!dateStr) return '---';
        try {
            const d = new Date(dateStr);
            if (isNaN(d.getTime())) return dateStr;
            const yyyy = d.getFullYear();
            const mm = String(d.getMonth() + 1).padStart(2, '0');
            const dd = String(d.getDate()).padStart(2, '0');
            let hours = d.getHours();
            const minutes = String(d.getMinutes()).padStart(2, '0');
            const ampm = hours >= 12 ? 'م' : 'ص';
            hours = hours % 12;
            hours = hours ? hours : 12;
            return `${yyyy}-${mm}-${dd} ${String(hours).padStart(2, '0')}:${minutes} ${ampm}`;
        } catch (e) {
            return dateStr;
        }
    }

    // ── Open Notification Modal ──
    function openNotificationModal(userId, userName, orderNo, statusArabic) {
        $('#modalUserId').val(userId);
        $('#modalUserName').text(userName);
        $('#modalOrderTag').text(orderNo);
        $('#modalNotifTitle').val('تحديث بخصوص طلبكم (' + orderNo + ')');
        $('#modalNotifBody').val('مرحباً ' + userName + '، بخصوص عملية الشراء رقم ' + orderNo + ' (' + statusArabic + ') في تطبيق فيك تحدي...');
        $('#notifAlertBox').hide();
        $('#sendNotificationModal').modal('show');
    }

    // ── Submit Notification via AJAX ──
    $('#sendNotificationForm').on('submit', function(e) {
        e.preventDefault();
        const btn = $('#btnSubmitNotification');
        const alertBox = $('#notifAlertBox');

        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> جاري الإرسال...');
        alertBox.hide();

        $.ajax({
            url: "{{ route('transaction.send.notification') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                btn.prop('disabled', false).html('<i class="bx bx-send"></i> <span>إرسال الإشعار الآن</span>');
                if (response.success) {
                    alertBox.removeClass('alert-danger').addClass('alert-success').text(response.message).show();
                    setTimeout(function() {
                        $('#sendNotificationModal').modal('hide');
                    }, 1500);
                } else {
                    alertBox.removeClass('alert-success').addClass('alert-danger').text(response.message).show();
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="bx bx-send"></i> <span>إرسال الإشعار الآن</span>');
                let err = 'حدث خطأ أثناء إرسال الإشعار.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    err = xhr.responseJSON.message;
                }
                alertBox.removeClass('alert-success').addClass('alert-danger').text(err).show();
            }
        });
    });

    // ── View Transaction Details in Modal ──
    function viewTransactionDetails(id) {
        $('#transactionDetailsModal').modal('show');
        $('#transactionDetailsBody').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status"></div>
                <div class="mt-2 text-muted">جاري تحميل تفاصيل العملية...</div>
            </div>
        `);

        $.ajax({
            url: "/admin/transactions/details/" + id,
            type: "GET",
            success: function(response) {
                if (response.success && response.data) {
                    const tx = response.data;
                    const user = tx.user;
                    const userName = user ? (user.fname + ' ' + (user.lname || '')) : (tx.customer_name || 'زائر');
                    const userEmail = user ? user.email : (tx.customer_email || '---');
                    const userPhone = user ? user.phone : (tx.customer_phone || '---');

                    let statusHtml = '';
                    const st = (tx.status || '').toLowerCase();
                    if (['paid', 'success', 'captured', 'completed', 'approved', 'processed'].includes(st)) {
                        statusHtml = '<span class="badge bg-success text-white px-3 py-2 fs-6"><i class="bx bx-check-circle me-1"></i> ناجحة (تم الدفع)</span>';
                    } else if (['failed', 'error'].includes(st)) {
                        statusHtml = '<span class="badge bg-danger text-white px-3 py-2 fs-6"><i class="bx bx-x-circle me-1"></i> فشلت العملية</span>';
                    } else if (['cancelled', 'canceled'].includes(st)) {
                        statusHtml = '<span class="badge bg-secondary text-white px-3 py-2 fs-6"><i class="bx bx-block me-1"></i> ملغاة</span>';
                    } else {
                        statusHtml = '<span class="badge bg-warning text-dark px-3 py-2 fs-6"><i class="bx bx-time me-1"></i> قيد الانتظار</span>';
                    }

                    let contentHtml = `
                        <div class="row g-3">
                            <!-- 1. Customer Info -->
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 border h-100">
                                    <h6 class="fw-bold text-primary mb-3"><i class="bx bx-user me-1"></i> بيانات العميل</h6>
                                    <p class="mb-1"><strong>الاسم:</strong> ${userName}</p>
                                    <p class="mb-1"><strong>البريد:</strong> ${userEmail}</p>
                                    <p class="mb-0"><strong>الهاتف:</strong> ${userPhone}</p>
                                </div>
                            </div>

                            <!-- 2. Order & Amount -->
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 border h-100">
                                    <h6 class="fw-bold text-success mb-3"><i class="bx bx-dollar-circle me-1"></i> بيانات الطلب والمبلغ</h6>
                                    <p class="mb-1"><strong>رقم الطلب (Order No):</strong> <span class="font-monospace fw-bold text-dark">${tx.order_no}</span></p>
                                    <p class="mb-1"><strong>المبلغ الإجمالي:</strong> <span class="text-success fw-bold fs-6">${parseFloat(tx.amount).toFixed(3)} ${tx.currency}</span></p>
                                    <p class="mb-0"><strong>حالة العملية:</strong> ${statusHtml}</p>
                                </div>
                            </div>

                            <!-- 3. Package Info -->
                            <div class="col-12">
                                <div class="p-3 bg-light rounded-3 border">
                                    <h6 class="fw-bold text-dark mb-2"><i class="bx bx-package me-1"></i> تفاصيل الباقة والمشتريات</h6>
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>اسم الباقة:</strong> ${tx.package_title || '---'}</p>
                                            <p class="mb-0"><strong>نوع الباقة:</strong> ${tx.package_type === 'coins' ? 'عملات اللعبة' : 'ألعاب'}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>عدد الألعاب المضافة:</strong> ${tx.games_count || 0}</p>
                                            <p class="mb-0"><strong>عدد العملات المضافة:</strong> ${tx.coins_count || 0}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 4. Ottu Gateway & Bank References -->
                            <div class="col-12">
                                <div class="p-3 rounded-3 border border-primary border-opacity-25" style="background-color: #f8fafc !important;">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="fw-bold text-primary mb-0">
                                            <i class="bx bx-credit-card-front me-1"></i> تفاصيل ومراجع بوابة الدفع (Ottu Gateway Details)
                                        </h6>
                                        <span class="badge bg-primary text-white">Ottu Integration</span>
                                    </div>
                                    
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <p class="mb-2">
                                                <strong>معرف جلسة Ottu (Session ID):</strong><br>
                                                <span class="font-monospace small bg-white p-1 border rounded d-inline-block text-break">${tx.session_id || '---'}</span>
                                                ${tx.session_id ? `<button type="button" class="btn btn-sm btn-outline-secondary ms-1 py-0 px-1" onclick="copyText('${tx.session_id}', 'تم نسخ Session ID')"><i class="bx bx-copy"></i></button>` : ''}
                                            </p>
                                            <p class="mb-1"><strong>وسيلة الدفع (Method):</strong> <span class="badge bg-dark">${tx.gateway_method_name || (tx.pg_code || 'KNET').toUpperCase()}</span></p>
                                            <p class="mb-1"><strong>رسوم البوابة (Fee):</strong> ${tx.gateway_fee || '0.000'} ${tx.currency}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>رقم العملية البنكية (Payment ID):</strong> <span class="font-monospace">${tx.gateway_payment_id || '---'}</span></p>
                                            <p class="mb-1"><strong>الرقم المرجعي (Reference No / RRN):</strong> <span class="font-monospace">${tx.gateway_ref_number || '---'}</span></p>
                                            <p class="mb-1"><strong>رقم التتبع (Track ID):</strong> <span class="font-monospace">${tx.gateway_track_id || '---'}</span></p>
                                            <p class="mb-0"><strong>تاريخ الدفع:</strong> ${formatDateTime(tx.paid_at)}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 5. Timestamps -->
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center text-muted small p-2 bg-light border rounded">
                                    <div><strong>تاريخ إنشاء الطلب:</strong> <span class="font-monospace text-dark ms-1">${formatDateTime(tx.created_at)}</span></div>
                                    <div><strong>آخر تحديث:</strong> <span class="font-monospace text-dark ms-1">${formatDateTime(tx.updated_at)}</span></div>
                                </div>
                            </div>
                        </div>
                    `;
                    $('#transactionDetailsBody').html(contentHtml);
                }
            },
            error: function() {
                $('#transactionDetailsBody').html('<div class="alert alert-danger mb-0">تعذر جلب تفاصيل العملية.</div>');
            }
        });
    }
</script>

@endsection
