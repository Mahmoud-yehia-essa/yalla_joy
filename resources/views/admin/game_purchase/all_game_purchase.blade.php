@extends('admin.master_admin')
@section('admin')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">جميع القيمة النقدية للألعاب</div>
    <div class="ms-auto">
        <div class="btn-group">
            <a href="{{ route('add.game.purchase') }}">
                <button type="button" class="btn btn-primary">إضافة قيمة نقدية جديدة للألعاب</button>
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
                        <th>عدد الألعاب</th>
                        <th>السعر النقدى</th>
                        <th>الاجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchases as $key => $item)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $item->games_count ?? 1 }} ألعاب</td>
                            <td>{{ number_format($item->price, 2) }}</td>
                            <td>
                                @if($item->status == 'active')
                                    <a href="{{ route('inactive.game.purchase', $item->id) }}" class="btn btn-primary" title="اخفاء">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                @else
                                    <a href="{{ route('active.game.purchase', $item->id) }}" class="btn btn-primary" title="اظهار">
                                        <i class="fa-solid fa-eye-slash"></i>
                                    </a>
                                @endif
                                <a href="{{ route('edit.game.purchase', $item->id) }}" class="btn btn-info">تعديل</a>
                                <a href="{{ route('delete.game.purchase', $item->id) }}" class="btn btn-danger" id="delete">حذف</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>الرقم</th>
                        <th>عدد الألعاب</th>
                        <th>السعر النقدى</th>
                        <th>الاجراء</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@endsection
