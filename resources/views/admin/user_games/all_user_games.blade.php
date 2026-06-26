@extends('admin.master_admin')
@section('admin')
<style>

.rating-stars i {
    margin-right: 2px;
    font-size: 1rem;
}

</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">ألعاب المستخدمين</div>
    <div class="ms-auto">
        <div class="btn-group">
            <a href="{{ route('add.user.game') }}">
                <button type="button" class="btn btn-primary">إضافة لعبة جديدة</button>
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
                        <th>#</th>
                        <th>اسم اللعبة</th>
                        <th>تم عمل اللعبة بواسطة</th>

                        <th>الخصوصية</th>



                        <th>الحالة</th>
                        <th>الصورة</th>
                        <th>تعديل الحالة</th>

                        <th>الإجراءات</th>

                         <th>عدد مستخدمي اللعبة</th>

                        <th>تقيم اللعبة</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($games as $key => $game)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $game->name }}</td>
                                                        <td>

                                                            <a href="{{ route('edit.user',$game->user_id) }}">
                                                            {{ $game->user->fname }}

                                                            </a>

                                                        </td>

                            <td>
                                @if($game->privacy == 'public')
                                    <span class="badge bg-success">عام</span>
                                @else
                                    <span class="badge bg-secondary">خاص</span>
                                @endif
                            </td>


                            <td>
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'published' => 'success',
                                        'canceled' => 'danger',
                                        'deleted' => 'dark',
                                        'suspended' => 'secondary'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$game->status] ?? 'info' }}">{{ $game->status }}</span>
                            </td>

                              <td>
                                @if($game->photo && file_exists(public_path($game->photo)))
                                    <img onclick="showImageModal(this.src)" src="{{ asset($game->photo) }}" style="width: 70px; height:40px; cursor: pointer;" >
                                @else
                                    <span>—</span>
                                @endif
                            </td>
                            <td>
    @if($game->status == 'pending')
        <a href="{{ route('publish.user.game', $game->id) }}" class="btn btn-success btn-sm">
            <i class="fa-solid fa-check"></i> نشر
        </a>
        <a href="{{ route('cancel.user.game', $game->id) }}" class="btn btn-warning btn-sm">
            <i class="fa-solid fa-ban"></i> إلغاء
        </a>
    @elseif($game->status == 'published')
        <a href="{{ route('suspend.user.game', $game->id) }}" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-pause"></i> تعليق
        </a>
         <a href="{{ route('cancel.user.game', $game->id) }}" class="btn btn-warning btn-sm">
            <i class="fa-solid fa-ban"></i> إلغاء
        </a>
    @elseif($game->status == 'suspended')
        <a href="{{ route('publish.user.game', $game->id) }}" class="btn btn-success btn-sm">
            <i class="fa-solid fa-play"></i> إعادة نشر
        </a>

         @elseif($game->status == 'canceled')
        <a href="{{ route('publish.user.game', $game->id) }}" class="btn btn-success btn-sm">
            <i class="fa-solid fa-play"></i> إعادة نشر
        </a>
    @endif

</td>

                            <td>
                                <a href="{{ route('edit.user.game', $game->id) }}" class="btn btn-info" title="تعديل">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="{{ route('delete.user.game', $game->id) }}" class="btn btn-danger" id="delete" title="حذف">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                                <a href="{{ route('user.game.questions', $game->id) }}" class="btn btn-success" title="الأسئلة">
                                    <i class="fa-solid fa-circle-question"></i>
                                </a>
                            </td>


                                                        <td>{{ $game->users_player_count }}</td>


                                              <td>
    @php
        $rate = $game->rate ?? 0; // افتراضياً 0 إذا لم يوجد تقييم
        $fullStars = floor($rate); // عدد النجوم الكاملة
        $halfStar = ($rate - $fullStars >= 0.5) ? true : false; // نجمة نصفية
        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0); // عدد النجوم الفارغة
    @endphp

    <span class="rating-stars">
        @for($i=0; $i<$fullStars; $i++)
            <i class="fa-solid fa-star text-warning"></i>
        @endfor
        @if($halfStar)
            <i class="fa-solid fa-star-half-stroke text-warning"></i>
        @endif
        @for($i=0; $i<$emptyStars; $i++)
            <i class="fa-regular fa-star text-warning"></i>
        @endfor
    </span>
</td>


                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content position-relative bg-transparent border-0">
            <button type="button" class="btn text-white" data-bs-dismiss="modal" aria-label="Close"
                style="position: absolute; top: 15px; right: 15px; background-color: black; font-size: 30px; padding: 1px 10px; border-radius: 8px; z-index: 1055;">
                &times;
            </button>
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
