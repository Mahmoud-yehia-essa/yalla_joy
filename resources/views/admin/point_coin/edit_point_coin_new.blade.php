@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">تعديل العملات مع النقاط</div>
    </div>
    <!--end breadcrumb-->

    <div class="container">
        <div class="main-body">
            <div class="row">
                <div class="col-lg-10">
                    <div class="card">
                        <div class="card-body">

                            <!-- Validation Errors -->
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- <form method="POST" action="{{ route('update.game.item') }}" enctype="multipart/form-data"> --}}
                                <form method="POST" action="{{ route('update.point.coin', $pointWithCoin->id) }}" enctype="multipart/form-data">

                                @csrf
                                <input type="hidden" name="id" value="{{ $pointWithCoin->id }}">

                                <!-- Item Type -->
                                  <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">نوع اللعبة</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <select name="type" class="form-select">
                                            <option value="">اختر نوع اللعبة</option>
                                        <option value="offline" {{$pointWithCoin->type == 'offline' ? 'selected' : ''}}>لعب محلي</option>
                                        <option value="online" {{$pointWithCoin->type == 'online' ? 'selected' : ''}}>لعب اون لاين</option>



                                        </select>
                                        @error('type') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>




                                <!-- Game Coin -->
                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">عملة اللعبة</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <select name="game_coin_id" class="form-select">
                                            <option value="">اختر عملة اللعبة</option>
                                            @foreach($gameCoins as $coin)
                                                <option value="{{ $coin->id }}" {{ $coin->id == $pointWithCoin->game_coin_id ? 'selected' : '' }}>
                                                    {{ $coin->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('game_coin_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Coins Number -->
                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">عدد العملات المكتسبة</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="number" name="coins_number" class="form-control" value="{{ $pointWithCoin->coins_number }}" />
                                        @error('coins_number') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                 <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">عدد النقاط المطلوبة</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="number" name="points_number" class="form-control" value="{{ $pointWithCoin->points_number }}" />
                                        @error('points_number') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>








                                <!-- Submit -->
                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="submit" class="btn btn-primary px-4" value="تعديل " />
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>

                    <script>
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
