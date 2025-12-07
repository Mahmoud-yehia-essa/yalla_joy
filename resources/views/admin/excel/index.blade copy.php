@extends('admin.master_admin')
@section('admin')


<style>
.table-responsive {
    max-height: 500px;
    overflow-y: auto;
}

/* Sticky Header fix */
table thead th {
    position: sticky !important;
    top: 0;
    background-color: #ffffff !important;
    z-index: 50 !important;
}

/* في حال جداول ذات خطوط */
.table-striped > thead > tr > th,
.table-bordered > thead > tr > th {
    background-color: #ffffff !important;
}


</style>
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">ملف Excel</div>
    <div class="ps-3"></div>

    <div class="ms-auto">
        <div class="btn-group">
            <form action="{{ route('excel.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="input-group">
                    <input type="file" name="excel_file" class="form-control" required>
                    <button type="submit" class="btn btn-success">
                        رفع الملف
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end breadcrumb-->

<hr/>
{{-- id="example" --}}
{{-- <div class="card">
    <div class="card-body">
        @isset($rows)
        <div class="table-responsive">
            <table  class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        @foreach($rows[0] as $header)
                            <th>{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    @foreach($rows->skip(1) as $row)
                    <tr>
                        @foreach($row as $cell)
                            <td>{{ $cell }}</td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        @foreach($rows[0] as $header)
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
</div> --}}

  <form action="{{ route('excel.approved') }}" method="POST" enctype="multipart/form-data">
                @csrf
<div class="card">
    <div class="card-body">
        @isset($rows)
      <div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>#</th>
                {{-- الهيدر --}}
                @foreach($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>

        <tbody>
            {{-- الصفوف --}}
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
         <input type="hidden" name="rows" class="form-control" value="{{ json_encode($rows) }}" />

 <button type="submit" class="btn btn-success">
                        اعتماد البيانات
                    </button>

@endisset


</form>

@endsection
