@extends('admin.master_admin')
@section('admin')

<style>
    /* Remove native up/down number arrows in WebKit/Firefox */
    .no-spinners::-webkit-outer-spin-button,
    .no-spinners::-webkit-inner-spin-button {
        -webkit-appearance: none !important;
        margin: 0 !important;
    }
    .no-spinners {
        -moz-appearance: textfield !important;
    }
</style>

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">كل عناصر الأفاتار</div>
    <div class="ms-auto">
        <div class="btn-group">
            <a href="{{ route('export.avatar.item', request()->query()) }}" class="btn btn-success px-3 d-flex align-items-center justify-content-center gap-1">
                <i class="bx bx-download"></i> تصدير إلى Excel
            </a>
        </div>
    </div>
</div>
<!--end breadcrumb-->

<hr/>

<!-- Filter Section -->
<div class="card mb-3 shadow-sm border-top border-0 border-4 border-primary">
    <div class="card-body p-3">
        <h6 class="mb-3 text-uppercase" style="font-weight: bold; color: #32296a;"><i class="bx bx-filter-alt"></i> تصفية وتصفح عناصر الأفاتار</h6>
        <form method="GET" action="{{ route('all.avatar.item') }}" class="row g-3 align-items-end">
            <!-- Gender Filter -->
            <div class="col-md-3">
                <label class="form-label" style="font-weight: 600; font-size: 0.9rem;">النوع</label>
                <select name="gender" class="form-select border-2">
                    <option value="">كل الأنواع (ولد وبنت)</option>
                    <option value="boy" {{ request('gender') == 'boy' ? 'selected' : '' }}>ولد (Boy)</option>
                    <option value="girl" {{ request('gender') == 'girl' ? 'selected' : '' }}>بنت (Girl)</option>
                </select>
            </div>

            <!-- Category Filter -->
            <div class="col-md-3">
                <label class="form-label" style="font-weight: 600; font-size: 0.9rem;">التصنيف</label>
                <select name="category_id" class="form-select border-2">
                    <option value="">كل التصنيفات</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Price Filter -->
            <div class="col-md-3">
                <label class="form-label" style="font-weight: 600; font-size: 0.9rem;">حالة السعر</label>
                <select name="price_type" class="form-select border-2">
                    <option value="">كل الحالات</option>
                    <option value="free" {{ request('price_type') == 'free' ? 'selected' : '' }}>مجاني</option>
                    <option value="paid" {{ request('price_type') == 'paid' ? 'selected' : '' }}>مدفوع</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-3 w-100 d-flex align-items-center justify-content-center gap-1" style="height: 38px;">
                    <i class="bx bx-filter-alt"></i> تصفية
                </button>
                <a href="{{ route('all.avatar.item') }}" class="btn btn-secondary px-3 w-100 d-flex align-items-center justify-content-center gap-1" style="height: 38px;">
                    <i class="bx bx-refresh"></i> إعادة تعيين
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="avatar-items-table" class="table table-striped table-bordered align-middle" style="width:100%">
                <thead>
                    <tr>
                        <th>الرقم</th>
                        <th>اسم العنصر</th>
                        <th>الصورة</th>
                        <th>التصنيف</th>
                        <th>النوع</th>
                        <th>نوع العملة</th>
                        <th>السعر (العملات)</th>
                        <th>الإجراء</th>
                    </tr>
                </thead>
                <tbody id="avatar-items-tbody">
                    @include('admin.avatar_item.partials.avatar_item_rows', ['avatarItems' => $avatarItems, 'categories' => $categories, 'coins' => $coins, 'startKey' => 0])
                </tbody>
                <tfoot>
                    <tr>
                        <th>الرقم</th>
                        <th>اسم العنصر</th>
                        <th>الصورة</th>
                        <th>التصنيف</th>
                        <th>النوع</th>
                        <th>نوع العملة</th>
                        <th>السعر (العملات)</th>
                        <th>الإجراء</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Lazy Loading Indicators -->
        <div id="lazy-loader-spinner" class="text-center my-3 d-none">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">جاري التحميل...</span>
            </div>
            <p class="text-muted small mt-1">جاري تحميل المزيد من عناصر الأفاتار...</p>
        </div>
        <div id="lazy-loader-no-more" class="text-center my-3 {{ $avatarItems->hasMorePages() ? 'd-none' : '' }}">
            <span class="badge bg-light text-secondary border px-3 py-2">
                <i class="bx bx-check-circle text-success me-1"></i> تم تحميل كافة عناصر الأفاتار
            </span>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content position-relative bg-transparent border-0">
            <!-- Close Button -->
            <button type="button"
                    class="btn text-white"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                    style="
                        position: absolute;
                        top: 15px;
                        right: 15px;
                        background-color: black;
                        font-size: 30px;
                        padding: 1px 10px;
                        border-radius: 8px;
                        z-index: 1055;
                    ">
                &times;
            </button>
            <!-- Image -->
            <img id="modalImage" src="" class="img-fluid rounded shadow" alt="image">
        </div>
    </div>
</div>

<script>
    function showImageModal(src) {
        document.getElementById('modalImage').src = src;
        var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
        myModal.show();
    }
</script>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // CSRF Setup for AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    // -------------------------------------------------------------
    // 1. ITEM NAME INLINE EDITING
    // -------------------------------------------------------------
    $(document).on('click', '.edit-name-btn, .item-name-text', function(e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        if (!id) {
            id = $(this).closest('[data-id]').attr('data-id');
        }
        $('#name-container-' + id).addClass('d-none');
        $('#name-wrapper-' + id).removeClass('d-none');
        $('#name-input-' + id).focus().select();
    });

    $(document).on('click', '.cancel-name-btn', function(e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        var originalName = $('#name-text-' + id).text().trim();
        $('#name-input-' + id).val(originalName);
        $('#name-wrapper-' + id).addClass('d-none');
        $('#name-container-' + id).removeClass('d-none');
    });

    function saveItemName(id) {
        var newName = $('#name-input-' + id).val().trim();
        if (!newName) {
            if (typeof toastr !== 'undefined') {
                toastr.warning('⚠️ الرجاء إدخال اسم العنصر');
            } else {
                alert('⚠️ الرجاء إدخال اسم العنصر');
            }
            return;
        }

        $.ajax({
            url: "{{ Route::has('ajax.update.avatar.item.name') ? route('ajax.update.avatar.item.name') : url('/ajax/update/avatar/item/name') }}",
            type: "POST",
            data: {
                id: id,
                name: newName
            },
            success: function(response) {
                if (response.success) {
                    $('#name-text-' + id).text(response.name);
                    $('#name-wrapper-' + id).addClass('d-none');
                    $('#name-container-' + id).removeClass('d-none');
                    if (typeof toastr !== 'undefined') {
                        toastr.success(response.message);
                    }
                }
            },
            error: function(xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : '⚠️ حدث خطأ أثناء تحديث الاسم';
                if (typeof toastr !== 'undefined') {
                    toastr.error(msg);
                } else {
                    alert(msg);
                }
            }
        });
    }

    $(document).on('click', '.save-name-btn', function(e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        saveItemName(id);
    });

    $(document).on('keydown', '.item-name-input', function(e) {
        var id = $(this).attr('data-id');
        if (e.key === 'Enter') {
            e.preventDefault();
            saveItemName(id);
        } else if (e.key === 'Escape') {
            $('.cancel-name-btn[data-id="' + id + '"]').click();
        }
    });

    // -------------------------------------------------------------
    // 2. AVATAR IMAGE AJAX UPLOAD
    // -------------------------------------------------------------
    $(document).on('change', '.ajax-image-input', function() {
        var id = $(this).attr('data-id');
        var fileInput = this;

        if (!fileInput.files || !fileInput.files[0]) {
            return;
        }

        var formData = new FormData();
        formData.append('id', id);
        formData.append('image', fileInput.files[0]);

        $('#image-spinner-' + id).removeClass('d-none').addClass('d-flex');

        $.ajax({
            url: "{{ Route::has('ajax.update.avatar.item.image') ? route('ajax.update.avatar.item.image') : url('/ajax/update/avatar/item/image') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#image-spinner-' + id).removeClass('d-flex').addClass('d-none');
                if (response.success) {
                    var newSrc = response.image_url + '?t=' + new Date().getTime();
                    $('#avatar-img-' + id).attr('src', newSrc);
                    if (typeof toastr !== 'undefined') {
                        toastr.success(response.message);
                    }
                }
            },
            error: function(xhr) {
                $('#image-spinner-' + id).removeClass('d-flex').addClass('d-none');
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : '⚠️ حدث خطأ أثناء تحميل الصورة';
                if (typeof toastr !== 'undefined') {
                    toastr.error(msg);
                } else {
                    alert(msg);
                }
            }
        });
    });

    // -------------------------------------------------------------
    // 3. CURRENCY TYPE AJAX UPDATE
    // -------------------------------------------------------------
    $(document).on('change', '.ajax-currency-select', function() {
        var id = $(this).attr('data-id');
        var currency = $(this).val();
        var priceInput = $('#price-input-' + id);

        // Immediate UI feedback: activate or disable price input right away
        if (currency === 'free') {
            priceInput.val(0).prop('disabled', true).attr('disabled', 'disabled');
            $('#save-price-btn-' + id).addClass('d-none');
        } else {
            priceInput.prop('disabled', false).removeAttr('disabled').focus();
        }

        $.ajax({
            url: "{{ Route::has('ajax.update.avatar.item.currency') ? route('ajax.update.avatar.item.currency') : url('/ajax/update/avatar/item/currency') }}",
            type: "POST",
            data: {
                id: id,
                currency: currency
            },
            success: function(response) {
                if (response.success) {
                    if (response.is_free == 1) {
                        priceInput.val(0).prop('disabled', true).attr('disabled', 'disabled');
                        $('#save-price-btn-' + id).addClass('d-none');
                    } else {
                        priceInput.prop('disabled', false).removeAttr('disabled');
                    }
                    if (typeof toastr !== 'undefined') {
                        toastr.success(response.message);
                    }
                }
            },
            error: function(xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : '⚠️ حدث خطأ أثناء تحديث نوع العملة';
                if (typeof toastr !== 'undefined') {
                    toastr.error(msg);
                } else {
                    alert(msg);
                }
            }
        });
    });

    // -------------------------------------------------------------
    // 4. PRICE AJAX UPDATE
    // -------------------------------------------------------------
    $(document).on('input change', '.ajax-price-input', function() {
        var id = $(this).attr('data-id');
        var originalVal = $(this).attr('data-original-val');
        var currentVal = $(this).val();

        if (currentVal != originalVal) {
            $('#save-price-btn-' + id).removeClass('d-none');
        } else {
            $('#save-price-btn-' + id).addClass('d-none');
        }
    });

    function savePrice(id) {
        var priceInput = $('#price-input-' + id);
        var coinsNumber = priceInput.val();

        if (coinsNumber === '' || coinsNumber < 0) {
            if (typeof toastr !== 'undefined') {
                toastr.warning('⚠️ الرجاء إدخال سعر صحيح (0 أو أكثر)');
            }
            return;
        }

        $.ajax({
            url: "{{ Route::has('ajax.update.avatar.item.price') ? route('ajax.update.avatar.item.price') : url('/ajax/update/avatar/item/price') }}",
            type: "POST",
            data: {
                id: id,
                coins_number: coinsNumber
            },
            success: function(response) {
                if (response.success) {
                    priceInput.attr('data-original-val', response.coins_number);
                    $('#save-price-btn-' + id).addClass('d-none');
                    if (typeof toastr !== 'undefined') {
                        toastr.success(response.message);
                    }
                }
            },
            error: function(xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : '⚠️ حدث خطأ أثناء تحديث السعر';
                if (typeof toastr !== 'undefined') {
                    toastr.error(msg);
                } else {
                    alert(msg);
                }
            }
        });
    }

    $(document).on('click', '.save-price-btn', function(e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        savePrice(id);
    });

    $(document).on('keydown', '.ajax-price-input', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            var id = $(this).attr('data-id');
            savePrice(id);
        }
    });

    $(document).on('blur', '.ajax-price-input', function() {
        var id = $(this).attr('data-id');
        var originalVal = $(this).attr('data-original-val');
        var currentVal = $(this).val();

        if (currentVal !== '' && currentVal != originalVal) {
            savePrice(id);
        }
    });



    // -------------------------------------------------------------
    // 6. LAZY LOADING / INFINITE SCROLL ON SCROLL
    // -------------------------------------------------------------
    var page = {{ $avatarItems->currentPage() }};
    var hasMore = {{ $avatarItems->hasMorePages() ? 'true' : 'false' }};
    var loading = false;

    $(window).on('scroll', function() {
        if (!hasMore || loading) return;

        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 350) {
            loading = true;
            page++;
            $('#lazy-loader-spinner').removeClass('d-none');

            var queryParams = new URLSearchParams(window.location.search);
            queryParams.set('page', page);

            $.ajax({
                url: "{{ route('all.avatar.item') }}?" + queryParams.toString(),
                type: "GET",
                dataType: "json",
                success: function(response) {
                    $('#lazy-loader-spinner').addClass('d-none');
                    if (response.html) {
                        $('#avatar-items-tbody').append(response.html);
                        hasMore = response.has_more;
                        loading = false;

                        if (!hasMore) {
                            $('#lazy-loader-no-more').removeClass('d-none');
                        }
                    }
                },
                error: function() {
                    $('#lazy-loader-spinner').addClass('d-none');
                    loading = false;
                }
            });
        }
    });
});
</script>
@endsection
