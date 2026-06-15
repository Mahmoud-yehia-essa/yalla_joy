@extends('admin.master_admin')
@section('admin')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">كل العبارات</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
        </nav>
    </div>
    <div class="ms-auto">
        <div class="btn-group">
            <a href="{{route('add.proverb')}}" >
                <button type="button" class="btn btn-primary">
                    اضافة عبارة جديدة
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
                            <th>العبارة</th>
                            <th>الصوت (عربي)</th>
                            <th>الصوت (انجليزي)</th>
                            <th>الرتبة</th>
                            <th>الاجراء</th>
                        </tr>
                </thead>
                <tbody>
                    @foreach($proverbs as $key => $item)
                        <tr>
                            <td> {{ $key+1 }} </td>
                            <td>
                                @if($item->type == 'positive')
                                    <span class="badge bg-success">إيجابية</span>
                                @else
                                    <span class="badge bg-danger">سلبية</span>
                                @endif
                            </td>
                            <td>{{ $item->title }} <br> <small>{{ $item->title_en }}</small></td>
                            <td>
                                @if($item->audio_ar)
                                <audio controls style="width: 200px;">
                                    <source src="{{ asset($item->audio_ar) }}">
                                </audio>
                                @else
                                <span class="text-danger">لا يوجد</span>
                                @endif
                            </td>
                            <td>
                                @if($item->audio_en)
                                <audio controls style="width: 200px;">
                                    <source src="{{ asset($item->audio_en) }}">
                                </audio>
                                @else
                                <span class="text-danger">لا يوجد</span>
                                @endif
                            </td>
                            <td>{{ $item->rankingNew ? $item->rankingNew->rank_name : 'غير محدد' }}</td>
                            <td>
                                <a href="{{route('edit.proverb',$item->id)}}" class="btn btn-info">تعديل</a>
                                <a href="{{ route('delete.proverb',$item->id) }}" class="btn btn-danger" id="delete" >حذف</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>الرقم</th>
                        <th>النوع</th>
                        <th>العبارة</th>
                        <th>الصوت (عربي)</th>
                        <th>الصوت (انجليزي)</th>
                        <th>الرتبة</th>
                        <th>الاجراء</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@endsection
