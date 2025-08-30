@extends('admin.master_admin')
@section('admin')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">كل عناصر اللعبة الرئيسية</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb"></nav>
    </div>
    <div class="ms-auto">
        <div class="btn-group">
            <a href="{{ route('add.game.element') }}">
                <button type="button" class="btn btn-primary">
                    إضافة عنصر لعبة جديد
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
                        <th>الاسم (عربي)</th>
                        <th>الاسم (إنجليزي)</th>
                        <th>الوصف</th>
                        <th>الوصف (EN)</th>
                        <th>الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($elements as $key => $item)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->name_en }}</td>
                            <td>{{ Str::limit($item->description, 30) }}</td>
                            <td>{{ Str::limit($item->description_en, 30) }}</td>
                            <td>
                                @if($item->status == 'active')
                                    <a href="{{ route('inactive.game.element', $item->id) }}" class="btn btn-primary" title="إخفاء">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                @else
                                    <a href="{{ route('active.game.element', $item->id) }}" class="btn btn-primary" title="إظهار">
                                        <i class="fa-solid fa-eye-slash"></i>
                                    </a>
                                @endif

                                <a href="{{ route('edit.game.element',$item->id) }}" class="btn btn-info">تعديل</a>
                                <a href="{{ route('delete.game.element',$item->id) }}" class="btn btn-danger" id="delete">حذف</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>الرقم</th>
                        <th>الاسم (عربي)</th>
                        <th>الاسم (إنجليزي)</th>
                        <th>الوصف</th>
                        <th>الوصف (EN)</th>
                        <th>الإجراء</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@endsection
