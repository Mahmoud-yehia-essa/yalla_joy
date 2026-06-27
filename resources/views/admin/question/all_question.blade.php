@extends('admin.master_admin')
@section('admin')
<!-- Load Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    td.question-column {
        /* white-space: normal;
        word-break: break-word; */
        /* max-width: 50px; Adjust the width as needed */
    }
    /* Customize Select2 to match Bootstrap 5 Form Select styling */
    .select2-container .select2-selection--single {
        height: 38px !important;
        border: 1px solid #ced4da !important;
        border-radius: 0.375rem !important;
        padding-top: 4px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 24px !important;
        color: #212529 !important;
        text-align: right !important;
        padding-right: 12px !important;
    }
    .select2-dropdown {
        border: 1px solid #ced4da !important;
        border-radius: 0.375rem !important;
        z-index: 9999 !important;
    }
    .select2-search__field {
        outline: none !important;
        border: 1px solid #ced4da !important;
        border-radius: 0.25rem !important;
    }
</style>
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">كل الأسئلة </div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">

        </nav>
    </div>
    <div class="ms-auto">
        <div class="btn-group">
            <button type="button" class="btn btn-danger me-2" id="delete-selected" style="display: none;">
                حذف المحدد
            </button>
            <a href="{{route('add.question')}}" class="me-2">
<button type="button" class="btn btn-primary">
    اضافة سؤال جديد
</button>
</a>
            <a href="{{ route('excel.index') }}">
<button type="button" class="btn btn-success">
    اضافة الاسئلة من خلال excel
</button>
</a>
        </div>
    </div>
</div>
<!--end breadcrumb-->

<hr/>
<div class="card">
    <div class="card-body">
        <div class="row mb-3 align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                <input type="text" id="search-input" class="form-control" placeholder="البحث في الأسئلة...">
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                <select id="sort-by-select" class="form-select">
                    <option value="newest" selected>الأحدث أولاً</option>
                    <option value="oldest">الأقدم أولاً</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                <select id="type-select" class="form-select">
                    <option value="all" selected>كل أنواع الأسئلة</option>
                    <option value="text">سؤال نصي</option>
                    <option value="image">سؤال صورة</option>
                    <option value="sound">سؤال صوتي</option>
                    <option value="video">سؤال فيديو</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                <select id="answer-type-select" class="form-select">
                    <option value="all" selected>كل أنواع الإجابات</option>
                    <option value="text">إجابة نصية</option>
                    <option value="image">إجابة صورة</option>
                    <option value="sound">إجابة صوتية</option>
                    <option value="video">إجابة فيديو</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                <select id="points-select" class="form-select">
                    <option value="all" selected>كل النقاط</option>
                    <option value="200">200 نقطة</option>
                    <option value="400">400 نقطة</option>
                    <option value="600">600 نقطة</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12 mb-2">
                <select id="category-select" class="form-select">
                    <option value="all" selected>كل الفئات الفرعية</option>
                    @foreach($categories as $item)
                        <option value="{{ $item->id }}" {{ request('category_id') == $item->id ? 'selected' : '' }}>
                            {{ $item->mainCategory->main_category_name ?? 'بدون فئة رئيسية' }} / {{ $item->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Timing Update Form by Points -->
        <div id="timing-update-container" class="card border shadow-sm mb-4" style="display: none; background-color: #f8f9fa;">
            <div class="card-body">
                <h6 class="card-title text-primary mb-3" id="timing-update-title">تعديل كل الوقت للأسئلة ذات القيمة 200</h6>
                <form id="timing-update-form">
                    @csrf
                    <input type="hidden" id="timing-update-points" name="points" value="">
                    <div class="row align-items-end">
                        <div class="col-md-4 col-sm-6 mb-3">
                            <label class="form-label fw-bold small">توقيت السؤال (ثواني)</label>
                            <input type="number" name="time_counter" id="timing-update-time-counter" class="form-control" placeholder="أدخل توقيت السؤال بالثواني" min="1" step="1">
                        </div>
                        <div class="col-md-4 col-sm-6 mb-3">
                            <label class="form-label fw-bold small">توقيت السؤال OnLine (ثواني)</label>
                            <input type="number" name="time_counter_online" id="timing-update-time-counter-online" class="form-control" placeholder="أدخل توقيت السؤال أونلاين بالثواني" min="1" step="1">
                        </div>
                        <div class="col-md-4 col-sm-12 mb-3">
                            <button type="submit" class="btn btn-success w-100" id="btn-save-timings">
                                <i class="bx bx-save"></i> حفظ التوقيتات
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table id="questions-table" class="table table-striped table-bordered" style="width:100%">
                <thead>
<tr>
<th style="width: 40px; text-align: center;">
    <input type="checkbox" id="select-all" class="form-check-input">
</th>
<th>الرقم</th>
<th>السؤال</th>
<th>نوع السؤال</th>
<th>نقاط السؤال</th>
<th>نقاط السؤال OnLine</th>
<th>توقيت السؤال</th>
<th>توقيت السؤال OnLine</th>
<th>الفئة</th>

<th> الاجابة</th>
<th> تاريخ الاضافة</th>

<th>الاجراء</th>
</tr>
</thead>
<tbody id="questions-tbody">
@include('admin.question.partials.questions_rows', ['questions' => $questions])
</tbody>
<tfoot>
<tr>
    <th style="text-align: center;">
        <input type="checkbox" id="select-all-footer" class="form-check-input">
    </th>
    <th>الرقم</th>
    <th>السؤال</th>
    <th>نوع السؤال</th>
    <th>نقاط السؤال</th>
    <th>نقاط السؤال OnLine</th>
    <th>توقيت السؤال</th>
    <th>توقيت السؤال OnLine</th>
    <th>الفئة</th>

    <th> الاجابة</th>
    <th> التاريخ</th>

    <th>الاجراء</th>
</tr>
</tfoot>
</table>
        </div>
        <div id="loading-spinner" class="text-center my-3" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">جاري التحميل...</span>
            </div>
        </div>
    </div>
</div>

<!-- Professional Preview Modal -->
<div class="modal fade" id="questionDetailsModal" tabindex="-1" aria-labelledby="questionDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="questionDetailsModalLabel">تفاصيل السؤال</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modal-preview-content" style="text-align: right; direction: rtl;">
        <!-- Content injected via JS -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
      </div>
    </div>
  </div>
</div>

<!-- Media Preview Modal -->
<div class="modal fade" id="mediaPreviewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0" style="background-color: #000;">
      <div class="modal-header border-0 text-white pb-0">
        <h5 class="modal-title">معاينة الملف</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center p-4" id="media-preview-body">
        <!-- JS will inject image/video here -->
      </div>
    </div>
  </div>
</div>

<script>
    window.addEventListener('load', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        // Dynamically load Select2 JS once the page is fully loaded to prevent jQuery overrides
        $.getScript("https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js", function() {
            $('#category-select').select2({
                placeholder: "اختر فئة فرعية",
                allowClear: false,
                width: '100%',
                dir: 'rtl'
            });

            // Re-bind change handler after Select2 initialization
            $('#category-select').on('change', function() {
                fetchQuestions(true);
            });
        });

        let page = 2; // Page 1 is already loaded by backend
        let hasMore = {{ $questions->hasMorePages() ? 'true' : 'false' }};
        let loading = false;
        let searchTimeout;
        let scrollToId = '{{ $scrollToId ?? '' }}';

        function fetchQuestions(reset = false, callback = null) {
            if (loading || (!hasMore && !reset)) {
                if (callback) callback(false);
                return;
            }
            loading = true;
            
            if (reset) {
                page = 1;
                hasMore = true;
                $('#questions-tbody').empty();
                scrollToId = ''; // Clear scroll target on manual reset
            }

            let searchQuery = $('#search-input').val();
            let categoryId = $('#category-select').val();
            let sortBy = $('#sort-by-select').val();
            let questionsType = $('#type-select').val();
            let answerType = $('#answer-type-select').val();
            let points = $('#points-select').val();
            $('#loading-spinner').show();

            $.ajax({
                url: "{{ route('all.question') }}",
                type: "GET",
                data: {
                    page: page,
                    search: searchQuery,
                    category_id: categoryId,
                    sort_by: sortBy,
                    questions_type: questionsType,
                    answer_type: answerType,
                    points: points
                },
                success: function(response) {
                    $('#questions-tbody').append(response.html);
                    if (response.next_page) {
                        page++;
                    } else {
                        hasMore = false;
                    }
                    loading = false;
                    $('#loading-spinner').hide();
                    if (callback) callback(true);
                },
                error: function(xhr) {
                    loading = false;
                    $('#loading-spinner').hide();
                    console.log(xhr.responseText);
                    if (callback) callback(false);
                }
            });
        }

        // Auto-load pages until we find the target question, then scroll to it
        function autoScrollToQuestion() {
            if (!scrollToId) return;
            let targetRow = $('#question-row-' + scrollToId);
            if (targetRow.length) {
                // Found it - scroll and highlight
                $('html, body').animate({
                    scrollTop: targetRow.offset().top - 150
                }, 500);
                targetRow.css('background-color', '#fff3cd');
                setTimeout(function() {
                    targetRow.css('transition', 'background-color 2s ease');
                    targetRow.css('background-color', '');
                }, 3000);
                scrollToId = ''; // Done
            } else if (hasMore) {
                // Not found yet, load next page
                fetchQuestions(false, function(success) {
                    if (success) {
                        autoScrollToQuestion();
                    }
                });
            }
        }

        // Trigger auto-scroll on page load
        if (scrollToId) {
            setTimeout(function() {
                autoScrollToQuestion();
            }, 500);
        }

        $('#search-input').on('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                fetchQuestions(true);
            }, 500);
        });

        $('#sort-by-select, #type-select, #answer-type-select, #category-select, #points-select').on('change', function() {
            fetchQuestions(true);
        });

        $('#points-select').on('change', function() {
            let val = $(this).val();
            if (val !== 'all') {
                $('#timing-update-points').val(val);
                $('#timing-update-title').text('تعديل كل الوقت للأسئلة ذات القيمة ' + val);
                $('#timing-update-time-counter').val('');
                $('#timing-update-time-counter-online').val('');
                $('#timing-update-container').slideDown();
            } else {
                $('#timing-update-container').slideUp();
                $('#timing-update-time-counter').val('');
                $('#timing-update-time-counter-online').val('');
            }
        });

        $('#timing-update-form').on('submit', function(e) {
            e.preventDefault();
            let points = $('#timing-update-points').val();
            let timeCounter = $('#timing-update-time-counter').val();
            let timeCounterOnline = $('#timing-update-time-counter-online').val();

            $('#btn-save-timings').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> جاري الحفظ...');

            $.ajax({
                url: "{{ route('question.update.timings.by.points') }}",
                type: "POST",
                data: {
                    points: points,
                    time_counter: timeCounter,
                    time_counter_online: timeCounterOnline
                },
                success: function(response) {
                    $('#btn-save-timings').prop('disabled', false).html('<i class="bx bx-save"></i> حفظ التوقيتات');
                    if (response.success) {
                        toastr.success(response.message);
                        fetchQuestions(true);
                    } else {
                        toastr.error('حدث خطأ أثناء حفظ التوقيتات.');
                    }
                },
                error: function(xhr) {
                    $('#btn-save-timings').prop('disabled', false).html('<i class="bx bx-save"></i> حفظ التوقيتات');
                    let errors = xhr.responseJSON;
                    if (errors && errors.message) {
                        toastr.error(errors.message);
                    } else {
                        toastr.error('حدث خطأ أثناء حفظ التوقيتات.');
                    }
                }
            });
        });

        $(window).scroll(function() {
            if($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
                fetchQuestions();
            }
        });

        // Click Preview Logic for Modal
        $(document).on('click', '.view-preview-btn', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            let content = $('#details-' + id).html();
            $('#modal-preview-content').html(content);
            
            // Show the modal
            $('#questionDetailsModal').modal('show');
        });

        // Handle Select All checkbox
        $(document).on('change', '#select-all, #select-all-footer', function() {
            let isChecked = $(this).prop('checked');
            $('.question-checkbox').prop('checked', isChecked);
            $('#select-all, #select-all-footer').prop('checked', isChecked);
            toggleDeleteButton();
        });

        // Handle individual checkboxes
        $(document).on('change', '.question-checkbox', function() {
            if ($('.question-checkbox:checked').length == $('.question-checkbox').length) {
                $('#select-all, #select-all-footer').prop('checked', true);
            } else {
                $('#select-all, #select-all-footer').prop('checked', false);
            }
            toggleDeleteButton();
        });

        function toggleDeleteButton() {
            if ($('.question-checkbox:checked').length > 0) {
                $('#delete-selected').show();
            } else {
                $('#delete-selected').hide();
            }
        }

        // Handle Delete Selected button
        $('#delete-selected').click(function() {
            let selectedIds = [];
            $('.question-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length > 0) {
                if (confirm('هل أنت متأكد من حذف الأسئلة المحددة؟')) {
                    $.ajax({
                        url: "{{ route('delete.multiple.questions') }}",
                        type: "POST",
                        data: {
                            ids: selectedIds
                        },
                        success: function(response) {
                            alert(response.success);
                            fetchQuestions(true); // Reload questions
                            $('#delete-selected').hide();
                            $('#select-all, #select-all-footer').prop('checked', false);
                        },
                        error: function(xhr) {
                            alert('حدث خطأ أثناء عملية الحذف.');
                        }
                    });
                }
            }
        });

        // Handle media preview triggers
        $(document).on('click', '.media-preview-trigger', function() {
            let type = $(this).data('type');
            let src = $(this).data('src');
            let contentHtml = '';

            if (type === 'image') {
                contentHtml = '<img src="' + src + '" class="img-fluid rounded shadow-lg" style="max-height: 75vh; width: auto; object-fit: contain;">';
            } else if (type === 'video') {
                contentHtml = '<video controls autoplay src="' + src + '" class="w-100 rounded shadow-lg" style="max-height: 75vh; outline: none;"></video>';
            }

            $('#media-preview-body').html(contentHtml);
            $('#mediaPreviewModal').modal('show');
        });

        // Pause/stop video when modal is closed
        $('#mediaPreviewModal').on('hide.bs.modal', function () {
            $('#media-preview-body').empty();
        });
    });
</script>

@endsection
