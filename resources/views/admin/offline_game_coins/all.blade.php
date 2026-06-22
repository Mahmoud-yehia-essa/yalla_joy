@extends('admin.master_admin')
@section('admin')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">عملات فائز في لعبة الجلسة</div>
    <div class="ms-auto">
        <div class="btn-group">
            <a href="{{ route('add.offline.game.coins') }}">
                <button type="button" class="btn btn-primary">إضافة عملات فائز في لعبة الجلسة</button>
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
                        <th>اسم البند</th>
                        <th>عملة اللعبة</th>
                        <th>عدد العملات</th>
                        <th>الاجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($offlineCoins as $key => $item)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->gameCoin->name ?? 'N/A' }}</td>
                            <td>{{ $item->coins_number ?? 0 }}</td>
                            <td>
                                <a href="{{ route('edit.offline.game.coins', $item->id) }}" class="btn btn-info">تعديل</a>
                                <a href="{{ route('delete.offline.game.coins', $item->id) }}" class="btn btn-danger" id="delete">حذف</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>الرقم</th>
                        <th>اسم البند</th>
                        <th>عملة اللعبة</th>
                        <th>عدد العملات</th>
                        <th>الاجراء</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@endsection
