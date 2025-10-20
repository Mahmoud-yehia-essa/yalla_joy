<?php

namespace App\Http\Controllers;

use DB;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller
{




        public function allPermission()
    {
        $permission = Permission::latest()->get();
        return view('admin.permission.all_permission',compact('permission'));
    }


          public function allRoles()
    {
        $roles = Role::latest()->get();
        return view('admin.roles.all_roles',compact('roles'));
    }

     public function addRoles()
    {
        return view('admin.roles.add_roles');
    }





       public function addRolesStore(Request $request)
    {



        $request->validate([




            'name' => 'required|string|max:255',


        ], [
            'name.required' => '⚠️ الرجاء اضافة اسم الدور',
            'name.string' => '⚠️ الرجاء التأكد من كتابة الدور بشكل صحيح',
            'name.max' => '⚠️ الرجاء التأكد من عدد احرف الدور لا يتجاوز 255 حرف',









        ]);




        // Insert category
        Role::create([

             'name' => $request->name,

        ]);


        $notification = array(
            'message' => 'تم اضافة الدور ',
            'alert-type' => 'success'
        );

        return redirect()->route('all.roles')->with($notification);
    }




    public function editRoles($id){



        $role = Role::findOrFail($id);
            return view('admin.roles.edit_roles',compact('role'));

        }







         public function editRoleStore(Request $request){





        $role = Role::findOrFail($request->id);



         $request->validate([




            'name' => 'required|string|max:255',


        ], [
            'name.required' => '⚠️ الرجاء اضافة اسم الدور',
            'name.string' => '⚠️ الرجاء التأكد من كتابة الدور بشكل صحيح',
            'name.max' => '⚠️ الرجاء التأكد من عدد احرف الدور لا يتجاوز 255 حرف',







        ]);



         $role->name = $request->name;

                   $role->save();



      $notification = array(
            'message' => 'تم تعديل الدور ',
            'alert-type' => 'success'
        );

        return redirect()->route('all.roles')->with($notification);
        }




        /////

        public function addPermission()
    {
        return view('admin.permission.add_permission');
    }






    public function deleteRole($id){
        $role = Role::findOrFail($id);

        // unlink($img );


        Role::findOrFail($id)->delete();
        $notification = array(
            'message' => 'تم حذف الدور',
            'alert-type' => 'success'
        );
        return redirect()->route('all.roles')->with($notification);

        // return redirect()->back()->with($notification);
    }// End Method




       public function addPermissiontore(Request $request)
    {



        $request->validate([


                        'group_name' => 'required|not_in:non',


            'name' => 'required|string|max:255',


        ], [
            'name.required' => '⚠️ الرجاء اضافة اسم الصلاحية',
            'name.string' => '⚠️ الرجاء التأكد من كتابة الصلاحية بشكل صحيح',
            'name.max' => '⚠️ الرجاء التأكد من عدد احرف الصلاحية لا يتجاوز 255 حرف',



                            'group_name.required' => '⚠️ الرجاء اختيار المجوعة .',
        'group_name.not_in' => '⚠️ الرجاء اختيار نوع المجموعة.',





        ]);




        // Insert category
        Permission::create([

             'name' => $request->name,
                          'group_name' => $request->group_name,

        ]);


        $notification = array(
            'message' => 'تم اضافة الصلاحية ',
            'alert-type' => 'success'
        );

        return redirect()->route('all.permission')->with($notification);
    }





    public function deletePermission($id){
        $permission = Permission::findOrFail($id);

        // unlink($img );


        Permission::findOrFail($id)->delete();
        $notification = array(
            'message' => 'تم حذف الصلاحية',
            'alert-type' => 'success'
        );
        return redirect()->route('all.permission')->with($notification);

        // return redirect()->back()->with($notification);
    }// End Method



        public function editPermission($id){



        $permission = Permission::findOrFail($id);
            return view('admin.permission.edit_permission',compact('permission'));

        }



         public function editPermissionStore(Request $request){





        $permission = Permission::findOrFail($request->id);



         $request->validate([


                        'group_name' => 'required|not_in:non',


            'name' => 'required|string|max:255',


        ], [
            'name.required' => '⚠️ الرجاء اضافة اسم الصلاحية',
            'name.string' => '⚠️ الرجاء التأكد من كتابة الصلاحية بشكل صحيح',
            'name.max' => '⚠️ الرجاء التأكد من عدد احرف الصلاحية لا يتجاوز 255 حرف',



                            'group_name.required' => '⚠️ الرجاء اختيار المجوعة .',
        'group_name.not_in' => '⚠️ الرجاء اختيار نوع المجموعة.',





        ]);



         $permission->name = $request->name;
                  $permission->group_name = $request->group_name;

                   $permission->save();



      $notification = array(
            'message' => 'تم تعديل الصلاحية ',
            'alert-type' => 'success'
        );

        return redirect()->route('all.permission')->with($notification);
        }







        //// Role in permission

            public function AddRolesPermission(){
         $roles = Role::all();
         $permissions = Permission::all();
        // $permission_groups = DB::table('permissions')->select('group_name')->groupBy('group_name')->get();

         $permission_groups = User::getpermissionGroups();


         return view('admin.roles.add_roles_permission',compact('roles','permissions','permission_groups'));
    }// End Method



    // public function RolePermissionStore(Request $request){


    //     $data = array();
    //     $permissions = $request->permission;

    //     foreach($permissions as $key => $item){
    //         $data['role_id'] = $request->role_id;
    //         $data['permission_id'] = $item;

    //         DB::table('role_has_permissions')->insert($data);
    //     }

    //      $notification = array(
    //         'message' => 'Role Permission Added Successfully',
    //         'alert-type' => 'success'
    //     );

    //     return redirect()->route('all.roles')->with($notification);

    // }// End Method

    public function RolePermissionStore(Request $request)
{
    $roleId = $request->role_id;
    $permissions = $request->permission ?? [];

    // return $permissions;

    // حذف الصلاحيات القديمة
    DB::table('role_has_permissions')->where('role_id', $roleId)->delete();

    // إدخال الصلاحيات الجديدة (إن وجدت)
    foreach ($permissions as $permissionId) {
        DB::table('role_has_permissions')->insert([
            'role_id' => $roleId,
            'permission_id' => $permissionId,
        ]);
    }

    return redirect()->route('all.roles')->with([
        'message' => 'تم تحديث الصلاحيات بنجاح',
        'alert-type' => 'success',
    ]);
}


public function EditRolePermission($id)
{
    $role = Role::findOrFail($id);

    $roles = Role::all();

    // $permission_groups = App\Models\User::getpermissionGroups(); // نفس الدالة التي تستخدمها لعرض المجموعات
         $permission_groups = User::getpermissionGroups();

    return view('admin.roles.edit_role_permission', compact('role', 'roles', 'permission_groups'));
}

public function UpdateRolePermission(Request $request, $id)
{


    $roleId = $id;
    $permissions = $request->permission ?? [];

    // حذف الصلاحيات القديمة
    DB::table('role_has_permissions')->where('role_id', $roleId)->delete();

    // إدخال الصلاحيات الجديدة
    foreach ($permissions as $permissionId) {
        DB::table('role_has_permissions')->insert([
            'role_id' => $roleId,
            'permission_id' => $permissionId,
        ]);
    }

            app()[PermissionRegistrar::class]->forgetCachedPermissions();




    return redirect()->route('all.roles')->with([
        'message' => 'تم تحديث صلاحيات الدور بنجاح',
        'alert-type' => 'success',
    ]);
}




}

