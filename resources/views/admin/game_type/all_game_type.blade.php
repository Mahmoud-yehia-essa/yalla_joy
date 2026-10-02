@extends('admin.master_admin')
@section('admin')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">كل الأنواع</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">

        </nav>
    </div>
    <div class="ms-auto">
        <div class="btn-group">
            <a href="{{route('add.game.type')}}" >

<button type="button" class="btn btn-primary">

    اضافة نوع جديد

</button>
</a>


        </div>
    </div>
</div>
<!--end breadcrumb-->

<hr/>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
<tr>
<th>الرقم</th>
<th>game_type_id</th>

<th>نوع اللعبة</th>
<th>عدد الفئات الرئيسية في نوع اللعبة</th>
<th>العملات المطلوبة للعب</th>

<th> الصورة</th>
<th>الاجراء</th>
</tr>
</thead>
<tbody>
@foreach($gameType as $key => $item)
<tr>
<td> {{ $key+1 }} </td>
<td>{{ $item->id }} </td>

<td>{{ $item->type_name }} </td>
<td style="width: 50px; font-size: 1.1rem;"><span class="badge  bg-dark">
    {{count($item->mainCategories)}}

</span></td>

<td>
    @php
        $hasCoins = false;
    @endphp
    <div class="d-flex flex-column gap-1" style="min-width: 170px; font-size: 0.8rem;">
        @if($item->offlineGameCoin && $item->offline_coins_number > 0)
            @php $hasCoins = true; @endphp
            <div class="d-flex align-items-center justify-content-between bg-primary-subtle text-primary px-2 py-0.5 rounded border border-primary-subtle">
                <span><i class="bx bx-group me-1"></i> الجلسة:</span>
                <span class="fw-bold">{{ $item->offline_coins_number }} {{ $item->offlineGameCoin->name }}</span>
            </div>
        @endif

        @if($item->onlineSearchGameCoin && $item->online_search_coins_number > 0)
            @php $hasCoins = true; @endphp
            <div class="d-flex align-items-center justify-content-between bg-info-subtle text-info px-2 py-0.5 rounded border border-info-subtle">
                <span><i class="bx bx-search-alt me-1"></i> بحث أونلاين:</span>
                <span class="fw-bold">{{ $item->online_search_coins_number }} {{ $item->onlineSearchGameCoin->name }}</span>
            </div>
        @endif

        @if($item->onlineCreateGameCoin && $item->online_create_coins_number > 0)
            @php $hasCoins = true; @endphp
            <div class="d-flex align-items-center justify-content-between bg-success-subtle text-success px-2 py-0.5 rounded border border-success-subtle">
                <span><i class="bx bx-grid-alt me-1"></i> إنشاء كاملة (6):</span>
                <span class="fw-bold">{{ $item->online_create_coins_number }} {{ $item->onlineCreateGameCoin->name }}</span>
            </div>
        @endif

        @if($item->onlineChallengeGameCoin && $item->online_challenge_coins_number > 0)
            @php $hasCoins = true; @endphp
            <div class="d-flex align-items-center justify-content-between bg-warning-subtle text-warning-emphasis px-2 py-0.5 rounded border border-warning-subtle">
                <span><i class="bx bx-user-plus me-1"></i> تحدي صديق (3):</span>
                <span class="fw-bold">{{ $item->online_challenge_coins_number }} {{ $item->onlineChallengeGameCoin->name }}</span>
            </div>
        @endif

        @if($item->topScorersGameCoin && $item->top_scorers_coins_number > 0)
            @php $hasCoins = true; @endphp
            <div class="d-flex align-items-center justify-content-between bg-danger-subtle text-danger px-2 py-0.5 rounded border border-danger-subtle">
                <span><i class="bx bx-trophy me-1"></i> التحديات:</span>
                <span class="fw-bold">{{ $item->top_scorers_coins_number }} {{ $item->topScorersGameCoin->name }}</span>
            </div>
        @endif

        @if(!$hasCoins)
            @if($item->gameCoin && $item->coins_number > 0)
                <div class="d-flex align-items-center justify-content-between bg-secondary-subtle text-secondary px-2 py-0.5 rounded border">
                    <span>افتراضي:</span>
                    <span class="fw-bold">{{ $item->coins_number }} {{ $item->gameCoin->name }}</span>
                </div>
            @else
                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 text-center">
                    <i class="bx bx-check-circle me-1"></i> مجاناً (بدون عملات)
                </span>
            @endif
        @endif
    </div>
</td>

<td> <img onclick="showImageModal(this.src)" src="{{ asset($item->type_photo) }}" style="width: 70px; height:40px; cursor: pointer;" >  </td>

<td>





    @if($item->status == 'active')
    <a href="{{ route('inactive.game.type', $item->id) }}" class="btn btn-primary" title="اخفاء">
        <i class="fa-solid fa-eye"></i>
    </a>
@else
    <a href="{{ route('active.game.type', $item->id) }}" class="btn btn-primary" title="اظهار">

        <i class="fa-solid fa-eye-slash"></i>

    </a>
@endif
<a href="{{route('edit.game.type',$item->id)}}" class="btn btn-info">تعديل</a>
<a href="{{ route('delete.game.type',$item->id) }}" class="btn btn-danger" id="delete" >حذف</a>

@if($item->is_kids == 1)
<span class="badge bg-success" title="للأطفال">
    <i class="fa-solid fa-child"></i> للأطفال
</span>
@endif
@if($item->is_term == 1)
<span class="badge bg-info" title="يوجد ترم اخر">
    <i class="fa-solid fa-plus"></i> ترم اخر
</span>
@endif
</td>
</tr>
@endforeach


</tbody>
<tfoot>
<tr>


    <th>الرقم</th>
    <th>game_type_id</th>

<th>نوع اللعبة</th>
<th>عدد الفئات في النوع</th>
<th>العملات المطلوبة للعب</th>

<th> الصورة</th>
<th>الاجراء</th>
</tr>
</tfoot>
</table>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content position-relative bg-transparent border-0">

        <!-- Rectangular Close Button -->
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
        <img id="modalImage" src="" class="img-fluid rounded shadow"  alt="image">
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
