@extends('admin.master_admin')
@section('admin')

<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">تعديل عنصر الأفاتار</div>
</div>
<hr/>

<div class="card">
    <div class="card-body p-4">

        <form method="post" action="{{ route('update.avatar.item') }}" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="id" value="{{ $avatarItem->id }}">

            <div class="row">

                <!-- Name -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">اسم العنصر</label>
                    <input type="text" name="name" class="form-control" value="{{ $avatarItem->name }}" placeholder="أدخل اسم العنصر">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Category -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">تصنيف الأفاتار</label>
                    <select name="category_id" class="form-select">
                        <option value="">اختر التصنيف</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $avatarItem->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Is Free Checkbox -->
                <div class="col-md-12 mb-3">
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" name="is_free" id="isFreeCheckbox" value="1" {{ $avatarItem->is_free ? 'checked' : '' }}>
                        <label class="form-check-label form-label" for="isFreeCheckbox" style="font-weight: bold; cursor: pointer;">هل هذا العنصر مجاني؟</label>
                    </div>
                </div>

                <!-- Game Coin -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">نوع العملة</label>
                    <select name="game_coin_id" id="gameCoinSelect" class="form-select">
                        <option value="">اختر نوع العملة</option>
                        @foreach($coins as $coin)
                            <option value="{{ $coin->id }}" {{ $avatarItem->game_coin_id == $coin->id ? 'selected' : '' }}>{{ $coin->name }}</option>
                        @endforeach
                    </select>
                    @error('game_coin_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Coins Number -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">السعر (عدد العملات)</label>
                    <input type="number" name="coins_number" id="coinsNumberInput" class="form-control" min="0" value="{{ $avatarItem->coins_number }}" placeholder="أدخل السعر">
                    @error('coins_number')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Image Upload -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">صورة العنصر</label>
                    <input type="file" name="image" class="form-control" id="imageInput">
                    @error('image')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                    <div class="mt-2">
                        <img id="showImage" src="{{ $avatarItem->image ? asset($avatarItem->image) : url('upload/no_image.jpg') }}" alt="image preview"
                             style="width: 100px; height: 100px; border:1px solid #ccc; border-radius:8px; object-fit: cover;">
                    </div>
                </div>

                <!-- Gender -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">نوع الأفاتار (ولد / بنت)</label>
                    <select name="gender" class="form-select">
                        <option value="boy" {{ old('gender', $avatarItem->gender) == 'boy' ? 'selected' : '' }}>ولد</option>
                        <option value="girl" {{ old('gender', $avatarItem->gender) == 'girl' ? 'selected' : '' }}>بنت</option>
                    </select>
                    @error('gender')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary px-4">حفظ التغييرات</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Show image preview
    document.getElementById('imageInput').addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('showImage').src = e.target.result;
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Toggle free / paid inputs
    const isFreeCheckbox = document.getElementById('isFreeCheckbox');
    const gameCoinSelect = document.getElementById('gameCoinSelect');
    const coinsNumberInput = document.getElementById('coinsNumberInput');

    function togglePaidFields() {
        if (isFreeCheckbox.checked) {
            gameCoinSelect.value = '';
            coinsNumberInput.value = '';
            gameCoinSelect.setAttribute('disabled', 'disabled');
            coinsNumberInput.setAttribute('disabled', 'disabled');
        } else {
            gameCoinSelect.removeAttribute('disabled');
            coinsNumberInput.removeAttribute('disabled');
        }
    }

    isFreeCheckbox.addEventListener('change', togglePaidFields);
    togglePaidFields(); // run on load
</script>

@endsection
