@extends('admin.master_admin')
@section('admin')

<!--breadcrumb-->
<div class="page-breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div class="d-flex align-items-center gap-2">
        <div class="breadcrumb-title pe-3 border-0">كل الفئات</div>
        <span class="badge bg-light-primary text-primary border border-primary px-3 py-2 fw-bold" style="font-size: 13px;">
            <i class="fa-solid fa-layer-group me-1"></i> إجمالي الفئات: <span id="header-total-count">{{ $categories->total() }}</span>
        </span>
    </div>
    <div class="ms-auto">
        <div class="d-flex gap-2">
            <a href="{{ route('add.category') }}" class="btn btn-primary px-3 d-flex align-items-center gap-1">
                <i class="bx bx-plus"></i> اضافة فئة
            </a>
        </div>
    </div>
</div>
<!--end breadcrumb-->

<hr/>

<!-- Professional AJAX Filter Card -->
<div class="card border shadow-sm mb-4">
    <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
            <h6 class="mb-0 text-primary fw-bold">
                <i class="fa-solid fa-filter me-2"></i> تصفية وبحث الفئات المتقدم
            </h6>
            <button type="button" id="btn-reset-filters" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1">
                <i class="fa-solid fa-rotate-left"></i> إعادة ضبط التصفية
            </button>
        </div>

        <div class="row g-2 align-items-center">
            <!-- Search Input -->
            <div class="col-12 col-md-6 col-lg-3">
                <label class="form-label small fw-bold text-muted mb-1">بحث بإسم الفئة أو الرقم:</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" id="filter-search" class="form-control" placeholder="اكتب اسم الفئة أو رقمها...">
                </div>
            </div>

            <!-- Game Type Select -->
            <div class="col-12 col-md-6 col-lg-3">
                <label class="form-label small fw-bold text-muted mb-1">نوع اللعبة:</label>
                <select id="filter-game-type" class="form-select form-select-sm">
                    <option value="all" selected>كل أنواع الألعاب</option>
                    @foreach($gameTypes as $gt)
                        <option value="{{ $gt->id }}">{{ $gt->type_name }} ({{ $gt->id }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Main Category Select -->
            <div class="col-12 col-md-6 col-lg-3">
                <label class="form-label small fw-bold text-muted mb-1">الفئة الرئيسية:</label>
                <select id="filter-main-category" class="form-select form-select-sm">
                    <option value="all" selected>كل الفئات الرئيسية</option>
                    @foreach($mainCategories as $mc)
                        <option value="{{ $mc->id }}" data-game-type="{{ $mc->game_type_id }}">{{ $mc->main_category_name }} ({{ $mc->id }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Display Target Select (تخصيص اللعبة) -->
            <div class="col-12 col-md-6 col-lg-3">
                <label class="form-label small fw-bold text-muted mb-1">تخصيص اللعبة:</label>
                <select id="filter-display-target" class="form-select form-select-sm">
                    <option value="all" selected>كل التخصيصات</option>
                    <option value="session">لعبة الجلسة</option>
                    <option value="field">لعبة الميدان</option>
                    <option value="both">الاثنين معاً</option>
                </select>
            </div>

            <!-- Questions Count Sort -->
            <div class="col-12 col-md-6 col-lg-4">
                <label class="form-label small fw-bold text-muted mb-1">عدد الأسئلة في الفئة:</label>
                <select id="filter-questions-sort" class="form-select form-select-sm">
                    <option value="all" selected>الافتراضي (حسب ترتيب الظهور)</option>
                    <option value="most">الأكثر أسئلة أولاً ⬇</option>
                    <option value="least">الأقل أسئلة أولاً ⬆</option>
                </select>
            </div>

            <!-- Date Added Sort -->
            <div class="col-12 col-md-6 col-lg-4">
                <label class="form-label small fw-bold text-muted mb-1">تاريخ الإضافة:</label>
                <select id="filter-date-sort" class="form-select form-select-sm">
                    <option value="all" selected>الافتراضي (حسب ترتيب الظهور)</option>
                    <option value="newest">الأحدث إضافة أولاً ⬇</option>
                    <option value="oldest">الأقدم إضافة أولاً ⬆</option>
                </select>
            </div>

            <!-- Results Summary Box -->
            <div class="col-12 col-lg-4">
                <label class="form-label small fw-bold text-muted mb-1">حالة النتائج:</label>
                <div class="d-flex align-items-center justify-content-between bg-light rounded px-3 py-1 border" style="height: 31px;">
                    <span class="small text-muted fw-bold">الفئات المطابقة:</span>
                    <span class="badge bg-primary fs-6" id="filter-results-count">{{ $categories->total() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Categories Table Card -->
<div class="card">
    <div class="card-body p-2 p-md-3">
        <div class="table-responsive">
            <table id="categories-table" class="table table-striped table-bordered align-middle mb-0" style="width:100%; font-size: 13.5px;">
                <thead class="table-light">
                    <tr class="text-center align-middle">
                        <th>#</th>
                        <th>نوع اللعبة</th>
                        <th>الفئة الرئيسية</th>
                        <th>إسم الفئة</th>
                        <th>تخصيص اللعبة</th>
                        <th>ترتيب الظهور</th>
                        <th>عدد الأسئلة في الفئة</th>
                        <th>عدد مرات الإستخدام</th>
                        <th>التاريخ</th>
                        <th>الصورة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="category-tbody">
                    @include('admin.category.partials.category_rows', ['category' => $categories, 'page' => 1, 'perPage' => 25])
                </tbody>
                <tfoot class="table-light">
                    <tr class="text-center align-middle">
                        <th>#</th>
                        <th>نوع اللعبة</th>
                        <th>الفئة الرئيسية</th>
                        <th>إسم الفئة</th>
                        <th>تخصيص اللعبة</th>
                        <th>ترتيب الظهور</th>
                        <th>عدد الأسئلة في الفئة</th>
                        <th>عدد مرات الإستخدام</th>
                        <th>التاريخ</th>
                        <th>الصورة</th>
                        <th>الإجراءات</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Loading Spinner -->
        <div id="loading-spinner" class="text-center my-4" style="display: none;">
            <div class="spinner-border text-primary" role="status" style="width: 2.2rem; height: 2.2rem;">
                <span class="visually-hidden">جاري تحميل الفئات...</span>
            </div>
            <div class="small text-muted mt-2 fw-bold">جاري تحميل الفئات...</div>
        </div>

        <!-- Load More Button (Manual Trigger Option) -->
        <div id="load-more-container" class="text-center my-3" style="{{ $categories->hasMorePages() ? '' : 'display: none;' }}">
            <button type="button" id="btn-load-more" class="btn btn-outline-primary px-4 py-2 rounded-pill shadow-sm">
                <i class="fa-solid fa-angles-down me-1"></i> تحميل المزيد من الفئات
            </button>
        </div>

        <!-- End of Results Alert -->
        <div id="end-of-results" class="alert alert-light text-center border mt-3 mb-1 text-muted" style="{{ $categories->hasMorePages() ? 'display: none;' : '' }}">
            <i class="fa-solid fa-circle-check text-success me-1"></i> تم عرض جميع الفئات بنجاح (<span id="end-count">{{ $categories->total() }}</span> فئة).
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content position-relative bg-transparent border-0">
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
        <img id="modalImage" src="" class="img-fluid rounded shadow" alt="image">
      </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showImageModal(src) {
        document.getElementById('modalImage').src = src;
        var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
        myModal.show();
    }

    function saveCategoryOrderAjax(categoryId, orderByVal, confirmSwap) {
        $.ajax({
            url: "{{ route('category.update.order') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: categoryId,
                order_by: orderByVal,
                confirm_swap: confirmSwap ? 1 : 0
            },
            success: function(response) {
                if (response.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم التحديث!',
                        text: response.message,
                        timer: 1800,
                        showConfirmButton: false
                    });
                } else if (response.is_duplicate) {
                    Swal.fire({
                        title: 'تنبيه وجود فئة بنفس الترتيب!',
                        text: response.message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'نعم، استبدل المراكز',
                        cancelButtonText: 'إلغاء'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            saveCategoryOrderAjax(categoryId, orderByVal, true);
                        }
                    });
                }
            },
            error: function(xhr) {
                var errorMsg = 'حدث خطأ أثناء حفظ الترتيب';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'تنبيه!',
                    text: errorMsg,
                    confirmButtonText: 'حسناً'
                });
            }
        });
    }

    function saveCategoryOrderBtn(e, categoryId) {
        if (e) e.preventDefault();
        var orderByVal = $('#order-input-' + categoryId).val();
        saveCategoryOrderAjax(categoryId, orderByVal, false);
    }

    $(document).on('click', '.btn-save-category-order', function(e) {
        e.preventDefault();
        var categoryId = $(this).data('id');
        if (categoryId) {
            var orderByVal = $('#order-input-' + categoryId).val();
            saveCategoryOrderAjax(categoryId, orderByVal, false);
        }
    });

    // ==========================================
    // AJAX Filters & Lazy Loading System
    // ==========================================
    window.addEventListener('load', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        let page = 1;
        let hasMore = {{ $categories->hasMorePages() ? 'true' : 'false' }};
        let loading = false;
        let searchTimeout;

        // Cache all main category options for fast client-side filtering by game_type
        const allMainCategoryOptions = [];
        $('#filter-main-category option').each(function() {
            allMainCategoryOptions.push({
                value: $(this).val(),
                text: $(this).text(),
                gameType: $(this).data('game-type')
            });
        });

        // Function to filter main categories dropdown based on selected game type
        function updateMainCategoriesDropdown(gameTypeId) {
            const $mainCategorySelect = $('#filter-main-category');
            $mainCategorySelect.empty();
            $mainCategorySelect.append('<option value="all" selected>كل الفئات الرئيسية</option>');

            if (gameTypeId === 'all') {
                allMainCategoryOptions.forEach(opt => {
                    if (opt.value !== 'all') {
                        $mainCategorySelect.append(`<option value="${opt.value}" data-game-type="${opt.gameType}">${opt.text}</option>`);
                    }
                });
            } else {
                // Fetch dynamic main categories from server
                $.ajax({
                    url: '/get-main-categories/' + gameTypeId,
                    type: 'GET',
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    success: function(data) {
                        if (data && data.length > 0) {
                            data.forEach(item => {
                                $mainCategorySelect.append(`<option value="${item.id}">${item.main_category_name} (${item.id})</option>`);
                            });
                        }
                    }
                });
            }
        }

        // Fetch categories with filter & lazy loading
        function fetchCategories(reset = false) {
            if (loading || (!hasMore && !reset)) {
                return;
            }

            loading = true;

            if (reset) {
                page = 1;
                hasMore = true;
                $('#category-tbody').empty();
                $('#end-of-results').hide();
                $('#load-more-container').hide();
            }

            const targetPage = reset ? 1 : page + 1;
            const search = $('#filter-search').val();
            const gameTypeId = $('#filter-game-type').val();
            const mainCategoryId = $('#filter-main-category').val();
            const displayTarget = $('#filter-display-target').val();
            const questionsSort = $('#filter-questions-sort').val();
            const dateSort = $('#filter-date-sort').val();

            $('#loading-spinner').show();

            $.ajax({
                url: "{{ route('all.category') }}",
                type: "GET",
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                data: {
                    page: targetPage,
                    search: search,
                    game_type_id: gameTypeId,
                    main_category_id: mainCategoryId,
                    display_target: displayTarget,
                    questions_sort: questionsSort,
                    date_sort: dateSort,
                    ajax: 1
                },
                dataType: "json",
                success: function(res) {
                    $('#loading-spinner').hide();
                    loading = false;

                    if (reset) {
                        $('#category-tbody').html(res.html);
                    } else {
                        $('#category-tbody').append(res.html);
                    }

                    page = res.current_page;
                    hasMore = res.has_more;

                    // Update counters
                    $('#filter-results-count').text(res.total);
                    $('#header-total-count').text(res.total);
                    $('#end-count').text(res.total);

                    if (hasMore) {
                        $('#load-more-container').show();
                        $('#end-of-results').hide();
                    } else {
                        $('#load-more-container').hide();
                        if (res.total > 0) {
                            $('#end-of-results').show();
                        }
                    }

                    // Refresh sticky scrollbars for wide table view
                    if (window.refreshTableStickyScrollbars) {
                        window.refreshTableStickyScrollbars();
                    }
                },
                error: function(err) {
                    $('#loading-spinner').hide();
                    loading = false;
                    console.error("Error fetching categories:", err);
                }
            });
        }

        // Live Search with Debounce
        $('#filter-search').on('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                fetchCategories(true);
            }, 300);
        });

        // Game Type Change Handler
        $('#filter-game-type').on('change', function() {
            const selectedGameType = $(this).val();
            updateMainCategoriesDropdown(selectedGameType);
            fetchCategories(true);
        });

        // Dropdown Filters Change Handlers
        $('#filter-main-category, #filter-display-target, #filter-questions-sort, #filter-date-sort').on('change', function() {
            fetchCategories(true);
        });

        // Reset Filters Button
        $('#btn-reset-filters').on('click', function() {
            $('#filter-search').val('');
            $('#filter-game-type').val('all');
            $('#filter-display-target').val('all');
            $('#filter-questions-sort').val('all');
            $('#filter-date-sort').val('all');
            updateMainCategoriesDropdown('all');
            fetchCategories(true);
        });

        // Load More Button Click
        $('#btn-load-more').on('click', function() {
            fetchCategories(false);
        });

        // Window Scroll for Infinite Lazy Loading
        $(window).on('scroll', function() {
            if ($(window).scrollTop() + $(window).height() >= $(document).height() - 400) {
                if (!loading && hasMore) {
                    fetchCategories(false);
                }
            }
        });
    });
</script>
@endsection
