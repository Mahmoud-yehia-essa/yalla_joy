@extends('admin.master_admin')
@section('admin')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">تفاصيل جلسة الميدان: {{ $game->game_session_name }}</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('report.online.games') }}"><i class="bx bx-left-arrow-alt"></i> عودة لتقرير التدقيق</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $game->game_session_name }}</li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->

<!-- Game Overview Card -->
<div class="card mb-3">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-white">معلومات الجلسة</h5>
        <span class="badge {{ $questions->count() > 36 ? 'bg-danger fs-6' : 'bg-success fs-6' }}">
            {{ $questions->count() }} سؤال مسجل في الجلسة (المعدل الطبيعي 36)
        </span>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <strong class="text-secondary">كود الجلسة:</strong>
                <p class="fs-5 text-primary fw-bold mb-0">{{ $game->game_session_name }}</p>
            </div>
            <div class="col-md-3">
                <strong class="text-secondary">نوع اللعبة:</strong>
                <p class="mb-0">{{ $game->game_online_type ?? '1v1 الميدان' }}</p>
            </div>
            <div class="col-md-3">
                <strong class="text-secondary">حالة الجلسة:</strong>
                <p class="mb-0">
                    <span class="badge bg-info">{{ $game->game_online_state }}</span>
                </p>
            </div>
            <div class="col-md-3">
                <strong class="text-secondary">تاريخ الإنشاء:</strong>
                <p class="mb-0">{{ $game->created_at ? $game->created_at->format('Y-m-d H:i:s') : '-' }}</p>
            </div>
        </div>

        <hr/>

        <div class="row g-3">
            <div class="col-md-6">
                <h6 class="fw-bold text-primary"><i class='bx bx-user-pin'></i> منشئ الغرفة (Host)</h6>
                @if($game->user)
                    <div><strong>الاسم:</strong> {{ $game->user->f_name }} {{ $game->user->l_name }} (ID: {{ $game->user->id }})</div>
                    <div><strong>البريد:</strong> {{ $game->user->email }} | <strong>الهاتف:</strong> {{ $game->user->phone }}</div>
                @else
                    <span class="text-muted">ID: {{ $game->created_user_id }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <h6 class="fw-bold text-success"><i class='bx bx-group'></i> اللاعبون المسجلون بالجلسة ({{ $players->count() }})</h6>
                <ul class="list-group list-group-flush">
                    @forelse($players as $p)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1">
                            <div>
                                @if($p->user)
                                    <strong>{{ $p->user->f_name }} {{ $p->user->l_name }}</strong> (ID: {{ $p->user_id }})
                                @else
                                    ID: {{ $p->user_id }}
                                @endif
                            </div>
                            <span class="badge {{ $p->role == 'admin' ? 'bg-primary' : 'bg-success' }}">{{ $p->role }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted px-0">لا يوجد لاعبون مسجلون في الجدول.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Selected Categories Card -->
<div class="card mb-3">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><i class='bx bx-category'></i> الفئات المختارة للجلسة ({{ $categories->count() }})</h6>
        <small class="text-muted">الحد الأقصى الطبيعي: 6 فئات (3 فئات لكل لاعب)</small>
    </div>
    <div class="card-body">
        <div class="row row-cols-2 row-cols-md-4 row-cols-lg-6 g-3">
            @forelse($categories as $catItem)
                <div class="col">
                    <div class="border rounded p-2 text-center h-100 bg-light">
                        @if($catItem->category && $catItem->category->category_photo)
                            <img src="{{ asset('upload/category_images/'.$catItem->category->category_photo) }}" alt="" class="rounded mb-2" style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                            <div class="rounded bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 50px; height: 50px;">
                                <i class='bx bx-image'></i>
                            </div>
                        @endif
                        <div class="fw-bold fs-6">{{ $catItem->category->category_name ?? 'فئة ID: '.$catItem->category_id }}</div>
                        <small class="text-muted">ID: {{ $catItem->category_id }}</small>
                    </div>
                </div>
            @empty
                <div class="col-12 text-muted">لا توجد فئات مسجلة لهذه الجلسة.</div>
            @endforelse
        </div>
    </div>
</div>

<!-- Questions Table Card -->
<div class="card">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><i class='bx bx-help-circle'></i> قائمة الأسئلة المسجلة في الجلسة ({{ $questions->count() }})</h6>
        @if($questions->count() > 36)
            <span class="badge bg-danger">⚠️ تنبيه: تم توليد {{ $questions->count() }} سؤالاً في هذه الجلسة وتجاوز الحد 36!</span>
        @else
            <span class="badge bg-success">عدد سليم ({{ $questions->count() }} سؤال)</span>
        @endif
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>الفئة</th>
                        <th>نص السؤال</th>
                        <th>النقاط</th>
                        <th>نوع السؤال</th>
                        <th>الترتيب في الفئة</th>
                        <th>تاريخ الإرفاق بالجلسة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($questions as $idx => $q)
                    <tr class="{{ $idx >= 36 ? 'table-warning' : '' }}">
                        <td><strong>{{ $idx + 1 }}</strong></td>
                        <td>
                            <span class="badge bg-info text-dark">{{ $q->category_name }}</span>
                        </td>
                        <td class="text-end" style="max-width: 350px;">
                            <div class="fw-bold">{{ $q->qu_title ?? $q->qu_hint }}</div>
                            @if($q->qu_hint)
                                <small class="text-muted d-block">{{ $q->qu_hint }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $q->qu_points ?? 0 }} نقطة</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $q->questions_type ?? 'text' }}</span>
                        </td>
                        <td>{{ $q->question_order ?? 0 }}</td>
                        <td><small>{{ $q->question_attached_at ?? '-' }}</small></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            لا توجد أسئلة مسجلة لهذه الجلسة.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
