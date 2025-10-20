@extends('admin.master_admin')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">تعديل صلاحيات الدور</div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-body">

                <form id="myForm" method="post" action="{{ route('role.permission.update', $role->id) }}">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <h6 class="mb-0">اسم الدور</h6>
                        </div>
                        <div class="form-group col-sm-9 text-secondary">
                            <select name="role_id" class="form-select" disabled>
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefaultAll">
                        <label class="form-check-label" for="flexCheckDefaultAll">تحديد الكل</label>
                    </div>
                    <hr>

                    @foreach($permission_groups as $group)
                        @php
                            $permissions = App\Models\User::getpermissionByGroupName($group->group_name);
                        @endphp

                        <div class="row">
                            <div class="col-3">
                                <div class="form-check">
                                    {{-- <input class="form-check-input" type="checkbox" id="group_{{ $group->group_name }}"> --}}
                                    <label class="form-check-label" for="group_{{ $group->group_name }}">
                                        {{ $group->group_name }}
                                    </label>
                                </div>
                            </div>

                            <div class="col-9">
                                @foreach($permissions as $permission)
                                    @php
                                        $hasPermission = DB::table('role_has_permissions')
                                            ->where('role_id', $role->id)
                                            ->where('permission_id', $permission->id)
                                            ->exists();
                                    @endphp
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            name="permission[]"
                                            type="checkbox"
                                            value="{{ $permission->id }}"
                                            id="perm_{{ $permission->id }}"
                                            {{ $hasPermission ? 'checked' : '' }}>
                                        <label class="form-check-label" for="perm_{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                @endforeach
                                <br>
                            </div>
                        </div>
                    @endforeach

                    <div class="row mt-3">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9 text-secondary">
                            <input type="submit" class="btn btn-primary px-4" value="حفظ التغييرات" />
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#flexCheckDefaultAll').click(function(){
        $('input[type=checkbox]').prop('checked', $(this).is(':checked'));
    });
</script>

@endsection
