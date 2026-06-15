@extends('admin.master_admin')
@section('admin')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">كل الرتب</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
        </nav>
    </div>
    <div class="ms-auto">
        <div class="btn-group">
            <a href="{{route('add.ranking.new')}}" >
                <button type="button" class="btn btn-primary">
                    اضافة رتبة جديدة
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
                        <th>اسم الرتبة</th>
                        <th>ترتيب الرتبة</th>
                        <th>عملة الوصول</th>
                        <th>العدد (الوصول)</th>
                        <th>الرتبة الأخيرة؟</th>
                        <th>هل هي مجانية؟</th>
                        <th>عدد المستويات</th>
                        <th>الفوز (للمستوى)</th>
                        <th>إجمالي الفوز للرتبة التالية</th>
                        <th>عملة الانتقال</th>
                        <th>العدد (الانتقال)</th>
                        <th>الاجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rankings as $key => $item)
                        <tr>
                            <td> {{ $key+1 }} </td>
                            <td>{{ $item->rank_name }} <br> <small>{{ $item->rank_name_en }}</small></td>
                            <td>{{ $item->rank_order }}</td>
                            <td>{{ $item->rankRewardCoin ? $item->rankRewardCoin->name : 'لا يوجد' }}</td>
                            <td>{{ $item->rank_reward_amount }}</td>
                            <td>
                                @if($item->is_last == 1)
                                    <span class="badge bg-success">نعم</span>
                                @else
                                    <span class="badge bg-danger">لا</span>
                                @endif
                            </td>
                            <td>
                                @if($item->is_free == 1)
                                    <span class="badge bg-success">نعم</span>
                                @else
                                    <span class="badge bg-danger">لا</span>
                                @endif
                            </td>
                            <td>{{ $item->levels_count }}</td>
                            <td>{{ $item->wins_to_next_level }}</td>
                            <td><span class="badge bg-primary" style="font-size: 14px;">{{ $item->total_wins_to_next_rank }}</span></td>
                            <td>{{ $item->levelRewardCoin ? $item->levelRewardCoin->name : 'لا يوجد' }}</td>
                            <td>{{ $item->level_reward_amount }}</td>
                            <td>
                                <a href="{{route('edit.ranking.new',$item->id)}}" class="btn btn-info">تعديل</a>
                                <a href="{{ route('delete.ranking.new',$item->id) }}" class="btn btn-danger" id="delete" >حذف</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>الرقم</th>
                        <th>اسم الرتبة</th>
                        <th>ترتيب الرتبة</th>
                        <th>عملة الوصول</th>
                        <th>العدد (الوصول)</th>
                        <th>الرتبة الأخيرة؟</th>
                        <th>هل هي مجانية؟</th>
                        <th>عدد المستويات</th>
                        <th>الفوز (للمستوى)</th>
                        <th>إجمالي الفوز للرتبة التالية</th>
                        <th>عملة الانتقال</th>
                        <th>العدد (الانتقال)</th>
                        <th>الاجراء</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@endsection
