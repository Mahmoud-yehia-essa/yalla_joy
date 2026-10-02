@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">اضافة نوع جديدة</div>
    </div>
    <!--end breadcrumb-->

    <div class="container">
        <div class="main-body">
            <div class="row">
                <div class="col-lg-10">
                    <div class="card">
                        <div class="card-body">
                            <!-- Display Validation Errors -->
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="post" action="{{ route('add.game.type.store') }}" enctype="multipart/form-data">
                                @csrf

                                <!-- Category Name -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">نوع اللعبة</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="game_type_name" class="form-control" value="{{ old('game_type_name') }}" />
                                        @error('game_type_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>


                                   <div class="row mb-3">
                                    <div class="col-sm-3">
                                <h6 class="mb-0" >Game Type</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" dir="ltr" name="game_type_name_en" class="form-control" value="{{ old('game_type_name_en') }}" />
                                        @error('game_type_name_en')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Category Description -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">الوصف</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="game_type_description" class="form-control" value="{{ old('game_type_description') }}" />
                                        @error('game_type_description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                        <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Description</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" dir="ltr" name="game_type_description_en" class="form-control" value="{{ old('game_type_description_en') }}" />
                                        @error('game_type_description_en')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Category Photo -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">الصورة</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="file" name="game_type_photo" class="form-control" id="image" />
                                        @error('game_type_photo')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Image Preview -->
                                <div class="row mb-3">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <img id="showImage" src="{{ url('upload/no_image.jpg') }}" alt="Preview" style="width:100px; height: 100px;">
                                    </div>
                                </div>


                                    <!-- category is_kids -->
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">هل هذا النوع خاص بالأطفال؟</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <div class="form-check form-switch">
                                                <input type="hidden" name="is_kids" value="0">
                                                <input class="form-check-input" type="checkbox" name="is_kids" value="1" id="isKidsSwitch" style="transform: scale(1.5); margin-right: 10px;">
                                                <label class="form-check-label" for="isKidsSwitch" style="margin-right: 10px;">نعم / لا</label>
                                            </div>
                                            @error('is_kids') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <!-- category is_term -->
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">هل يوجد ترم اخر ؟</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <div class="form-check form-switch">
                                                <input type="hidden" name="is_term" value="0">
                                                <input class="form-check-input" type="checkbox" name="is_term" value="1" id="isTermSwitch" style="transform: scale(1.5); margin-right: 10px;">
                                                <label class="form-check-label" for="isTermSwitch" style="margin-right: 10px;">نعم / لا</label>
                                            </div>
                                            @error('is_term') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <!-- Coins Requirement Section Header -->
                                    <div class="row mb-3 mt-4">
                                        <div class="col-sm-12">
                                            <div class="p-3 rounded bg-light border-start border-primary border-4 shadow-sm">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="bx bx-coin-stack text-warning fs-3"></i>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold text-dark">العملات المطلوبة للعب حسب أماكن وأنماط اللعب</h6>
                                                        <small class="text-muted">حدد نوع العملة وعدد العملات المطلوب خصمها عند بدء اللعب من كل نمط/مصدر في التطبيق</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-4">
                                        <!-- 1. لعبة الجلسة -->
                                        <div class="col-md-6">
                                            <div class="card border border-primary-subtle shadow-none h-100 mb-0" style="background-color: #f8faff;">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                                                        <span class="badge bg-primary rounded-circle p-2"><i class="bx bx-group fs-6"></i></span>
                                                        <h6 class="mb-0 fw-bold text-primary">1. لعبة الجلسة (أوفلاين)</h6>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label class="form-label small fw-bold">نوع العملة</label>
                                                        <select name="offline_game_coin_id" class="form-select form-select-sm">
                                                            <option value="">-- مجاناً (بدون عملات) --</option>
                                                            @foreach($gameCoins as $coin)
                                                                <option value="{{ $coin->id }}" {{ old('offline_game_coin_id') == $coin->id ? 'selected' : '' }}>
                                                                    {{ $coin->name }} ({{ $coin->name_en }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="form-label small fw-bold">عدد العملات المطلوبة</label>
                                                        <input type="number" min="0" name="offline_coins_number" class="form-control form-control-sm" value="{{ old('offline_coins_number', 0) }}" placeholder="0" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 2. لعبة الميدان - البحث عن لاعبين -->
                                        <div class="col-md-6">
                                            <div class="card border border-info-subtle shadow-none h-100 mb-0" style="background-color: #f6fbff;">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                                                        <span class="badge bg-info text-white rounded-circle p-2"><i class="bx bx-search-alt fs-6"></i></span>
                                                        <h6 class="mb-0 fw-bold text-info">2. لعبة الميدان (البحث عن لاعبين)</h6>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label class="form-label small fw-bold">نوع العملة</label>
                                                        <select name="online_search_game_coin_id" class="form-select form-select-sm">
                                                            <option value="">-- مجاناً (بدون عملات) --</option>
                                                            @foreach($gameCoins as $coin)
                                                                <option value="{{ $coin->id }}" {{ old('online_search_game_coin_id') == $coin->id ? 'selected' : '' }}>
                                                                    {{ $coin->name }} ({{ $coin->name_en }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="form-label small fw-bold">عدد العملات المطلوبة</label>
                                                        <input type="number" min="0" name="online_search_coins_number" class="form-control form-control-sm" value="{{ old('online_search_coins_number', 0) }}" placeholder="0" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 3. لعبة الميدان - إنشاء لعبة كاملة (6 فئات) -->
                                        <div class="col-md-6">
                                            <div class="card border border-success-subtle shadow-none h-100 mb-0" style="background-color: #f6fbf8;">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                                                        <span class="badge bg-success rounded-circle p-2"><i class="bx bx-grid-alt fs-6"></i></span>
                                                        <h6 class="mb-0 fw-bold text-success">3. لعبة الميدان (إنشاء لعبة كاملة - 6 فئات)</h6>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label class="form-label small fw-bold">نوع العملة</label>
                                                        <select name="online_create_game_coin_id" class="form-select form-select-sm">
                                                            <option value="">-- مجاناً (بدون عملات) --</option>
                                                            @foreach($gameCoins as $coin)
                                                                <option value="{{ $coin->id }}" {{ old('online_create_game_coin_id') == $coin->id ? 'selected' : '' }}>
                                                                    {{ $coin->name }} ({{ $coin->name_en }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="form-label small fw-bold">عدد العملات المطلوبة</label>
                                                        <input type="number" min="0" name="online_create_coins_number" class="form-control form-control-sm" value="{{ old('online_create_coins_number', 0) }}" placeholder="0" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 4. لعبة الميدان - تحدي مع صديق (3 فئات) -->
                                        <div class="col-md-6">
                                            <div class="card border border-warning-subtle shadow-none h-100 mb-0" style="background-color: #fffdf6;">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                                                        <span class="badge bg-warning text-dark rounded-circle p-2"><i class="bx bx-user-plus fs-6"></i></span>
                                                        <h6 class="mb-0 fw-bold text-warning text-dark">4. لعبة الميدان (تحدي مع صديق - 3 فئات)</h6>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label class="form-label small fw-bold">نوع العملة</label>
                                                        <select name="online_challenge_game_coin_id" class="form-select form-select-sm">
                                                            <option value="">-- مجاناً (بدون عملات) --</option>
                                                            @foreach($gameCoins as $coin)
                                                                <option value="{{ $coin->id }}" {{ old('online_challenge_game_coin_id') == $coin->id ? 'selected' : '' }}>
                                                                    {{ $coin->name }} ({{ $coin->name_en }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="form-label small fw-bold">عدد العملات المطلوبة</label>
                                                        <input type="number" min="0" name="online_challenge_coins_number" class="form-control form-control-sm" value="{{ old('online_challenge_coins_number', 0) }}" placeholder="0" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 5. التحديات - نافس الحاصلين على أعلى الدرجات والبحث عن منافس -->
                                        <div class="col-md-12">
                                            <div class="card border border-danger-subtle shadow-none mb-0" style="background-color: #fff9f9;">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                                                        <span class="badge bg-danger rounded-circle p-2"><i class="bx bx-trophy fs-6"></i></span>
                                                        <h6 class="mb-0 fw-bold text-danger">5. التحديات (نافس الحاصلين على أعلى الدرجات والبحث عن منافس)</h6>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-2">
                                                            <label class="form-label small fw-bold">نوع العملة</label>
                                                            <select name="top_scorers_game_coin_id" class="form-select form-select-sm">
                                                                <option value="">-- مجاناً (بدون عملات) --</option>
                                                                @foreach($gameCoins as $coin)
                                                                    <option value="{{ $coin->id }}" {{ old('top_scorers_game_coin_id') == $coin->id ? 'selected' : '' }}>
                                                                        {{ $coin->name }} ({{ $coin->name_en }})
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-bold">عدد العملات المطلوبة</label>
                                                            <input type="number" min="0" name="top_scorers_coins_number" class="form-control form-control-sm" value="{{ old('top_scorers_coins_number', 0) }}" placeholder="0" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <!-- Submit Button -->
                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="submit" class="btn btn-primary px-4" value="اضافة نوع لعبة " />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- jQuery for Image Preview -->
                    <script type="text/javascript">
                        $(document).ready(function(){
                            $('#image').change(function(e){
                                var reader = new FileReader();
                                reader.onload = function(e){
                                    $('#showImage').attr('src', e.target.result);
                                }
                                reader.readAsDataURL(e.target.files[0]);
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
