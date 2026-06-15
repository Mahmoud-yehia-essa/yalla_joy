@extends('admin.master_admin')
@section('admin')

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">سجل استخدام الكوبونات</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">سجل الاستخدام</li>
                </ol>
            </nav>
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
                            <th>المستخدم</th>
                            <th>رقم الهاتف</th>
                            <th>الكوبون</th>
                            <th>كود الكوبون</th>
                            <th>الشركة</th>
                            <th>حالة الشراء</th>
                            <th>تاريخ الشراء</th>
                            <th>حالة الاستخدام</th>
                            <th>تاريخ الاستخدام</th>
                            <th>الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usedCoupons as $key => $item)
                        <tr>
                            <td> {{ $key+1 }} </td>
                            <td> 
                                @if($item->user)
                                    <a href="{{ route('edit.user', $item->user->id) }}">
                                        {{ $item->user->fname }} {{ $item->user->lname }} 
                                        @if($item->user->username)
                                            ({{ $item->user->username }})
                                        @endif
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td> {{ $item->user->phone ?? 'N/A' }} </td>
                            <td> {{ $item->couponCompany->coupon_name ?? 'N/A' }} </td>
                            <td> <span class="badge bg-primary">{{ $item->couponCompany->coupon_code ?? 'N/A' }}</span> </td>
                            <td> {{ $item->couponCompany->sponsor->title ?? 'N/A' }} </td>
                            <td>
                                @if($item->is_buy)
                                    <span class="badge bg-success">تم الشراء</span>
                                @else
                                    <span class="badge bg-secondary">لم يتم الشراء</span>
                                @endif
                            </td>
                            <td> {{ $item->created_at->format('Y-m-d H:i:s') }} </td>
                            <td>
                                @if($item->is_used)
                                    <span class="badge bg-danger">تم الاستخدام</span>
                                @else
                                    <span class="badge bg-warning text-dark">غير مستخدم</span>
                                @endif
                            </td>
                            <td> {{ $item->used_at ? Carbon\Carbon::parse($item->used_at)->format('Y-m-d H:i:s') : '-' }} </td>
                            <td>
                                <a href="{{ route('delete.used_coupon', $item->id) }}" class="btn btn-danger btn-sm" id="delete">حذف السجل</a>
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
