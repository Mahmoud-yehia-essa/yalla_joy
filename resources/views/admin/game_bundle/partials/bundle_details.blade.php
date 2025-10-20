<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 text-center mb-3">
            <h4>{{ $bundle->name }} / {{ $bundle->name_en }}</h4>
            <img src="{{ asset($bundle->photo) }}" style="width:150px; height:150px;" class="rounded mb-3">
            <p>{{ $bundle->description }}</p>
            <p>{{ $bundle->description_en }}</p>
            <small>{{ $bundle->hint }} / {{ $bundle->hint_en }}</small>
            <p><strong>نوع الحزمة: </strong>{{ $bundle->bundle_type }}</p>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-12">
            <h5>العملات المضافة</h5>
            <table class="table table-bordered">
                <thead>
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
        </div>

        <div class="col-md-12 mt-3">
            <h5>عناصر اللعبة</h5>
            <table class="table table-bordered">
                <thead>
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
        </div>

        <div class="col-md-12 mt-3">
            <h5>عناصر المساعدة</h5>
            <table class="table table-bordered">
                <thead>
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
    </div>
</div>
