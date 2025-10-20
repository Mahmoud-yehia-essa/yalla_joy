@extends('admin.master_admin')
@section('admin')

<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">أسئلة اللعبة: {{ $game->name }}</div>
    <div class="ms-auto">
        <a href="{{ route('add.user.game.question', $game->id) }}" class="btn btn-primary">إضافة سؤال جديد</a>
    </div>
</div>

<hr/>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>السؤال</th>
                    <th>النقاط</th>
                    <th>الوقت</th>
                    <th>النوع</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($questions as $key => $q)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $q->qu_title }}</td>
                        <td>{{ $q->qu_points }}</td>
                        <td>{{ $q->time_counter }}</td>
                        <td>{{ $q->questions_type }}</td>
                        <td>

   <a href="{{ route('edit.user.game.question', $q->id) }}" class="btn btn-info btn-sm">
        <i class="fa-solid fa-pen-to-square"></i> تعديل
    </a>
                            <a href="{{ route('delete.user.game.question', $q->id) }}" class="btn btn-danger btn-sm" id="delete">
                                <i class="fa-solid fa-trash"></i> حذف
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
