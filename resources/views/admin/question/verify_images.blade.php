@extends('admin.master_admin')
@section('admin')

<style>
    /* ============================================
       التحقق من صور الأسئلة والإجابات - Styles
    ============================================ */
    :root {
        --color-found:   #10b981;
        --color-missing: #ef4444;
        --color-no-path: #f59e0b;
        --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --gradient-success: linear-gradient(135deg, #10b981 0%, #059669 100%);
        --gradient-danger:  linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        --gradient-warning: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        --card-shadow:       0 4px 24px rgba(0,0,0,0.08);
        --card-shadow-hover: 0 8px 40px rgba(0,0,0,0.15);
    }

    .verify-page-header {
        background: var(--gradient-primary);
        border-radius: 16px;
        padding: 28px 32px;
        margin-bottom: 28px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .verify-page-header::before {
        content: '';
        position: absolute;
        top: -50%; right: -20%;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.06);
        border-radius: 50%;
    }
    .verify-page-header h1 { font-size: 1.7rem; font-weight: 800; margin: 0; }
    .verify-page-header p  { margin: 6px 0 0; opacity: 0.85; font-size: 0.95rem; }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 18px;
        margin-bottom: 28px;
    }
    .stat-card {
        border-radius: 14px;
        padding: 22px 20px;
        color: #fff;
        position: relative;
        overflow: hidden;
        transition: transform .3s, box-shadow .3s;
        cursor: default;
    }
    .stat-card:hover { transform: translateY(-4px); box-shadow: var(--card-shadow-hover); }
    .stat-card::after {
        content: '';
        position: absolute;
        bottom: -30px; left: -20px;
        width: 120px; height: 120px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }
    .stat-card .stat-icon  { font-size: 2.2rem; opacity: .9; margin-bottom: 10px; }
    .stat-card .stat-value { font-size: 2.4rem; font-weight: 800; line-height: 1; margin-bottom: 4px; }
    .stat-card .stat-label { font-size: 0.82rem; opacity: .88; font-weight: 600; }

    .stat-card.stat-total   { background: var(--gradient-primary); }
    .stat-card.stat-success { background: var(--gradient-success); }
    .stat-card.stat-danger  { background: var(--gradient-danger); }
    .stat-card.stat-warning { background: var(--gradient-warning); }
    .stat-card.stat-info    { background: linear-gradient(135deg,#3b82f6,#1d4ed8); }
    .stat-card.stat-teal    { background: linear-gradient(135deg,#14b8a6,#0d9488); }
    .stat-card.stat-orange  { background: linear-gradient(135deg,#fb923c,#ea580c); }
    .stat-card.stat-purple  { background: linear-gradient(135deg,#8b5cf6,#6d28d9); }

    /* Skeleton loader for stat values */
    .stat-skeleton {
        display: inline-block;
        width: 60px; height: 38px;
        background: rgba(255,255,255,0.25);
        border-radius: 8px;
        animation: shimmer 1.4s infinite;
    }
    @keyframes shimmer {
        0%,100% { opacity:.6; }
        50%      { opacity:1; }
    }

    /* Search & Filter */
    .search-filter-card {
        background: #fff;
        border-radius: 14px;
        padding: 20px 24px;
        margin-bottom: 24px;
        box-shadow: var(--card-shadow);
        border: 1px solid rgba(0,0,0,.05);
    }
    .search-filter-card .form-control,
    .search-filter-card .form-select {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        font-size: .92rem;
        padding: 10px 14px;
        transition: border-color .25s, box-shadow .25s;
    }
    .search-filter-card .form-control:focus,
    .search-filter-card .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,.15);
    }
    .btn-search {
        background: var(--gradient-primary);
        border: none; border-radius: 10px;
        color: #fff; padding: 10px 22px;
        font-weight: 600; font-size: .92rem;
        transition: opacity .2s, transform .2s;
    }
    .btn-search:hover { opacity:.9; transform:translateY(-1px); color:#fff; }
    .btn-reset {
        background: #f1f5f9;
        border: 1.5px solid #e2e8f0; border-radius: 10px;
        color: #475569; padding: 10px 20px;
        font-weight: 600; font-size: .92rem;
        transition: all .2s;
    }
    .btn-reset:hover { background:#e2e8f0; color:#334155; }

    /* Filter Tabs */
    .filter-tabs { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 20px; }
    .filter-tab {
        padding: 7px 16px;
        border-radius: 20px;
        border: 1.5px solid #e2e8f0;
        background: #fff; color: #475569;
        font-size: .83rem; font-weight: 600;
        cursor: pointer; text-decoration: none;
        transition: all .2s;
    }
    .filter-tab:hover, .filter-tab.active { background:#667eea; border-color:#667eea; color:#fff; text-decoration:none; }
    .filter-tab.tab-missing.active  { background:#ef4444; border-color:#ef4444; }
    .filter-tab.tab-found.active    { background:#10b981; border-color:#10b981; }
    .filter-tab.tab-no-path.active  { background:#f59e0b; border-color:#f59e0b; }
    .filter-tab.tab-issues.active   { background:#8b5cf6; border-color:#8b5cf6; }

    /* Table */
    .verify-table-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
        border: 1px solid rgba(0,0,0,.05);
    }
    .verify-table-header {
        background: linear-gradient(90deg,#f8fafc,#f1f5f9);
        padding: 16px 24px;
        border-bottom: 2px solid #e2e8f0;
        display: flex; align-items: center; justify-content: space-between;
    }
    .verify-table-header h5 { margin:0; font-weight:700; color:#1e293b; font-size:1.05rem; }

    .table-verify { margin:0; font-size:.88rem; }
    .table-verify thead th {
        background: #1e293b; color: #e2e8f0;
        font-weight: 600; font-size: .8rem;
        letter-spacing: .5px; text-transform: uppercase;
        padding: 14px 12px; border: none; white-space: nowrap;
    }
    /* content-visibility يحسّن أداء الرسم بتخطي الصفوف خارج الشاشة */
    .table-verify tbody tr {
        border-bottom: 1px solid #f1f5f9;
        content-visibility: auto;
        contain-intrinsic-size: 0 90px;
    }
    .table-verify tbody tr:hover { background: #f8faff; }
    .table-verify tbody td { padding: 14px 12px; vertical-align: middle; border: none; }
    /* تعطيل hover وتعطيل transition أثناء التمرير لتحسين الأداء */
    body.is-scrolling .table-verify tbody tr { transition: none !important; }
    body.is-scrolling .table-verify tbody tr:hover { background: transparent; }
    /* منع scroll anchoring من إعاقة التمرير */
    #verifyTbody { overflow-anchor: none; }

    /* Status Badges */
    .status-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 12px; border-radius: 20px;
        font-size: .78rem; font-weight: 700;
    }
    .status-badge.found     { background:rgba(16,185,129,.12); color:#065f46; border:1px solid rgba(16,185,129,.3); }
    /* animation مُعطَّلة افتراضياً - تُشغَّل فقط أثناء الفحص */
    .status-badge.missing   { background:rgba(239,68,68,.12);  color:#991b1b; border:1px solid rgba(239,68,68,.3); }
    body.scanning .status-badge.missing { animation:pulse-miss 2s infinite; }
    .status-badge.no-path   { background:rgba(245,158,11,.12); color:#92400e; border:1px solid rgba(245,158,11,.3); }
    .status-badge.not-applicable { background:rgba(107,114,128,.1); color:#374151; border:1px solid rgba(107,114,128,.2); }
    @keyframes pulse-miss { 0%,100%{opacity:1} 50%{opacity:.7} }

    /* Type badges */
    .type-badge { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:12px; font-size:.76rem; font-weight:700; }
    .type-badge.image { background:#ede9fe; color:#5b21b6; }
    .type-badge.text  { background:#e0f2fe; color:#0369a1; }
    .type-badge.sound { background:#fef3c7; color:#92400e; }
    .type-badge.video { background:#fce7f3; color:#9d174d; }

    /* Image thumbnails */
    .img-preview-thumb {
        width:56px; height:56px; object-fit:cover;
        border-radius:8px; border:2px solid #e2e8f0;
        cursor:pointer; transition:transform .2s, box-shadow .2s;
    }
    .img-preview-thumb:hover { transform:scale(1.1); box-shadow:0 4px 12px rgba(0,0,0,.15); }

    /* عنوان السؤال - CSS hover بدل inline JS لتجنب reflow */
    .verify-q-title { font-size:0.88rem; line-height:1.4; transition:color .2s; }
    body.is-scrolling .verify-q-title { transition: none !important; }
    a:hover .verify-q-title { color: #667eea; }

    /* Answers list */
    .answers-list { list-style:none; padding:0; margin:0; }
    .answer-item  { display:flex; align-items:center; gap:8px; padding:5px 0; border-bottom:1px dashed #f1f5f9; font-size:.82rem; }
    .answer-item:last-child { border-bottom:none; }
    .answer-number {
        width:20px; height:20px; border-radius:50%;
        background:#667eea; color:#fff;
        font-size:.68rem; font-weight:700;
        display:flex; align-items:center; justify-content:center; flex-shrink:0;
    }

    /* Legend */
    .legend-item { display:inline-flex; align-items:center; gap:5px; font-size:.8rem; color:#475569; }
    .legend-dot  { width:10px; height:10px; border-radius:50%; }

    /* Progress bar */
    .progress-custom { height:8px; border-radius:8px; background:#f1f5f9; }
    .progress-bar-custom { height:100%; border-radius:8px; transition:width 1s ease; }

    /* =====================
       Lazy loading elements
    ===================== */
    /* Overall scan progress bar */
    #scanProgressWrap {
        background: #fff;
        border-radius: 14px;
        padding: 18px 24px;
        margin-bottom: 20px;
        box-shadow: var(--card-shadow);
        border: 1px solid rgba(102,126,234,.15);
    }
    #scanProgressBar {
        height: 10px; border-radius: 10px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        transition: width .4s ease;
    }
    #scanProgressTrack {
        height: 10px; border-radius: 10px;
        background: #e2e8f0;
        overflow: hidden;
    }

    /* Row skeleton loader */
    .skeleton-row td { padding: 14px 12px; }
    .skeleton-cell {
        height: 16px; border-radius: 6px;
        background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
        background-size: 200% 100%;
        animation: skeleton-wave 1.4s infinite;
    }
    @keyframes skeleton-wave {
        0%   { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* Infinite scroll sentinel */
    #scrollSentinel { height: 1px; }

    /* Empty state */
    .empty-state { text-align:center; padding:60px 24px; color:#94a3b8; }
    .empty-state .empty-icon { font-size:4rem; margin-bottom:12px; opacity:.4; }

    #imagePreviewModal .modal-content { border-radius:16px; border:none; box-shadow:0 25px 60px rgba(0,0,0,.3); }
    #imagePreviewModal .modal-body    { padding:8px; }
    #imagePreviewModal img            { width:100%; border-radius:10px; }

    @media (max-width:768px) {
        .stats-grid { grid-template-columns: repeat(2,1fr); }
        .verify-page-header { padding:20px; }
        .verify-page-header h1 { font-size:1.3rem; }
    }
</style>

{{-- Breadcrumb --}}
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">التحقق من صور الأسئلة والإجابات</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('all.question') }}">الأسئلة</a></li>
                <li class="breadcrumb-item active">التحقق من الصور</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="{{ route('all.question') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
            <i class="bx bx-arrow-back me-1"></i> عودة للأسئلة
        </a>
    </div>
</div>

{{-- Page Header --}}
<div class="verify-page-header">
    <div class="d-flex align-items-center gap-3">
        <div style="font-size:2.8rem;opacity:.95;">🔍</div>
        <div>
            <h1><i class="bx bx-image-check me-2"></i>التحقق من صور الأسئلة والإجابات</h1>
            <p>مقارنة مسارات الصور في قاعدة البيانات مع الملفات الفعلية على الخادم</p>
        </div>
    </div>
</div>

{{-- Stats Cards --}}
<div class="stats-grid">
    <div class="stat-card stat-total">
        <div class="stat-icon"><i class="bx bx-list-ul"></i></div>
        <div class="stat-value">{{ $stats['total_questions'] }}</div>
        <div class="stat-label">إجمالي الأسئلة</div>
    </div>
    <div class="stat-card stat-info">
        <div class="stat-icon"><i class="bx bx-image"></i></div>
        <div class="stat-value">{{ $stats['image_questions'] }}</div>
        <div class="stat-label">أسئلة من نوع صورة</div>
    </div>
    <div class="stat-card stat-success" id="card-q-found">
        <div class="stat-icon"><i class="bx bx-check-circle"></i></div>
        <div class="stat-value" id="val-q-found"><span class="stat-skeleton"></span></div>
        <div class="stat-label">صور أسئلة موجودة ✓</div>
    </div>
    <div class="stat-card stat-danger" id="card-q-missing">
        <div class="stat-icon"><i class="bx bx-x-circle"></i></div>
        <div class="stat-value" id="val-q-missing"><span class="stat-skeleton"></span></div>
        <div class="stat-label">صور أسئلة مفقودة ✗</div>
    </div>
    <div class="stat-card stat-warning" id="card-q-nopath">
        <div class="stat-icon"><i class="bx bx-error-circle"></i></div>
        <div class="stat-value" id="val-q-nopath"><span class="stat-skeleton"></span></div>
        <div class="stat-label">أسئلة بلا مسار صورة</div>
    </div>
    <div class="stat-card stat-teal">
        <div class="stat-icon"><i class="bx bx-images"></i></div>
        <div class="stat-value">{{ $stats['total_answer_images'] }}</div>
        <div class="stat-label">إجابات من نوع صورة</div>
    </div>
    <div class="stat-card stat-success" id="card-a-found">
        <div class="stat-icon"><i class="bx bx-check-double"></i></div>
        <div class="stat-value" id="val-a-found"><span class="stat-skeleton"></span></div>
        <div class="stat-label">صور إجابات موجودة ✓</div>
    </div>
    <div class="stat-card stat-orange" id="card-a-issue">
        <div class="stat-icon"><i class="bx bx-error"></i></div>
        <div class="stat-value" id="val-a-issue"><span class="stat-skeleton"></span></div>
        <div class="stat-label">صور إجابات مشكلة ✗</div>
    </div>
    <div class="stat-card stat-purple" id="card-best-q-cat" style="display: none;">
        <div class="stat-icon"><i class="bx bx-crown"></i></div>
        <div class="stat-value" id="val-best-q-cat" style="font-size:1.3rem; font-weight:800; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="">—</div>
        <div class="stat-label" id="label-best-q-cat">أكثر فئة سليمة (صور الأسئلة)</div>
    </div>
    <div class="stat-card stat-purple" id="card-best-a-cat" style="display: none;">
        <div class="stat-icon"><i class="bx bx-award"></i></div>
        <div class="stat-value" id="val-best-a-cat" style="font-size:1.3rem; font-weight:800; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="">—</div>
        <div class="stat-label" id="label-best-a-cat">أكثر فئة سليمة (صور الإجابات)</div>
    </div>
    <div class="stat-card stat-danger" id="card-worst-q-cat" style="display: none;">
        <div class="stat-icon"><i class="bx bx-error-alt"></i></div>
        <div class="stat-value" id="val-worst-q-cat" style="font-size:1.3rem; font-weight:800; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="">—</div>
        <div class="stat-label" id="label-worst-q-cat">أكثر فئة بها مشاكل (صور الأسئلة)</div>
    </div>
    <div class="stat-card stat-danger" id="card-worst-a-cat" style="display: none;">
        <div class="stat-icon"><i class="bx bx-shield-x"></i></div>
        <div class="stat-value" id="val-worst-a-cat" style="font-size:1.3rem; font-weight:800; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="">—</div>
        <div class="stat-label" id="label-worst-a-cat">أكثر فئة بها مشاكل (صور الإجابات)</div>
    </div>
</div>

{{-- Scan Progress --}}
<div id="scanProgressWrap">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="fw-700 text-dark" style="font-size:.9rem;">
            <i class="bx bx-loader-alt bx-spin me-2 text-primary" id="scanSpinnerIcon"></i>
            <span id="scanStatusText">جاري فحص الملفات... يرجى الانتظار</span>
        </span>
        <span class="text-muted" style="font-size:.82rem;" id="scanCounter">0 / {{ $stats['total_questions'] }}</span>
    </div>
    <div id="scanProgressTrack">
        <div id="scanProgressBar" style="width:0%;"></div>
    </div>
    <div class="mt-2 d-flex gap-3 align-items-center" id="scanLegend" style="display:none!important;">
        <span class="legend-item"><span class="legend-dot" style="background:#10b981;"></span> موجودة: <strong id="leg-found">0</strong></span>
        <span class="legend-item"><span class="legend-dot" style="background:#ef4444;"></span> مفقودة: <strong id="leg-missing">0</strong></span>
        <span class="legend-item"><span class="legend-dot" style="background:#f59e0b;"></span> بلا مسار: <strong id="leg-nopath">0</strong></span>
    </div>
</div>

{{-- Search --}}
<div class="search-filter-card">
    <div class="row g-3 align-items-end mb-3">
        <div class="col-md-12">
            <label class="form-label fw-600 text-dark mb-1" style="font-size:.85rem;">
                <i class="bx bx-category me-1 text-primary"></i> التصفية حسب الفئة
            </label>
            <select id="categoryFilter" class="form-select">
                <option value="">كل الفئات</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->category_name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row g-3 align-items-end">
        <div class="col-md-7">
            <label class="form-label fw-600 text-dark mb-1" style="font-size:.85rem;">
                <i class="bx bx-search me-1 text-primary"></i> البحث في الأسئلة
            </label>
            <input type="text" id="searchInput" class="form-control"
                   placeholder="ابحث بعنوان السؤال..."
                   value="{{ $search ?? '' }}">
            {{-- حقل مخفي للحفاظ على توافق الكود --}}
            <input type="hidden" id="filterTypeSelect" value="">
        </div>
        <div class="col-md-5">
            <div class="d-flex gap-2">
                <button type="button" class="btn-search btn" id="btnSearch" style="white-space: nowrap;">
                    <i class="bx bx-search me-1"></i> بحث وتحقق
                </button>
                <button type="button" class="btn-reset btn" id="btnReset" style="white-space: nowrap;">
                    <i class="bx bx-refresh me-1"></i> إعادة تعيين
                </button>
                <button type="button" class="btn btn-success" id="btnSanitizeNames" style="background: linear-gradient(135deg, #10b981, #059669); border:none; border-radius:10px; font-weight:600; font-size:.92rem; padding: 10px 18px; white-space: nowrap;">
                    <i class="bx bx-magic-wand me-1"></i> ضبط أسماء الصور
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Filter Tabs (مخفية حتى اكتمال الفحص) --}}
<div class="filter-tabs" id="filterTabs" style="display:none;">
    <a href="javascript:;" class="filter-tab tab-missing active" data-filter="question_missing">
        <i class="bx bx-x-circle me-1"></i> صور أسئلة مفقودة
        <span class="badge rounded-pill ms-1" style="background:rgba(255,255,255,.3);color:inherit;" id="tab-count-q-missing">—</span>
    </a>
    <a href="javascript:;" class="filter-tab tab-no-path" data-filter="question_no_path">
        <i class="bx bx-error-circle me-1"></i> بلا مسار صورة
        <span class="badge rounded-pill ms-1" style="background:rgba(255,255,255,.3);color:inherit;" id="tab-count-q-nopath">—</span>
    </a>
    <a href="javascript:;" class="filter-tab" data-filter="answer_missing" style="border-color:#ef4444;color:#ef4444;">
        <i class="bx bx-image-alt me-1"></i> صور إجابات مفقودة
        <span class="badge rounded-pill ms-1" style="background:rgba(239,68,68,.15);color:#ef4444;" id="tab-count-a-missing">—</span>
    </a>
    <a href="javascript:;" class="filter-tab tab-found" data-filter="all_ok">
        <i class="bx bx-check-circle me-1"></i> جميعها سليمة
        <span class="badge rounded-pill ms-1" style="background:rgba(255,255,255,.3);color:inherit;" id="tab-count-ok">—</span>
    </a>
</div>

{{-- Main Table (مخفية حتى اكتمال الفحص) --}}
<div class="verify-table-card" id="verifyTableCard" style="display:none;">
    <div class="verify-table-header">
        <h5><i class="bx bx-table me-2 text-primary"></i>تفاصيل التحقق من الصور</h5>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <span class="legend-item"><span class="legend-dot" style="background:#10b981;"></span>موجودة</span>
            <span class="legend-item"><span class="legend-dot" style="background:#ef4444;"></span>مفقودة</span>
            <span class="legend-item"><span class="legend-dot" style="background:#f59e0b;"></span>بلا مسار</span>
            <span class="badge bg-secondary rounded-pill" id="visibleCountBadge">—</span>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-verify" id="verifyTable">
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th style="width:200px;">السؤال</th>
                    <th style="width:80px;">النوع</th>
                    <th style="width:90px;">الفئة</th>
                    <th style="width:150px;">
                        مسار صورة السؤال
                        <small class="d-block text-muted" style="font-size:.65rem;">upload/questions/images/</small>
                    </th>
                    <th style="width:120px;">حالة صورة السؤال</th>
                    <th>الإجابات وحالة صورها</th>
                </tr>
            </thead>
            <tbody id="verifyTbody">
                {{-- Skeleton rows shown while first batch loads --}}
                @for($s = 0; $s < 8; $s++)
                <tr class="skeleton-row">
                    <td><div class="skeleton-cell" style="width:30px;"></div></td>
                    <td><div class="skeleton-cell mb-1"></div><div class="skeleton-cell" style="width:60%;height:12px;"></div></td>
                    <td><div class="skeleton-cell" style="width:50px;"></div></td>
                    <td><div class="skeleton-cell" style="width:70px;"></div></td>
                    <td><div class="skeleton-cell" style="width:80px;"></div></td>
                    <td><div class="skeleton-cell" style="width:70px;"></div></td>
                    <td><div class="skeleton-cell"></div></td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>

    {{-- Infinite scroll sentinel --}}
    <div id="scrollSentinel"></div>

    {{-- Loading more indicator --}}
    <div id="loadingMore" class="text-center py-3" style="display:none;">
        <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
        <span class="text-muted" style="font-size:.85rem;">جاري تحميل المزيد وفحص الملفات...</span>
    </div>

    {{-- All loaded message --}}
    <div id="allLoaded" class="text-center py-3" style="display:none;">
        <i class="bx bx-check-circle text-success me-1"></i>
        <span class="text-muted" style="font-size:.85rem;">تم الانتهاء من فحص جميع الأسئلة</span>
    </div>

    {{-- Table Footer --}}
    <div class="d-flex justify-content-between align-items-center px-4 py-3"
         style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <small class="text-muted">
            <i class="bx bx-info-circle me-1"></i>
            الصفوف المعروضة: <strong id="footerVisibleCount">—</strong>
        </small>
        <div class="d-flex gap-3">
            <span class="legend-item">
                <span class="legend-dot" style="background:#10b981;"></span>
                صور موجودة: <strong id="footer-found">—</strong>
            </span>
            <span class="legend-item">
                <span class="legend-dot" style="background:#ef4444;"></span>
                صور مفقودة: <strong id="footer-missing">—</strong>
            </span>
        </div>
    </div>
</div>

{{-- Image Preview Modal --}}
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-700" id="imagePreviewTitle">معاينة الصورة</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <img id="imagePreviewSrc" src="" alt="معاينة"
                     style="width:100%;border-radius:10px;max-height:70vh;object-fit:contain;">
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    // ─── Config ───────────────────────────────────────
    const ROUTE_AJAX     = "{{ route('verify.question.images') }}";
    const ROUTE_SANITIZE = "{{ route('verify.question.images.sanitize') }}";
    const CSRF           = "{{ csrf_token() }}";

    // ─── State ────────────────────────────────────────
    let currentPage  = 0;
    let lastPage     = null;
    let isLoading    = false;
    let scanDone     = false;
    let activeFilter = 'all';

    // Buffer: نجمع كل HTML من الدفعات هنا، ثم نعرضها دفعة واحدة عند الانتهاء
    let htmlBuffer = '';

    // إحصائيات تراكمية
    const cStats = {
        q_found: 0, q_missing: 0, q_nopath: 0,
        a_found: 0, a_missing: 0, a_nopath: 0,
    };

    // ─── Elements ─────────────────────────────────────
    const tbody          = document.getElementById('verifyTbody');
    const tableCard      = document.getElementById('verifyTableCard');
    const filterTabs     = document.getElementById('filterTabs');
    const allLoaded      = document.getElementById('allLoaded');
    const scanProgress   = document.getElementById('scanProgressBar');
    const scanCounter    = document.getElementById('scanCounter');
    const scanStatusText = document.getElementById('scanStatusText');
    const scanLegend     = document.getElementById('scanLegend');

    // ─── Start: تفريغ الـ skeleton وبدء الجلب التلقائي ─────
    function init() {
        tbody.innerHTML = '';
        // تفعيل animation أثناء الفحص فقط
        document.body.classList.add('scanning');
        fetchLoop();
    }

    // ─── Loop تلقائي يجلب كل الدفعات بلا توقف ────────────
    async function fetchLoop() {
        while (true) {
            if (scanDone) break;
            if (lastPage !== null && currentPage >= lastPage) {
                markScanDone();
                break;
            }

            currentPage++;
            isLoading = true;

            const search     = document.getElementById('searchInput').value.trim();
            const filterType = document.getElementById('filterTypeSelect').value;
            const categoryId = document.getElementById('categoryFilter').value;

            try {
                const res  = await fetch(ROUTE_AJAX + '?' + new URLSearchParams({
                    page: currentPage,
                    search: search,
                    filter_type: filterType,
                    category_id: categoryId,
                }), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': CSRF,
                    }
                });

                const data = await res.json();
                isLoading  = false;
                lastPage   = data.last_page;

                // تجميع HTML في الـ buffer
                htmlBuffer += data.html;

                // تحديث الإحصائيات التراكمية
                const bs = data.batch_stats;
                cStats.q_found   += bs.question_images_found   || 0;
                cStats.q_missing += bs.question_images_missing || 0;
                cStats.q_nopath  += bs.question_images_no_path || 0;
                cStats.a_found   += bs.answer_images_found     || 0;
                cStats.a_missing += bs.answer_images_missing   || 0;
                cStats.a_nopath  += bs.answer_images_no_path   || 0;

                // تحديث شريط التقدم فقط
                const scanned = Math.min(currentPage * 50, data.total);
                const pct     = data.total > 0 ? Math.round((scanned / data.total) * 100) : 100;
                scanProgress.style.width = pct + '%';
                scanCounter.textContent  = scanned + ' / ' + data.total;

                if (!data.next_page) {
                    markScanDone();
                    break;
                }

                // منح المتصفح إطار واحد للتنفس بين كل دفعة
                await sleep(16);

            } catch (err) {
                isLoading = false;
                currentPage--; // أعد المحاولة عند الخطأ
                await sleep(1000);
            }
        }
    }

    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    // ─── عند اكتمال الفحص: اعرض كل شيء دفعة واحدة ──────
    function markScanDone() {
        scanDone = true;

        // ① inject كل الصفوف عبر template لتحسين الأداء
        const tpl = document.createElement('template');
        tpl.innerHTML = htmlBuffer;
        tbody.innerHTML = '';
        tbody.appendChild(tpl.content);
        htmlBuffer = ''; // تحرير الذاكرة فوراً

        // ② تعيين الفلتر الافتراضي إلى "صور أسئلة مفقودة" عند اكتمال الفحص
        activeFilter = 'question_missing';
        document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
        const missingTab = document.querySelector('.filter-tab[data-filter="question_missing"]');
        if (missingTab) missingTab.classList.add('active');

        // ③ تطبيق الفلتر
        applyDisplayFilterAll();

        // ④ تحديث الإحصائيات النهائية
        updateStatCards();
        updateTabCounts();
        updateFooter();
        updateBestCategories();

        // ⑤ إظهار الجدول وتبويبات الفلترة
        tableCard.style.display  = '';
        filterTabs.style.display = '';

        // ⑥ تحديث شريط الفحص
        scanProgress.style.width = '100%';
        const spinnerIcon = document.getElementById('scanSpinnerIcon');
        if (spinnerIcon) spinnerIcon.style.display = 'none';
        scanStatusText.innerHTML = '<i class="bx bx-check-circle text-success me-1"></i> اكتمل الفحص';
        scanLegend.removeAttribute('style');
        updateLegend();
        allLoaded.style.display  = '';

        // ⑦ إيقاف كل الـ CSS animations لتحسين أداء التمرير
        document.body.classList.remove('scanning');
        document.body.classList.add('scan-complete');
    }

    // ─── تحديث بطاقات الإحصائيات ─────────────────────────
    function updateStatCards() {
        setText('val-q-found',   cStats.q_found);
        setText('val-q-missing', cStats.q_missing);
        setText('val-q-nopath',  cStats.q_nopath);
        setText('val-a-found',   cStats.a_found);
        setText('val-a-issue',   cStats.a_missing + cStats.a_nopath);
    }

    function setText(id, val) {
        const el = document.getElementById(id);
        if (el) el.textContent = val;
    }

    // ─── تحديث أعداد تبويبات الفلترة ────────────────────
    function updateTabCounts() {
        const rows = tbody.querySelectorAll('.verify-row');
        let qMissing = 0, qNopath = 0, aMissing = 0, ok = 0;
        rows.forEach(r => {
            if (r.dataset.questionStatus === 'missing')    qMissing++;
            if (r.dataset.questionStatus === 'no_path')    qNopath++;
            if (r.dataset.hasAnswerMissing === '1')        aMissing++;
            if (r.dataset.allFound === '1')                ok++;
        });
        setText('tab-count-all',       rows.length);
        // "التي بها مشاكل" = مجموع المفقودة (صور أسئلة + صور إجابات)
        setText('tab-count-issues',    cStats.q_missing + cStats.a_missing);
        setText('tab-count-q-missing', qMissing);
        setText('tab-count-q-nopath',  qNopath);
        setText('tab-count-a-missing', aMissing);
        setText('tab-count-ok',        ok);
    }

    // ─── تحديث footer (يستخدم العداد المُخزَّن بدلاً من scan DOM) ────
    let _visibleCount = 0;
    function updateFooter(count) {
        if (count !== undefined) _visibleCount = count;
        setText('footerVisibleCount', _visibleCount);
        setText('visibleCountBadge',  _visibleCount + ' صف');
        setText('footer-found',   cStats.q_found  + cStats.a_found);
        setText('footer-missing', cStats.q_missing + cStats.a_missing);
    }

    // ─── تحديث legend الفحص ───────────────────────────────
    function updateLegend() {
        setText('leg-found',   cStats.q_found  + cStats.a_found);
        setText('leg-missing', cStats.q_missing + cStats.a_missing);
        setText('leg-nopath',  cStats.q_nopath  + cStats.a_nopath);
    }

    // ─── فلتر عرض الصفوف (جانب العميل) ──────────────────
    // يستخدم requestAnimationFrame لتجميع عمليات الكتابة في إطار واحد
    function applyDisplayFilterAll() {
        const rows = tbody.querySelectorAll('.verify-row');
        // قراءة data-attributes دفعة واحدة (لا تسبب reflow)
        const filter = activeFilter;
        let visible = 0;

        // دفعة كتابة واحدة داخل rAF لتجنب forced reflow
        requestAnimationFrame(function () {
            rows.forEach(function (row) {
                const qs  = row.dataset.questionStatus;
                const hi  = row.dataset.hasIssue === '1';
                const af  = row.dataset.allFound === '1';
                const ham = row.dataset.hasAnswerMissing === '1';
                let show;
                switch (filter) {
                    case 'has_issues':       show = hi;               break;
                    case 'question_missing': show = qs === 'missing'; break;
                    case 'question_no_path': show = qs === 'no_path'; break;
                    case 'answer_missing':   show = ham;              break;
                    case 'all_ok':           show = af;               break;
                    default:                 show = true;
                }
                if (show) {
                    row.classList.remove('d-none');
                    visible++;
                } else {
                    row.classList.add('d-none');
                }
            });
            updateFooter(visible);
        });
    }

    // ─── تبويبات الفلترة ──────────────────────────────────
    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            activeFilter = this.dataset.filter;
            applyDisplayFilterAll();
        });
    });

    // ─── أزرار البحث وإعادة التعيين ─────────────────────
    document.getElementById('btnSearch').addEventListener('click', restartScan);
    document.getElementById('btnReset').addEventListener('click', function () {
        document.getElementById('searchInput').value      = '';
        document.getElementById('filterTypeSelect').value = '';
        document.getElementById('categoryFilter').value   = '';
        restartScan();
    });
    document.getElementById('searchInput').addEventListener('keydown', e => {
        if (e.key === 'Enter') restartScan();
    });
    document.getElementById('categoryFilter').addEventListener('change', restartScan);

    document.getElementById('btnSanitizeNames').addEventListener('click', async function () {
        const categoryId = document.getElementById('categoryFilter').value;
        const confirmMsg = categoryId 
            ? 'هل أنت متأكد من رغبتك في تصحيح وضبط أسماء صور الأسئلة والإجابات للفئة المحددة فقط؟'
            : 'هل أنت متأكد من رغبتك في تصحيح وضبط أسماء صور الأسئلة والإجابات لجميع الفئات؟';
        
        if (!confirm(confirmMsg)) return;

        const btn = this;
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> جاري الضبط...';

        try {
            const response = await fetch(ROUTE_SANITIZE, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ category_id: categoryId })
            });

            const data = await response.json();
            btn.innerHTML = originalText;
            btn.disabled = false;

            if (data.success) {
                if (typeof toastr !== 'undefined') {
                    toastr.success(data.message);
                } else {
                    alert(data.message);
                }
                restartScan();
            } else {
                alert('فشلت العملية: ' + (data.message || 'خطأ غير معروف'));
            }
        } catch (error) {
            btn.innerHTML = originalText;
            btn.disabled = false;
            console.error(error);
            alert('حدث خطأ أثناء الاتصال بالسيرفر.');
        }
    });

    function restartScan() {
        // إخفاء الجدول وإعادة التهيئة
        tableCard.style.display  = 'none';
        filterTabs.style.display = 'none';
        tbody.innerHTML  = '';
        htmlBuffer       = '';
        currentPage      = 0;
        lastPage         = null;
        isLoading        = false;
        scanDone         = false;
        allLoaded.style.display  = 'none';
        scanProgress.style.width = '0%';
        const spinnerIconReset = document.getElementById('scanSpinnerIcon');
        if (spinnerIconReset) spinnerIconReset.style.display = '';
        scanStatusText.innerHTML = 'جاري فحص الملفات... يرجى الانتظار';
        scanLegend.style.display = 'none';

        const cardBestQ = document.getElementById('card-best-q-cat');
        const cardBestA = document.getElementById('card-best-a-cat');
        if (cardBestQ) cardBestQ.style.display = 'none';
        if (cardBestA) cardBestA.style.display = 'none';
        const cardWorstQ = document.getElementById('card-worst-q-cat');
        const cardWorstA = document.getElementById('card-worst-a-cat');
        if (cardWorstQ) cardWorstQ.style.display = 'none';
        if (cardWorstA) cardWorstA.style.display = 'none';

        // إعادة تفعيل الـ animations للفحص الجديد
        document.body.classList.remove('scan-complete');
        document.body.classList.add('scanning');

        // إعادة تعيين الإحصائيات
        Object.keys(cStats).forEach(k => cStats[k] = 0);
        ['val-q-found','val-q-missing','val-q-nopath','val-a-found','val-a-issue'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.innerHTML = '<span class="stat-skeleton"></span>';
        });

        activeFilter = 'question_missing';
        document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
        const missingTabReset = document.querySelector('.filter-tab[data-filter="question_missing"]');
        if (missingTabReset) missingTabReset.classList.add('active');

        fetchLoop();
    }

    // ─── تعطيل hover effects أثناء التمرير لتحسين الأداء ────
    let scrollTimer = null;
    let ticking = false;
    window.addEventListener('scroll', function () {
        if (!ticking) {
            requestAnimationFrame(function () {
                document.body.classList.add('is-scrolling');
                ticking = false;
            });
            ticking = true;
        }
        clearTimeout(scrollTimer);
        scrollTimer = setTimeout(function () {
            document.body.classList.remove('is-scrolling');
        }, 200);
    }, { passive: true });

    function updateBestCategories() {
        const rows = tbody.querySelectorAll('.verify-row');
        const catStats = {};

        rows.forEach(row => {
            const catName = row.dataset.categoryName || '—';
            if (catName === '—') return;

            if (!catStats[catName]) {
                catStats[catName] = {
                    qOk: 0,
                    qBad: 0,
                    aOk: 0,
                    aBad: 0
                };
            }

            const qType = row.dataset.questionType;
            const qStatus = row.dataset.questionStatus;
            if (qType === 'image') {
                if (qStatus === 'found') {
                    catStats[catName].qOk++;
                } else if (qStatus === 'missing' || qStatus === 'no_path') {
                    catStats[catName].qBad++;
                }
            }

            const aOk = parseInt(row.dataset.ansOkCount || '0', 10);
            const aBad = parseInt(row.dataset.ansBadCount || '0', 10);
            catStats[catName].aOk += aOk;
            catStats[catName].aBad += aBad;
        });

        // 1. Best Question Category
        let bestQCat = null;
        let bestQScore = { bad: Infinity, ok: -1 };
        
        Object.keys(catStats).forEach(name => {
            const stats = catStats[name];
            if (stats.qOk > 0 || stats.qBad > 0) {
                if (stats.qBad < bestQScore.bad || (stats.qBad === bestQScore.bad && stats.qOk > bestQScore.ok)) {
                    bestQScore = { bad: stats.qBad, ok: stats.qOk };
                    bestQCat = name;
                }
            }
        });

        // 2. Best Answer Category
        let bestACat = null;
        let bestAScore = { bad: Infinity, ok: -1 };
        
        Object.keys(catStats).forEach(name => {
            const stats = catStats[name];
            if (stats.aOk > 0 || stats.aBad > 0) {
                if (stats.aBad < bestAScore.bad || (stats.aBad === bestAScore.bad && stats.aOk > bestAScore.ok)) {
                    bestAScore = { bad: stats.aBad, ok: stats.aOk };
                    bestACat = name;
                }
            }
        });

        // 3. Worst Question Category (most bad questions, then fewest ok questions)
        let worstQCat = null;
        let worstQScore = { bad: -1, ok: Infinity };
        
        Object.keys(catStats).forEach(name => {
            const stats = catStats[name];
            if (stats.qBad > 0) {
                if (stats.qBad > worstQScore.bad || (stats.qBad === worstQScore.bad && stats.qOk < worstQScore.ok)) {
                    worstQScore = { bad: stats.qBad, ok: stats.qOk };
                    worstQCat = name;
                }
            }
        });

        // 4. Worst Answer Category (most bad answers, then fewest ok answers)
        let worstACat = null;
        let worstAScore = { bad: -1, ok: Infinity };
        
        Object.keys(catStats).forEach(name => {
            const stats = catStats[name];
            if (stats.aBad > 0) {
                if (stats.aBad > worstAScore.bad || (stats.aBad === worstAScore.bad && stats.aOk < worstAScore.ok)) {
                    worstAScore = { bad: stats.aBad, ok: stats.aOk };
                    worstACat = name;
                }
            }
        });

        // Show/hide best question card
        const cardBestQ = document.getElementById('card-best-q-cat');
        if (bestQCat) {
            const stats = catStats[bestQCat];
            const valEl = document.getElementById('val-best-q-cat');
            const lblEl = document.getElementById('label-best-q-cat');
            if (valEl) {
                valEl.textContent = bestQCat;
                valEl.title = bestQCat;
            }
            if (lblEl) {
                lblEl.innerHTML = `أكثر فئة سليمة (صور الأسئلة)<br><small style="font-size:0.75rem;opacity:0.9;">سليمة: ${stats.qOk} | مفقودة: ${stats.qBad}</small>`;
            }
            if (cardBestQ) cardBestQ.style.display = 'block';
        } else {
            if (cardBestQ) cardBestQ.style.display = 'none';
        }

        // Show/hide best answer card
        const cardBestA = document.getElementById('card-best-a-cat');
        if (bestACat) {
            const stats = catStats[bestACat];
            const valEl = document.getElementById('val-best-a-cat');
            const lblEl = document.getElementById('label-best-a-cat');
            if (valEl) {
                valEl.textContent = bestACat;
                valEl.title = bestACat;
            }
            if (lblEl) {
                lblEl.innerHTML = `أكثر فئة سليمة (صور الإجابات)<br><small style="font-size:0.75rem;opacity:0.9;">سليمة: ${stats.aOk} | مفقودة: ${stats.aBad}</small>`;
            }
            if (cardBestA) cardBestA.style.display = 'block';
        } else {
            if (cardBestA) cardBestA.style.display = 'none';
        }

        // Show/hide worst question card
        const cardWorstQ = document.getElementById('card-worst-q-cat');
        if (worstQCat) {
            const stats = catStats[worstQCat];
            const valEl = document.getElementById('val-worst-q-cat');
            const lblEl = document.getElementById('label-worst-q-cat');
            if (valEl) {
                valEl.textContent = worstQCat;
                valEl.title = worstQCat;
            }
            if (lblEl) {
                lblEl.innerHTML = `أكثر فئة بها مشاكل (صور الأسئلة)<br><small style="font-size:0.75rem;opacity:0.9;">مفقودة: ${stats.qBad} | سليمة: ${stats.qOk}</small>`;
            }
            if (cardWorstQ) cardWorstQ.style.display = 'block';
        } else {
            if (cardWorstQ) cardWorstQ.style.display = 'none';
        }

        // Show/hide worst answer card
        const cardWorstA = document.getElementById('card-worst-a-cat');
        if (worstACat) {
            const stats = catStats[worstACat];
            const valEl = document.getElementById('val-worst-a-cat');
            const lblEl = document.getElementById('label-worst-a-cat');
            if (valEl) {
                valEl.textContent = worstACat;
                valEl.title = worstACat;
            }
            if (lblEl) {
                lblEl.innerHTML = `أكثر فئة بها مشاكل (صور الإجابات)<br><small style="font-size:0.75rem;opacity:0.9;">مفقودة: ${stats.aBad} | سليمة: ${stats.aOk}</small>`;
            }
            if (cardWorstA) cardWorstA.style.display = 'block';
        } else {
            if (cardWorstA) cardWorstA.style.display = 'none';
        }
    }

    // ─── بدء التشغيل ──────────────────────────────────────
    init();

})();

// ─── معاينة الصور (global) ─────────────────────────────
function showImagePreview(src, title) {
    document.getElementById('imagePreviewSrc').src           = src;
    document.getElementById('imagePreviewTitle').textContent = title || 'معاينة الصورة';
    new bootstrap.Modal(document.getElementById('imagePreviewModal')).show();
}
</script>

@endsection
