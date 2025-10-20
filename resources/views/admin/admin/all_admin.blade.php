@extends('admin.master_admin')
@section('admin')

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">كل المديرين</div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('add.category') }}" class="btn btn-primary">اضافة مدير جديد</a>
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
                            <th>Sl</th>
                            <th>الصورة</th>
                            <th>الإسم</th>
                            <th>البريد الإلكتروني</th>
                            <th>رقم الهاتف</th>
                            <th>الدور</th>
                            <th>الإحصائيات</th>
                            <th>الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alladminuser as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <img src="{{ (!empty($item->photo)) ? url('upload/admin_images/'.$item->photo):url('upload/no_image.jpg') }}" style="width: 50px; height:50px;">
                            </td>
                            <td>{{ $item->fname }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->phone }}</td>
                            <td style="white-space: normal; word-wrap: break-word; max-width: 450px; font-size: 17px;">
                                @foreach($item->roles as $role)
                                    <a href="{{route('role.permission.edit',$role->id)}}">
                                        <span class="badge bg-success">{{ $role->name }}</span>
                                    </a>
                                @endforeach
                            </td>
                            {{-- <td style="white-space: normal; word-wrap: break-word; max-width: 450px; font-size: 16px;">
                                <!-- يمكنك إضافة إحصائيات أخرى هنا إذا رغبت -->
                            </td> --}}
                          <td style="white-space: normal; word-wrap: break-word; max-width: 450px; font-size: 14px;">
    @php $badgeColor = '#6f42c1'; @endphp

    <!-- أنواع اللعب -->
    <button type="button" class="badge rounded-pill text-light d-inline-flex align-items-center me-2 mb-1"
            style="background-color: {{ $badgeColor }}; font-size:13px; padding:0.25rem 0.5rem; cursor:pointer;"
            data-bs-toggle="modal" data-bs-target="#gameTypesModal{{ $item->id }}">
        <span style="background:white; color: {{ $badgeColor }}; border-radius:50%; padding:0.15rem 0.3rem; margin-left:0.4rem;">🎮</span>
        عدد أنواع اللعب: {{ count($item->gameTypes) }}
    </button>

    <!-- الفئات الرئيسية -->
    <button type="button" class="badge rounded-pill text-light d-inline-flex align-items-center me-2 mb-1"
            style="background-color: {{ $badgeColor }}; font-size:13px; padding:0.25rem 0.5rem; cursor:pointer;"
            data-bs-toggle="modal" data-bs-target="#mainCategoriesModal{{ $item->id }}">
        <span style="background:white; color: {{ $badgeColor }}; border-radius:50%; padding:0.15rem 0.3rem; margin-left:0.4rem;">🧭</span>
        عدد الفئات الرئيسية: {{ count($item->mainCategories) }}
    </button>

    <!-- الفئات الفرعية -->
    <button type="button" class="badge rounded-pill text-light d-inline-flex align-items-center me-2 mb-1"
            style="background-color: {{ $badgeColor }}; font-size:13px; padding:0.25rem 0.5rem; cursor:pointer;"
            data-bs-toggle="modal" data-bs-target="#categoriesModal{{ $item->id }}">
        <span style="background:white; color: {{ $badgeColor }}; border-radius:50%; padding:0.15rem 0.3rem; margin-left:0.4rem;">🧩</span>
        عدد الفئات الفرعية: {{ count($item->categories) }}
    </button>

    <!-- الأسئلة -->
    <button type="button" class="badge rounded-pill text-light d-inline-flex align-items-center me-2 mb-1"
            style="background-color: {{ $badgeColor }}; font-size:13px; padding:0.25rem 0.5rem; cursor:pointer;"
            data-bs-toggle="modal" data-bs-target="#questionsModal{{ $item->id }}">
        <span style="background:white; color: {{ $badgeColor }}; border-radius:50%; padding:0.15rem 0.3rem; margin-left:0.4rem;">❓</span>
        عدد الأسئلة: {{ count($item->questions) }}
    </button>
</td>

                            <td>
                                <a href="{{ route('edit.admin',$item->id) }}" class="btn btn-info">تعديل</a>
                                <a href="{{ route('delete.admin',$item->id) }}" class="btn btn-danger" id="delete">حذف</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Sl</th>
                            <th>الصورة</th>
                            <th>الإسم</th>
                            <th>البريد الإلكتروني</th>
                            <th>رقم الهاتف</th>
                            <th>الدور</th>
                            <th>الإحصائيات</th>
                            <th>الإجراء</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modals لكل إحصائية -->
@foreach($alladminuser as $item)

<!-- Modal أنواع اللعب -->
<div class="modal fade" id="gameTypesModal{{ $item->id }}" tabindex="-1" aria-labelledby="gameTypesModalLabel{{ $item->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header text-light" style="background-color:#6f42c1">
        <h5 class="modal-title" id="gameTypesModalLabel{{ $item->id }}" style="color: white">تفاصيل أنواع اللعب</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        @if(count($item->gameTypes) > 0)
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <thead class="table-dark">
              <tr>
                <th>رقم</th>
                <th>اسم نوع اللعبة</th>
              </tr>
            </thead>
            <tbody>
              @foreach($item->gameTypes as $key => $type)
              <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $type->type_name }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @else
        <p class="text-center">لا توجد أنواع لعب مضافة.</p>
        @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal الفئات الرئيسية -->
<div class="modal fade" id="mainCategoriesModal{{ $item->id }}" tabindex="-1" aria-labelledby="mainCategoriesModalLabel{{ $item->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header text-light" style="background-color:#6f42c1">
        <h5 class="modal-title" id="mainCategoriesModalLabel{{ $item->id }}" style="color:white">تفاصيل الفئات الرئيسية</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        @if(count($item->mainCategories) > 0)
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <thead class="table-dark">
              <tr>
                <th>رقم</th>
                <th>اسم الفئة الرئيسية</th>
              </tr>
            </thead>
            <tbody>
              @foreach($item->mainCategories as $key => $mainCat)
              <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $mainCat->main_category_name }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @else
        <p class="text-center">لا توجد فئات رئيسية مضافة.</p>
        @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal الفئات الفرعية -->
<div class="modal fade" id="categoriesModal{{ $item->id }}" tabindex="-1" aria-labelledby="categoriesModalLabel{{ $item->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header text-light" style="background-color:#6f42c1">
        <h5 class="modal-title" id="categoriesModalLabel{{ $item->id }}" style="color:white">تفاصيل الفئات الفرعية</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        @if(count($item->categories) > 0)
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <thead class="table-dark">
              <tr>
                <th>رقم</th>
                <th>اسم الفئة الفرعية</th>
                <th>الفئة الرئيسية</th>
              </tr>
            </thead>
            <tbody>
              @foreach($item->categories as $key => $cat)
              <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $cat->category_name }}</td>
                <td>{{ $cat->mainCategory->main_category_name ?? '-' }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @else
        <p class="text-center">لا توجد فئات فرعية مضافة.</p>
        @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal الأسئلة -->
<div class="modal fade" id="questionsModal{{ $item->id }}" tabindex="-1" aria-labelledby="questionsModalLabel{{ $item->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header text-light" style="background-color:#6f42c1">
        <h5 class="modal-title" id="questionsModalLabel{{ $item->id }}" style="color:white">تفاصيل الأسئلة</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        @if(count($item->questions) > 0)
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <thead class="table-dark">
              <tr>
                <th>رقم</th>
                <th>محتوى السؤال</th>
                <th>الفئة الرئيسية</th>
                <th>الفئة الفرعية</th>
                <th>نوع اللعبة</th>
              </tr>
            </thead>
            <tbody>
              @foreach($item->questions as $qkey => $question)
              <tr>
                <td>{{ $qkey + 1 }}</td>
                <td>{{ $question->qu_title }}</td>
                <td>{{ $question->mainCategory->main_category_name ?? '-' }}</td>
                <td>{{ $question->category->category_name ?? '-' }}</td>
                <td>{{ $question->gameType->type_name ?? '-' }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @else
        <p class="text-center">لا توجد أسئلة مضافة.</p>
        @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
      </div>
    </div>
  </div>
</div>

@endforeach

@endsection
