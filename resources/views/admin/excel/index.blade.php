@extends('admin.master_admin')
@section('admin')

<style>
    /* Wizard Steps */
    .step-wizard {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-bottom: 2rem;
    }
    .step-wizard-item {
        flex: 1;
        text-align: center;
        position: relative;
    }
    .step-wizard-item::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 3px;
        background-color: #e2e8f0;
        top: 20px;
        right: 50%;
        z-index: 1;
    }
    .step-wizard-item:first-child::after {
        display: none;
    }
    .step-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #ffffff;
        border: 2px solid #cbd5e1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #64748b;
        position: relative;
        z-index: 2;
        transition: all 0.3s ease;
    }
    .step-wizard-item.active .step-icon {
        background-color: #10b981;
        border-color: #10b981;
        color: #ffffff;
        box-shadow: 0 0 12px rgba(16, 185, 129, 0.45);
    }
    .step-wizard-item.completed .step-icon {
        background-color: #059669;
        border-color: #059669;
        color: #ffffff;
    }
    .step-title {
        font-size: 0.9rem;
        margin-top: 0.5rem;
        color: #475569;
        font-weight: 600;
    }
    .step-wizard-item.active .step-title {
        color: #0f172a;
        font-weight: 700;
    }

    /* Cards & Tables */
    .card-step {
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.04);
        background: #ffffff;
    }
    .nav-tabs-custom .nav-link {
        border-radius: 10px 10px 0 0;
        padding: 12px 22px;
        font-weight: 600;
        color: #64748b;
        border: none;
        border-bottom: 3px solid transparent;
    }
    .nav-tabs-custom .nav-link.active {
        color: #10b981;
        background: transparent;
        border-bottom: 3px solid #10b981;
        font-weight: 700;
    }
    .table-responsive-custom {
        max-height: 560px;
        overflow-y: auto;
        border-radius: 8px;
    }
    .table-responsive-custom thead th {
        position: sticky !important;
        top: 0;
        background-color: #f8fafc !important;
        z-index: 20 !important;
        border-bottom: 2px solid #cbd5e1;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    }
    .stat-card {
        border-radius: 12px;
        padding: 18px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 15px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.06);
    }
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    .media-badge {
        font-size: 0.72rem;
        padding: 2px 6px;
        border-radius: 4px;
        font-weight: normal;
    }

    /* Inline Editor Styling */
    .inline-input {
        font-size: 0.82rem;
        padding: 5px 8px;
        border-radius: 5px;
        border: 1px solid #cbd5e1;
        transition: all 0.2s ease;
    }
    .inline-input:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
        outline: none;
    }
    .inline-table thead th {
        font-size: 0.82rem;
        font-weight: 700;
        white-space: nowrap;
        background-color: #f1f5f9;
        color: #334155;
    }
    .inline-table td {
        vertical-align: top;
        padding: 8px 6px;
    }
    .row-new {
        background-color: #f0fdf4 !important;
    }
    .media-box-toggle {
        font-size: 0.75rem;
        color: #475569;
        cursor: pointer;
        padding: 3px 6px;
        background: #f1f5f9;
        border-radius: 4px;
        border: 1px dashed #cbd5e1;
        display: inline-block;
        margin-top: 4px;
    }
    .media-box-toggle:hover {
        background: #e2e8f0;
    }
    .media-inputs-container {
        margin-top: 5px;
        padding: 6px;
        background: #f8fafc;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
    }

    /* Success Checkmark Animation */
    .success-card {
        border-radius: 20px;
        border: 1px solid #d1fae5;
        background: linear-gradient(180deg, #ffffff 0%, #f0fdf4 100%);
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.12);
        padding: 40px 20px;
        text-align: center;
    }
    .checkmark-wrapper {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 140px;
        height: 140px;
        margin-bottom: 20px;
    }
    .checkmark-circle-pulse {
        position: absolute;
        width: 130px;
        height: 130px;
        border-radius: 50%;
        background: rgba(16, 185, 129, 0.15);
        animation: pulse-ring 2s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
    }
    .checkmark-circle-pulse-2 {
        position: absolute;
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: rgba(16, 185, 129, 0.25);
        animation: pulse-ring 2s cubic-bezier(0.215, 0.61, 0.355, 1) 0.5s infinite;
    }
    @keyframes pulse-ring {
        0% { transform: scale(0.8); opacity: 0.9; }
        50% { transform: scale(1.35); opacity: 0; }
        100% { transform: scale(0.8); opacity: 0; }
    }
    .checkmark-svg {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        display: block;
        stroke-width: 3;
        stroke: #10b981;
        stroke-miterlimit: 10;
        box-shadow: inset 0px 0px 0px #10b981;
        animation: fill-success .4s ease-in-out .4s forwards, scale-success .3s ease-in-out .9s both;
        position: relative;
        z-index: 5;
    }
    .checkmark-svg-circle {
        stroke-dasharray: 166;
        stroke-dashoffset: 166;
        stroke-width: 3;
        stroke-miterlimit: 10;
        stroke: #10b981;
        fill: none;
        animation: stroke-circle 0.7s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }
    .checkmark-svg-check {
        transform-origin: 50% 50%;
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
        stroke-width: 4;
        stroke: #ffffff;
        animation: stroke-check 0.4s cubic-bezier(0.65, 0, 0.45, 1) 0.75s forwards;
    }
    @keyframes stroke-circle {
        100% { stroke-dashoffset: 0; }
    }
    @keyframes stroke-check {
        100% { stroke-dashoffset: 0; }
    }
    @keyframes scale-success {
        0%, 100% { transform: none; }
        50% { transform: scale3d(1.15, 1.15, 1); }
    }
    @keyframes fill-success {
        100% { box-shadow: inset 0px 0px 0px 50px #10b981; }
    }
    .btn-pulse-success {
        transition: all 0.3s ease;
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.35);
    }
    .btn-pulse-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(16, 185, 129, 0.5);
    }

    /* Loading Overlay */
    .loading-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(6px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        color: #ffffff;
    }
    .loading-card {
        background: #ffffff;
        color: #1e293b;
        padding: 35px 45px;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        text-align: center;
        max-width: 460px;
        width: 90%;
    }
    .spinner-animated {
        width: 65px;
        height: 65px;
        border: 4px solid #e2e8f0;
        border-top-color: #10b981;
        border-right-color: #3b82f6;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 20px;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .progress-bar-animated-custom {
        height: 10px;
        border-radius: 5px;
        background: #e2e8f0;
        overflow: hidden;
        margin: 15px 0;
        position: relative;
    }
    .progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #10b981, #3b82f6, #10b981);
        background-size: 200% 100%;
        animation: progress-slide 1.8s linear infinite;
        width: 100%;
    }
    @keyframes progress-slide {
        0% { background-position: 100% 0; }
        100% { background-position: -100% 0; }
    }
</style>

{{-- Loading Overlay for Form Submissions & AJAX --}}
<div id="loadingOverlay" class="loading-overlay">
    <div class="loading-card animate__animated animate__fadeIn">
        <div class="spinner-animated"></div>
        <h4 class="fw-bold mb-2 text-dark" id="loadingTitle">جاري معالجة البيانات...</h4>
        <div class="progress-bar-animated-custom">
            <div class="progress-bar-fill"></div>
        </div>
        <p class="text-muted small mb-0" id="loadingSub">يرجى الانتظار...</p>
    </div>
</div>

{{-- Modal لرفع ملفات ZIP الخاصة بالوسائط مباشرة من محرر الصفحة --}}
<div class="modal fade" id="modalUploadInlineMedia" tabindex="-1" aria-labelledby="modalUploadInlineMediaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold text-primary" id="modalUploadInlineMediaLabel">
                    <i class="bx bx-cloud-upload me-1"></i> رفع وسائط الأسئلة والإجابات (ملفات ZIP)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="ajaxInlineZipForm" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 bg-light-info text-dark mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-info-circle fs-3 text-info me-2"></i>
                            <div>
                                <strong>طريقة تنظيم ملفات الـ ZIP:</strong>
                                <p class="mb-0 small mt-1">
                                    - وسائط الأسئلة: ترفع في مسار <code>public/upload/questions/</code> (تحتوي على مجلدات <code>images</code>, <code>sounds</code>, <code>videos</code>).<br>
                                    - وسائط الإجابات: ترفع في مسار <code>public/upload/answers/</code> (تحتوي على مجلدات <code>images</code>, <code>sounds</code>, <code>videos</code>).<br>
                                    * فك الضغط آمن 100% ولن يحذف أي ملفات سابقة.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 border rounded bg-light">
                                <label class="form-label fw-bold text-dark">
                                    <i class="bx bx-images me-1 text-primary"></i> 1. ZIP وسائط الأسئلة (Questions)
                                </label>
                                <input type="file" name="zip_questions" class="form-control" accept=".zip">
                                <small class="text-muted d-block mt-1">المسار: <code>upload/questions/</code></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 border rounded bg-light">
                                <label class="form-label fw-bold text-dark">
                                    <i class="bx bx-check-shield me-1 text-success"></i> 2. ZIP وسائط الإجابات (Answers)
                                </label>
                                <input type="file" name="zip_answers" class="form-control" accept=".zip">
                                <small class="text-muted d-block mt-1">المسار: <code>upload/answers/</code></small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bx bx-upload me-1"></i> بدء رفع وفك ضغط الـ ZIP الآن
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">
        <i class="bx bx-file text-success me-1"></i> إدارة واستيراد الأسئلة عبر Excel
    </div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('all.question') }}">الأسئلة</a></li>
                <li class="breadcrumb-item active" aria-current="page">رفع وتعديل مجمع عبر Excel</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="{{ route('excel.download_sample') }}" class="btn btn-outline-success btn-sm shadow-sm">
            <i class="bx bx-download me-1"></i> تنزيل نموذج فارغ جديد (innov.xlsx)
        </a>
        <a href="{{ route('all.question') }}" class="btn btn-outline-secondary btn-sm shadow-sm ms-1">
            <i class="bx bx-list-ul me-1"></i> عرض جميع الأسئلة
        </a>
    </div>
</div>

<hr/>

{{-- شريط المراحل (Step Wizard) --}}
@php
    $currentStep = $activeStep ?? 1;
    if (session()->has('upload_completed') || session('active_step') == 4) {
        $currentStep = 4;
    } elseif (isset($rows)) {
        $currentStep = 2;
    } elseif (session()->has('import_success') || session()->has('media_upload_stats')) {
        $currentStep = 3;
    }
@endphp

<div class="step-wizard">
    <div class="step-wizard-item {{ $currentStep == 1 ? 'active' : ($currentStep > 1 ? 'completed' : '') }}">
        <div class="step-icon">1</div>
        <div class="step-title">تحديد الفئات أو التعديل المباشر والتصدير</div>
    </div>
    <div class="step-wizard-item {{ $currentStep == 2 ? 'active' : ($currentStep > 2 ? 'completed' : '') }}">
        <div class="step-icon">2</div>
        <div class="step-title">معاينة وتأكيد البيانات (إضافة / تعديل)</div>
    </div>
    <div class="step-wizard-item {{ $currentStep == 3 ? 'active' : ($currentStep > 3 ? 'completed' : '') }}">
        <div class="step-icon">3</div>
        <div class="step-title">رفع وسائط ZIP</div>
    </div>
    <div class="step-wizard-item {{ $currentStep == 4 ? 'active' : '' }}">
        <div class="step-icon"><i class="bx bx-check"></i></div>
        <div class="step-title">اكتمال الرفع</div>
    </div>
</div>

{{-- تنبيهات الأخطاء والرسائل --}}
@if(isset($errors) && $errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 bg-danger text-white mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="bx bx-error-circle fs-3 me-2"></i>
            <div>
                <strong class="d-block mb-1">يرجى الانتباه للأخطاء التالية:</strong>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- ========================================================================= --}}
{{-- الخطوة 4: شاشة النجاح الإبداعية بعد اكتمال الرفع والاعتماد --}}
{{-- ========================================================================= --}}
@if($currentStep == 4 || session()->has('upload_completed'))
@php
    $mediaStats = session('media_upload_stats');
    $importData = session('import_success');
    $replacedFiles = array_merge($mediaStats['questions']['replaced'] ?? [], $mediaStats['answers']['replaced'] ?? []);
    $totalExtracted = ($mediaStats['questions']['extracted'] ?? 0) + ($mediaStats['answers']['extracted'] ?? 0);
@endphp

<div class="success-card mb-4">
    {{-- أيقونة علامة الصح الخضراء المتحركة داخل الدائرة --}}
    <div class="checkmark-wrapper">
        <div class="checkmark-circle-pulse"></div>
        <div class="checkmark-circle-pulse-2"></div>
        <svg class="checkmark-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
            <circle class="checkmark-svg-circle" cx="26" cy="26" r="25" fill="none"/>
            <path class="checkmark-svg-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
        </svg>
    </div>

    <h2 class="fw-bold text-success mb-2">تم الرفع والاعتماد بنجاح!</h2>
    <p class="text-muted fs-5 mb-4">
        تم استيراد وحفظ الأسئلة وتحديث السجلات في قاعدة البيانات، وفك ضغط ملفات الوسائط في مساراتها الصحيحة بأمان تام.
    </p>

    {{-- ملخص الإحصائيات --}}
    <div class="row justify-content-center g-3 mb-4">
        @if(!empty($importData['inserted_count']))
        <div class="col-md-3 col-sm-6">
            <div class="p-3 bg-white rounded-3 border shadow-sm">
                <div class="text-muted small mb-1">أسئلة جديدة أضيفت</div>
                <h3 class="fw-bold text-success mb-0">➕ {{ $importData['inserted_count'] }}</h3>
            </div>
        </div>
        @endif

        @if(!empty($importData['updated_count']))
        <div class="col-md-3 col-sm-6">
            <div class="p-3 bg-white rounded-3 border shadow-sm">
                <div class="text-muted small mb-1">أسئلة تم تعديلها وتحديثها</div>
                <h3 class="fw-bold text-primary mb-0">🔄 {{ $importData['updated_count'] }}</h3>
            </div>
        </div>
        @endif

        @if($totalExtracted > 0)
        <div class="col-md-3 col-sm-6">
            <div class="p-3 bg-white rounded-3 border shadow-sm">
                <div class="text-muted small mb-1">ملفات الوسائط المستخرجة</div>
                <h3 class="fw-bold text-info mb-0">📁 {{ $totalExtracted }}</h3>
            </div>
        </div>
        @endif

        <div class="col-md-3 col-sm-6">
            <div class="p-3 bg-white rounded-3 border shadow-sm">
                <div class="text-muted small mb-1">حالة العملية</div>
                <h5 class="fw-bold text-success mb-0 mt-1"><i class="bx bx-check-shield me-1"></i> مكتملة 100%</h5>
            </div>
        </div>
    </div>

    {{-- تنبيه الاستبدال إن وجد --}}
    @if(count($replacedFiles) > 0)
        <div class="alert alert-warning border-0 bg-light-warning text-dark max-w-700 mx-auto mb-4 text-start">
            <div class="d-flex align-items-center mb-2">
                <i class="bx bx-error fs-4 text-warning me-2"></i>
                <strong>تنبيه: تم استبدال ({{ count($replacedFiles) }}) ملفات كانت موجودة مسبقاً بنفس الاسم:</strong>
            </div>
            <div class="d-flex flex-wrap gap-1 ps-4">
                @foreach($replacedFiles as $rf)
                    <span class="badge bg-warning text-dark">{{ $rf }}</span>
                @endforeach
            </div>
        </div>
    @endif

    {{-- أزرار الانتقال الكبيرة --}}
    <div class="d-flex justify-content-center align-items-center flex-wrap gap-3 mt-3">
        <a href="{{ route('all.question') }}" class="btn btn-success btn-lg px-5 py-3 fs-5 rounded-pill btn-pulse-success">
            <i class="bx bx-list-check me-2 fs-4"></i> الانتقال إلى عرض الأسئلة
        </a>
        <a href="{{ route('excel.index') }}" class="btn btn-outline-secondary btn-lg px-4 py-3 rounded-pill">
            <i class="bx bx-upload me-2"></i> رفع أو تعديل ملف Excel آخر
        </a>
    </div>
</div>
@endif

{{-- ========================================================================= --}}
{{-- الخطوة 1: تحديد الفئات الموحدة / التعديل المباشر في الصفحة / التصدير --}}
{{-- ========================================================================= --}}
@if($currentStep == 1)
<div class="card card-step mb-4">
    <div class="card-header bg-light pt-3 pb-0 border-bottom-0">
        <ul class="nav nav-tabs nav-tabs-custom" id="excelTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="import-tab" data-bs-toggle="tab" data-bs-target="#import-content" type="button" role="tab">
                    <i class="bx bx-upload me-1"></i> 1. استيراد ورفع ملف Excel (إضافة وتعديل مجمع)
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-primary" id="export-tab" data-bs-toggle="tab" data-bs-target="#export-content" type="button" role="tab">
                    <i class="bx bx-download me-1"></i> 2. تصدير أسئلة الفئة (Excel) أو التعديل المباشر (تجريبي)
                </button>
            </li>
        </ul>
    </div>

    <div class="card-body p-4">
        <div class="tab-content" id="excelTabsContent">

            {{-- التبويب 1: الرفع والاستيراد --}}
            <div class="tab-pane fade show active" id="import-content" role="tabpanel">
                <form action="{{ route('excel.import') }}" method="POST" enctype="multipart/form-data" id="excelImportForm">
                    @csrf

                    <div class="row g-3 mb-4">
                        {{-- نوع اللعبة --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark">
                                <i class="bx bx-joystick me-1 text-primary"></i> نوع اللعبة (Game Type)
                            </label>
                            <select name="game_type_id" id="import_game_type_id" class="form-select border-primary shadow-none">
                                <option value="non">الرجاء اختيار نوع اللعبة (اختياري)</option>
                                @foreach ($gameTypes as $item)
                                    <option value="{{ $item->id }}" {{ old('game_type_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->type_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- الفئة الرئيسية --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark">
                                <i class="bx bx-category me-1 text-primary"></i> الفئة الرئيسية (Main Category)
                            </label>
                            <select name="main_category_id" id="import_main_category_id" class="form-select border-primary shadow-none">
                                <option value="non">الرجاء اختيار الفئة الرئيسية (اختياري)</option>
                            </select>
                        </div>

                        {{-- الفئة الفرعية --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark">
                                <i class="bx bx-grid-alt me-1 text-primary"></i> الفئة الفرعية (Category)
                            </label>
                            <select name="category_id" id="import_category_id" class="form-select border-primary shadow-none">
                                <option value="non">الرجاء اختيار الفئة الفرعية (اختياري)</option>
                            </select>
                        </div>
                    </div>

                    <div class="alert alert-info border-0 bg-light-info text-dark mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-info-circle fs-3 text-info me-2"></i>
                            <div>
                                <strong>ذكاء النظام التلقائي في التعرف على العمليات:</strong>
                                <ul class="mb-0 ps-3 mt-1 small">
                                    <li>إذا كان الصف يحتوي على <code>qu_id</code> لسؤال موجود، سيتعرف النظام عليه تلقائياً ويقوم بعمل <strong>تحديث (Update)</strong> له ولإجاباته.</li>
                                    <li>إذا كان الصف <strong>بدون <code>qu_id</code></strong> (فارغ)، سيعتبره النظام <strong>سؤالاً جديداً (Insert)</strong> ويضيفه تلقائياً.</li>
                                    <li>يمكنك دمج أسئلة للتعديل وأسئلة جديدة في نفس ملف الـ Excel بكل سهولة!</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-dark">
                                <i class="bx bx-upload me-1 text-success"></i> ملف Excel للأسئلة (.xlsx / .xls / .csv) <span class="text-danger">*</span>
                            </label>
                            <input type="file" name="excel_file" class="form-control form-control-lg border-success" accept=".xlsx, .xls, .csv" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-success btn-lg px-4 shadow-sm">
                            <i class="bx bx-check-circle me-1"></i> رفع ومعاينة بيانات Excel
                        </button>
                    </div>
                </form>
            </div>

            {{-- التبويب 2: قسم التصدير كملف Excel أو التعديل المباشر --}}
            <div class="tab-pane fade" id="export-content" role="tabpanel">
                <div class="p-3 bg-light rounded-3 border mb-4">
                    <h6 class="fw-bold text-success mb-2">
                        <i class="bx bx-download me-1"></i> تصدير أسئلة الفئة كملف Excel للتعديل (الخيار الأساسي والموصى به)
                    </h6>
                    <p class="text-muted small mb-0">
                        اختر الفئة المطلوبة ثم اضغط على <strong>"تنزيل كملف Excel للتعديل"</strong> للحصول على ملف Excel منسق بالكامل من اليمين إلى اليسار (RTL) يحتوي على كافة الأسئلة والإجابات والـ IDs لتعديلها أوفلاين وإعادة رفعها، أو يمكنك تجربة <strong>العرض والتعديل المباشر في الصفحة (تجريبي)</strong>.
                    </p>
                </div>

                <div class="row g-3 mb-4">
                    {{-- نوع اللعبة --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-dark">
                            <i class="bx bx-joystick me-1 text-primary"></i> 1. اختر نوع اللعبة <span class="text-danger">*</span>
                        </label>
                        <select id="export_game_type_id" class="form-select border-primary shadow-none">
                            <option value="non">-- الرجاء اختيار نوع اللعبة --</option>
                            @foreach ($gameTypes as $item)
                                <option value="{{ $item->id }}">{{ $item->type_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- الفئة الرئيسية --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-dark">
                            <i class="bx bx-category me-1 text-primary"></i> 2. اختر الفئة الرئيسية <span class="text-danger">*</span>
                        </label>
                        <select id="export_main_category_id" class="form-select border-primary shadow-none">
                            <option value="non">-- الرجاء اختيار الفئة الرئيسية --</option>
                        </select>
                    </div>

                    {{-- الفئة الفرعية --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-dark">
                            <i class="bx bx-grid-alt me-1 text-primary"></i> 3. اختر الفئة الفرعية <span class="text-danger">*</span>
                        </label>
                        <select id="export_category_id" class="form-select border-primary shadow-none">
                            <option value="non">-- الرجاء اختيار الفئة الفرعية --</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 pb-3 border-bottom bg-white p-3 rounded-3 border">
                    {{-- زر تنزيل Excel في البداية بشكل واضح وبارز جداً --}}
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <button type="button" id="btnDownloadExcel" class="btn btn-success btn-lg px-4 py-2 shadow fw-bold fs-6">
                            <i class="bx bx-download fs-5 me-1"></i> تنزيل كملف Excel للتعديل (RTL)
                        </button>
                        
                        <div class="vr mx-2 d-none d-md-block"></div>

                        {{-- أزرار العرض المباشر ورفع الوسائط مع وسم (تجريبي) --}}
                        <button type="button" id="btnLoadQuestions" class="btn btn-outline-primary btn-lg px-3 py-2 shadow-sm">
                            <i class="bx bx-show me-1"></i> عرض وتعديل الأسئلة مباشرة في الصفحة <span class="badge bg-warning text-dark ms-1">تجريبي</span>
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-lg px-3 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalUploadInlineMedia">
                            <i class="bx bx-cloud-upload me-1"></i> رفع وسائط الفئة (ZIP) <span class="badge bg-warning text-dark ms-1">تجريبي</span>
                        </button>
                    </div>
                    <span class="text-muted small">
                        <i class="bx bx-check-shield text-success me-1"></i> ملف الـ Excel يفتح من اليمين للشمال مع كامل الـ IDs
                    </span>
                </div>

                {{-- منطقة محرر الأسئلة المباشر داخل الصفحة (Inline Table Editor) --}}
                <div id="inlineEditorWrapper" style="display: none;">
                    {{-- شريط ملخص الوسائط والإحصائيات --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted small d-block">إجمالي الأسئلة في الجدول</span>
                                    <h4 class="mb-0 fw-bold text-dark" id="inlineCountBadge">0 سؤال</h4>
                                </div>
                                <i class="bx bx-list-ol fs-1 text-primary"></i>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted small d-block">ملفات وسائط متوفرة على السيرفر</span>
                                    <h4 class="mb-0 fw-bold text-success" id="inlineMediaFoundBadge">0 ملف ✅</h4>
                                </div>
                                <i class="bx bx-check-circle fs-1 text-success"></i>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted small d-block">ملفات وسائط بانتظار رفع ZIP</span>
                                    <h4 class="mb-0 fw-bold text-warning" id="inlineMediaMissingBadge">0 ملف ❌</h4>
                                </div>
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalUploadInlineMedia">
                                    <i class="bx bx-upload me-1"></i> رفع ZIP الآن (تجريبي)
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-3 bg-light p-3 rounded-3 border">
                        <div class="d-flex align-items-center gap-2">
                            <h6 class="mb-0 fw-bold text-dark">
                                <i class="bx bx-table text-success me-1"></i> جدول الأسئلة والإجابات والوسائط:
                            </h6>
                            <span class="badge bg-warning text-dark">تجريبي (Beta)</span>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" id="btnAddQuestionRow" class="btn btn-outline-primary btn-sm">
                                <i class="bx bx-plus me-1"></i> إضافة سؤال جديد للجدول
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalUploadInlineMedia">
                                <i class="bx bx-images me-1"></i> رفع وسائط ZIP (تجريبي)
                            </button>
                            <button type="button" id="btnSaveInlineQuestionsTop" class="btn btn-success btn-sm px-4 shadow-sm">
                                <i class="bx bx-save me-1"></i> حفظ جميع التعديلات مباشرة
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive table-responsive-custom border mb-3">
                        <table class="table table-bordered inline-table mb-0" id="inlineQuestionsTable">
                            <thead>
                                <tr>
                                    <th style="width: 70px;"># ID</th>
                                    <th style="min-width: 250px;">السؤال (عربي / إنجليزي)</th>
                                    <th style="min-width: 140px;">النقاط / الوقت / التلميح</th>
                                    <th style="min-width: 180px;">وسائط السؤال</th>
                                    <th style="min-width: 240px; background-color: #ecfdf5;">الإجابة الصحيحة 1 (محلية وأونلاين)</th>
                                    <th style="min-width: 220px;">الإجابة 2</th>
                                    <th style="min-width: 220px;">الإجابة 3</th>
                                    <th style="min-width: 220px;">الإجابة 4</th>
                                    <th style="width: 50px;">حذف</th>
                                </tr>
                            </thead>
                            <tbody id="inlineQuestionsBody">
                                {{-- Rows populated via JavaScript --}}
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3 border">
                        <button type="button" id="btnAddQuestionRowBottom" class="btn btn-outline-primary">
                            <i class="bx bx-plus me-1"></i> إضافة سؤال جديد للجدول
                        </button>
                        <button type="button" id="btnSaveInlineQuestionsBottom" class="btn btn-success btn-lg px-5 shadow">
                            <i class="bx bx-save me-1"></i> حفظ جميع التعديلات مباشرة في قاعدة البيانات
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
@endif

{{-- ========================================================================= --}}
{{-- الخطوة 2: معاينة وتأكيد الأسئلة قبل الحفظ (مع كشف الإضافة مقابل التعديل) --}}
{{-- ========================================================================= --}}
@isset($rows)
<div class="card card-step mb-4">
    <div class="card-header bg-light py-3 d-flex align-items-center justify-content-between">
        <div>
            <h5 class="mb-0 text-success">
                <i class="bx bx-table me-1"></i> الخطوة 2: معاينة الأسئلة قبل الاعتماد ({{ count($rows) }} صف)
            </h5>
            <small class="text-muted">يرجى مراجعة الأسئلة وتفاصيلها ونوع العملية (تعديل 🔄 أو إضافة ➕) قبل تأكيد الحفظ.</small>
        </div>
        <a href="{{ route('excel.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bx bx-arrow-back me-1"></i> إلغاء وإعادة الرفع
        </a>
    </div>

    <div class="card-body p-4">
        {{-- بطاقات الإحصائيات مع عداد التعديل والإضافة --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-icon bg-light-primary text-primary"><i class="bx bx-help-circle"></i></div>
                    <div>
                        <div class="text-muted small">إجمالي الأسئلة</div>
                        <h4 class="mb-0 fw-bold">{{ $stats['total_questions'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-icon bg-light-info text-info"><i class="bx bx-sync"></i></div>
                    <div>
                        <div class="text-muted small">أسئلة للتعديل والتحديث</div>
                        <h4 class="mb-0 fw-bold text-info">🔄 {{ $stats['update_count'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-icon bg-light-success text-success"><i class="bx bx-plus-circle"></i></div>
                    <div>
                        <div class="text-muted small">أسئلة جديدة ستضاف</div>
                        <h4 class="mb-0 fw-bold text-success">➕ {{ $stats['insert_count'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-icon bg-light-warning text-warning"><i class="bx bx-folder"></i></div>
                    <div>
                        <div class="text-muted small">إجمالي ملفات الوسائط</div>
                        <h4 class="mb-0 fw-bold">{{ $stats['question_media_need'] + $stats['answer_media_need'] }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- ملخص الفئات المختارة --}}
        <div class="alert alert-light border d-flex align-items-center justify-content-between p-3 mb-4">
            <div>
                <span class="badge bg-primary px-2 py-1 me-2">التصنيف المطبق:</span>
                <strong>نوع اللعبة:</strong> {{ $selectedGameType->type_name ?? ($game_type_id != 'non' && $game_type_id ? "ID: $game_type_id" : 'من ملف Excel') }}
                <span class="mx-2 text-muted">|</span>
                <strong>الفئة الرئيسية:</strong> {{ $selectedMainCategory->main_category_name ?? ($main_category_id != 'non' && $main_category_id ? "ID: $main_category_id" : 'من ملف Excel') }}
                <span class="mx-2 text-muted">|</span>
                <strong>الفئة الفرعية:</strong> {{ $selectedCategory->category_name ?? ($category_id != 'non' && $category_id ? "ID: $category_id" : 'من ملف Excel') }}
            </div>
            <div>
                @if($stats['media_missing'] > 0)
                    <span class="badge bg-warning text-dark px-3 py-2">
                        <i class="bx bx-info-circle me-1"></i> يوجد {{ $stats['media_missing'] }} ملف وسائط بانتظار الرفع في الخطوة التالية (ZIP)
                    </span>
                @else
                    <span class="badge bg-success px-3 py-2">
                        <i class="bx bx-check-double me-1"></i> كافة الوسائط متوفرة حالياً
                    </span>
                @endif
            </div>
        </div>

        {{-- جدول معاينة الأسئلة --}}
        <div class="table-responsive table-responsive-custom border mb-4">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th style="width: 120px;">العملية</th>
                        <th style="width: 80px;">النوع</th>
                        <th>السؤال (عربي / إنجليزي)</th>
                        <th>وسائط السؤال</th>
                        <th style="width: 100px;">النقاط / الوقت</th>
                        <th>الإجابة الصحيحة (محلية وأونلاين 1)</th>
                        <th>الإجابة 2</th>
                        <th>الإجابة 3</th>
                        <th>الإجابة 4</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $row)
                    <tr>
                        <td class="fw-bold">{{ $loop->iteration }}</td>
                        <td>
                            @if($row['_row_action'] == 'update')
                                <span class="badge bg-primary px-2 py-1 shadow-sm">
                                    <i class="bx bx-sync me-1"></i> تعديل #{{ $row['qu_id'] }}
                                </span>
                            @else
                                <span class="badge bg-success px-2 py-1 shadow-sm">
                                    <i class="bx bx-plus me-1"></i> إضافة جديد
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($row['_detected_type'] == 'image')
                                <span class="badge bg-success media-badge"><i class="bx bx-image me-1"></i>صورة</span>
                            @elseif($row['_detected_type'] == 'sound')
                                <span class="badge bg-warning text-dark media-badge"><i class="bx bx-volume-full me-1"></i>صوت</span>
                            @elseif($row['_detected_type'] == 'video')
                                <span class="badge bg-danger media-badge"><i class="bx bx-video me-1"></i>فيديو</span>
                            @else
                                <span class="badge bg-secondary media-badge"><i class="bx bx-text me-1"></i>نصي</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $row['qu_title'] ?? '-' }}</div>
                            <small class="text-muted">{{ $row['qu_title_en'] ?? '' }}</small>
                        </td>
                        <td>
                            @if(!empty($row['qu_image']))
                                @php $qExists = file_exists(public_path('upload/questions/images/' . $row['qu_image'])); @endphp
                                <div class="small">
                                    <span class="badge {{ $qExists ? 'bg-light-success text-success' : 'bg-light-danger text-danger' }}">
                                        {{ $qExists ? '✅ متوفرة' : '❌ بانتظار ZIP' }}
                                    </span>
                                    <code class="d-block mt-1">{{ $row['qu_image'] }}</code>
                                </div>
                            @elseif(!empty($row['qu_sound']))
                                @php $sExists = file_exists(public_path('upload/questions/sounds/' . $row['qu_sound'])); @endphp
                                <div class="small">
                                    <span class="badge {{ $sExists ? 'bg-light-success text-success' : 'bg-light-danger text-danger' }}">
                                        {{ $sExists ? '✅ متوفر' : '❌ بانتظار ZIP' }}
                                    </span>
                                    <code class="d-block mt-1">{{ $row['qu_sound'] }}</code>
                                </div>
                            @elseif(!empty($row['qu_video']))
                                @php $vExists = file_exists(public_path('upload/questions/videos/' . $row['qu_video'])); @endphp
                                <div class="small">
                                    <span class="badge {{ $vExists ? 'bg-light-success text-success' : 'bg-light-danger text-danger' }}">
                                        {{ $vExists ? '✅ متوفر' : '❌ بانتظار ZIP' }}
                                    </span>
                                    <code class="d-block mt-1">{{ $row['qu_video'] }}</code>
                                </div>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light-primary text-primary">{{ $row['qu_points'] ?? 0 }} نقطة</span>
                            <span class="badge bg-light-secondary text-secondary mt-1">{{ $row['time_counter'] ?? 30 }} ثانية</span>
                        </td>
                        <td>
                            <div class="fw-bold text-success">{{ $row['answer_title'] ?? '-' }}</div>
                            <small class="text-muted">{{ $row['answer_title_en'] ?? '' }}</small>
                            @if(!empty($row['answer_image']))
                                <code class="d-block small text-muted mt-1">{{ $row['answer_image'] }}</code>
                            @endif
                        </td>
                        <td>
                            <div>{{ $row['answer_title_two'] ?? '-' }}</div>
                            <small class="text-muted">{{ $row['answer_title_en_two'] ?? '' }}</small>
                            @if(!empty($row['answer_image_two']))
                                <code class="d-block small text-muted mt-1">{{ $row['answer_image_two'] }}</code>
                            @endif
                        </td>
                        <td>
                            <div>{{ $row['answer_title_three'] ?? '-' }}</div>
                            <small class="text-muted">{{ $row['answer_title_en_three'] ?? '' }}</small>
                            @if(!empty($row['answer_image_three']))
                                <code class="d-block small text-muted mt-1">{{ $row['answer_image_three'] }}</code>
                            @endif
                        </td>
                        <td>
                            <div>{{ $row['answer_title_four'] ?? '-' }}</div>
                            <small class="text-muted">{{ $row['answer_title_en_four'] ?? '' }}</small>
                            @if(!empty($row['answer_image_four']))
                                <code class="d-block small text-muted mt-1">{{ $row['answer_image_four'] }}</code>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- زر تأكيد الاعتماد والحفظ --}}
        <form action="{{ route('excel.approved') }}" method="POST" id="approveQuestionsForm">
            @csrf
            <input type="hidden" name="rows" value="{{ json_encode($rows) }}">
            <input type="hidden" name="game_type_id" value="{{ $game_type_id }}">
            <input type="hidden" name="main_category_id" value="{{ $main_category_id }}">
            <input type="hidden" name="category_id" value="{{ $category_id }}">

            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('excel.index') }}" class="btn btn-outline-danger px-4">
                    <i class="bx bx-x me-1"></i> إلغاء
                </a>
                <button type="submit" class="btn btn-success btn-lg px-5 shadow">
                    <i class="bx bx-check-circle me-1"></i> اعتماد وحفظ التعديلات والأسئلة الجديدة
                </button>
            </div>
        </form>
    </div>
</div>
@endisset

{{-- ========================================================================= --}}
{{-- الخطوة 3: رفع ملفات ZIP للأسئلة والإجابات والتحقق الفوري --}}
{{-- ========================================================================= --}}
@if($currentStep == 3)
@php
    $importData = session('import_success');
    $mediaStats = session('media_upload_stats');
@endphp

<div class="card card-step mb-4">
    <div class="card-header bg-light py-3 d-flex align-items-center justify-content-between">
        <div>
            <h5 class="mb-0 text-primary">
                <i class="bx bx-cloud-upload me-1"></i> الخطوة 3: رفع وسائط الأسئلة والإجابات (ملفات ZIP)
            </h5>
            <small class="text-muted">
                يتم استخراج ملفات الوسائط في مساراتها الصحيحة دون حذف أي ملفات سابقة مع تنبيهك في حال استبدال ملف مسبق.
            </small>
        </div>
        <a href="{{ route('all.question') }}" class="btn btn-success btn-sm">
            <i class="bx bx-check me-1"></i> الانتقال لعرض الأسئلة
        </a>
    </div>

    <div class="card-body p-4">

        {{-- تنبيه تقرير استبدال الملفات إن وجد --}}
        @if($mediaStats)
            @php
                $replacedFiles = array_merge($mediaStats['questions']['replaced'] ?? [], $mediaStats['answers']['replaced'] ?? []);
            @endphp
            @if(count($replacedFiles) > 0)
                <div class="alert alert-warning border-0 bg-light-warning text-dark mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bx bx-error fs-3 text-warning me-2"></i>
                        <strong>تنبيه استبدال الملفات: تم استبدال ({{ count($replacedFiles) }}) ملفات كانت موجودة مسبقاً بنفس الاسم:</strong>
                    </div>
                    <div class="d-flex flex-wrap gap-1 ps-4">
                        @foreach($replacedFiles as $rf)
                            <span class="badge bg-warning text-dark">{{ $rf }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif

        {{-- نموذج رفع ملفات ZIP --}}
        <form action="{{ route('excel.upload_media') }}" method="POST" enctype="multipart/form-data" id="zipUploadForm">
            @csrf

            <div class="row g-4 mb-4">
                {{-- ZIP وسائط الأسئلة --}}
                <div class="col-md-6">
                    <div class="p-3 border rounded bg-light">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bx bx-images fs-3 text-primary me-2"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">1. ملف ZIP لوسائط الأسئلة (Questions Media)</h6>
                                <small class="text-muted">يحتوي على مجلدات <code>images</code>, <code>sounds</code>, <code>videos</code></small>
                            </div>
                        </div>
                        <input type="file" name="zip_questions" class="form-control mt-2" accept=".zip">
                        <div class="form-text text-muted mt-1 small">
                            <i class="bx bx-folder me-1"></i> المسار المستهدف: <code>public/upload/questions/</code>
                        </div>
                    </div>
                </div>

                {{-- ZIP وسائط الإجابات --}}
                <div class="col-md-6">
                    <div class="p-3 border rounded bg-light">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bx bx-check-shield fs-3 text-success me-2"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">2. ملف ZIP لوسائط الإجابات (Answers Media)</h6>
                                <small class="text-muted">يحتوي على مجلدات <code>images</code>, <code>sounds</code>, <code>videos</code></small>
                            </div>
                        </div>
                        <input type="file" name="zip_answers" class="form-control mt-2" accept=".zip">
                        <div class="form-text text-muted mt-1 small">
                            <i class="bx bx-folder me-1"></i> المسار المستهدف: <code>public/upload/answers/</code>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="text-muted small">
                    <i class="bx bx-shield-quarter text-success me-1"></i> فك الضغط آمن 100% ولا يؤثر على أي ملفات أو مجلدات أخرى موجودة على السيرفر.
                </div>
                <button type="submit" class="btn btn-primary btn-lg px-4 shadow">
                    <i class="bx bx-upload me-1"></i> رفع وفك ضغط ملفات ZIP الآن
                </button>
            </div>
        </form>

        {{-- قائمة التحقق من ملفات الوسائط المطلوبة --}}
        @if($importData && !empty($importData['has_media']))
        <div class="mt-4">
            <h6 class="fw-bold mb-3 text-dark">
                <i class="bx bx-check-square text-success me-1"></i> قائمة التحقق من وسائط الأسئلة والإجابات المرجعية:
            </h6>

            <div class="row g-3">
                {{-- قائمة وسائط الأسئلة --}}
                @if(!empty($importData['question_media']))
                <div class="col-md-6">
                    <div class="card border shadow-none mb-0">
                        <div class="card-header bg-light py-2">
                            <span class="fw-bold small text-dark">وسائط الأسئلة ({{ count($importData['question_media']) }} ملف)</span>
                        </div>
                        <ul class="list-group list-group-flush" style="max-height: 250px; overflow-y: auto;">
                            @foreach($importData['question_media'] as $qm)
                            <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                                <div class="d-flex align-items-center">
                                    <i class="bx {{ $qm['type'] == 'image' ? 'bx-image text-primary' : ($qm['type'] == 'sound' ? 'bx-volume-full text-warning' : 'bx-video text-danger') }} me-2 fs-5"></i>
                                    <span class="small font-monospace">{{ $qm['name'] }}</span>
                                </div>
                                <span class="badge {{ $qm['exists'] ? 'bg-success' : 'bg-danger' }}">
                                    {{ $qm['exists'] ? 'متوفر ✅' : 'مفقود ❌' }}
                                </span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                {{-- قائمة وسائط الإجابات --}}
                @if(!empty($importData['answer_media']))
                <div class="col-md-6">
                    <div class="card border shadow-none mb-0">
                        <div class="card-header bg-light py-2">
                            <span class="fw-bold small text-dark">وسائط الإجابات ({{ count($importData['answer_media']) }} ملف)</span>
                        </div>
                        <ul class="list-group list-group-flush" style="max-height: 250px; overflow-y: auto;">
                            @foreach($importData['answer_media'] as $am)
                            <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                                <div class="d-flex align-items-center">
                                    <i class="bx {{ $am['type'] == 'image' ? 'bx-image text-success' : ($am['type'] == 'sound' ? 'bx-volume-full text-warning' : 'bx-video text-danger') }} me-2 fs-5"></i>
                                    <span class="small font-monospace">{{ $am['name'] }}</span>
                                </div>
                                <span class="badge {{ $am['exists'] ? 'bg-success' : 'bg-danger' }}">
                                    {{ $am['exists'] ? 'متوفر ✅' : 'مفقود ❌' }}
                                </span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

    </div>
</div>
@endif

{{-- سكريبت القوائم المنسدلة والمحرر المباشر ورفع الـ ZIP التفاعلي --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){

    // CSRF Token setup for AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    // مؤشر التحميل عند العمليات
    function showLoading(title, sub) {
        if (title) $('#loadingTitle').text(title);
        if (sub) $('#loadingSub').text(sub);
        $('#loadingOverlay').css('display', 'flex');
    }

    function hideLoading() {
        $('#loadingOverlay').hide();
    }

    $('#zipUploadForm').on('submit', function() {
        showLoading('جاري رفع وفك ضغط ملفات الـ ZIP...', 'يرجى الانتظار، يتم استخراج ملفات الصور والصوت والفيديو وفحص المسارات بأمان.');
    });

    $('#excelImportForm').on('submit', function() {
        showLoading('جاري قراءة ومعاينة ملف Excel...', 'يتم الآن فحص الأعمدة والصفوف وربط الفئات والتعرف على الأسئلة للتعديل أو الإضافة.');
    });

    $('#approveQuestionsForm').on('submit', function() {
        showLoading('جاري اعتماد وحفظ وتحديث الأسئلة والإجابات...', 'يتم حفظ السجلات في قاعدة البيانات داخل عملية آمنة.');
    });

    // Helper to reset select options
    function resetSelect(selectElement, defaultText){
        $(selectElement).prop('disabled', false)
                        .empty()
                        .append('<option value="non">' + defaultText + '</option>');
    }

    // Helper for AJAX chaining
    function loadOptions(triggerSelect, targetSelect, urlPrefix, valueField, textField, defaultText) {
        $(triggerSelect).on('change', function(){
            var selectedId = $(this).val();
            var target = $(targetSelect);

            if(selectedId && selectedId !== 'non'){
                target.prop('disabled', true)
                      .empty()
                      .append('<option value="">جاري التحميل...</option>');

                $.ajax({
                    url: urlPrefix + selectedId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data){
                        target.empty();
                        target.append('<option value="non">' + defaultText + '</option>');
                        $.each(data, function(key, value){
                            target.append('<option value="'+ value[valueField] +'">'+ value[textField] +'</option>');
                        });
                        target.prop('disabled', false);
                    },
                    error: function(){
                        resetSelect(target, defaultText);
                        target.append('<option value="">حدث خطأ، حاول مرة أخرى</option>');
                    }
                });
            } else {
                resetSelect(target, defaultText);
            }
        });
    }

    // ربط القوائم لتبويب الاستيراد
    loadOptions(
        '#import_game_type_id',
        '#import_main_category_id',
        '/get-main-categories/',
        'id',
        'main_category_name',
        'الرجاء اختيار الفئة الرئيسية (اختياري)'
    );

    loadOptions(
        '#import_main_category_id',
        '#import_category_id',
        '/get-sub-categories/',
        'id',
        'category_name',
        'الرجاء اختيار الفئة الفرعية (اختياري)'
    );

    $('#import_game_type_id').on('change', function(){
        resetSelect('#import_category_id', 'الرجاء اختيار الفئة الفرعية (اختياري)');
    });

    // ربط القوائم لتبويب التعديل المباشر والتصدير
    loadOptions(
        '#export_game_type_id',
        '#export_main_category_id',
        '/get-main-categories/',
        'id',
        'main_category_name',
        '-- الرجاء اختيار الفئة الرئيسية --'
    );

    loadOptions(
        '#export_main_category_id',
        '#export_category_id',
        '/get-sub-categories/',
        'id',
        'category_name',
        '-- الرجاء اختيار الفئة الفرعية --'
    );

    $('#export_game_type_id').on('change', function(){
        resetSelect('#export_category_id', '-- الرجاء اختيار الفئة الفرعية --');
    });

    // تنزيل كملف Excel للتعديل (مع التحقق الإلزامي من اختيار نوع اللعبة والفئة الرئيسية والفرعية)
    $('#btnDownloadExcel').on('click', function(){
        var gameType = $('#export_game_type_id').val();
        var mainCat  = $('#export_main_category_id').val();
        var subCat   = $('#export_category_id').val();

        if (!gameType || gameType === 'non') {
            Swal.fire({
                icon: 'warning',
                title: 'تنبيه: نوع اللعبة مطلوب',
                text: 'يرجى اختيار نوع اللعبة أولاً قبل تنزيل ملف Excel.',
                confirmButtonText: 'حسناً، سأقوم بالاختيار',
                confirmButtonColor: '#198754'
            });
            $('#export_game_type_id').focus();
            return;
        }

        if (!mainCat || mainCat === 'non') {
            Swal.fire({
                icon: 'warning',
                title: 'تنبيه: الفئة الرئيسية مطلوبة',
                text: 'يرجى اختيار الفئة الرئيسية أولاً قبل تنزيل ملف Excel.',
                confirmButtonText: 'حسناً، سأقوم بالاختيار',
                confirmButtonColor: '#198754'
            });
            $('#export_main_category_id').focus();
            return;
        }

        if (!subCat || subCat === 'non') {
            Swal.fire({
                icon: 'warning',
                title: 'تنبيه: الفئة الفرعية مطلوبة',
                text: 'يرجى اختيار الفئة الفرعية أولاً قبل تنزيل ملف Excel الخاص بها.',
                confirmButtonText: 'حسناً، سأقوم بالاختيار',
                confirmButtonColor: '#198754'
            });
            $('#export_category_id').focus();
            return;
        }

        var url = '{{ route("excel.export_category") }}?game_type_id=' + gameType + '&main_category_id=' + mainCat + '&category_id=' + subCat;
        window.location.href = url;
    });

    // =========================================================================
    // محرر الأسئلة المباشر مع وسائط الأسئلة والإجابات الكاملة
    // =========================================================================

    function mediaBadge(exists, filename) {
        if (!filename) return '';
        return exists ? '<span class="badge bg-success media-badge ms-1" title="متوفر على السيرفر">✅</span>'
                      : '<span class="badge bg-danger media-badge ms-1" title="مفقود، بانتظار رفع ZIP">❌</span>';
    }

    // دالة إنشاء صف سؤال في الجدول
    function renderQuestionRow(q, index) {
        var isNew = !q.qu_id;
        var rowClass = isNew ? 'row-new' : '';
        var badge = isNew ? '<span class="badge bg-success">جديد</span>' : '<span class="badge bg-primary">ID: ' + q.qu_id + '</span>';

        var html = '<tr class="question-row ' + rowClass + '" data-qu-id="' + (q.qu_id || '') + '">';

        // 1) ID / Action
        html += '<td class="text-center align-middle">' + badge + '</td>';

        // 2) Question Title
        html += '<td>';
        html += '<input type="text" class="form-control inline-input mb-1 q-title" placeholder="نص السؤال بالعربية" value="' + (q.qu_title || '').replace(/"/g, '&quot;') + '">';
        html += '<input type="text" class="form-control inline-input q-title-en text-muted" placeholder="Question in English" value="' + (q.qu_title_en || '').replace(/"/g, '&quot;') + '">';
        html += '</td>';

        // 3) Points / Timer / Hint / Term
        html += '<td>';
        html += '<div class="d-flex gap-1 mb-1">';
        html += '<input type="number" class="form-control inline-input q-points" placeholder="نقاط" title="النقاط" value="' + (q.qu_points || 0) + '" style="width: 65px;">';
        html += '<input type="number" class="form-control inline-input q-timer" placeholder="وقت" title="الوقت بالثواني" value="' + (q.time_counter || 30) + '" style="width: 65px;">';
        html += '</div>';
        html += '<input type="text" class="form-control inline-input mb-1 q-hint" placeholder="التلميح (Hint)" value="' + (q.qu_hint || '').replace(/"/g, '&quot;') + '">';
        html += '<input type="text" class="form-control inline-input q-term text-muted" placeholder="الترم (Term)" value="' + (q.term || '').replace(/"/g, '&quot;') + '">';
        html += '</td>';

        // 4) Question Media (Image / Sound / Video)
        html += '<td>';
        html += '<div class="input-group input-group-sm mb-1">';
        html += '<span class="input-group-text p-1 text-primary"><i class="bx bx-image"></i></span>';
        html += '<input type="text" class="form-control inline-input q-img" placeholder="صورة السؤال" value="' + (q.qu_image || '').replace(/"/g, '&quot;') + '">';
        html += mediaBadge(q.qu_image_exists, q.qu_image);
        html += '</div>';

        html += '<div class="input-group input-group-sm mb-1">';
        html += '<span class="input-group-text p-1 text-warning"><i class="bx bx-volume-full"></i></span>';
        html += '<input type="text" class="form-control inline-input q-snd" placeholder="صوت السؤال" value="' + (q.qu_sound || '').replace(/"/g, '&quot;') + '">';
        html += mediaBadge(q.qu_sound_exists, q.qu_sound);
        html += '</div>';

        html += '<div class="input-group input-group-sm">';
        html += '<span class="input-group-text p-1 text-danger"><i class="bx bx-video"></i></span>';
        html += '<input type="text" class="form-control inline-input q-vid" placeholder="فيديو السؤال" value="' + (q.qu_video || '').replace(/"/g, '&quot;') + '">';
        html += mediaBadge(q.qu_video_exists, q.qu_video);
        html += '</div>';
        html += '</td>';

        // 5) Answer 1 (Correct Answer - Local & Online 1)
        html += '<td style="background-color: #f0fdf4;">';
        html += '<input type="hidden" class="ans-id-local" value="' + (q.ans_id || '') + '">';
        html += '<input type="hidden" class="ans-id-online-1" value="' + (q.online_ans_id_1 || '') + '">';
        html += '<input type="text" class="form-control inline-input mb-1 a1-title fw-bold text-success" placeholder="الإجابة الصحيحة (عربي)" value="' + (q.answer_title || '').replace(/"/g, '&quot;') + '">';
        html += '<input type="text" class="form-control inline-input mb-1 a1-title-en text-muted" placeholder="Correct Answer (EN)" value="' + (q.answer_title_en || '').replace(/"/g, '&quot;') + '">';

        html += '<div class="media-inputs-container">';
        html += '<div class="d-flex align-items-center mb-1">';
        html += '<small class="text-muted me-1" style="width: 40px;">🖼️ صورة:</small>';
        html += '<input type="text" class="form-control inline-input a1-img" placeholder="صورة الإجابة 1" value="' + (q.answer_image || '').replace(/"/g, '&quot;') + '">';
        html += mediaBadge(q.answer_image_exists, q.answer_image);
        html += '</div>';

        html += '<div class="d-flex align-items-center mb-1">';
        html += '<small class="text-muted me-1" style="width: 40px;">🔊 صوت:</small>';
        html += '<input type="text" class="form-control inline-input a1-snd" placeholder="صوت الإجابة 1" value="' + (q.answer_sound || '').replace(/"/g, '&quot;') + '">';
        html += mediaBadge(q.answer_sound_exists, q.answer_sound);
        html += '</div>';

        html += '<div class="d-flex align-items-center">';
        html += '<small class="text-muted me-1" style="width: 40px;">🎬 فيديو:</small>';
        html += '<input type="text" class="form-control inline-input a1-vid" placeholder="فيديو الإجابة 1" value="' + (q.answer_video || '').replace(/"/g, '&quot;') + '">';
        html += mediaBadge(q.answer_video_exists, q.answer_video);
        html += '</div>';
        html += '</div>';
        html += '</td>';

        // 6) Answer 2
        html += '<td>';
        html += '<input type="hidden" class="ans-id-online-2" value="' + (q.online_ans_id_2 || '') + '">';
        html += '<input type="text" class="form-control inline-input mb-1 a2-title" placeholder="الإجابة الثانية (عربي)" value="' + (q.answer_title_two || '').replace(/"/g, '&quot;') + '">';
        html += '<input type="text" class="form-control inline-input mb-1 a2-title-en text-muted" placeholder="Answer 2 (EN)" value="' + (q.answer_title_en_two || '').replace(/"/g, '&quot;') + '">';

        html += '<div class="media-inputs-container">';
        html += '<div class="d-flex align-items-center mb-1">';
        html += '<small class="text-muted me-1" style="width: 40px;">🖼️ صورة:</small>';
        html += '<input type="text" class="form-control inline-input a2-img" placeholder="صورة الإجابة 2" value="' + (q.answer_image_two || '').replace(/"/g, '&quot;') + '">';
        html += mediaBadge(q.answer_image_two_exists, q.answer_image_two);
        html += '</div>';

        html += '<div class="d-flex align-items-center mb-1">';
        html += '<small class="text-muted me-1" style="width: 40px;">🔊 صوت:</small>';
        html += '<input type="text" class="form-control inline-input a2-snd" placeholder="صوت الإجابة 2" value="' + (q.answer_sound_two || '').replace(/"/g, '&quot;') + '">';
        html += mediaBadge(q.answer_sound_two_exists, q.answer_sound_two);
        html += '</div>';

        html += '<div class="d-flex align-items-center">';
        html += '<small class="text-muted me-1" style="width: 40px;">🎬 فيديو:</small>';
        html += '<input type="text" class="form-control inline-input a2-vid" placeholder="فيديو الإجابة 2" value="' + (q.answer_video_two || '').replace(/"/g, '&quot;') + '">';
        html += mediaBadge(q.answer_video_two_exists, q.answer_video_two);
        html += '</div>';
        html += '</div>';
        html += '</td>';

        // 7) Answer 3
        html += '<td>';
        html += '<input type="hidden" class="ans-id-online-3" value="' + (q.online_ans_id_3 || '') + '">';
        html += '<input type="text" class="form-control inline-input mb-1 a3-title" placeholder="الإجابة الثالثة (عربي)" value="' + (q.answer_title_three || '').replace(/"/g, '&quot;') + '">';
        html += '<input type="text" class="form-control inline-input mb-1 a3-title-en text-muted" placeholder="Answer 3 (EN)" value="' + (q.answer_title_en_three || '').replace(/"/g, '&quot;') + '">';

        html += '<div class="media-inputs-container">';
        html += '<div class="d-flex align-items-center mb-1">';
        html += '<small class="text-muted me-1" style="width: 40px;">🖼️ صورة:</small>';
        html += '<input type="text" class="form-control inline-input a3-img" placeholder="صورة الإجابة 3" value="' + (q.answer_image_three || '').replace(/"/g, '&quot;') + '">';
        html += mediaBadge(q.answer_image_three_exists, q.answer_image_three);
        html += '</div>';

        html += '<div class="d-flex align-items-center mb-1">';
        html += '<small class="text-muted me-1" style="width: 40px;">🔊 صوت:</small>';
        html += '<input type="text" class="form-control inline-input a3-snd" placeholder="صوت الإجابة 3" value="' + (q.answer_sound_three || '').replace(/"/g, '&quot;') + '">';
        html += mediaBadge(q.answer_sound_three_exists, q.answer_sound_three);
        html += '</div>';

        html += '<div class="d-flex align-items-center">';
        html += '<small class="text-muted me-1" style="width: 40px;">🎬 فيديو:</small>';
        html += '<input type="text" class="form-control inline-input a3-vid" placeholder="فيديو الإجابة 3" value="' + (q.answer_video_three || '').replace(/"/g, '&quot;') + '">';
        html += mediaBadge(q.answer_video_three_exists, q.answer_video_three);
        html += '</div>';
        html += '</div>';
        html += '</td>';

        // 8) Answer 4
        html += '<td>';
        html += '<input type="hidden" class="ans-id-online-4" value="' + (q.online_ans_id_4 || '') + '">';
        html += '<input type="text" class="form-control inline-input mb-1 a4-title" placeholder="الإجابة الرابعة (عربي)" value="' + (q.answer_title_four || '').replace(/"/g, '&quot;') + '">';
        html += '<input type="text" class="form-control inline-input mb-1 a4-title-en text-muted" placeholder="Answer 4 (EN)" value="' + (q.answer_title_en_four || '').replace(/"/g, '&quot;') + '">';

        html += '<div class="media-inputs-container">';
        html += '<div class="d-flex align-items-center mb-1">';
        html += '<small class="text-muted me-1" style="width: 40px;">🖼️ صورة:</small>';
        html += '<input type="text" class="form-control inline-input a4-img" placeholder="صورة الإجابة 4" value="' + (q.answer_image_four || '').replace(/"/g, '&quot;') + '">';
        html += mediaBadge(q.answer_image_four_exists, q.answer_image_four);
        html += '</div>';

        html += '<div class="d-flex align-items-center mb-1">';
        html += '<small class="text-muted me-1" style="width: 40px;">🔊 صوت:</small>';
        html += '<input type="text" class="form-control inline-input a4-snd" placeholder="صوت الإجابة 4" value="' + (q.answer_sound_four || '').replace(/"/g, '&quot;') + '">';
        html += mediaBadge(q.answer_sound_four_exists, q.answer_sound_four);
        html += '</div>';

        html += '<div class="d-flex align-items-center">';
        html += '<small class="text-muted me-1" style="width: 40px;">🎬 فيديو:</small>';
        html += '<input type="text" class="form-control inline-input a4-vid" placeholder="فيديو الإجابة 4" value="' + (q.answer_video_four || '').replace(/"/g, '&quot;') + '">';
        html += mediaBadge(q.answer_video_four_exists, q.answer_video_four);
        html += '</div>';
        html += '</div>';
        html += '</td>';

        // 9) Delete Button
        html += '<td class="text-center align-middle">';
        html += '<button type="button" class="btn btn-outline-danger btn-sm btn-delete-row" title="حذف هذا السؤال"><i class="bx bx-trash"></i></button>';
        html += '</td>';

        html += '</tr>';

        return html;
    }

    // جلب الأسئلة عبر AJAX
    $('#btnLoadQuestions').on('click', function(){
        var gameType = $('#export_game_type_id').val();
        var mainCat  = $('#export_main_category_id').val();
        var subCat   = $('#export_category_id').val();

        if (!gameType || gameType === 'non' || !mainCat || mainCat === 'non' || !subCat || subCat === 'non') {
            Swal.fire({
                icon: 'warning',
                title: 'تنبيه: تحديد الفئة مطلوب',
                text: 'يرجى اختيار نوع اللعبة والفئة الرئيسية والفئة الفرعية لعرض وتعديل أسئلتها.',
                confirmButtonText: 'حسناً',
                confirmButtonColor: '#0d6efd'
            });
            return;
        }

        showLoading('جاري جلب أسئلة الفئة...', 'يرجى الانتظار، يتم فحص بيانات الأسئلة ووسائط الإجابات.');

        $.ajax({
            url: '{{ route("excel.load_category_questions") }}',
            type: 'GET',
            data: {
                game_type_id: gameType,
                main_category_id: mainCat,
                category_id: subCat
            },
            success: function(res){
                hideLoading();
                if(res.success){
                    var tbody = $('#inlineQuestionsBody');
                    tbody.empty();

                    if(res.questions.length === 0){
                        toastr.info('لا توجد أسئلة مسجلة في هذه الفئة حالياً. يمكنك إضافة أسئلة جديدة مباشرة.');
                    }

                    $.each(res.questions, function(idx, q){
                        tbody.append(renderQuestionRow(q, idx));
                    });

                    $('#inlineCountBadge').text(res.count + ' سؤال');
                    $('#inlineMediaFoundBadge').text((res.total_media_found || 0) + ' ملف ✅');
                    $('#inlineMediaMissingBadge').text((res.total_media_missing || 0) + ' ملف ❌');

                    $('#inlineEditorWrapper').slideDown();
                    toastr.success('تم تحميل ' + res.count + ' سؤال مع كافة وسائط الإجابات بنجاح.');
                } else {
                    toastr.error(res.message || 'تعذر تحميل الأسئلة.');
                }
            },
            error: function(xhr){
                hideLoading();
                toastr.error('حدث خطأ أثناء تحميل الأسئلة.');
            }
        });
    });

    // رفع ملفات الـ ZIP عبر الـ Modal التفاعلي
    $('#ajaxInlineZipForm').on('submit', function(e){
        e.preventDefault();

        var formData = new FormData(this);
        var qZip = $('input[name="zip_questions"]').val();
        var aZip = $('input[name="zip_answers"]').val();

        if(!qZip && !aZip){
            toastr.warning('الرجاء اختيار ملف ZIP واحد على الأقل للرفع.');
            return;
        }

        showLoading('جاري رفع واستخراج وسائط الـ ZIP بأمان...', 'يتم فك الضغط في المسارات المحددة وتحديث حالة الوسائط في الجدول فوراً.');

        $.ajax({
            url: '{{ route("excel.upload_media") }}',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(res){
                hideLoading();
                $('#modalUploadInlineMedia').modal('hide');
                $('#ajaxInlineZipForm')[0].reset();

                if(res.success){
                    toastr.success(res.message);
                    if(res.replaced_count > 0){
                        toastr.warning('تم استبدال ' + res.replaced_count + ' ملف كان موجوداً مسبقاً بنفس الاسم.');
                    }
                    // إعادة تحميل الأسئلة لتحديث علامات توفر الوسائط ✅
                    $('#btnLoadQuestions').trigger('click');
                } else {
                    toastr.error(res.message || 'تعذر رفع الملفات.');
                }
            },
            error: function(xhr){
                hideLoading();
                var msg = 'حدث خطأ أثناء رفع ملفات ZIP.';
                if(xhr.responseJSON && xhr.responseJSON.message){
                    msg = xhr.responseJSON.message;
                } else if(xhr.status === 413) {
                    msg = 'حجم ملف الـ ZIP كبير جداً وتجاوز الحد الأقصى المسموح به في السيرفر. يرجى رفعه بأحجام أصغر.';
                } else if(xhr.status === 504 || xhr.status === 503) {
                    msg = 'استغرق رفع وفك ضغط الملفات وقتاً طويلاً (Timeout). يرجى تقليل عدد الملفات في ملف الـ ZIP.';
                }
                toastr.error(msg);
            }
        });
    });

    // إضافة صف سؤال جديد
    function addNewQuestionRow() {
        var newQ = {
            qu_id: '',
            qu_title: '',
            qu_title_en: '',
            qu_points: 100,
            time_counter: 30,
            qu_hint: '',
            term: '',
            qu_image: '',
            qu_image_exists: false,
            qu_sound: '',
            qu_sound_exists: false,
            qu_video: '',
            qu_video_exists: false,
            ans_id: '',
            online_ans_id_1: '',
            online_ans_id_2: '',
            online_ans_id_3: '',
            online_ans_id_4: '',
            answer_title: '',
            answer_title_en: '',
            answer_image: '',
            answer_image_exists: false,
            answer_sound: '',
            answer_sound_exists: false,
            answer_video: '',
            answer_video_exists: false,
            answer_title_two: '',
            answer_title_en_two: '',
            answer_image_two: '',
            answer_image_two_exists: false,
            answer_sound_two: '',
            answer_sound_two_exists: false,
            answer_video_two: '',
            answer_video_two_exists: false,
            answer_title_three: '',
            answer_title_en_three: '',
            answer_image_three: '',
            answer_image_three_exists: false,
            answer_sound_three: '',
            answer_sound_three_exists: false,
            answer_video_three: '',
            answer_video_three_exists: false,
            answer_title_four: '',
            answer_title_en_four: '',
            answer_image_four: '',
            answer_image_four_exists: false,
            answer_sound_four: '',
            answer_sound_four_exists: false,
            answer_video_four: '',
            answer_video_four_exists: false
        };

        var rowHtml = renderQuestionRow(newQ, $('#inlineQuestionsBody tr').length);
        $('#inlineQuestionsBody').prepend(rowHtml);

        var currentCount = $('#inlineQuestionsBody tr').length;
        $('#inlineCountBadge').text(currentCount + ' سؤال');
        toastr.info('تمت إضافة صف سؤال جديد مع كافة حقول وسائط الإجابات في أعلى الجدول.');
    }

    $('#btnAddQuestionRow, #btnAddQuestionRowBottom').on('click', function(){
        addNewQuestionRow();
    });

    // حذف صف سؤال
    $(document).on('click', '.btn-delete-row', function(){
        var row = $(this).closest('tr');
        var quId = row.data('qu-id');

        if(!quId){
            // صف جديد غير محفوظ في قاعدة البيانات
            row.fadeOut(200, function(){
                $(this).remove();
                var currentCount = $('#inlineQuestionsBody tr').length;
                $('#inlineCountBadge').text(currentCount + ' سؤال');
            });
        } else {
            // سؤال موجود في قاعدة البيانات
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "سيتم حذف هذا السؤال وإجاباته نهائياً من قاعدة البيانات!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading('جاري حذف السؤال...');
                    $.ajax({
                        url: '/excel/delete-category-question/' + quId,
                        type: 'POST',
                        success: function(res){
                            hideLoading();
                            if(res.success){
                                row.fadeOut(200, function(){
                                    $(this).remove();
                                    var currentCount = $('#inlineQuestionsBody tr').length;
                                    $('#inlineCountBadge').text(currentCount + ' سؤال');
                                });
                                toastr.success('تم حذف السؤال بنجاح.');
                            } else {
                                toastr.error(res.message);
                            }
                        },
                        error: function(){
                            hideLoading();
                            toastr.error('حدث خطأ أثناء محاولة حذف السؤال.');
                        }
                    });
                }
            });
        }
    });

    // حفظ جميع التعديلات مباشرة عبر AJAX
    function saveAllInlineQuestions() {
        var rows = [];

        $('#inlineQuestionsBody tr').each(function(){
            var row = $(this);
            var title = $.trim(row.find('.q-title').val());

            if(!title){
                return;
            }

            var item = {
                qu_id:               row.data('qu-id') || '',
                qu_title:            title,
                qu_title_en:         $.trim(row.find('.q-title-en').val()),
                qu_points:           row.find('.q-points').val() || 0,
                time_counter:        row.find('.q-timer').val() || 30,
                qu_hint:             $.trim(row.find('.q-hint').val()),
                term:                $.trim(row.find('.q-term').val()),
                qu_image:            $.trim(row.find('.q-img').val()),
                qu_sound:            $.trim(row.find('.q-snd').val()),
                qu_video:            $.trim(row.find('.q-vid').val()),
                ans_id:              row.find('.ans-id-local').val() || '',
                online_ans_id_1:     row.find('.ans-id-online-1').val() || '',
                online_ans_id_2:     row.find('.ans-id-online-2').val() || '',
                online_ans_id_3:     row.find('.ans-id-online-3').val() || '',
                online_ans_id_4:     row.find('.ans-id-online-4').val() || '',
                answer_title:        $.trim(row.find('.a1-title').val()),
                answer_title_en:     $.trim(row.find('.a1-title-en').val()),
                answer_image:        $.trim(row.find('.a1-img').val()),
                answer_sound:        $.trim(row.find('.a1-snd').val()),
                answer_video:        $.trim(row.find('.a1-vid').val()),
                answer_title_two:    $.trim(row.find('.a2-title').val()),
                answer_title_en_two: $.trim(row.find('.a2-title-en').val()),
                answer_image_two:    $.trim(row.find('.a2-img').val()),
                answer_sound_two:    $.trim(row.find('.a2-snd').val()),
                answer_video_two:    $.trim(row.find('.a2-vid').val()),
                answer_title_three:  $.trim(row.find('.a3-title').val()),
                answer_title_en_three: $.trim(row.find('.a3-title-en').val()),
                answer_image_three:  $.trim(row.find('.a3-img').val()),
                answer_sound_three:  $.trim(row.find('.a3-snd').val()),
                answer_video_three:  $.trim(row.find('.a3-vid').val()),
                answer_title_four:   $.trim(row.find('.a4-title').val()),
                answer_title_en_four: $.trim(row.find('.a4-title-en').val()),
                answer_image_four:   $.trim(row.find('.a4-img').val()),
                answer_sound_four:   $.trim(row.find('.a4-snd').val()),
                answer_video_four:   $.trim(row.find('.a4-vid').val()),
            };

            rows.push(item);
        });

        if(rows.length === 0){
            toastr.warning('لا توجد أسئلة تحتوي على عناوين لحفظها.');
            return;
        }

        var gameType = $('#export_game_type_id').val();
        var mainCat  = $('#export_main_category_id').val();
        var subCat   = $('#export_category_id').val();

        showLoading('جاري حفظ جميع التعديلات مباشرة...', 'يتم تحديث وإنشاء الأسئلة والإجابات والوسائط في قاعدة البيانات.');

        $.ajax({
            url: '{{ route("excel.save_category_questions") }}',
            type: 'POST',
            data: {
                questions: rows,
                game_type_id: gameType,
                main_category_id: mainCat,
                category_id: subCat
            },
            success: function(res){
                hideLoading();
                if(res.success){
                    toastr.success(res.message);
                    // إعادة تحميل الأسئلة للحصول على الـ IDs والوسائط المحدثة
                    $('#btnLoadQuestions').trigger('click');
                } else {
                    toastr.error(res.message || 'حدث خطأ أثناء الحفظ.');
                }
            },
            error: function(xhr){
                hideLoading();
                var msg = 'حدث خطأ أثناء حفظ التعديلات.';
                if(xhr.responseJSON && xhr.responseJSON.message){
                    msg = xhr.responseJSON.message;
                }
                toastr.error(msg);
            }
        });
    }

    $('#btnSaveInlineQuestionsTop, #btnSaveInlineQuestionsBottom').on('click', function(){
        saveAllInlineQuestions();
    });

});
</script>

@endsection
