@extends('admin.master_admin')
@section('admin')
<style>
    td.question-column {
        /* white-space: normal;
        word-break: break-word; */
        /* max-width: 50px; Adjust the width as needed */
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
            <a href="{{route('add.question')}}" >
<button type="button" class="btn btn-primary">
    اضافة سؤال جديد
</button>
</a>
        </div>
    </div>
</div>
<!--end breadcrumb-->

<hr/>
<div class="card">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-4">
                <input type="text" id="search-input" class="form-control" placeholder="البحث في الأسئلة...">
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        let page = 2; // Page 1 is already loaded by backend
        let hasMore = {{ $questions->hasMorePages() ? 'true' : 'false' }};
        let loading = false;
        let searchTimeout;

        function fetchQuestions(reset = false) {
            if (loading || (!hasMore && !reset)) return;
            loading = true;
            
            if (reset) {
                page = 1;
                hasMore = true;
                $('#questions-tbody').empty();
            }

            let searchQuery = $('#search-input').val();
            let categoryId = new URLSearchParams(window.location.search).get('category_id');
            $('#loading-spinner').show();

            $.ajax({
                url: "{{ route('all.question') }}",
                type: "GET",
                data: {
                    page: page,
                    search: searchQuery,
                    category_id: categoryId
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
                },
                error: function(xhr) {
                    loading = false;
                    $('#loading-spinner').hide();
                    console.log(xhr.responseText);
                }
            });
        }

        $('#search-input').on('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                fetchQuestions(true);
            }, 500);
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
    });
</script>

@endsection
