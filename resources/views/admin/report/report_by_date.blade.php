@extends('admin.master_admin')
@section('admin')

<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Task', 'Count'],
            ['أنواع اللعب',        {{ $gameType }}],
            ['الفئات الرئيسية',    {{ $mainCategory }}],
            ['الفئات الفرعية',     {{ $category }}],
            ['عناصر اللعبة',       {{ $titlePosition }}],
            ['المستخدمين المسجلين',{{ $users }}],
            ['الأسئلة',            {{ $questions }}],
            ['الألعاب',            {{ $games }}],
            ['الرعاة',             {{ $sponsor }}],
        ]);

        var options = {
            title: 'نتائج الاحصائية في تاريخ : {{ $formatDate }}',
            is3D: true,
            colors: [
                '#1C3E14', // أخضر غامق
                '#D22FBF', // بنفسجي
                '#67B586', // أخضر فاتح
                '#4B0A05', // بني محمر
                '#5636D3', // أزرق بنفسجي
                '#3357FF', // أزرق
                '#15232A', // رمادي غامق
                '#894818'  // بني
            ]
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
    }
</script>

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">الاحصائية خلال تاريخ معين</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb"></nav>
    </div>
    <div class="ms-auto">
        <div class="btn-group"></div>
    </div>
</div>
<!--end breadcrumb-->

<h3>نتائج الاحصائية في تاريخ - {{ $formatDate }}</h3>
<hr/>

@if ($users == 0 && $category == 0 && $games == 0 && $questions == 0 && $gameType == 0 && $mainCategory == 0 && $sponsor == 0 && $titlePosition == 0)
    <h3 class="text-danger">لا توجد بيانات متاحة لعرض الاحصائية</h3>

@else
    <div id="piechart" style="width: 100%; height: 500px;"></div>
@endif

@endsection
