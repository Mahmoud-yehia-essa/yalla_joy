@extends('admin.master_admin')
@section('admin')

<style>
.table-responsive {
    max-height: 500px;
    overflow-y: auto;
}
table thead th {
    position: sticky !important;
    top: 0;
    background-color: #ffffff !important;
    z-index: 50 !important;
}
</style>

<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">ملف Excel</div>
    <div class="ms-auto">
        <form action="{{ route('excel.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="input-group">
                <input type="file" name="excel_file" class="form-control" required>
                <button class="btn btn-success">رفع Excel</button>
            </div>
        </form>
    </div>
</div>

<hr/>

<form action="{{ route('excel.approved') }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- رفع ملف ZIP --}}
    <div class="mb-3">
        <label class="form-label">ملف ZIP (يحتوي على صور / صوتيات / فيديوهات)</label>
        <input type="file" name="zip_file" class="form-control" required>
    </div>

    <div class="card">
        <div class="card-body">

            @isset($rows)
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            @foreach($headers as $header)
                                <th>{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($rows as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            @foreach($headers as $header)
                                <td>{{ $row[$header] ?? '' }}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <th>#</th>
                            @foreach($headers as $header)
                                <th>{{ $header }}</th>
                            @endforeach
                        </tr>
                    </tfoot>
                </table>
            </div>

            @else
                <p class="text-center text-secondary">قم برفع ملف Excel لعرض البيانات هنا</p>
            @endisset
        </div>
    </div>

    @isset($rows)
        <input type="hidden" name="rows" value="{{ json_encode($rows) }}">
        <button class="btn btn-success mt-3">اعتماد البيانات</button>
    @endisset

</form>

@endsection
