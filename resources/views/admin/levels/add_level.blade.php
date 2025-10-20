@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">إضافة مستوى جديد</div>
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

                            <form method="post" action="{{ route('store.level') }}">
                                @csrf

                                <!-- Level Name (AR) -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">اسم المستوى</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" />
                                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Level Name (EN) -->
                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">Level Name</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" dir="ltr" name="name_en" class="form-control" value="{{ old('name_en') }}" />
                                        @error('name_en') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Description (AR) -->
                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">الوصف</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="description" class="form-control" value="{{ old('description') }}" />
                                        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Description (EN) -->
                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">Description</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" dir="ltr" name="description_en" class="form-control" value="{{ old('description_en') }}" />
                                        @error('description_en') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Coins Number -->
                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">عدد العملات</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="number" name="coins_number" class="form-control" value="{{ old('coins_number') }}" />
                                        @error('coins_number') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Game Coin -->
                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">عملة اللعبة</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <select name="game_coin_id" class="form-select">
                                            <option value="">-- اختر عملة --</option>
                                            @foreach($gameCoins as $coin)
                                                <option value="{{ $coin->id }}" {{ old('game_coin_id') == $coin->id ? 'selected' : '' }}>
                                                    {{ $coin->name }} ({{ $coin->name_en }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('game_coin_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Submit -->
                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="submit" class="btn btn-primary px-4" value="إضافة مستوى" />
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
