{{-- ['Task', 'Hours per Day'],
['المستخدمين المسجلين',     {{$users->count()}}],
['الفئات',      {{$category->count()}}],
['الألعاب',  {{$games->count()}}],
['الأسئلة', {{$questions->count()}}], --}}
@extends('admin.master_admin')
@section('admin')
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Task', 'Hours per Day'],
                [' أنواع اللعب', {{$gameType->count()}}],
                [' الفئات الرئيسية', {{$mainCategory->count()}}],

        ['الفئات الفرعية', {{$category->count()}}],

                ['عناصر اللعبة', {{$titlePosition->count()}}],

        ['المستخدمين المسجلين', {{$users->count()}}],
        ['الأسئلة', {{$questions->count()}}],
        ['الألعاب', {{$games->count()}}],
                ['الرعاة', {{$sponsor->count()}}],

    ]);

    var options = {
        title: '',
        //#endregion


        colors: ['#1C3E14','#D22FBF','#67B586','#4B0A05', '#5636D3', '#3357FF', '#15232A','#894818'] // Add your desired colors here

    };

    var chart = new google.visualization.PieChart(document.getElementById('piechart'));

    chart.draw(data, options);
}
  </script>

  <style>

    .bg-gradient-magenta {
    background: linear-gradient(135deg, #FF00CC, #333399);
    color: white;
}


.bg-gradient-cyan {
 background: linear-gradient(135deg, #400000, #8B0000);
    color: white;
}

.bg-gradient-darkteal {
    background: linear-gradient(135deg, #0B3D0B, #06470C);
    color: white;
}


.bg-gradient-darkorange {
    background: linear-gradient(135deg, #8B4000, #FF7300);
    color: white;
}


.bg-gradient-game-new {
    background: linear-gradient(135deg, #0a0a0a, #866c15);
    color: white;
}
  </style>

  <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
    <div class="col">
        <a href="{{route('all.game.type')}}">
        <div class="card radius-10 bg-gradient-darkteal">
         <div class="card-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-white">{{$gameType->count()}}</h5>
                <div class="ms-auto">
                    <i class='bx bx-user fs-3 text-white'></i>

                </div>
            </div>
            <div class="progress my-2 bg-opacity-25 bg-white" style="height:4px;">
                <div class="progress-bar bg-white" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="d-flex align-items-center text-white">
                <p class="mb-0">عدد انواع اللعب</p>

            </div>
        </div>
    </a>
      </div>
    </div>
    <div class="col">
        <a href="{{route('all.main.category')}}">

        <div class="card radius-10  bg-gradient-magenta">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-white"> {{$mainCategory->count()}}</h5>
                <div class="ms-auto">
                    <i class='bx bx-category fs-3 text-white'></i>
                </div>
            </div>
            <div class="progress my-2 bg-opacity-25 bg-white" style="height:4px;">
                <div class="progress-bar bg-white" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="d-flex align-items-center text-white">
                <p class="mb-0">عدد الفئات الرئيسية</p>
            </div>
        </div>
    </a>

      </div>
    </div>
   <div class="col">
        <a href="{{route('all.category')}}">

        <div class="card radius-10 bg-gradient-ohhappiness">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-white"> {{$category->count()}}</h5>
                <div class="ms-auto">
                    <i class='bx bx-category fs-3 text-white'></i>
                </div>
            </div>
            <div class="progress my-2 bg-opacity-25 bg-white" style="height:4px;">
                <div class="progress-bar bg-white" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="d-flex align-items-center text-white">
                <p class="mb-0">عدد الفئات الفرعية</p>
            </div>
        </div>
    </a>

    </div>
    </div>
    <div class="col">
        <a href="{{route('all.title.position')}}">

        <div class="card radius-10 bg-gradient-cyan bg-warning">
         <div class="card-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-white">{{$titlePosition->count()}}</h5>
                <div class="ms-auto">
                    <i class='bx bx-joystick fs-3 text-white'></i>
                </div>
            </div>
            <div class="progress my-2 bg-opacity-25 bg-white" style="height:4px;">
                <div class="progress-bar bg-white" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="d-flex align-items-center text-white">
                <p class="mb-0">عدد عناصر اللعبة</p>
            </div>
        </div>
    </a>

     </div>
    </div>
</div><!--end row-->

<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
    <div class="col">
        <a href="{{route('all.users')}}">
        <div class="card radius-10 bg-gradient-deepblue">
         <div class="card-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-white">{{$users->count()}}</h5>
                <div class="ms-auto">
                    <i class='bx bx-user fs-3 text-white'></i>

                </div>
            </div>
            <div class="progress my-2 bg-opacity-25 bg-white" style="height:4px;">
                <div class="progress-bar bg-white" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="d-flex align-items-center text-white">
                <p class="mb-0">عدد المستخدمين</p>

            </div>
        </div>
    </a>
      </div>
    </div>


       <div class="col">
        <a href="{{route('sponsor.all')}}">

        <div class="card radius-10 bg-gradient-darkorange">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-white">{{$sponsor->count()}}</h5>
                <div class="ms-auto">
                    <i class='bx bx-help-circle fs-3 text-white'></i>

                </div>
            </div>
            <div class="progress my-2 bg-opacity-25 bg-white" style="height:4px;">
                <div class="progress-bar bg-white" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="d-flex align-items-center text-white">
                <p class="mb-0">عدد الرعاة</p>
            </div>
        </div>
    </a>





      </div>



    </div>
    <div class="col">
        <a href="{{route('all.question')}}">

        <div class="card radius-10 bg-gradient-ibiza">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-white">{{$questions->count()}}</h5>
                <div class="ms-auto">
                    <i class='bx bx-help-circle fs-3 text-white'></i>

                </div>
            </div>
            <div class="progress my-2 bg-opacity-25 bg-white" style="height:4px;">
                <div class="progress-bar bg-white" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="d-flex align-items-center text-white">
                <p class="mb-0">عدد الأسئلة</p>
            </div>
        </div>
    </a>

    </div>
    </div>
    <div class="col">
        <a href="{{route('all.games')}}">

        <div class="card radius-10 bg-gradient-moonlit bg-warning">
         <div class="card-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-white">{{$games->count()}}</h5>
                <div class="ms-auto">
                    <i class='bx bx-joystick fs-3 text-white'></i>
                </div>
            </div>
            <div class="progress my-2 bg-opacity-25 bg-white" style="height:4px;">
                <div class="progress-bar bg-white" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="d-flex align-items-center text-white">
                <p class="mb-0">عدد الألعاب</p>
            </div>
        </div>
    </a>

     </div>
    </div>
</div><!--end row-->



<div class="row row-cols-1 row-cols-md-2 row-cols-xl-2 justify-content-center">

    <div class="col-md-6 col-xl-4">
    <a href="{{ route('all.category') }}">
        <div class="card radius-10 bg-gradient-game-new bg-warning text-center">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 text-white">الفئة الأكثر استخداما</h5>
                    <div class="ms-auto">
                        <div style="
                            width: 35px;
                            height: 35px;
                            background-color: white;
                            color: #000;
                            border-radius: 50%;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-weight: bold;
                            font-size: 14px;
                        ">
                            {{$categoryHowManyUse->how_many_use}}
                        </div>
                    </div>
                </div>
                <div class="progress my-2 bg-opacity-25 bg-white" style="height:4px;">
                    <div class="progress-bar bg-white" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex align-items-center justify-content-center text-white">
                    <p class="mb-0">

                 {{$categoryHowManyUse->gameType->type_name}}  / {{$categoryHowManyUse->mainCategory->main_category_name}} / {{$categoryHowManyUse->category_name}}

                    </p>
                </div>
            </div>
        </div>
    </a>
</div>


</div><!--end row-->




   <div class="row row-cols-1 row-cols-lg-1">
    <div class="col">
        <div id="piechart" style="width: 100%; height: 500px;"></div>

     </div>


    </div><!--End Row-->



    <hr>
    <h4 class="mb-4">المستخدمين</h4>


    <div class="card">

        <div class="card-body">

            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
    <tr>
    <th>الرقم</th>
    <th>إسم الأول</th>
    <th>إسم العائلة</th>
    <th>البريد الإلكتروني</th>
    <th>تاريخ التسجيل</th>

    <th> الصورة</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $key => $item)
    <tr>
    <td> {{ $key+1 }} </td>
    <td>{{ $item->fname }}</td>
    <td>{{ $item->lname }}</td>
    <td>{{ $item->email }}</td>
    <td>{{ $item->created_at ? $item->created_at->diffForHumans() : 'لم يتم التحديد' }}</td>


    <td> <img class="rounded-circle"  src="{{ (!empty($item->photo)) ? url('upload/user_images/'.$item->photo):url('upload/no_image.jpg') }}" style="width: 50px; height:50px; border: 2px solid #0aa2dd;" >  </td>


    </tr>
    @endforeach


    </tbody>
    <tfoot>
    <tr>
        <th>الرقم</th>
        <th>إسم الأول</th>
        <th>إسم العائلة</th>
        <th>البريد الإلكتروني</th>
        <th>تاريخ التسجيل</th>

        <th> الصورة</th>
    </tr>
    </tfoot>
    </table>
            </div>
        </div>
    </div>



@endsection
