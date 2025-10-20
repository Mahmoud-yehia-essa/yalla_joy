@extends('admin.master_admin')
@section('admin')

<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">كل المستويات</div>
    <div class="ms-auto">
        <div class="btn-group">
            <a href="{{route('add.level')}}">
                <button type="button" class="btn btn-primary">إضافة مستوى جديد</button>
            </a>
        </div>
    </div>
</div>

<hr/>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>الرقم</th>
                        <th>الاسم</th>
                        <th>الوصف</th>
                        <th>العملة المرتبطة</th>
                        <th>عدد العملات</th>
                        <th>الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($levels as $key => $item)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ Str::limit($item->description, 50) }}</td>
                        <td>{{ $item->gameCoin?->name }}</td>
                        <td>{{ $item->coins_number }}</td>
                        <td>
                            <a href="{{ route('edit.level', $item->id) }}" class="btn btn-info">تعديل</a>
                            <a href="{{ route('delete.level', $item->id) }}" class="btn btn-danger" id="delete">حذف</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>الرقم</th>
                        <th>الاسم</th>
                        <th>الوصف</th>
                        <th>العملة المرتبطة</th>
                        <th>عدد العملات</th>
                        <th>الإجراء</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@endsection
