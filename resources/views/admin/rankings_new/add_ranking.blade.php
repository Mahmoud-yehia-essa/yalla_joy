@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">اضافة رتبة جديدة</div>
    </div>

    <div class="container">
        <div class="main-body">
            <div class="row">
                <div class="col-lg-10">
                    <div class="card">
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="post" action="{{ route('add.ranking.new.store') }}">
                                @csrf

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">اسم الرتبة (عربي)</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="rank_name" class="form-control" value="{{ old('rank_name') }}" />
                                        @error('rank_name')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">Rank Name (English)</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" dir="ltr" name="rank_name_en" class="form-control" value="{{ old('rank_name_en') }}" />
                                        @error('rank_name_en')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">وصف الرتبة (عربي)</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <textarea name="rank_description" class="form-control">{{ old('rank_description') }}</textarea>
                                        @error('rank_description')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">Rank Description (English)</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <textarea dir="ltr" name="rank_description_en" class="form-control">{{ old('rank_description_en') }}</textarea>
                                        @error('rank_description_en')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">ترتيب الرتبة</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="number" name="rank_order" class="form-control" value="{{ old('rank_order') }}" />
                                        @error('rank_order')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">هل هذه الرتبة الأخيرة؟</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_last" value="1" id="flexSwitchCheckDefault">
                                            <label class="form-check-label" for="flexSwitchCheckDefault">نعم</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">هل هي مجانية؟</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_free" value="1" id="isFreeSwitch">
                                            <label class="form-check-label" for="isFreeSwitch">نعم</label>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">العملة عند الوصول للرتبة</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <select name="rank_reward_coin_id" class="form-select mb-3" aria-label="Default select example">
                                            <option selected="" value="">اختر العملة</option>
                                            @foreach($coins as $coin)
                                            <option value="{{ $coin->id }}">{{ $coin->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">عدد العملات المكتسبة عند الوصول</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="number" name="rank_reward_amount" class="form-control" value="{{ old('rank_reward_amount') }}" />
                                    </div>
                                </div>

                                <hr>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">عدد المستويات داخل الرتبة</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="number" name="levels_count" id="levels_count" class="form-control" value="{{ old('levels_count') }}" />
                                        @error('levels_count')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">عدد مرات الفوز للوصول للمستوى التالي</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="number" name="wins_to_next_level" id="wins_to_next_level" class="form-control" value="{{ old('wins_to_next_level', 0) }}" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">إجمالي مرات الفوز للانتقال للرتبة التالية</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="number" id="total_wins_preview" class="form-control bg-light" value="0" readonly />
                                        <small class="text-muted">يتم حسابه تلقائياً = (عدد المستويات × مرات الفوز للمستوى). القيمة النهائية ستُحسب تراكمياً مع بقية الرتب عند الحفظ.</small>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">العملة عند الانتقال بين المستويات</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <select name="level_reward_coin_id" class="form-select mb-3" aria-label="Default select example">
                                            <option selected="" value="">اختر العملة</option>
                                            @foreach($coins as $coin)
                                            <option value="{{ $coin->id }}">{{ $coin->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3"><h6 class="mb-0">عدد العملات المكتسبة للمستوى</h6></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="number" name="level_reward_amount" class="form-control" value="{{ old('level_reward_amount') }}" />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="submit" class="btn btn-primary px-4" value="اضافة رتبة" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        function calcTotalWins() {
            var levels = parseInt($('#levels_count').val()) || 0;
            var wins   = parseInt($('#wins_to_next_level').val()) || 0;
            $('#total_wins_preview').val(levels * wins);
        }

        $('#levels_count, #wins_to_next_level').on('input change', calcTotalWins);

        // حساب أوَّلي عند تحميل الصفحة
        calcTotalWins();
    });
</script>
@endsection
