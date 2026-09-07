@extends('admin.master_admin')
@section('admin')

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">تعديل قسم / وصف اللعبة</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('all.game.instructions') }}">وصف وتعريف الألعاب</a></li>
                    <li class="breadcrumb-item active" aria-current="page">تعديل القسم</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="container">
        <div class="main-body">
            <div class="row">
                <div class="col-lg-10">
                    <div class="card">
                        <div class="card-body p-4">
                            <!-- Display Validation Errors -->
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="post" action="{{ route('edit.game.instruction.store') }}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $instruction->id }}">

                                <!-- Game Target -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0 fw-bold">نوع اللعبة <span class="text-danger">*</span></h6>
                                    </div>
                                    <div class="col-sm-9">
                                        <select name="game_target" class="form-select">
                                            <option value="session" {{ old('game_target', $instruction->game_target) == 'session' ? 'selected' : '' }}>لعبة الجلسة (وضع اللعب المحلي)</option>
                                            <option value="field" {{ old('game_target', $instruction->game_target) == 'field' ? 'selected' : '' }}>لعبة الميدان (وضع اللعب أونلاين)</option>
                                        </select>
                                        @error('game_target') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Section Type -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0 fw-bold">نوع البطاقة <span class="text-danger">*</span></h6>
                                    </div>
                                    <div class="col-sm-9">
                                        <select name="section_type" class="form-select">
                                            <option value="section" {{ old('section_type', $instruction->section_type) == 'section' ? 'selected' : '' }}>قسم تفصيلي (بطاقة محتوى بأيقونة وبنود)</option>
                                            <option value="intro" {{ old('section_type', $instruction->section_type) == 'intro' ? 'selected' : '' }}>مقدمة رئيسية (البطاقة التمهيدية العلوية للعبة)</option>
                                        </select>
                                        @error('section_type') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Title (Arabic) -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0 fw-bold">عنوان القسم (عربي) <span class="text-danger">*</span></h6>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" name="title" class="form-control" value="{{ old('title', $instruction->title) }}" placeholder="مثال: آلية اللعب ونظام الأدوار" />
                                        @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Title (English) -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">عنوان القسم (إنجليزي)</h6>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" dir="ltr" name="title_en" class="form-control" value="{{ old('title_en', $instruction->title_en) }}" placeholder="e.g. Gameplay & Turns System" />
                                        @error('title_en') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Icon -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">الأيقونة (Icon)</h6>
                                    </div>
                                    <div class="col-sm-9">
                                        <select name="icon" class="form-select">
                                            <option value="settings" {{ old('icon', $instruction->icon) == 'settings' ? 'selected' : '' }}>⚙️ Settings (إعدادات / آلية اللعب)</option>
                                            <option value="timer" {{ old('icon', $instruction->icon) == 'timer' ? 'selected' : '' }}>⏱️ Timer (الوقت والمستويات)</option>
                                            <option value="analytics" {{ old('icon', $instruction->icon) == 'analytics' ? 'selected' : '' }}>📊 Analytics / Scoring (احتساب النقاط والنتائج)</option>
                                            <option value="sports_esports" {{ old('icon', $instruction->icon) == 'sports_esports' ? 'selected' : '' }}>🎮 Gamepad (طريقة وأسلوب اللعب)</option>
                                            <option value="people" {{ old('icon', $instruction->icon) == 'people' ? 'selected' : '' }}>👥 People (اللاعبين / طرق الدخول والمنافسة)</option>
                                            <option value="play_circle" {{ old('icon', $instruction->icon) == 'play_circle' ? 'selected' : '' }}>▶️ Play Circle (بداية وتحديد الفريق)</option>
                                            <option value="sort" {{ old('icon', $instruction->icon) == 'sort' ? 'selected' : '' }}>🔀 Sort (ترتيب وعرض الأسئلة)</option>
                                            <option value="emoji_events" {{ old('icon', $instruction->icon) == 'emoji_events' ? 'selected' : '' }}>🏆 Trophy (نهاية اللعبة والجوائز)</option>
                                            <option value="globe" {{ old('icon', $instruction->icon) == 'globe' ? 'selected' : '' }}>🌐 Globe (العالم / أونلاين)</option>
                                            <option value="info" {{ old('icon', $instruction->icon) == 'info' ? 'selected' : '' }}>ℹ️ Info (معلومات عامة)</option>
                                        </select>
                                        <small class="text-muted">اختر الأيقونة التي ستظهر بجانب عنوان البطاقة في التطبيق.</small>
                                        @error('icon') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Intro text (Optional) -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">مقدمة فرعية داخل البطاقة (اختياري)</h6>
                                    </div>
                                    <div class="col-sm-9">
                                        <textarea name="intro" rows="2" class="form-control" placeholder="مقدمة أو عبارة تمهيدية تظهر أعلى النقاط في البطاقة">{{ old('intro', $instruction->intro) }}</textarea>
                                        @error('intro') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Content / Bullets (Arabic) -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0 fw-bold">المحتوى والبنود (عربي) <span class="text-danger">*</span></h6>
                                    </div>
                                    <div class="col-sm-9">
                                        <textarea name="content" rows="6" class="form-control" placeholder="اكتب كل نقطة أو بند في سطر مستقل">{{ old('content', $instruction->content) }}</textarea>
                                        <small class="text-muted">💡 كل سطر جديد (Enter) سيتم عرضه كنقطة منفصلة (Bullet Point) تلقائياً داخل البطاقة.</small>
                                        @error('content') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Content (English) -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">المحتوى والبنود (إنجليزي)</h6>
                                    </div>
                                    <div class="col-sm-9">
                                        <textarea dir="ltr" name="content_en" rows="4" class="form-control" placeholder="English content (one bullet point per line)">{{ old('content_en', $instruction->content_en) }}</textarea>
                                        @error('content_en') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Order By -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">ترتيب الظهور</h6>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="number" name="order_by" class="form-control" value="{{ old('order_by', $instruction->order_by) }}" min="1" />
                                        @error('order_by') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9">
                                        <input type="submit" class="btn btn-primary px-4" value="حفظ التعديلات" />
                                        <a href="{{ route('all.game.instructions') }}" class="btn btn-secondary px-4 ms-2">إلغاء</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
