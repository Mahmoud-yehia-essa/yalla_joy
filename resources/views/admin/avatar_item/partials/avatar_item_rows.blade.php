@foreach($avatarItems as $index => $item)
@php
    $rowNumber = ($startKey ?? 0) + $index + 1;
@endphp
<tr>
    <td>{{ $rowNumber }}</td>
    
    <!-- 1. Item Name Inline Editing -->
    <td data-order="{{ $item->name ?? '' }}">
        <div class="d-flex align-items-center justify-content-between gap-2 item-name-container" id="name-container-{{ $item->id }}" data-id="{{ $item->id }}">
            <span class="item-name-text fw-bold text-dark cursor-pointer flex-grow-1" 
                  id="name-text-{{ $item->id }}" 
                  data-id="{{ $item->id }}"
                  title="انقر لتعديل الاسم"
                  style="border-bottom: 1px dashed #6c757d; cursor: pointer; padding-bottom: 1px;">
                {{ $item->name ?? '---' }}
            </span>
            <button type="button" class="btn btn-sm btn-light border-0 text-primary edit-name-btn p-1" data-id="{{ $item->id }}" title="تعديل الاسم">
                <i class="bx bx-pencil" style="font-size: 1.1rem; pointer-events: none;"></i>
            </button>
        </div>
        <div class="item-name-input-wrapper d-none" id="name-wrapper-{{ $item->id }}" data-id="{{ $item->id }}">
            <div class="input-group input-group-sm flex-nowrap" style="min-width: 160px;">
                <input type="text" class="form-control item-name-input border-primary" id="name-input-{{ $item->id }}" value="{{ $item->name }}" data-id="{{ $item->id }}">
                <button class="btn btn-success save-name-btn px-2" type="button" data-id="{{ $item->id }}" title="حفظ">
                    <i class="bx bx-check" style="pointer-events: none; font-size: 1.1rem;"></i>
                </button>
                <button class="btn btn-secondary cancel-name-btn px-2" type="button" data-id="{{ $item->id }}" title="إلغاء">
                    <i class="bx bx-x" style="pointer-events: none; font-size: 1.1rem;"></i>
                </button>
            </div>
        </div>
    </td>

    <!-- 2. Avatar Image Inline Upload -->
    <td>
        <div class="position-relative d-inline-block avatar-image-wrapper" style="width: 60px; height: 60px;">
            <img id="avatar-img-{{ $item->id }}"
                 onclick="showImageModal(this.src)"
                 src="{{ $item->image ? asset($item->image) : url('upload/no_image.jpg') }}"
                 style="width: 60px; height: 60px; cursor: pointer; object-fit: contain; background-color: #f8f9fa; border: 1px solid #e3e6f0; border-radius: 8px; padding: 2px;">
            
            <label for="image-upload-{{ $item->id }}" 
                   class="btn btn-sm btn-primary rounded-circle position-absolute shadow-sm p-0 d-flex align-items-center justify-content-center"
                   style="top: -6px; right: -6px; width: 24px; height: 24px; cursor: pointer; z-index: 5;"
                   title="تعديل الصورة">
                <i class="bx bx-pencil" style="font-size: 12px; pointer-events: none;"></i>
            </label>
            
            <input type="file" 
                   id="image-upload-{{ $item->id }}" 
                   class="d-none ajax-image-input" 
                   data-id="{{ $item->id }}" 
                   accept="image/*">
                   
            <div id="image-spinner-{{ $item->id }}" 
                 class="position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-75 rounded d-none justify-content-center align-items-center"
                 style="z-index: 6;">
                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
            </div>
        </div>
    </td>

    <!-- Category -->
    <td>{{ $item->category->name ?? '---' }}</td>

    <!-- Gender -->
    <td>
        @if($item->gender == 'boy')
            <span class="badge bg-primary">ولد</span>
        @elseif($item->gender == 'girl')
            <span class="badge bg-danger">بنت</span>
        @else
            <span class="badge bg-secondary">ولد</span>
        @endif
    </td>

    <!-- 3. Currency Select -->
    <td data-order="{{ $item->is_free ? 'مجاني' : ($item->coin->name ?? '') }}">
        <select class="form-select form-select-sm ajax-currency-select border-2" data-id="{{ $item->id }}" style="min-width: 120px;">
            <option value="free" {{ $item->is_free ? 'selected' : '' }}>🎁 مجاني</option>
            @foreach($coins as $coin)
                <option value="{{ $coin->id }}" {{ !$item->is_free && $item->game_coin_id == $coin->id ? 'selected' : '' }}>
                    🪙 {{ $coin->name }}
                </option>
            @endforeach
        </select>
    </td>

    <!-- 4. Price Input -->
    <td data-order="{{ $item->coins_number ?? 0 }}">
        <div class="input-group input-group-sm flex-nowrap" style="min-width: 130px;">
            <input type="number" 
                   min="0" 
                   class="form-control form-control-sm ajax-price-input border-2 no-spinners" 
                   value="{{ $item->coins_number ?? 0 }}" 
                   data-id="{{ $item->id }}"
                   data-original-val="{{ $item->coins_number ?? 0 }}"
                   id="price-input-{{ $item->id }}"
                   {{ $item->is_free ? 'disabled' : '' }}>
            <button class="btn btn-sm btn-success save-price-btn d-none px-2" 
                    type="button" 
                    id="save-price-btn-{{ $item->id }}"
                    data-id="{{ $item->id }}" 
                    title="حفظ السعر">
                <i class="bx bx-check" style="pointer-events: none; font-size: 1.1rem;"></i>
            </button>
        </div>
    </td>


    <!-- Actions -->
    <td>
        @if($item->status == 'active')
            <a href="{{ route('inactive.avatar.item', $item->id) }}" class="btn btn-primary" title="اخفاء">
                <i class="fa-solid fa-eye"></i>
            </a>
        @else
            <a href="{{ route('active.avatar.item', $item->id) }}" class="btn btn-primary" title="اظهار">
                <i class="fa-solid fa-eye-slash"></i>
            </a>
        @endif
        <a href="{{ route('edit.avatar.item', $item->id) }}" class="btn btn-info">تعديل</a>
        <a href="{{ route('avatar.item.purchased.users', $item->id) }}" class="btn btn-warning">المشترون</a>
    </td>
</tr>
@endforeach
