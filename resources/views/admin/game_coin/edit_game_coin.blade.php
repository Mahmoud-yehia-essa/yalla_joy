@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="col-lg-16">
    <div class="card">
        <div class="card-body">

            {{-- Display Success Message --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Display Validation Errors --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('store.game.coin') }}" enctype="multipart/form-data">
                @csrf



                                <input type="hidden" name="id" value="{{ $gameCoin->id }}">
                                <input type="hidden" name="old_image" value="{{ $gameCoin->photo }}">

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">عملة اللعبة</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               name="name" value="{{ old('name', $gameCoin->name) }}">
                        @error('version')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


                 <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Game coin</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <input type="text" class="form-control @error('name_en') is-invalid @enderror"
                               name="name_en" value="{{ old('name_en', $gameCoin->name_en) }}">
                        @error('name_en')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>



                <!-- Category Photo -->
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">صورة العملة</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="file" name="photo" class="form-control" id="image" />
                                        @error('photo')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                  <!-- Image Preview -->
                                <div class="row mb-3">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <img id="showImage" src="{{$gameCoin->photo == null ? url('upload/no_image.jpg'): url($gameCoin->photo) }}" alt="Preview" style="width:100px; height: 100px;">
                                    </div>
                                </div>












                <div class="row">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-9 text-secondary">
                        <input type="submit" class="btn btn-primary px-4" value="تحديث">
                    </div>
                </div>





            </form>
        </div>
    </div>

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
@endsection
