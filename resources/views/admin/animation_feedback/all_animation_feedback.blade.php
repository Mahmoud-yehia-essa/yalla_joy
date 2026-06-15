@extends('admin.master_admin')
@section('admin')
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">كل حركات الأنيميشن</div>
    <div class="ms-auto">
        <div class="btn-group">
            <a href="{{route('add.animation')}}" >
                <button type="button" class="btn btn-primary">
                    اضافة حركة جديدة
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
                            <th>النوع</th>
                            <th>الحركة (أنيميشن)</th>
                            <th>الصوت</th>
                            <th>اسم الحركة</th>
                        <th>الرتبة</th>
                        <th>مجانية؟</th>
                        <th>العملة / العدد</th>
                        <th>الاجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($animations as $key => $item)
                        <tr>
                            <td> {{ $key+1 }} </td>
                            <td>
                                @if($item->type == 'positive')
                                    <span class="badge bg-success">إيجابية</span>
                                @else
                                    <span class="badge bg-danger">سلبية</span>
                                @endif
                            </td>
                            <td>
                                @if($item->file_path)
                                <div class="lottie-container" data-src="{{ asset($item->file_path) }}" data-audio="{{ $item->audio ? asset($item->audio) : '' }}" style="width: 80px; height: 80px; margin: 0 auto; display: block; overflow: hidden; position: relative; z-index: 1; cursor: pointer;" title="اضغط للتكبير">
                                </div>
                                @else
                                <span class="text-danger">لا يوجد</span>
                                @endif
                            </td>
                            <td>
                                @if($item->audio)
                                <audio controls style="width: 200px;">
                                    <source src="{{ asset($item->audio) }}">
                                </audio>
                                @else
                                <span class="text-danger">لا يوجد</span>
                                @endif
                            </td>
                            <td>{{ $item->name }} <br> <small>{{ $item->name_en }}</small></td>
                            <td>{{ $item->rankingNew ? $item->rankingNew->rank_name : 'غير محدد' }}</td>
                            <td>
                                @if($item->is_free == 1)
                                    <span class="badge bg-success">نعم</span>
                                @else
                                    <span class="badge bg-danger">لا</span>
                                @endif
                            </td>
                            <td>
                                @if($item->is_free == 0 && $item->coin)
                                    {{ $item->coin->name }} <br> <small>{{ $item->coin_amount }}</small>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <a href="{{route('edit.animation',$item->id)}}" class="btn btn-info">تعديل</a>
                                <a href="{{ route('delete.animation',$item->id) }}" class="btn btn-danger" id="delete" >حذف</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>الرقم</th>
                        <th>النوع</th>
                        <th>الحركة (أنيميشن)</th>
                        <th>الصوت</th>
                        <th>اسم الحركة</th>
                        <th>الرتبة</th>
                        <th>مجانية؟</th>
                        <th>العملة / العدد</th>
                        <th>الاجراء</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Animation Preview Modal -->
<div class="modal fade" id="animationPreviewModal" tabindex="-1" aria-labelledby="animationPreviewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="animationPreviewModalLabel">معاينة الحركة</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center" id="modalLottieBody">
        <!-- Lottie player will be injected here -->
      </div>
    </div>
  </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        function renderLotties() {
            $('.lottie-container').each(function() {
                var src = $(this).data('src');
                if ($(this).is(':empty') || !$(this).find('lottie-player').length) {
                    $(this).html('<lottie-player src="' + src + '" background="transparent" speed="1" style="width: 100%; height: 100%;" loop autoplay></lottie-player>');
                }
            });
        }

        // Render on first load with a slight delay to allow DataTables to initialize
        setTimeout(renderLotties, 300);

        // Render whenever DataTables redraws (pagination, sort, search)
        $('#example').on('draw.dt', function () {
            renderLotties();
        });

        // Handle click on animation to open modal
        $(document).on('click', '.lottie-container', function() {
            var src = $(this).data('src');
            var audio = $(this).data('audio');
            
            var modalHtml = '<lottie-player src="' + src + '" background="transparent" speed="1" style="width: 300px; height: 300px; margin: 0 auto;" loop autoplay></lottie-player>';
            
            if (audio) {
                modalHtml += '<audio controls autoplay loop style="width: 100%; margin-top: 15px;"><source src="' + audio + '"></audio>';
            }

            $('#modalLottieBody').html(modalHtml);
            $('#animationPreviewModal').modal('show');
        });

        // Stop animation when modal is closed
        $('#animationPreviewModal').on('hidden.bs.modal', function () {
            $('#modalLottieBody').empty();
        });
    });
</script>

@endsection
