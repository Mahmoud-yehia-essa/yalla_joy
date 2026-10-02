@extends('admin.master_admin')
@section('admin')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">تقرير تدقيق ومراقبة مباريات الميدان (أونلاين)</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('report.view') }}"><i class="bx bx-home-alt"></i> الاحصائيات</a></li>
                <li class="breadcrumb-item active" aria-current="page">تدقيق جلسات الأونلاين</li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->

<!-- Statistics Cards -->
<div class="row row-cols-1 row-cols-md-3 row-cols-xl-3 mb-3">
    <div class="col">
        <div class="card radius-10 border-start border-0 border-4 border-info">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">إجمالي جلسات الأونلاين</p>
                        <h4 class="my-1 text-info">{{ number_format($totalOnlineGames) }}</h4>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-light-info text-info ms-auto">
                        <i class='bx bx-game'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card radius-10 border-start border-0 border-4 border-success">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">إجمالي الأسئلة المسجلة بالجلسات</p>
                        <h4 class="my-1 text-success">{{ number_format($totalOnlineQuestionsInDb) }}</h4>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-light-success text-success ms-auto">
                        <i class='bx bx-help-circle'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card radius-10 border-start border-0 border-4 border-danger">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">المعدل الطبيعي للجلسة</p>
                        <h4 class="my-1 text-danger">36 سؤالاً (6 فئات × 6)</h4>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-light-danger text-danger ms-auto">
                        <i class='bx bx-shield-quarter'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Box -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('report.online.games') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">بحث بكود الجلسة أو اسم/هاتف المستخدم:</label>
                    <input type="text" name="search" class="form-control" placeholder="كود الجلسة (مثال: R1234) أو اسم اللاعب" value="{{ $search }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">من تاريخ:</label>
                    <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">إلى تاريخ:</label>
                    <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                </div>
                <div class="col-md-2">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" name="anomaly_only" value="1" id="anomalyCheck" {{ $anomalyOnly == '1' ? 'checked' : '' }}>
                        <label class="form-check-label text-danger fw-bold" for="anomalyCheck">
                            ⚠️ الجلسات المتجاوزة فقط
                        </label>
                    </div>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class='bx bx-search'></i> تصفية
                    </button>
                    <a href="{{ route('report.online.games') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Games Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>كود الجلسة</th>
                        <th>منشئ الغرفة (Host)</th>
                        <th>المنافس (Opponent)</th>
                        <th>عدد الفئات</th>
                        <th>عدد الأسئلة</th>
                        <th>حالة الجلسة</th>
                        <th>حالة التدقيق</th>
                        <th>تاريخ الإنشاء</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allGames as $key => $game)
                    @php
                        $opponent = $game->onlineGameUsers->firstWhere('role', 'user');
                    @endphp
                    <tr class="{{ $game->is_anomaly ? 'table-danger' : '' }}">
                        <td>{{ $game->id }}</td>
                        <td>
                            <strong class="badge bg-primary text-wrap fs-6">{{ $game->game_session_name }}</strong>
                        </td>
                        <td>
                            @if($game->user)
                                <div class="fw-bold">{{ $game->user->f_name }} {{ $game->user->l_name }}</div>
                                <small class="text-muted">{{ $game->user->phone ?? $game->user->email }}</small>
                            @else
                                <span class="text-muted">مستخدم غير متوفر (ID: {{ $game->created_user_id }})</span>
                            @endif
                        </td>
                        <td>
                            @if($opponent && $opponent->user)
                                <div class="fw-bold">{{ $opponent->user->f_name }} {{ $opponent->user->l_name }}</div>
                                <small class="text-muted">{{ $opponent->user->phone ?? $opponent->user->email }}</small>
                            @elseif($opponent)
                                <span class="text-muted">ID: {{ $opponent->user_id }}</span>
                            @else
                                <span class="badge bg-secondary">بانتظار المنافس / بوت</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $game->categories_count > 6 ? 'bg-danger' : 'bg-info' }} fs-6">
                                {{ $game->categories_count }} فئات
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $game->questions_count > 36 ? 'bg-danger' : ($game->questions_count == 36 ? 'bg-success' : 'bg-warning text-dark') }} fs-6">
                                {{ $game->questions_count }} سؤال
                            </span>
                        </td>
                        <td>
                            @if($game->game_online_state == 'finished')
                                <span class="badge bg-success">مكتملة</span>
                            @elseif($game->game_online_state == 'waiting')
                                <span class="badge bg-warning text-dark">انتظار</span>
                            @elseif($game->game_online_state == 'matched')
                                <span class="badge bg-info">مطابقة</span>
                            @else
                                <span class="badge bg-secondary">{{ $game->game_online_state }}</span>
                            @endif
                        </td>
                        <td>
                            @if($game->is_anomaly)
                                <span class="badge bg-danger text-wrap p-2" data-bs-toggle="tooltip" title="تجاوز في عدد الأسئلة أو الفئات">
                                    ⚠️ تجاوز غير طبيعي ({{ $game->questions_count }} سؤال)
                                </span>
                            @else
                                <span class="badge bg-success">
                                    ✅ سليم (ضمن الحد 36)
                                </span>
                            @endif
                        </td>
                        <td>
                            <small>{{ $game->created_at ? $game->created_at->format('Y-m-d H:i') : '-' }}</small>
                        </td>
                        <td>
                            <a href="{{ route('report.online.game.details', $game->id) }}" class="btn btn-sm btn-outline-primary" title="عرض تفاصيل الأسئلة والفئات">
                                <i class='bx bx-show'></i> التفاصيل
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">
                            لا توجد جلسات مطابقة لمعايير البحث.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $allGames->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

@endsection
