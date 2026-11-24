@extends('admin.master_admin')
@section('admin')

<style>
    /* ==========================================================
       التحقق المتقدم من فيديوهات الأسئلة والإجابات - Premium Styles
    ========================================================== */
    :root {
        --vid-found:       #10b981;
        --vid-missing:     #ef4444;
        --vid-corrupted:   #9333ea;
        --vid-no-path:     #f59e0b;
        --vid-primary:     #6366f1;
        --grad-hero:       linear-gradient(135deg, #1e1b4b 0%, #3730a3 48%, #7c3aed 100%);
        --grad-success:    linear-gradient(135deg, #10b981 0%, #059669 100%);
        --grad-danger:     linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        --grad-warning:    linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        --grad-info:       linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        --grad-violet:     linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
        --grad-rose:       linear-gradient(135deg, #ec4899 0%, #be185d 100%);
        --grad-teal:       linear-gradient(135deg, #14b8a6 0%, #0f766e 100%);
        --grad-slate:      linear-gradient(135deg, #334155 0%, #0f172a 100%);
        --card-shadow:     0 4px 24px rgba(15, 23, 42, 0.07);
        --card-shadow-hov: 0 10px 36px rgba(15, 23, 42, 0.14);
    }

    /* Hero Banner */
    .vid-hero {
        background: var(--grad-hero);
        border-radius: 18px;
        padding: 28px 32px;
        margin-bottom: 24px;
        color: #fff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 12px 32px rgba(55, 48, 163, 0.28);
    }
    .vid-hero::before {
        content: '';
        position: absolute;
        top: -60px; left: -60px;
        width: 240px; height: 240px;
        background: radial-gradient(circle, rgba(255,255,255,0.14) 0%, transparent 70%);
        border-radius: 50%;
    }
    .vid-hero::after {
        content: '';
        position: absolute;
        bottom: -80px; right: 15%;
        width: 280px; height: 280px;
        background: radial-gradient(circle, rgba(236,72,153,0.18) 0%, transparent 70%);
        border-radius: 50%;
    }
    .vid-hero-content { position: relative; z-index: 2; }
    .vid-hero h1 { font-size: 1.65rem; font-weight: 800; margin: 0; letter-spacing: -0.2px; }
    .vid-hero p  { margin: 8px 0 0; opacity: 0.88; font-size: 0.92rem; max-width: 680px; }

    /* Health Pill inside Hero */
    .hero-health-box {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 14px;
        padding: 14px 20px;
        min-width: 210px;
        text-align: center;
    }
    .hero-health-score {
        font-size: 2rem;
        font-weight: 900;
        line-height: 1.1;
    }
    .hero-health-label {
        font-size: 0.76rem;
        opacity: 0.85;
        margin-top: 2px;
    }
    .hero-size-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin-top: 8px;
        padding: 4px 12px;
        border-radius: 20px;
        background: rgba(15, 23, 42, 0.35);
        font-size: 0.76rem;
        font-weight: 600;
    }

    /* Stats Cards Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(195px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    .stat-card {
        border-radius: 14px;
        padding: 20px 18px;
        color: #fff;
        position: relative;
        overflow: hidden;
        transition: transform .25s ease, box-shadow .25s ease;
    }
    .stat-card:hover { transform: translateY(-4px); box-shadow: var(--card-shadow-hov); }
    .stat-card::after {
        content: '';
        position: absolute;
        bottom: -26px; left: -18px;
        width: 105px; height: 105px;
        background: rgba(255,255,255,0.09);
        border-radius: 50%;
    }
    .stat-card .stat-icon  { font-size: 2rem; opacity: .92; margin-bottom: 8px; }
    .stat-card .stat-value { font-size: 2.15rem; font-weight: 800; line-height: 1.05; margin-bottom: 4px; }
    .stat-card .stat-label { font-size: 0.81rem; opacity: .9; font-weight: 600; }
    .stat-card .stat-sub   { font-size: 0.72rem; opacity: .82; margin-top: 4px; }

    .stat-card.stat-total   { background: var(--grad-slate); }
    .stat-card.stat-violet  { background: var(--grad-violet); }
    .stat-card.stat-success { background: var(--grad-success); }
    .stat-card.stat-danger  { background: var(--grad-danger); }
    .stat-card.stat-warning { background: var(--grad-warning); }
    .stat-card.stat-info    { background: var(--grad-info); }
    .stat-card.stat-teal    { background: var(--grad-teal); }
    .stat-card.stat-rose    { background: var(--grad-rose); }

    .stat-skeleton {
        display: inline-block;
        width: 54px; height: 32px;
        background: rgba(255,255,255,0.25);
        border-radius: 8px;
        animation: shimmer 1.3s infinite;
    }
    @keyframes shimmer { 0%,100%{opacity:.55} 50%{opacity:1} }

    /* Progress Bar Box */
    #scanProgressWrap {
        background: #fff;
        border-radius: 14px;
        padding: 18px 22px;
        margin-bottom: 20px;
        box-shadow: var(--card-shadow);
        border: 1px solid rgba(99, 102, 241, 0.15);
    }
    #scanProgressTrack {
        height: 10px; border-radius: 10px;
        background: #e2e8f0;
        overflow: hidden;
    }
    #scanProgressBar {
        height: 10px; border-radius: 10px;
        background: linear-gradient(90deg, #6366f1, #8b5cf6, #ec4899);
        transition: width .35s ease;
    }

    /* Search & Filter Panel */
    .search-filter-card {
        background: #fff;
        border-radius: 14px;
        padding: 20px 24px;
        margin-bottom: 20px;
        box-shadow: var(--card-shadow);
        border: 1px solid rgba(15, 23, 42, 0.05);
    }
    .search-filter-card .form-control,
    .search-filter-card .form-select {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        font-size: .9rem;
        padding: 10px 14px;
        transition: border-color .2s, box-shadow .2s;
    }
    .search-filter-card .form-control:focus,
    .search-filter-card .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,.14);
    }

    .btn-vid-primary {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        border: none; border-radius: 10px;
        color: #fff; padding: 10px 20px;
        font-weight: 700; font-size: .88rem;
        transition: opacity .2s, transform .2s;
    }
    .btn-vid-primary:hover { opacity: .92; transform: translateY(-1px); color: #fff; }

    .btn-vid-sanitize {
        background: linear-gradient(135deg, #10b981, #059669);
        border: none; border-radius: 10px;
        color: #fff; padding: 10px 18px;
        font-weight: 700; font-size: .88rem;
        transition: opacity .2s, transform .2s;
    }
    .btn-vid-sanitize:hover { opacity: .92; transform: translateY(-1px); color: #fff; }

    .btn-vid-export {
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        border: none; border-radius: 10px;
        color: #fff; padding: 10px 18px;
        font-weight: 700; font-size: .88rem;
        transition: opacity .2s, transform .2s;
    }
    .btn-vid-export:hover { opacity: .92; transform: translateY(-1px); color: #fff; }

    .btn-vid-reset {
        background: #f1f5f9;
        border: 1.5px solid #e2e8f0; border-radius: 10px;
        color: #475569; padding: 10px 18px;
        font-weight: 600; font-size: .88rem;
    }
    .btn-vid-reset:hover { background: #e2e8f0; color: #1e293b; }

    /* Filter Tabs */
    .filter-tabs { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 18px; }
    .filter-tab {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 15px;
        border-radius: 24px;
        border: 1.5px solid #e2e8f0;
        background: #fff; color: #475569;
        font-size: .82rem; font-weight: 700;
        cursor: pointer; text-decoration: none;
        transition: all .2s;
    }
    .filter-tab:hover { border-color: #6366f1; color: #4f46e5; text-decoration: none; }
    .filter-tab.active { background: #4f46e5; border-color: #4f46e5; color: #fff; text-decoration: none; }
    .filter-tab.tab-issues.active    { background: #dc2626; border-color: #dc2626; }
    .filter-tab.tab-missing.active   { background: #ef4444; border-color: #ef4444; }
    .filter-tab.tab-nopath.active    { background: #f59e0b; border-color: #f59e0b; }
    .filter-tab.tab-corrupted.active { background: #9333ea; border-color: #9333ea; }
    .filter-tab.tab-warnings.active  { background: #ea580c; border-color: #ea580c; }
    .filter-tab.tab-found.active     { background: #10b981; border-color: #10b981; }
    .filter-tab .tab-pill {
        padding: 2px 8px; border-radius: 12px; font-size: .72rem;
        background: rgba(15, 23, 42, 0.08); color: inherit;
    }
    .filter-tab.active .tab-pill { background: rgba(255, 255, 255, 0.25); color: #fff; }

    /* Main Table Card */
    .verify-table-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
        border: 1px solid rgba(15, 23, 42, 0.06);
    }
    .verify-table-header {
        background: linear-gradient(90deg, #f8fafc, #f1f5f9);
        padding: 16px 24px;
        border-bottom: 2px solid #e2e8f0;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 10px;
    }
    .verify-table-header h5 { margin: 0; font-weight: 800; color: #0f172a; font-size: 1.02rem; }

    .table-verify { margin: 0; font-size: .86rem; }
    .table-verify thead th {
        background: #0f172a; color: #e2e8f0;
        font-weight: 700; font-size: .78rem;
        padding: 14px 12px; border: none; white-space: nowrap;
    }
    .table-verify tbody tr {
        border-bottom: 1px solid #f1f5f9;
        content-visibility: auto;
        contain-intrinsic-size: 0 96px;
    }
    .table-verify tbody tr:hover { background: #f8faff; }
    .table-verify tbody td { padding: 14px 12px; vertical-align: middle; border: none; }
    body.is-scrolling .table-verify tbody tr:hover { background: transparent; }
    #verifyTbody { overflow-anchor: none; }

    /* Video Play Tiles */
    .video-play-tile {
        width: 62px; height: 52px; flex-shrink: 0;
        border-radius: 10px; border: none;
        background: linear-gradient(135deg, #1e1b4b, #4f46e5);
        color: #fff;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        cursor: pointer; transition: transform .2s, box-shadow .2s;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
    }
    .video-play-tile:hover { transform: scale(1.06); box-shadow: 0 6px 18px rgba(79, 70, 229, 0.4); }
    .video-play-tile i { font-size: 1.55rem; line-height: 1; }
    .video-play-tile span { font-size: 0.6rem; font-weight: 800; letter-spacing: .5px; opacity: .9; }

    .video-status-tile {
        width: 62px; height: 52px; flex-shrink: 0;
        border-radius: 10px;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
    }
    .video-status-tile i { font-size: 1.4rem; line-height: 1; }
    .video-status-tile span { font-size: 0.62rem; font-weight: 800; margin-top: 2px; }
    .tile-missing   { background: #fef2f2; border: 1.5px dashed #ef4444; color: #dc2626; }
    .tile-corrupted { background: #faf5ff; border: 1.5px dashed #9333ea; color: #7e22ce; }

    .btn-play-inline {
        display: inline-flex; align-items: center; gap: 3px;
        padding: 2px 9px; border-radius: 14px; border: none;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: #fff; font-size: 0.69rem; font-weight: 700;
        cursor: pointer; transition: opacity .2s, transform .2s;
    }
    .btn-play-inline:hover { opacity: .9; transform: translateY(-1px); }

    .video-path-code {
        font-size: 0.7rem; color: #334155;
        background: #f1f5f9; padding: 2px 6px; border-radius: 6px;
        word-break: break-all; max-width: 195px;
        display: inline-block; direction: ltr; text-align: left;
    }
    .btn-micro-icon {
        display: inline-flex; align-items: center; justify-content: center;
        width: 24px; height: 24px; border-radius: 6px;
        border: 1px solid #e2e8f0; background: #fff; color: #475569;
        font-size: 0.78rem; cursor: pointer; text-decoration: none;
        transition: all .15s;
    }
    .btn-micro-icon:hover { background: #4f46e5; color: #fff; border-color: #4f46e5; }

    /* Metadata Pills */
    .meta-pill {
        display: inline-flex; align-items: center; gap: 3px;
        padding: 2px 7px; border-radius: 10px;
        font-size: 0.67rem; font-weight: 700;
    }
    .pill-size { background: #ecfeff; color: #0e7490; border: 1px solid #cffafe; }
    .pill-warn { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
    .pill-info { background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe; }

    /* Status Badges */
    .status-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 5px 11px; border-radius: 20px;
        font-size: .76rem; font-weight: 700; white-space: nowrap;
    }
    .status-badge.found          { background: rgba(16,185,129,.12); color: #065f46; border: 1px solid rgba(16,185,129,.3); }
    .status-badge.missing        { background: rgba(239,68,68,.12);  color: #991b1b; border: 1px solid rgba(239,68,68,.3); }
    .status-badge.corrupted      { background: rgba(147,51,234,.12); color: #6b21a8; border: 1px solid rgba(147,51,234,.3); }
    .status-badge.no-path        { background: rgba(245,158,11,.12); color: #92400e; border: 1px solid rgba(245,158,11,.3); }
    .status-badge.not-applicable { background: rgba(100,116,139,.1); color: #475569; border: 1px solid rgba(100,116,139,.2); }

    /* Type & Scope Badges */
    .type-badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 12px; font-size: .75rem; font-weight: 700; }
    .type-badge.video { background: #fce7f3; color: #9d174d; }
    .type-badge.image { background: #ede9fe; color: #5b21b6; }
    .type-badge.text  { background: #e0f2fe; color: #0369a1; }
    .type-badge.sound { background: #fef3c7; color: #92400e; }

    .scope-tag {
        display: inline-block; padding: 2px 7px; border-radius: 6px;
        font-size: 0.65rem; font-weight: 800; white-space: nowrap; flex-shrink: 0;
    }
    .scope-local  { background: #dbeafe; color: #1e40af; }
    .scope-online { background: #f3e8ff; color: #6b21a8; }

    /* Answers list */
    .answers-list { list-style: none; padding: 0; margin: 0; }
    .answer-item  { display: flex; align-items: flex-start; gap: 8px; padding: 5px 0; border-bottom: 1px dashed #e2e8f0; font-size: .8rem; }
    .answer-item:last-child { border-bottom: none; }

    .verify-q-title { font-size: 0.87rem; line-height: 1.45; transition: color .2s; }
    a:hover .verify-q-title { color: #4f46e5; }

    .legend-item { display: inline-flex; align-items: center; gap: 5px; font-size: .8rem; color: #475569; }
    .legend-dot  { width: 10px; height: 10px; border-radius: 50%; }

    /* Empty State */
    .empty-state-box {
        text-align: center;
        padding: 56px 24px;
        color: #64748b;
    }
    .empty-state-box .empty-icon {
        width: 76px; height: 76px; border-radius: 50%;
        background: #eef2ff; color: #4f46e5;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 2.3rem; margin-bottom: 14px;
    }

    /* Video Preview Modal */
    #videoPreviewModal .modal-content {
        border-radius: 18px; border: none; overflow: hidden;
        box-shadow: 0 25px 65px rgba(15, 23, 42, 0.45);
    }
    #videoPreviewModal .modal-header {
        background: #0f172a; color: #fff; border-bottom: 1px solid rgba(255,255,255,.1);
        padding: 16px 22px;
    }
    #videoPreviewModal .btn-close { filter: invert(1); }
    .video-stage {
        background: #020617;
        display: flex; align-items: center; justify-content: center;
        min-height: 280px; max-height: 62vh;
    }
    #videoPreviewPlayer {
        width: 100%; max-height: 62vh;
        outline: none; background: #000;
    }
    .video-modal-meta {
        background: #f8fafc; padding: 16px 22px;
        border-top: 1px solid #e2e8f0;
    }

    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .vid-hero { padding: 20px; }
        .vid-hero h1 { font-size: 1.25rem; }
    }
</style>

{{-- Breadcrumb --}}
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">التحقق من فيديوهات الأسئلة والإجابات</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('all.question') }}">الأسئلة</a></li>
                <li class="breadcrumb-item active">التحقق من الفيديوهات</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto d-flex gap-2">
        <a href="{{ route('verify.question.images') }}" class="btn btn-outline-primary btn-sm rounded-pill">
            <i class="bx bx-image-check me-1"></i> التحقق من الصور
        </a>
        <a href="{{ route('all.question') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
            <i class="bx bx-arrow-back me-1"></i> عودة للأسئلة
        </a>
    </div>
</div>

{{-- Hero Banner --}}
<div class="vid-hero">
    <div class="vid-hero-content d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div style="width:62px;height:62px;border-radius:16px;background:rgba(255,255,255,0.14);display:flex;align-items:center;justify-content:center;font-size:2rem;">
                <i class="bx bx-movie-play"></i>
            </div>
            <div>
                <h1>التحقق من فيديوهات الأسئلة والإجابات</h1>
                <p>فحص ذكي ومتكامل لمسارات ملفات الفيديو في قاعدة البيانات ومطابقتها مع الخادم (الأسئلة + الإجابات المحلية) مع تحليل الحجم والصيغة والترميز</p>
            </div>
        </div>
        <div class="hero-health-box">
            <div class="hero-health-score" id="heroHealthScore">—</div>
            <div class="hero-health-label">نسبة سلامة الفيديوهات</div>
            <div class="hero-size-pill">
                <i class="bx bx-hdd"></i>
                <span>المساحة: <strong id="heroTotalSize">—</strong></span>
            </div>
        </div>
    </div>
</div>

{{-- Stats Cards --}}
<div class="stats-grid">
    <div class="stat-card stat-total">
        <div class="stat-icon"><i class="bx bx-list-ul"></i></div>
        <div class="stat-value">{{ number_format($stats['total_questions']) }}</div>
        <div class="stat-label">إجمالي الأسئلة</div>
        <div class="stat-sub">المرتبطة بالفيديو: {{ number_format($stats['video_related_questions']) }}</div>
    </div>

    <div class="stat-card stat-violet">
        <div class="stat-icon"><i class="bx bx-video"></i></div>
        <div class="stat-value">{{ number_format($stats['video_questions']) }}</div>
        <div class="stat-label">أسئلة من نوع فيديو</div>
        <div class="stat-sub">upload/questions/videos/</div>
    </div>

    <div class="stat-card stat-success">
        <div class="stat-icon"><i class="bx bx-check-circle"></i></div>
        <div class="stat-value" id="val-q-found"><span class="stat-skeleton"></span></div>
        <div class="stat-label">فيديوهات أسئلة سليمة ✓</div>
    </div>

    <div class="stat-card stat-danger">
        <div class="stat-icon"><i class="bx bx-x-circle"></i></div>
        <div class="stat-value" id="val-q-missing"><span class="stat-skeleton"></span></div>
        <div class="stat-label">فيديوهات أسئلة مفقودة / تالفة ✗</div>
        <div class="stat-sub" id="sub-q-corrupted">تالفة (0B): —</div>
    </div>

    <div class="stat-card stat-warning">
        <div class="stat-icon"><i class="bx bx-error-circle"></i></div>
        <div class="stat-value" id="val-q-nopath"><span class="stat-skeleton"></span></div>
        <div class="stat-label">أسئلة فيديو بلا مسار</div>
    </div>

    <div class="stat-card stat-teal">
        <div class="stat-icon"><i class="bx bx-film"></i></div>
        <div class="stat-value">{{ number_format($stats['total_answer_videos']) }}</div>
        <div class="stat-label">إجابات من نوع فيديو</div>
        <div class="stat-sub">upload/answers/videos/</div>
    </div>

    <div class="stat-card stat-success">
        <div class="stat-icon"><i class="bx bx-check-double"></i></div>
        <div class="stat-value" id="val-a-found"><span class="stat-skeleton"></span></div>
        <div class="stat-label">فيديوهات إجابات سليمة ✓</div>
    </div>

    <div class="stat-card stat-rose">
        <div class="stat-icon"><i class="bx bx-error"></i></div>
        <div class="stat-value" id="val-a-issue"><span class="stat-skeleton"></span></div>
        <div class="stat-label">فيديوهات إجابات بها مشاكل ✗</div>
        <div class="stat-sub" id="sub-a-issue-split">مفقودة: — | تالفة: — | بلا مسار: —</div>
    </div>

    {{-- بطاقات تحليل الفئات الديناميكية --}}
    <div class="stat-card stat-violet" id="card-best-q-cat" style="display:none;">
        <div class="stat-icon"><i class="bx bx-crown"></i></div>
        <div class="stat-value" id="val-best-q-cat" style="font-size:1.2rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">—</div>
        <div class="stat-label" id="label-best-q-cat">أكثر فئة سليمة (فيديوهات الأسئلة)</div>
    </div>

    <div class="stat-card stat-danger" id="card-worst-q-cat" style="display:none;">
        <div class="stat-icon"><i class="bx bx-error-alt"></i></div>
        <div class="stat-value" id="val-worst-q-cat" style="font-size:1.2rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">—</div>
        <div class="stat-label" id="label-worst-q-cat">أكثر فئة بها مشاكل (فيديوهات الأسئلة)</div>
    </div>
</div>

{{-- Scan Progress --}}
<div id="scanProgressWrap">
    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
        <span class="fw-700 text-dark" style="font-size:.9rem;">
            <i class="bx bx-loader-alt bx-spin me-2 text-primary" id="scanSpinnerIcon"></i>
            <span id="scanStatusText">جاري فحص ملفات الفيديو على الخادم... يرجى الانتظار</span>
        </span>
        <span class="badge bg-light text-dark border" style="font-size:.8rem;" id="scanCounter">0 / 0</span>
    </div>
    <div id="scanProgressTrack">
        <div id="scanProgressBar" style="width:0%;"></div>
    </div>
    <div class="mt-2 d-flex gap-3 align-items-center flex-wrap" id="scanLegend" style="display:none!important;">
        <span class="legend-item"><span class="legend-dot" style="background:#10b981;"></span> موجود وسليم: <strong id="leg-found">0</strong></span>
        <span class="legend-item"><span class="legend-dot" style="background:#ef4444;"></span> مفقود: <strong id="leg-missing">0</strong></span>
        <span class="legend-item"><span class="legend-dot" style="background:#9333ea;"></span> تالف (0B): <strong id="leg-corrupted">0</strong></span>
        <span class="legend-item"><span class="legend-dot" style="background:#f59e0b;"></span> بلا مسار: <strong id="leg-nopath">0</strong></span>
    </div>
</div>

{{-- Search, Scope & Action Controls --}}
<div class="search-filter-card">
    <div class="row g-3 align-items-end mb-3">
        <div class="col-md-6">
            <label class="form-label fw-700 text-dark mb-1" style="font-size:.84rem;">
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
        <div class="col-md-6">
            <label class="form-label fw-700 text-dark mb-1" style="font-size:.84rem;">
                <i class="bx bx-radar me-1 text-primary"></i> نطاق الفحص
            </label>
            <select id="scopeFilter" class="form-select">
                <option value="video_only" {{ ($scope ?? 'video_only') === 'video_only' ? 'selected' : '' }}>
                    الأسئلة المرتبطة بالفيديو فقط (فحص فائق السرعة ⚡)
                </option>
                <option value="all" {{ ($scope ?? '') === 'all' ? 'selected' : '' }}>
                    جميع الأسئلة في النظام (فحص شامل)
                </option>
            </select>
        </div>
    </div>

    <div class="row g-3 align-items-end">
        <div class="col-lg-5">
            <label class="form-label fw-700 text-dark mb-1" style="font-size:.84rem;">
                <i class="bx bx-search me-1 text-primary"></i> بحث متقدم (رقم السؤال ID / العنوان / اسم ملف الفيديو)
            </label>
            <input type="text" id="searchInput" class="form-control"
                   placeholder="مثال: 15544 أو عنوان السؤال أو video.mp4..."
                   value="{{ $search ?? '' }}">
        </div>
        <div class="col-lg-7">
            <div class="d-flex gap-2 flex-wrap justify-content-lg-end">
                <button type="button" class="btn btn-vid-primary" id="btnSearch">
                    <i class="bx bx-search-alt me-1"></i> بحث وفحص
                </button>
                <button type="button" class="btn btn-vid-reset" id="btnReset">
                    <i class="bx bx-refresh me-1"></i> إعادة تعيين
                </button>
                <button type="button" class="btn btn-vid-sanitize" id="btnSanitizeVideos">
                    <i class="bx bx-magic-wand me-1"></i> ضبط أسماء الفيديوهات
                </button>
                <button type="button" class="btn btn-vid-export" id="btnExportCsv">
                    <i class="bx bx-download me-1"></i> تصدير تقرير Excel
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Filter Tabs --}}
<div class="filter-tabs" id="filterTabs" style="display:none;">
    <a href="javascript:;" class="filter-tab tab-issues" data-filter="has_issues">
        <i class="bx bx-error-alt"></i> بها مشاكل (الكل)
        <span class="tab-pill" id="tab-count-issues">0</span>
    </a>
    <a href="javascript:;" class="filter-tab tab-missing" data-filter="question_missing">
        <i class="bx bx-x-circle"></i> فيديوهات أسئلة مفقودة
        <span class="tab-pill" id="tab-count-q-missing">0</span>
    </a>
    <a href="javascript:;" class="filter-tab tab-nopath" data-filter="question_no_path">
        <i class="bx bx-error-circle"></i> بلا مسار فيديو
        <span class="tab-pill" id="tab-count-q-nopath">0</span>
    </a>
    <a href="javascript:;" class="filter-tab tab-corrupted" data-filter="corrupted">
        <i class="bx bx-bug"></i> ملفات تالفة (0B)
        <span class="tab-pill" id="tab-count-corrupted">0</span>
    </a>
    <a href="javascript:;" class="filter-tab tab-missing" data-filter="answer_missing">
        <i class="bx bx-film"></i> مشاكل فيديوهات الإجابات
        <span class="tab-pill" id="tab-count-a-missing">0</span>
    </a>
    <a href="javascript:;" class="filter-tab tab-found" data-filter="all_ok">
        <i class="bx bx-check-circle"></i> سليمة تماماً
        <span class="tab-pill" id="tab-count-ok">0</span>
    </a>
    <a href="javascript:;" class="filter-tab" data-filter="all">
        <i class="bx bx-grid-alt"></i> الكل
        <span class="tab-pill" id="tab-count-all">0</span>
    </a>
</div>

{{-- Main Table Card --}}
<div class="verify-table-card" id="verifyTableCard" style="display:none;">
    <div class="verify-table-header">
        <h5><i class="bx bx-table me-2 text-primary"></i>تقرير التحقق من فيديوهات الأسئلة والإجابات</h5>
        <div class="d-flex gap-3 align-items-center flex-wrap">
            <span class="legend-item"><span class="legend-dot" style="background:#10b981;"></span>موجود وسليم</span>
            <span class="legend-item"><span class="legend-dot" style="background:#ef4444;"></span>مفقود</span>
            <span class="legend-item"><span class="legend-dot" style="background:#9333ea;"></span>تالف (0B)</span>
            <span class="legend-item"><span class="legend-dot" style="background:#f59e0b;"></span>بلا مسار</span>
            <span class="badge bg-dark rounded-pill px-3" id="visibleCountBadge">0 صف</span>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-verify" id="verifyTable">
            <thead>
                <tr>
                    <th style="width:72px;"># / ID</th>
                    <th style="width:220px;">السؤال</th>
                    <th style="width:120px;">النوع والفئة</th>
                    <th style="width:280px;">
                        فيديو السؤال والمسار
                        <small class="d-block" style="font-size:.66rem; opacity:.75;">upload/questions/videos/</small>
                    </th>
                    <th style="width:125px;">حالة فيديو السؤال</th>
                    <th>
                        الإجابات وحالة فيديوهاتها
                        <small class="d-block" style="font-size:.66rem; opacity:.75;">upload/answers/videos/</small>
                    </th>
                    <th style="width:90px;" class="text-center">إجراء</th>
                </tr>
            </thead>
            <tbody id="verifyTbody"></tbody>
        </table>
    </div>

    {{-- Empty State (عند عدم وجود صفوف تطابق التبويب أو الفحص) --}}
    <div id="emptyStateBox" class="empty-state-box" style="display:none;">
        <div class="empty-icon"><i class="bx bx-video"></i></div>
        <h5 class="fw-800 text-dark mb-2" id="emptyStateTitle">لا توجد نتائج مطابقة</h5>
        <p class="mb-3" style="font-size:0.9rem; max-width:480px; margin:0 auto;" id="emptyStateDesc">
            لم يتم العثور على فيديوهات تطابق الفلتر المحدد حالياً.
        </p>
        <div class="d-flex gap-2 justify-content-center flex-wrap">
            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" id="btnShowAllRows">
                <i class="bx bx-list-ul me-1"></i> عرض جميع النتائج
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" id="btnSwitchFullScope">
                <i class="bx bx-radar me-1"></i> التبديل للفحص الشامل لكل الأسئلة
            </button>
        </div>
    </div>

    {{-- Table Footer --}}
    <div class="d-flex justify-content-between align-items-center px-4 py-3 flex-wrap gap-2"
         style="background:#f8fafc; border-top:1px solid #e2e8f0;">
        <small class="text-muted">
            <i class="bx bx-info-circle me-1"></i>
            الصفوف المعروضة حالياً: <strong id="footerVisibleCount">0</strong>
        </small>
        <div class="d-flex gap-3 flex-wrap">
            <span class="legend-item">
                <span class="legend-dot" style="background:#10b981;"></span>
                فيديوهات سليمة: <strong id="footer-found">0</strong>
            </span>
            <span class="legend-item">
                <span class="legend-dot" style="background:#ef4444;"></span>
                مفقودة / تالفة: <strong id="footer-missing">0</strong>
            </span>
            <span class="legend-item">
                <span class="legend-dot" style="background:#6366f1;"></span>
                إجمالي الحجم: <strong id="footer-size">0 B</strong>
            </span>
        </div>
    </div>
</div>

{{-- Professional HTML5 Video Preview Modal --}}
<div class="modal fade" id="videoPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h6 class="modal-title fw-800 mb-0" id="videoPreviewTitle">معاينة الفيديو</h6>
                    <small class="text-light opacity-75" id="videoPreviewScope">—</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="video-stage position-relative">
                    <video id="videoPreviewPlayer" controls playsinline preload="metadata"></video>
                    <div id="videoPlayError" class="text-center text-white p-4" style="display:none;">
                        <i class="bx bx-error-circle text-danger" style="font-size:3rem;"></i>
                        <h6 class="mt-2 fw-700 text-white">تعذّر تشغيل الفيديو داخل المتصفح</h6>
                        <p class="small text-light opacity-75 mb-0">
                            قد يكون ترميز الفيديو (Codec) غير مدعوم في المتصفح أو الملف تالف، يمكنك تحميله أو فتحه مباشرة.
                        </p>
                    </div>
                </div>
                <div class="video-modal-meta">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="badge bg-secondary" id="videoMetaExt">MP4</span>
                            <span class="badge bg-info text-dark" id="videoMetaSize">—</span>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" id="btnModalCopyPath">
                                <i class="bx bx-copy me-1"></i> نسخ المسار
                            </button>
                            <a href="#" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill" id="btnModalOpenDirect">
                                <i class="bx bx-link-external me-1"></i> فتح مباشر
                            </a>
                            <a href="#" download class="btn btn-sm btn-outline-success rounded-pill" id="btnModalDownload">
                                <i class="bx bx-download me-1"></i> تحميل
                            </a>
                            <a href="#" target="_blank" class="btn btn-sm btn-primary rounded-pill" id="btnModalEditQuestion">
                                <i class="bx bx-edit me-1"></i> تعديل السؤال
                            </a>
                        </div>
                    </div>
                    <div class="small text-muted d-flex align-items-center gap-2 flex-wrap">
                        <strong>المسار في قاعدة البيانات:</strong>
                        <code id="videoMetaPath" class="text-dark fw-bold" style="direction:ltr;">—</code>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    // ─── Config ──────────────────────────────────────────────
    const ROUTE_AJAX     = "{{ route('verify.question.videos') }}";
    const ROUTE_SANITIZE = "{{ route('verify.question.videos.sanitize') }}";
    const ROUTE_EXPORT   = "{{ route('verify.question.videos.export') }}";
    const CSRF           = "{{ csrf_token() }}";

    // ─── State ───────────────────────────────────────────────
    let scanToken    = 0; // لمنع تداخل نتائج فحص قديم مع فحص جديد
    let currentPage  = 0;
    let lastPage     = null;
    let scanDone     = false;
    let activeFilter = 'has_issues';
    let htmlBuffer   = '';
    let totalScannedRows = 0;

    const cStats = {
        q_found: 0, q_missing: 0, q_no_path: 0, q_corrupted: 0,
        a_found: 0, a_missing: 0, a_no_path: 0, a_corrupted: 0,
        total_size_bytes: 0,
        needs_sanitize_count: 0,
        large_videos_count: 0,
        incompatible_format_count: 0,
    };

    // ─── Elements ────────────────────────────────────────────
    const tbody          = document.getElementById('verifyTbody');
    const tableCard      = document.getElementById('verifyTableCard');
    const filterTabs     = document.getElementById('filterTabs');
    const emptyStateBox  = document.getElementById('emptyStateBox');
    const scanProgress   = document.getElementById('scanProgressBar');
    const scanCounter    = document.getElementById('scanCounter');
    const scanStatusText = document.getElementById('scanStatusText');
    const scanLegend     = document.getElementById('scanLegend');

    // ─── Helpers ─────────────────────────────────────────────
    function formatBytes(bytes) {
        if (!bytes || bytes <= 0) return '0 B';
        const units = ['B', 'KB', 'MB', 'GB'];
        const i = Math.min(Math.floor(Math.log(bytes) / Math.log(1024)), units.length - 1);
        const val = bytes / Math.pow(1024, i);
        return (i >= 2 ? val.toFixed(2) : val.toFixed(1)) + ' ' + units[i];
    }

    function setText(id, val) {
        const el = document.getElementById(id);
        if (el) el.textContent = val;
    }

    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    function notify(msg, type = 'success') {
        if (typeof toastr !== 'undefined') {
            toastr[type] ? toastr[type](msg) : toastr.info(msg);
        } else {
            alert(msg);
        }
    }

    // ─── Scan Loop ───────────────────────────────────────────
    async function fetchLoop(myToken) {
        while (true) {
            if (myToken !== scanToken || scanDone) break;
            if (lastPage !== null && currentPage >= lastPage) {
                markScanDone(myToken);
                break;
            }

            currentPage++;
            const search     = document.getElementById('searchInput').value.trim();
            const categoryId = document.getElementById('categoryFilter').value;
            const scope      = document.getElementById('scopeFilter').value;

            try {
                const res = await fetch(ROUTE_AJAX + '?' + new URLSearchParams({
                    page: currentPage,
                    search: search,
                    category_id: categoryId,
                    scope: scope,
                }), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': CSRF,
                    }
                });

                if (myToken !== scanToken) break;
                if (!res.ok) throw new Error('HTTP ' + res.status);

                const data = await res.json();
                if (myToken !== scanToken) break;

                lastPage = data.last_page;
                htmlBuffer += data.html || '';
                totalScannedRows += data.rows_count || 0;

                // تجميع الإحصائيات
                const bs = data.batch_stats || {};
                Object.keys(cStats).forEach(k => {
                    cStats[k] += Number(bs[k] || 0);
                });

                // تحديث شريط التقدم
                const scanned = Math.min(currentPage * 50, data.total || 0);
                const pct     = data.total > 0 ? Math.round((scanned / data.total) * 100) : 100;
                scanProgress.style.width = pct + '%';
                scanCounter.textContent  = scanned + ' / ' + (data.total || 0);

                if (!data.next_page || (data.total || 0) === 0) {
                    markScanDone(myToken);
                    break;
                }

                await sleep(16);
            } catch (err) {
                if (myToken !== scanToken) break;
                currentPage--;
                console.error('Video scan batch error:', err);
                await sleep(1000);
            }
        }
    }

    // ─── Finish Scan & Render ────────────────────────────────
    function markScanDone(myToken) {
        if (myToken !== scanToken) return;
        scanDone = true;

        const tpl = document.createElement('template');
        tpl.innerHTML = htmlBuffer;
        tbody.innerHTML = '';
        tbody.appendChild(tpl.content);
        htmlBuffer = '';

        updateStatCards();
        const counts = updateTabCounts();
        updateCategoryInsights();

        // اختيار ذكي لأفضل تبويب افتراضي حتى لا يظهر جدول فارغ بلا داعي
        if (counts.issues > 0) {
            activeFilter = 'has_issues';
        } else {
            activeFilter = 'all';
        }
        setActiveTabUI(activeFilter);
        applyDisplayFilterAll();

        tableCard.style.display  = '';
        filterTabs.style.display = '';

        scanProgress.style.width = '100%';
        const spinnerIcon = document.getElementById('scanSpinnerIcon');
        if (spinnerIcon) spinnerIcon.style.display = 'none';
        scanStatusText.innerHTML = '<i class="bx bx-check-circle text-success me-1"></i> اكتمل فحص الفيديوهات بنجاح';
        scanLegend.removeAttribute('style');
        updateLegend();
    }

    function setActiveTabUI(filterKey) {
        document.querySelectorAll('.filter-tab').forEach(t => {
            t.classList.toggle('active', t.dataset.filter === filterKey);
        });
    }

    // ─── Update Stat Cards & Health Score ────────────────────
    function updateStatCards() {
        const aFoundTotal  = cStats.a_found;
        const aIssueTotal  = cStats.a_missing + cStats.a_no_path + cStats.a_corrupted;
        const qBrokenTotal = cStats.q_missing + cStats.q_corrupted;

        const totalAllVideos = cStats.q_found + cStats.q_missing + cStats.q_no_path + cStats.q_corrupted
                             + aFoundTotal + aIssueTotal;
        const totalFoundAll  = cStats.q_found + aFoundTotal;
        const healthPct      = totalAllVideos > 0 ? Math.round((totalFoundAll / totalAllVideos) * 100) : 100;

        setText('val-q-found',   cStats.q_found);
        setText('val-q-missing', qBrokenTotal);
        setText('sub-q-corrupted', `مفقودة: ${cStats.q_missing} | تالفة (0B): ${cStats.q_corrupted}`);
        setText('val-q-nopath',  cStats.q_no_path);

        setText('val-a-found', aFoundTotal);
        setText('val-a-issue', aIssueTotal);
        setText('sub-a-issue-split', `مفقودة: ${cStats.a_missing} | تالفة: ${cStats.a_corrupted} | بلا مسار: ${cStats.a_no_path}`);

        const formattedSize = formatBytes(cStats.total_size_bytes);
        setText('heroHealthScore', totalAllVideos > 0 ? (healthPct + '%') : '100%');
        setText('heroTotalSize', formattedSize);
        setText('footer-size', formattedSize);
    }

    function updateTabCounts() {
        const rows = tbody.querySelectorAll('.verify-row');
        let issues = 0, qMissing = 0, qNopath = 0, corrupted = 0, aMissing = 0, ok = 0;

        rows.forEach(r => {
            if (r.dataset.hasIssue === '1')          issues++;
            if (r.dataset.questionStatus === 'missing') qMissing++;
            if (r.dataset.questionStatus === 'no_path') qNopath++;
            if (r.dataset.hasCorrupted === '1')      corrupted++;
            if (r.dataset.hasAnswerMissing === '1')  aMissing++;
            if (r.dataset.allFound === '1')          ok++;
        });

        setText('tab-count-issues',    issues);
        setText('tab-count-q-missing', qMissing);
        setText('tab-count-q-nopath',  qNopath);
        setText('tab-count-corrupted', corrupted);
        setText('tab-count-a-missing', aMissing);
        setText('tab-count-ok',        ok);
        setText('tab-count-all',       rows.length);

        return { issues, qMissing, qNopath, corrupted, aMissing, ok, all: rows.length };
    }

    function updateLegend() {
        const totalFound   = cStats.q_found + cStats.a_found;
        const totalMissing = cStats.q_missing + cStats.a_missing;
        const totalCorrupt = cStats.q_corrupted + cStats.a_corrupted;
        const totalNoPath  = cStats.q_no_path + cStats.a_no_path;

        setText('leg-found',     totalFound);
        setText('leg-missing',   totalMissing);
        setText('leg-corrupted', totalCorrupt);
        setText('leg-nopath',    totalNoPath);

        setText('footer-found',   totalFound);
        setText('footer-missing', totalMissing + totalCorrupt);
    }

    // ─── Client-side Tab Filtering ───────────────────────────
    function applyDisplayFilterAll() {
        const rows   = tbody.querySelectorAll('.verify-row');
        const filter = activeFilter;
        let visible  = 0;

        requestAnimationFrame(function () {
            rows.forEach(function (row) {
                const qs  = row.dataset.questionStatus;
                const hi  = row.dataset.hasIssue === '1';
                const af  = row.dataset.allFound === '1';
                const ham = row.dataset.hasAnswerMissing === '1';
                const hc  = row.dataset.hasCorrupted === '1';

                let show;
                switch (filter) {
                    case 'has_issues':       show = hi; break;
                    case 'question_missing': show = qs === 'missing'; break;
                    case 'question_no_path': show = qs === 'no_path'; break;
                    case 'corrupted':        show = hc; break;
                    case 'answer_missing':   show = ham; break;
                    case 'all_ok':           show = af; break;
                    default:                 show = true;
                }

                if (show) {
                    row.classList.remove('d-none');
                    visible++;
                } else {
                    row.classList.add('d-none');
                }
            });

            setText('footerVisibleCount', visible);
            setText('visibleCountBadge', visible + ' صف');

            // عرض رسالة empty state احترافية إذا كان العدد 0
            if (visible === 0) {
                emptyStateBox.style.display = '';
                const totalRows = rows.length;
                if (totalRows === 0) {
                    setText('emptyStateTitle', 'لا توجد أسئلة أو إجابات من نوع فيديو حالياً');
                    setText('emptyStateDesc', 'لم يتم العثور على أي أسئلة أو إجابات مسجلة من نوع فيديو ضمن معايير البحث الحالية. يمكنك إضافة فيديوهات للأسئلة أو التبديل للفحص الشامل.');
                } else {
                    setText('emptyStateTitle', 'لا توجد عناصر في هذا التبويب');
                    setText('emptyStateDesc', 'رائع! لا توجد عناصر مطابقة لهذا التصنيف ضمن النتائج المفحوصة.');
                }
            } else {
                emptyStateBox.style.display = 'none';
            }
        });
    }

    // ─── Best / Worst Category Insights ──────────────────────
    function updateCategoryInsights() {
        const rows = tbody.querySelectorAll('.verify-row');
        const catStats = {};

        rows.forEach(row => {
            const cat = row.dataset.categoryName || '—';
            if (cat === '—') return;
            if (!catStats[cat]) catStats[cat] = { qOk: 0, qBad: 0 };

            if (row.dataset.questionType === 'video') {
                if (row.dataset.questionStatus === 'found') catStats[cat].qOk++;
                else if (['missing', 'no_path', 'corrupted'].includes(row.dataset.questionStatus)) catStats[cat].qBad++;
            }
        });

        let bestQ = null, bestScore = { bad: Infinity, ok: -1 };
        let worstQ = null, worstScore = { bad: -1, ok: Infinity };

        Object.keys(catStats).forEach(name => {
            const s = catStats[name];
            if (s.qOk > 0 || s.qBad > 0) {
                if (s.qBad < bestScore.bad || (s.qBad === bestScore.bad && s.qOk > bestScore.ok)) {
                    bestScore = { bad: s.qBad, ok: s.qOk };
                    bestQ = name;
                }
            }
            if (s.qBad > 0) {
                if (s.qBad > worstScore.bad || (s.qBad === worstScore.bad && s.qOk < worstScore.ok)) {
                    worstScore = { bad: s.qBad, ok: s.qOk };
                    worstQ = name;
                }
            }
        });

        const bestCard  = document.getElementById('card-best-q-cat');
        const worstCard = document.getElementById('card-worst-q-cat');

        if (bestQ && bestCard) {
            const s = catStats[bestQ];
            setText('val-best-q-cat', bestQ);
            document.getElementById('val-best-q-cat').title = bestQ;
            document.getElementById('label-best-q-cat').innerHTML = `أكثر فئة سليمة (فيديوهات الأسئلة)<br><small style="opacity:.88;">سليمة: ${s.qOk} | مشاكل: ${s.qBad}</small>`;
            bestCard.style.display = '';
        } else if (bestCard) {
            bestCard.style.display = 'none';
        }

        if (worstQ && worstCard) {
            const s = catStats[worstQ];
            setText('val-worst-q-cat', worstQ);
            document.getElementById('val-worst-q-cat').title = worstQ;
            document.getElementById('label-worst-q-cat').innerHTML = `أكثر فئة بها مشاكل (فيديوهات الأسئلة)<br><small style="opacity:.88;">مشاكل: ${s.qBad} | سليمة: ${s.qOk}</small>`;
            worstCard.style.display = '';
        } else if (worstCard) {
            worstCard.style.display = 'none';
        }
    }

    // ─── Restart Scan ────────────────────────────────────────
    function restartScan() {
        scanToken++;
        const myToken = scanToken;

        tableCard.style.display     = 'none';
        filterTabs.style.display    = 'none';
        emptyStateBox.style.display = 'none';
        tbody.innerHTML             = '';
        htmlBuffer                  = '';
        currentPage                 = 0;
        lastPage                    = null;
        scanDone                    = false;
        totalScannedRows            = 0;

        scanProgress.style.width = '0%';
        const spinnerIcon = document.getElementById('scanSpinnerIcon');
        if (spinnerIcon) spinnerIcon.style.display = '';
        scanStatusText.textContent = 'جاري فحص ملفات الفيديو على الخادم... يرجى الانتظار';
        scanLegend.style.display   = 'none';

        document.getElementById('card-best-q-cat').style.display  = 'none';
        document.getElementById('card-worst-q-cat').style.display = 'none';

        Object.keys(cStats).forEach(k => cStats[k] = 0);
        ['val-q-found','val-q-missing','val-q-nopath','val-a-found','val-a-issue'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.innerHTML = '<span class="stat-skeleton"></span>';
        });
        setText('heroHealthScore', '—');
        setText('heroTotalSize', '—');

        fetchLoop(myToken);
    }

    // ─── Event Listeners ─────────────────────────────────────
    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.addEventListener('click', function () {
            activeFilter = this.dataset.filter;
            setActiveTabUI(activeFilter);
            applyDisplayFilterAll();
        });
    });

    document.getElementById('btnSearch').addEventListener('click', restartScan);
    document.getElementById('categoryFilter').addEventListener('change', restartScan);
    document.getElementById('scopeFilter').addEventListener('change', restartScan);
    document.getElementById('searchInput').addEventListener('keydown', e => {
        if (e.key === 'Enter') restartScan();
    });

    document.getElementById('btnReset').addEventListener('click', function () {
        document.getElementById('searchInput').value    = '';
        document.getElementById('categoryFilter').value = '';
        document.getElementById('scopeFilter').value    = 'video_only';
        restartScan();
    });

    document.getElementById('btnShowAllRows').addEventListener('click', function () {
        activeFilter = 'all';
        setActiveTabUI('all');
        applyDisplayFilterAll();
    });

    document.getElementById('btnSwitchFullScope').addEventListener('click', function () {
        document.getElementById('scopeFilter').value = 'all';
        restartScan();
    });

    // تصدير تقرير Excel (CSV)
    document.getElementById('btnExportCsv').addEventListener('click', function () {
        const params = new URLSearchParams({
            search: document.getElementById('searchInput').value.trim(),
            category_id: document.getElementById('categoryFilter').value,
            filter: activeFilter,
        });
        window.location.href = ROUTE_EXPORT + '?' + params.toString();
    });

    // ضبط أسماء الفيديوهات
    document.getElementById('btnSanitizeVideos').addEventListener('click', async function () {
        const categoryId = document.getElementById('categoryFilter').value;
        const confirmMsg = categoryId
            ? 'هل أنت متأكد من ضبط وتصحيح أسماء ملفات الفيديو للفئة المحددة فقط؟ (سيتم إعادة تسمية الملفات الموجودة فعلياً وتحديث قاعدة البيانات تلقائياً)'
            : 'هل أنت متأكد من ضبط وتصحيح أسماء ملفات الفيديو لجميع الفئات؟ (الأسئلة + الإجابات المحلية)';

        if (!confirm(confirmMsg)) return;

        const btn = this;
        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> جاري ضبط الأسماء...';

        try {
            const res = await fetch(ROUTE_SANITIZE, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ category_id: categoryId }),
            });

            const data = await res.json();
            btn.innerHTML = originalHtml;
            btn.disabled = false;

            if (data.success) {
                notify(data.message, 'success');
                restartScan();
            } else {
                notify('فشلت العملية: ' + (data.message || 'خطأ غير معروف'), 'error');
            }
        } catch (err) {
            btn.innerHTML = originalHtml;
            btn.disabled = false;
            console.error(err);
            notify('حدث خطأ أثناء الاتصال بالخادم.', 'error');
        }
    });

    // ─── Event Delegation: تشغيل الفيديو ونسخ المسار ──────────
    const videoModalEl = document.getElementById('videoPreviewModal');
    const videoPlayer  = document.getElementById('videoPreviewPlayer');
    const videoErrBox  = document.getElementById('videoPlayError');
    let currentModalPath = '';

    videoPlayer.addEventListener('error', function () {
        if (videoPlayer.getAttribute('src')) {
            videoPlayer.style.display = 'none';
            videoErrBox.style.display = '';
        }
    });

    videoModalEl.addEventListener('hidden.bs.modal', function () {
        videoPlayer.pause();
        videoPlayer.removeAttribute('src');
        videoPlayer.load();
    });

    tbody.addEventListener('click', function (e) {
        const playBtn = e.target.closest('.btn-play-video');
        if (playBtn) {
            const src   = playBtn.dataset.videoSrc || '';
            const title = playBtn.dataset.videoTitle || 'معاينة الفيديو';
            const path  = playBtn.dataset.videoPath || '—';
            const size  = playBtn.dataset.videoSize || '—';
            const ext   = playBtn.dataset.videoExt || 'VIDEO';
            const scope = playBtn.dataset.videoScope || '';
            const edit  = playBtn.dataset.editUrl || '#';

            currentModalPath = path;
            setText('videoPreviewTitle', title);
            setText('videoPreviewScope', scope);
            setText('videoMetaPath', path);
            setText('videoMetaSize', size);
            setText('videoMetaExt', ext);

            document.getElementById('btnModalOpenDirect').href   = src;
            document.getElementById('btnModalDownload').href     = src;
            document.getElementById('btnModalEditQuestion').href = edit;

            videoErrBox.style.display = 'none';
            videoPlayer.style.display = '';
            videoPlayer.src = src;
            videoPlayer.load();

            new bootstrap.Modal(videoModalEl).show();
            return;
        }

        const copyBtn = e.target.closest('.btn-copy-path');
        if (copyBtn) {
            const text = copyBtn.dataset.copyText || '';
            copyToClipboard(text, copyBtn);
        }
    });

    document.getElementById('btnModalCopyPath').addEventListener('click', function () {
        copyToClipboard(currentModalPath, this);
    });

    function copyToClipboard(text, triggerEl) {
        if (!text) return;
        const done = () => {
            if (typeof toastr !== 'undefined') {
                toastr.info('تم نسخ المسار: ' + text);
            } else if (triggerEl) {
                const old = triggerEl.innerHTML;
                triggerEl.innerHTML = '<i class="bx bx-check text-success"></i>';
                setTimeout(() => { triggerEl.innerHTML = old; }, 1200);
            }
        };
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(done).catch(() => fallbackCopy(text, done));
        } else {
            fallbackCopy(text, done);
        }
    }

    function fallbackCopy(text, cb) {
        const ta = document.createElement('textarea');
        ta.value = text;
        ta.style.position = 'fixed';
        ta.style.opacity = '0';
        document.body.appendChild(ta);
        ta.select();
        try { document.execCommand('copy'); cb(); } catch (e) {}
        document.body.removeChild(ta);
    }

    // ─── تحسين أداء التمرير ───────────────────────────────────
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
        scrollTimer = setTimeout(() => document.body.classList.remove('is-scrolling'), 180);
    }, { passive: true });

    // ─── بدء الفحص التلقائي ──────────────────────────────────
    restartScan();
})();
</script>

@endsection
