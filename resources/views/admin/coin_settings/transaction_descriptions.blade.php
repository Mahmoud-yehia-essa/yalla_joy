@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">

<div class="page-wrapper">
    <div class="page-content" style="font-family: 'Cairo', sans-serif;">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">إعدادات العملات</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">نصوص حركات وسجل العملات</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-9 mx-auto">
                
                <!-- Page Info Header Card -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 text-white" style="background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: rgba(255,255,255,0.18); font-size: 28px;">
                                🪙
                            </div>
                            <div>
                                <h4 class="mb-1 fw-bold text-white">إعدادات نصوص حركات وسجل العملات</h4>
                                <p class="mb-0 text-white-50" style="font-size: 14px;">
                                    يمكنك من هنا تخصيص وتعديل النصوص التوضيحية لجميع عمليات كسب أو خصم أو شراء العملات التي تظهر للمستخدم داخل التطبيق في سجل المحفظة ("تفاصيل العملات").
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Display Validation Errors --}}
                @if($errors->any())
                    <div class="alert alert-danger border-0 alert-dismissible fade show shadow-sm rounded-3">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('coin.transaction.settings.update') }}">
                    @csrf

                    <!-- 1. الشراء الإلكتروني للباقات -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-pill p-2"><i class="bx bx-credit-card-alt fs-5"></i></span>
                            <h5 class="mb-0 fw-bold text-dark" style="font-size: 16px;">1. الشراء الإلكتروني للباقات (Ottu / KNET)</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary" style="font-size: 14px;">
                                    وصف عملية الشراء في سجل المحفظة
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg @error('coin_desc_online_purchase') is-invalid @enderror" 
                                       name="coin_desc_online_purchase" 
                                       value="{{ old('coin_desc_online_purchase', $appVersion->coin_desc_online_purchase ?? 'شراء باقة عملات عبر الدفع الإلكتروني') }}"
                                       placeholder="مثال: شراء باقة عملات عبر الدفع الإلكتروني">
                                @error('coin_desc_online_purchase')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text text-muted mt-2" style="font-size: 13px;">
                                    <i class="bx bx-info-circle text-primary"></i>
                                    <strong>تلميح:</strong> هذا النص هو الذي يظهر أسفل اسم العملة في شاشة "تفاصيل العملات التي تم الحصول عليها" بعد الدفع بنجاح. يمكنك أيضاً استخدام <code class="text-primary">{package_title}</code> إذا رغبت بإدراج اسم الباقة تلقائياً (مثال: <code>شراء {package_title} عبر الدفع الإلكتروني</code>).
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. الفوز في التحديات والترقيات -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                            <span class="badge bg-warning rounded-pill p-2 text-dark"><i class="bx bx-trophy fs-5"></i></span>
                            <h5 class="mb-0 fw-bold text-dark" style="font-size: 16px;">2. مكافآت الفوز في التحديات والترقيات</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-secondary" style="font-size: 14px;">
                                        وصف مكافأة الفوز في التحدي
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('coin_desc_game_win') is-invalid @enderror" 
                                           name="coin_desc_game_win" 
                                           value="{{ old('coin_desc_game_win', $appVersion->coin_desc_game_win ?? 'مكافأة الفوز في التحدي') }}"
                                           placeholder="مكافأة الفوز في التحدي">
                                    @error('coin_desc_game_win')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-muted">يظهر عند كسب المستخدم عملات بعد الفوز بمستوى/جولة.</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-secondary" style="font-size: 14px;">
                                        وصف مكافأة الترقية إلى رتبة جديدة
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('coin_desc_rank_upgrade') is-invalid @enderror" 
                                           name="coin_desc_rank_upgrade" 
                                           value="{{ old('coin_desc_rank_upgrade', $appVersion->coin_desc_rank_upgrade ?? 'مكافأة الترقية إلى رتبة جديدة') }}"
                                           placeholder="مكافأة الترقية إلى رتبة جديدة">
                                    @error('coin_desc_rank_upgrade')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-muted">يظهر عند حصول المستخدم على رتبة جديدة بمكافأة عملات.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. استبدال الكوبونات والمتاجر -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                            <span class="badge bg-success rounded-pill p-2"><i class="bx bx-gift fs-5"></i></span>
                            <h5 class="mb-0 fw-bold text-dark" style="font-size: 16px;">3. استبدال الكوبونات وشراء عناصر المتجر</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary" style="font-size: 14px;">
                                        وصف استبدال كوبون خصم
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('coin_desc_coupon_exchange') is-invalid @enderror" 
                                           name="coin_desc_coupon_exchange" 
                                           value="{{ old('coin_desc_coupon_exchange', $appVersion->coin_desc_coupon_exchange ?? 'استبدال عملات بكوبون خصم') }}"
                                           placeholder="استبدال عملات بكوبون خصم">
                                    @error('coin_desc_coupon_exchange')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-muted">يظهر عند خصم عملات لاستبدال كوبون شركة.</div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary" style="font-size: 14px;">
                                        وصف شراء عنصر أفاتار
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('coin_desc_avatar_purchase') is-invalid @enderror" 
                                           name="coin_desc_avatar_purchase" 
                                           value="{{ old('coin_desc_avatar_purchase', $appVersion->coin_desc_avatar_purchase ?? 'شراء عنصر من متجر الأفاتار') }}"
                                           placeholder="شراء عنصر من متجر الأفاتار">
                                    @error('coin_desc_avatar_purchase')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-muted">يظهر عند خصم عملات لشراء مظهر/أفاتار.</div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary" style="font-size: 14px;">
                                        وصف شراء حركة تفاعلية
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('coin_desc_animation_purchase') is-invalid @enderror" 
                                           name="coin_desc_animation_purchase" 
                                           value="{{ old('coin_desc_animation_purchase', $appVersion->coin_desc_animation_purchase ?? 'شراء حركة تفاعلية من متجر الحركات') }}"
                                           placeholder="شراء حركة تفاعلية من متجر الحركات">
                                    @error('coin_desc_animation_purchase')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-muted">يظهر عند خصم عملات لشراء حركة أنيميشن.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. تعديلات الإدارة اليدوية -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                            <span class="badge bg-secondary rounded-pill p-2"><i class="bx bx-slider-alt fs-5"></i></span>
                            <h5 class="mb-0 fw-bold text-dark" style="font-size: 16px;">4. تعديل الرصيد اليدوي من الإدارة</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-2">
                                <label class="form-label fw-bold text-secondary" style="font-size: 14px;">
                                    الوصف الافتراضي عند إضافة أو خصم عملات للمستخدم يدوياً من لوحة التحكم
                                </label>
                                <input type="text" 
                                       class="form-control @error('coin_desc_admin_adjustment') is-invalid @enderror" 
                                       name="coin_desc_admin_adjustment" 
                                       value="{{ old('coin_desc_admin_adjustment', $appVersion->coin_desc_admin_adjustment ?? 'تعديل رصيد من إدارة التطبيق') }}"
                                       placeholder="تعديل رصيد من إدارة التطبيق">
                                @error('coin_desc_admin_adjustment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex align-items-center justify-content-between p-3 bg-white rounded-4 shadow-sm">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary px-4 rounded-3 fw-bold">
                            <i class="bx bx-arrow-back me-1"></i> العودة للرئيسية
                        </a>
                        <button type="submit" class="btn btn-primary px-5 py-2.5 rounded-3 fw-bold shadow">
                            <i class="bx bx-save me-1"></i> حفظ وتحديث النصوص
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection
