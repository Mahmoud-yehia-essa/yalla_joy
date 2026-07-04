@extends('admin.master_admin')
@section('admin')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">جميع كوبونات ألعاب الفيديو</div>
    <div class="ms-auto">
        <div class="d-flex gap-2">
            <a href="{{ route('export.game.coupon') }}" class="btn btn-success px-3 d-flex align-items-center gap-1">
                <i class="bx bx-download"></i> تصدير إلى Excel
            </a>
            <a href="{{ route('add.game.coupon') }}" class="btn btn-primary px-3 d-flex align-items-center gap-1">
                <i class="bx bx-plus"></i> إضافة كوبون ألعاب جديد
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
                        <th>كود الكوبون</th>
                        <th>النوع</th>
                        <th>التأثير / القيمة</th>
                        <th>باقة الشراء المرتبطة</th>
                        <th>الحد الأقصى / الاستخدام</th>
                        <th>تاريخ الانتهاء</th>
                        <th>الحالة</th>
                        <th>الاجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($coupons as $key => $item)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td><strong class="text-primary">{{ $item->code }}</strong></td>
                            <td>
                                @if($item->type == 'percentage')
                                    <span class="badge bg-info">خصم نسبة مئوية</span>
                                @elseif($item->type == 'free_games')
                                    <span class="badge bg-success">ألعاب مجانية</span>
                                @elseif($item->type == 'package_bonus')
                                    <span class="badge bg-primary">مكافأة باقة</span>
                                @endif
                            </td>
                            <td>
                                @if($item->type == 'percentage')
                                    {{ $item->discount_percentage }}%
                                @elseif($item->type == 'free_games' || $item->type == 'package_bonus')
                                    {{ $item->free_games_count }} ألعاب
                                @endif
                            </td>
                            <td>
                                @if($item->gamePurchase)
                                    {{ $item->gamePurchase->games_count }} ألعاب بسعر {{ $item->gamePurchase->price }}
                                @else
                                    <span class="text-muted">غير مرتبط</span>
                                @endif
                            </td>
                            <td>
                                @if($item->usage_limit)
                                    {{ $item->used_count }} / {{ $item->usage_limit }}
                                @else
                                    {{ $item->used_count }} / ∞
                                @endif
                            </td>
                            <td>
                                {{ $item->expires_at ? \Carbon\Carbon::parse($item->expires_at)->format('Y-m-d H:i') : 'غير محدد' }}
                            </td>
                            <td>
                                @if($item->is_active)
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-danger">معطل</span>
                                @endif
                            </td>
                            <td>
                                @if($item->is_active)
                                    <a href="{{ route('inactive.game.coupon', $item->id) }}" class="btn btn-warning btn-sm" title="تعطيل الكوبون">
                                        <i class="fa-solid fa-thumbs-down"></i>
                                    </a>
                                @else
                                    <a href="{{ route('active.game.coupon', $item->id) }}" class="btn btn-success btn-sm" title="تفعيل الكوبون">
                                        <i class="fa-solid fa-thumbs-up"></i>
                                    </a>
                                @endif
                                <a href="{{ route('edit.game.coupon', $item->id) }}" class="btn btn-info btn-sm" title="تعديل">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a href="{{ route('delete.game.coupon', $item->id) }}" class="btn btn-danger btn-sm" id="delete" title="حذف">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>الرقم</th>
                        <th>كود الكوبون</th>
                        <th>النوع</th>
                        <th>التأثير / القيمة</th>
                        <th>باقة الشراء المرتبطة</th>
                        <th>الحد الأقصى / الاستخدام</th>
                        <th>تاريخ الانتهاء</th>
                        <th>الحالة</th>
                        <th>الاجراء</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@endsection
