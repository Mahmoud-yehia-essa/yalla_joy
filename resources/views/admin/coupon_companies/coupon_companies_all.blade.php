@extends('admin.master_admin')
@section('admin')

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">كوبونات الشركات</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">جميع كوبونات الشركات</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="d-flex gap-2">
                <a href="{{ route('export.coupon_companies') }}" class="btn btn-success px-3 d-flex align-items-center gap-1">
                    <i class="bx bx-download"></i> تصدير إلى Excel
                </a>
                <a href="{{ route('add.coupon_companies') }}" class="btn btn-primary px-3 d-flex align-items-center gap-1">
                    <i class="bx bx-plus"></i> إضافة كوبون جديد
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
                            <th>اسم الكوبون</th>
                            <th>الكود</th>
                            <th>الشركة</th>
                            <th>تاريخ الانتهاء</th>
                            <th>تكلفة الشراء</th>
                            <th>نوع الكوبون</th>
                            <th>كوبون خاص؟</th>
                            <th>الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($couponCompanies as $key => $item)
                        <tr>
                            <td> {{ $key+1 }} </td>
                            <td> {{ $item->coupon_name }} <br> <small>{{ $item->coupon_name_en }}</small></td>
                            <td> <span class="badge bg-primary">{{ $item->coupon_code }}</span> </td>
                            <td> {{ $item->sponsor->title ?? 'N/A' }} </td>
                            <td> {{ $item->valid_until ? Carbon\Carbon::parse($item->valid_until)->format('Y-m-d') : 'دائم' }} </td>
                            <td> 
                                @if($item->gameCoin)
                                    {{ $item->game_coins_count }} ({{ $item->gameCoin->name }})
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($item->is_scratch_coupon)
                                    <span class="badge bg-info">كوبون قشط</span>
                                @else
                                    <span class="badge bg-secondary">كوبون عادي</span>
                                @endif
                            </td>
                            <td>
                                @if($item->is_special_coupon)
                                    <span class="badge bg-success">نعم</span>
                                @else
                                    <span class="badge bg-danger">لا</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('edit.coupon_companies', $item->id) }}" class="btn btn-info btn-sm">تعديل</a>
                                <a href="{{ route('delete.coupon_companies', $item->id) }}" class="btn btn-danger btn-sm" id="delete">حذف</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
