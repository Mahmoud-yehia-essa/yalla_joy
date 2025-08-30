@extends('admin.master_admin')
@section('admin')

<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">متابعة المستخدمين للمكافات</div>
					<div class="ps-3">

					</div>
					<div class="ms-auto">

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
                <th>المستخدم</th>

				<th>المكافأة </th>
				<th>التاريخ </th>
				<th>الاجراء</th>
			</tr>
		</thead>
		<tbody>
	@foreach($followUserRewards as $key => $item)
			<tr>
				<td> {{ $key+1 }} </td>
                <td> {{ $item->user->fname }}</td>

				<td> {{ $item->rewardsSponsors->title }}</td>


                <td>{{ $item->created_at ? $item->created_at->diffForHumans() : 'لم يتم التحديد' }}</td>




				<td>
					<a href="{{ route('delete.rewards.users',$item->id) }}" class="btn btn-danger" id="delete" >حذف</a>
				</td>
			</tr>
			@endforeach


		</tbody>
		<tfoot>
				<tr>
				<th>الرقم</th>
                <th>المستخدم</th>

				<th>المكافأة </th>
				<th>التاريخ </th>
				<th>الاجراء</th>
			</tr>
		</tfoot>
	</table>
						</div>
					</div>
				</div>



			</div>




@endsection
