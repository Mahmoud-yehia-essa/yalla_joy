@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">إضافة كوبون ألعاب جديد</div>
    </div>
    <!--end breadcrumb-->

    <div class="container">
        <div class="main-body">
            <div class="row">
                <div class="col-lg-10">
                    <div class="card">
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('store.game.coupon') }}">
                                @csrf

                                <!-- Coupon Code -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">كود الكوبون</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="code" class="form-control" placeholder="مثل: GAME50" value="{{ old('code') }}" required />
                                        @error('code') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Coupon Type -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">نوع الكوبون</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <select name="type" id="coupon_type" class="form-select" required>
                                            <option value="">اختر النوع</option>
                                            <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>نسبة مئوية من السعر (Percentage Discount)</option>
                                            <option value="free_games" {{ old('type') == 'free_games' ? 'selected' : '' }}>ألعاب مجانية مباشرة (Free Games Count)</option>
                                            <option value="package_bonus" {{ old('type') == 'package_bonus' ? 'selected' : '' }}>ألعاب إضافية مع باقة شراء (Package Bonus)</option>
                                        </select>
                                        @error('type') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Discount Percentage Field -->
                                <div class="row mb-3" id="discount_percentage_div" style="display: none;">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">نسبة الخصم (%)</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="number" step="0.01" name="discount_percentage" class="form-control" value="{{ old('discount_percentage') }}" min="0" max="100" />
                                        @error('discount_percentage') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Free Games Count Field -->
                                <div class="row mb-3" id="free_games_count_div" style="display: none;">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">عدد الألعاب المجانية / المكافأة</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="number" name="free_games_count" class="form-control" value="{{ old('free_games_count') }}" min="0" />
                                        @error('free_games_count') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Game Purchases Select Field -->
                                <div class="row mb-3" id="game_purchases_div" style="display: none;">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">الباقة المرتبطة</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <select name="game_purchases_id" class="form-select">
                                            <option value="">اختر باقة الشراء</option>
                                            @foreach($purchases as $p)
                                                <option value="{{ $p->id }}" {{ old('game_purchases_id') == $p->id ? 'selected' : '' }}>
                                                    {{ $p->games_count }} ألعاب بسعر {{ $p->price }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('game_purchases_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Usage Limit -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">الحد الأقصى للاستخدام (اختياري)</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="number" name="usage_limit" class="form-control" placeholder="اتركه فارغًا للاستخدام اللانهائي" value="{{ old('usage_limit') }}" min="1" />
                                        @error('usage_limit') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Expires At -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">تاريخ انتهاء الصلاحية (اختياري)</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at') }}" />
                                        @error('expires_at') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Submit -->
                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="submit" class="btn btn-primary px-4" value="إضافة الكوبون" />
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

<script type="text/javascript">
    $(document).ready(function() {
        function toggleFields() {
            var selectedType = $('#coupon_type').val();
            if (selectedType === 'percentage') {
                $('#discount_percentage_div').show().find('input').prop('required', true);
                $('#free_games_count_div').hide().find('input').prop('required', false);
                $('#game_purchases_div').hide().find('select').prop('required', false);
            } else if (selectedType === 'free_games') {
                $('#discount_percentage_div').hide().find('input').prop('required', false);
                $('#free_games_count_div').show().find('input').prop('required', true);
                $('#game_purchases_div').hide().find('select').prop('required', false);
            } else if (selectedType === 'package_bonus') {
                $('#discount_percentage_div').hide().find('input').prop('required', false);
                $('#free_games_count_div').show().find('input').prop('required', true);
                $('#game_purchases_div').show().find('select').prop('required', true);
            } else {
                $('#discount_percentage_div').hide().find('input').prop('required', false);
                $('#free_games_count_div').hide().find('input').prop('required', false);
                $('#game_purchases_div').hide().find('select').prop('required', false);
            }
        }

        // Run on page load
        toggleFields();

        // Run on change
        $('#coupon_type').change(function() {
            toggleFields();
        });
    });
</script>
@endsection
