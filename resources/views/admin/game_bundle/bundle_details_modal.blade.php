<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <!-- Card الحزمة -->
            <div class="card shadow-lg mb-4 border-primary">
                <div class="card-header text-center bg-danger text-white">
                    <h4  style="color: white"  class="mb-0">{{ $bundle->name }} / {{ $bundle->name_en }}</h4>
                </div>
                <div class="card-body">

                    <!-- صورة الحزمة -->
                    <div class="text-center mb-3">
                        <img src="{{ asset($bundle->photo) }}" class="rounded-circle shadow-sm"
                             style="width:150px; height:150px; object-fit:cover;">
                    </div>

                    <!-- وصف الحزمة -->
                    <div class="mb-3 text-center">
                        <strong>
  <p class="mb-1" style="text-align: right; line-height: 2.5;">
    {{ $bundle->description }}
  </p>
</strong>
                        <p class="mb-1 text-muted" style="text-align: left; line-height: 2.5;">{{ $bundle->description_en }}</p>
                        <small class="text-info">{{ $bundle->hint }} / {{ $bundle->hint_en }}</small>
                        <p class="mt-2"><strong>نوع الحزمة: </strong>
                            <span class="badge bg-success">{{ $bundle->bundle_type }}</span>
                        </p>
                    </div>

                    <hr class="border-primary">

                    <!-- جدول العملات -->
                    <h5 class="text-primary mb-2">💰 العملات المضافة</h5>
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>العملة</th>
                                <th>عدد العملات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bundle->bundleCoins as $coin)
                                <tr>
                                    <td>{{ $coin->coin->name ?? '---' }}</td>
                                    <td>{{ $coin->number }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- جدول عناصر اللعبة -->
                    <h5 class="text-primary mt-4 mb-2">🎮 عناصر اللعبة</h5>
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>العنصر</th>
                                <th>العدد</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bundle->bundleItems as $item)
                                <tr>
                                    <td>{{ $item->item->name ?? '---' }}</td>
                                    <td>{{ $item->number }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- جدول عناصر المساعدة -->
                    <h5 class="text-primary mt-4 mb-2">🛠️ عناصر المساعدة</h5>
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>العنصر</th>
                                <th>العدد</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bundle->bundleHelpers as $helper)
                                <tr>
                                    <td>{{ $helper->helper->name ?? '---' }}</td>
                                    <td>{{ $helper->number }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
                <div class="card-footer text-center">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">
                        إغلاق
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* تحسين الشكل */
    .card-header h4 {
        font-weight: bold;
        font-size: 1.5rem;
    }

    table th, table td {
        text-align: center;
        vertical-align: middle;
    }

    table {
        border-radius: 5px;
        overflow: hidden;
    }
</style>
