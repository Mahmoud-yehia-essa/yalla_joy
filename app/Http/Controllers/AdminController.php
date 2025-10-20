<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'تم تسجيل الخروج',
            'alert-type' => 'success'
        );

        return redirect('/login')->with($notification);
    }

    public function adminProfile()
    {
        $id = Auth::user()->id;
        $userAdmin = User::find($id);
        return view('admin.admin_profile',compact('userAdmin'));
    }

    public function adminProfileStore(Request $request)
    {

        $id = Auth::user()->id;
        $userAdmin = User::find($id);

        $userAdmin->fname = $request->fname;

        $userAdmin->lname = $request->lname;

        $userAdmin->email = $request->email;
        $userAdmin->phone = $request->phone;

        $userAdmin->address = $request->address;


        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/admin_images/'.$userAdmin->photo));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'),$filename);
            $userAdmin['photo'] = $filename;
        }


        $userAdmin->save();


        $notification = array(
            'message' => 'تم تعديل البيانات',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.profile')->with($notification);
    }


    public function AdminChangePassword(){
        return view('admin.admin_change_password');
    } // End Mehtod


    public function AdminUpdatePassword(Request $request){
        // Validation
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ],[
            'old_password.required' => 'الرجاء التأكد من كتابة كلمة المرور القديمة',
            'new_password.required' => 'الرجاء التأكد من كتابة كلمة المرور الجديدة',
            'new_password.confirmed' => 'الرجاء التأكد من تطابق كلمة المرور',
        ]);
        // Match The Old Password
        if (!Hash::check($request->old_password, auth::user()->password)) {
            return back()->with("error", "الرجاء التحقق من كتابة كلمة المرور القديمة بطريقة صحيحة");
        }
        // Update The new password
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);
        return back()->with("status", "تم تعديل كلمة المرور");
    } // End Mehtod





     /// new sow all admin

       public function AllAdmin(){
        $alladminuser = User::where('role','admin')->latest()->get();


        return view('admin.admin.all_admin',compact('alladminuser'));
    }// End Mehtod



     public function AddAdmin(){
        $roles = Role::all();
        return view('admin.admin.add_admin',compact('roles'));
    }// End Mehtod



    //  public function addAdminStore(Request $request)
    // {


    //     $request->validate([
    //         'fname' => 'required|string|max:255',
    //         'lname' => 'required|string|max:255',
    //         'email' => 'required|email|unique:users,email',
    //         'password' => 'required|min:6|confirmed',
    //         'password_confirmation' => 'required',
    //         'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //                                 'roles' => 'required|not_in:non',


    //     ], [
    //         'fname.required' => 'حقل الاسم الأول مطلوب.',
    //         'fname.string' => 'يجب أن يكون الاسم الأول نصًا.',
    //         'fname.max' => 'يجب ألا يزيد الاسم الأول عن 255 حرفًا.',

    //         'lname.required' => 'حقل اسم العائلة مطلوب.',
    //         'lname.string' => 'يجب أن يكون اسم العائلة نصًا.',
    //         'lname.max' => 'يجب ألا يزيد اسم العائلة عن 255 حرفًا.',

    //         'email.required' => 'حقل البريد الإلكتروني مطلوب.',
    //         'email.email' => 'يجب إدخال بريد إلكتروني صالح.',
    //         'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل.',

    //         'password.required' => 'حقل كلمة المرور مطلوب.',
    //         'password.min' => 'يجب أن تكون كلمة المرور على الأقل 6 أحرف.',
    //         'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',

    //         'password_confirmation.required' => 'حقل تأكيد كلمة المرور مطلوب.',



    //         'photo.image' => 'يجب أن يكون الملف صورة.',
    //         'photo.mimes' => 'يجب أن تكون الصورة من نوع jpeg أو png أو jpg أو gif.',
    //         'photo.max' => 'يجب ألا يتجاوز حجم الصورة 2 ميغابايت.',




    //                         'roles.required' => '⚠️ الرجاء اختيار الدور .',
    //     'roles.not_in' => '⚠️ الرجاء اختيار الدور.',

    //     ]);


    //     $filename = "";

    //     if ($request->file('photo')) {
    //         // $file = $request->file('photo');
    //         // $filename = date('YmdHi').$file->getClientOriginalName();
    //         // $file->move(public_path('upload/user_images'),$filename);


    //         $file = $request->file('photo');
    //         $filename = date('YmdHi') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    //         $file->move(public_path('upload/admin_images'), $filename);
    //     }


    //    $user = User::create([
    //         'fname' => $request->fname,
    //         'lname' => $request->lname,

    //         'email' => $request->email,
    //         'phone' => $request->phone,
    //         'address' => $request->address,
    //         'role' => 'admin',
    //         'password' => Hash::make($request->password),
    //         'photo' => $filename,


    //     ]);


    //        if ($request->roles) {
    //         $user->assignRole($request->roles);
    //     }

    //     $notification = array(
    //         'message' => 'تم اضافة مدير جديد',
    //         'alert-type' => 'success'
    //     );

    //     return redirect()->route('all.admin')->with($notification);



    // }



    public function addAdminStore(Request $request)
{
    $request->validate([
        'fname' => 'required|string|max:255',
        'lname' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
        'password_confirmation' => 'required',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'roles' => 'required|not_in:non',
    ], [
        'fname.required' => 'حقل الاسم الأول مطلوب.',
        'lname.required' => 'حقل اسم العائلة مطلوب.',
        'email.required' => 'حقل البريد الإلكتروني مطلوب.',
        'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل.',
        'password.required' => 'حقل كلمة المرور مطلوب.',
        'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
        'roles.required' => '⚠️ الرجاء اختيار الدور.',
        'roles.not_in' => '⚠️ الرجاء اختيار الدور.',
    ]);

    // رفع الصورة إن وجدت
    $filename = "";
    if ($request->file('photo')) {
        $file = $request->file('photo');
        $filename = date('YmdHi') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('upload/admin_images'), $filename);
    }

    // إنشاء المستخدم باستخدام save()
    $user = new User();
    $user->fname = $request->fname;
    $user->lname = $request->lname;
    $user->email = $request->email;
    $user->phone = $request->phone;
    $user->address = $request->address;
    $user->role = 'admin';
    $user->password = Hash::make($request->password);
    $user->photo = $filename;
    $user->save();

    // تعيين الدور
    if ($request->roles) {
        // إن كانت القيمة رقمية، جلب الدور بالـ ID
        if (is_numeric($request->roles)) {
            $role = \Spatie\Permission\Models\Role::findById($request->roles, 'web');
            $user->assignRole($role->name);
        } else {
            // إن كانت القيمة اسم الدور مباشرة
            $user->assignRole($request->roles);
        }
    }

    $notification = [
        'message' => 'تم اضافة مدير جديد بنجاح ✅',
        'alert-type' => 'success'
    ];

    return redirect()->route('all.admin')->with($notification);
}


 public function editAdmin($id)
    {

        $user = User::findOrFail($id);
        $roles = Role::all();






        return view('admin.admin.edit_admin',compact('user','roles'));





    }




    public function editAdminStore(Request $request)
    {

        $user_id = $request->id;
        $old_img = $request->old_image;
        $old_email = $request->old_email;

        $user = User::findOrFail($user_id);


// Check if the email hasn't changed
if ($old_email == $request->email) {
    // Validate without the unique rule
    $rules = [
        'fname' => 'required|string|max:255',
        'lname' => 'required|string|max:255',
                'roles' => 'required|not_in:non',

        'email' => 'required|email', // Removed the 'unique' rule here
        'password' => 'nullable|min:6|confirmed', // Changed to 'nullable' to avoid validation if empty
        'password_confirmation' => 'nullable',  // Make confirmation optional if password is empty
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ];
} else {
    // Validate with the unique rule for a new email
    $rules = [
        'fname' => 'required|string|max:255',
        'lname' => 'required|string|max:255',
                'roles' => 'required|not_in:non',

        'email' => 'required|email|unique:users,email', // Unique validation for email
        'password' => 'nullable|min:6|confirmed', // Password validation is now optional if empty
        'password_confirmation' => 'nullable',  // Confirmation is optional if password is empty
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ];


     $user->email = $request->email;

}



$request->validate($rules, [
    'fname.required' => 'حقل الاسم الأول مطلوب.',
    'fname.string' => 'يجب أن يكون الاسم الأول نصًا.',
    'fname.max' => 'يجب ألا يزيد الاسم الأول عن 255 حرفًا.',

    'lname.required' => 'حقل اسم العائلة مطلوب.',
    'lname.string' => 'يجب أن يكون اسم العائلة نصًا.',
    'lname.max' => 'يجب ألا يزيد اسم العائلة عن 255 حرفًا.',

    'email.required' => 'حقل البريد الإلكتروني مطلوب.',
    'email.email' => 'يجب إدخال بريد إلكتروني صالح.',
    'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل.',

    'password.min' => 'يجب أن تكون كلمة المرور على الأقل 6 أحرف.',
    'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
    'password_confirmation.required' => 'حقل تأكيد كلمة المرور مطلوب.',

    'photo.image' => 'يجب أن يكون الملف صورة.',
    'photo.mimes' => 'يجب أن تكون الصورة من نوع jpeg أو png أو jpg أو gif.',
    'photo.max' => 'يجب ألا يتجاوز حجم الصورة 2 ميغابايت.',
      'roles.required' => '⚠️ الرجاء اختيار الدور.',
        'roles.not_in' => '⚠️ الرجاء اختيار الدور.',

]);




        // $filename = "";

        $path = 'upload/admin_images/'.$old_img;




        if ($request->file('photo')) {


            $file = $request->file('photo');
            $filename = date('YmdHi') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/admin_images'), $filename);


            if (file_exists($path) && $old_img != "" ) {
                unlink($path);
             }


             $user->photo = $filename;








        }



        if($request->password != "")
        {

            $user->password = Hash::make($request->password);

        }



        $user->fname = $request->fname;
        $user->lname = $request->lname;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->is_game_free ='free';


        $user->save();


        $user->roles()->detach();

           // تعيين الدور
    if ($request->roles) {
        // إن كانت القيمة رقمية، جلب الدور بالـ ID
        if (is_numeric($request->roles)) {
            $role = \Spatie\Permission\Models\Role::findById($request->roles, 'web');
            $user->assignRole($role->name);
        } else {
            // إن كانت القيمة اسم الدور مباشرة
            $user->assignRole($request->roles);
        }
    }

        $notification = array(
            'message' => 'تم تعديل المدير',
            'alert-type' => 'success'
        );
        return redirect()->route('all.admin')->with($notification);











    }


      public function deleteAdmin($id){
        $user = User::findOrFail($id);
        $img = $user->photo;

        // unlink($img );

      //  return $user->photo;

        $path = 'upload/admin_images/'.$user->photo;

        if ($user->photo && file_exists(public_path($path))) {
            unlink(public_path($path));
        }
        User::findOrFail($id)->delete();
        $notification = array(
            'message' => 'تم حذف المدير',
            'alert-type' => 'success'
        );
        return redirect()->route('all.admin')->with($notification);

        // return redirect()->back()->with($notification);
    }

}
