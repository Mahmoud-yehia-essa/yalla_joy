@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Load Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
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

<div class="col-lg-12">
    <form action="{{ route('store.notification') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">المستلم</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <select name="user_id" id="user-select" class="form-select" required>
                            <option value="all">كل المستخدمين (إرسال للجميع)</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ trim(($user->fname ?? '') . ' ' . ($user->lname ?? '')) ?: ($user->user_name ?? $user->email) }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">عنوان الاشعار</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <input type="text" class="form-control" name="title" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">رقم الـ Badge <small class="text-muted">(يظهر على أيقونة التطبيق)</small></h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <input type="number" class="form-control" name="badge" min="0" placeholder="0" value="1">
                        <small class="text-muted">الرقم الذي يظهر على أيقونة التطبيق من الخارج. اتركه 0 لإخفائه.</small>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">التفاصيل (HTML) <small class="text-muted">(لن تُرسل في الإشعار، تُخزن فقط)</small></h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <textarea id="elm1" name="description"></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-9 text-secondary">
                        <input type="submit" class="btn btn-primary px-4" value="ارسال الاشعار">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    window.addEventListener('load', function() {
        $.getScript("https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js", function() {
            $('#user-select').select2({
                placeholder: "اختر مستخدم لإرسال الإشعار له",
                allowClear: false,
                width: '100%',
                dir: 'rtl'
            });
        });
    });
</script>
@endsection
