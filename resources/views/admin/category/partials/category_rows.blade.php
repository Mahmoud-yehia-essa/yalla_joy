@if(count($category) > 0)
    @foreach($category as $key => $item)
    @php
        $rowNumber = (isset($page) && isset($perPage)) ? (($page - 1) * $perPage + $key + 1) : ($key + 1);
    @endphp
    <tr>
        <td class="text-center">{{ $rowNumber }}</td>
        <td>{{ $item->gameType ? $item->gameType->type_name : '-' }} - {{ $item->game_type_id }}</td>
        <td>{{ $item->mainCategory ? $item->mainCategory->main_category_name : '-' }} - {{ $item->main_category_id }}</td>
        <td class="fw-bold">
            {{ $item->category_name }} - {{ $item->id }}
            @if($item->is_soon == 'yes')
                <span class="badge bg-warning text-dark ms-1" style="font-size: 11px;"><i class="fa-solid fa-clock"></i> قريباً</span>
            @endif
            @if($item->special == 'active')
                <span class="badge bg-danger ms-1" style="font-size: 11px;"><i class="fa-solid fa-star"></i> مميزة</span>
            @endif
        </td>
        <td class="text-center">
            @if($item->display_target == 'session')
                <span class="badge bg-info text-dark">لعبة الجلسة</span>
            @elseif($item->display_target == 'field')
                <span class="badge bg-warning text-dark">لعبة الميدان</span>
            @else
                <span class="badge bg-secondary">الاثنين معاً</span>
            @endif
        </td>
        <td class="text-center" style="min-width: 130px;">
            <div class="input-group input-group-sm">
                <input type="number" class="form-control text-center fw-bold category-order-input"
                       id="order-input-{{ $item->id }}"
                       data-id="{{ $item->id }}"
                       value="{{ $item->order_by }}"
                       placeholder="-"
                       min="1">
                <button class="btn btn-primary btn-save-category-order"
                        type="button"
                        onclick="saveCategoryOrderBtn(event, {{ $item->id }})"
                        data-id="{{ $item->id }}"
                        title="حفظ الترتيب">
                    <i class="fa-solid fa-floppy-disk"></i>
                </button>
            </div>
        </td>
        <td class="text-center" style="width: 50px; font-size: 1.1rem;">
            <a href="{{ route('all.question', ['category_id' => $item->id]) }}" class="badge bg-info text-dark" style="font-size: 14px;" title="عرض أسئلة هذه الفئة">
                {{ $item->questions_count ?? (isset($item->questions) ? count($item->questions) : 0) }}
            </a>
        </td>
        <td class="text-center" style="width: 50px; font-size: 1.1rem;">
            <span class="badge bg-danger">{{ $item->how_many_use }}</span>
        </td>
        <td class="text-center">
            {{ $item->created_at ? $item->created_at->format('Y-m-d') . ' (' . $item->created_at->diffForHumans(['parts' => 1]) . ' تقريبًا)' : 'لم يتم التحديد' }}
        </td>
        <td class="text-center">
            <img onclick="showImageModal(this.src)" src="{{ (!empty($item->category_photo) && file_exists(public_path($item->category_photo))) ? asset($item->category_photo) : url('upload/no_image.jpg') }}" style="width: 70px; height:40px; cursor: pointer; object-fit: cover;" class="rounded shadow-sm">
        </td>
        <td class="text-center">
            @if($item->status == 'active')
                <a href="{{ route('inactive.category', $item->id) }}" class="btn btn-sm btn-primary" title="اخفاء">
                    <i class="fa-solid fa-eye"></i>
                </a>
            @else
                <a href="{{ route('active.category', $item->id) }}" class="btn btn-sm btn-primary" title="اظهار">
                    <i class="fa-solid fa-eye-slash"></i>
                </a>
            @endif
            <a href="{{ route('edit.category', $item->id) }}" class="btn btn-sm btn-info" title="تعديل">تعديل</a>
            <a href="{{ route('delete.category', $item->id) }}" class="btn btn-sm btn-danger" id="delete" title="حذف">حذف</a>

            @if($item->special == 'active')
                <img title="مميز" style="width: 30px; height:30px;" src="{{ asset('backend/assets/images/logo-icon.png') }}">
            @endif
        </td>
    </tr>
    @endforeach
@elseif(isset($page) && $page == 1)
    <tr>
        <td colspan="11" class="text-center text-muted p-4">
            <div class="my-3">
                <i class="fa-regular fa-folder-open fa-3x mb-2 text-secondary"></i>
                <div class="fs-6 fw-bold">لا توجد فئات مطابقة لمعايير البحث والتصفية</div>
            </div>
        </td>
    </tr>
@endif
