<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

use Kreait\Laravel\Firebase\Facades\Firebase;
use Illuminate\Support\Str;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;


class UserController extends Controller
{
    public function getAllUsers()
    {
        // $users = User::latest()->get();
        $users = User::where('role', '!=', 'admin')->latest()->get();


        return view('admin.users.all_users',compact('users'));


    }


    public function addUser()
    {


        return view('admin.users.add_user');


    }

    public function addUserStore(Request $request)
    {


        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'date_of_birth' => 'nullable|date',
        ], [
            'fname.required' => 'حقل الاسم الأول مطلوب.',
            'fname.string' => 'يجب أن يكون الاسم الأول نصًا.',
            'fname.max' => 'يجب ألا يزيد الاسم الأول عن 255 حرفًا.',

            'lname.required' => 'حقل اسم العائلة مطلوب.',
            'lname.string' => 'يجب أن يكون اسم العائلة نصًا.',
            'lname.max' => 'يجب ألا يزيد اسم العائلة عن 255 حرفًا.',

            'email.required' => 'حقل البريد الإلكتروني مطلوب.',
            'email.email' => 'يجب إدخال بريد إلكتروني صالح.',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل.',

            'password.required' => 'حقل كلمة المرور مطلوب.',
            'password.min' => 'يجب أن تكون كلمة المرور على الأقل 6 أحرف.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',

            'password_confirmation.required' => 'حقل تأكيد كلمة المرور مطلوب.',



            'photo.image' => 'يجب أن يكون الملف صورة.',
            'photo.mimes' => 'يجب أن تكون الصورة من نوع jpeg أو png أو jpg أو gif.',
            'photo.max' => 'يجب ألا يتجاوز حجم الصورة 2 ميغابايت.',
            'date_of_birth.date' => 'يجب إدخال تاريخ ميلاد صالح.',
        ]);


        $filename = "";

        if ($request->file('photo')) {
            // $file = $request->file('photo');
            // $filename = date('YmdHi').$file->getClientOriginalName();
            // $file->move(public_path('upload/user_images'),$filename);


            $file = $request->file('photo');
            $filename = date('YmdHi') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/user_images'), $filename);
        }


        User::create([
            'fname' => $request->fname,
            'lname' => $request->lname,

            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'photo' => $filename,
            'date_of_birth' => $request->date_of_birth,


        ]);

        $notification = array(
            'message' => 'تم اضافة المستخدم',
            'alert-type' => 'success'
        );

        return redirect()->route('all.users')->with($notification);



    }





    public function editUser($id)
    {

        $user = User::findOrFail($id);






        return view('admin.users.edit_user',compact('user'));





    }

    public function editUserStore(Request $request)
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
        'email' => 'required|email', // Removed the 'unique' rule here
        'password' => 'nullable|min:6|confirmed', // Changed to 'nullable' to avoid validation if empty
        'password_confirmation' => 'nullable',  // Make confirmation optional if password is empty
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'date_of_birth' => 'nullable|date',
    ];
} else {
    // Validate with the unique rule for a new email
    $rules = [
        'fname' => 'required|string|max:255',
        'lname' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email', // Unique validation for email
        'password' => 'nullable|min:6|confirmed', // Password validation is now optional if empty
        'password_confirmation' => 'nullable',  // Confirmation is optional if password is empty
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'date_of_birth' => 'nullable|date',
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
    'date_of_birth.date' => 'يجب إدخال تاريخ ميلاد صالح.',
]);




        // $filename = "";

        $path = 'upload/user_images/'.$old_img;




        if ($request->file('photo')) {


            $file = $request->file('photo');
            $filename = date('YmdHi') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/user_images'), $filename);


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
        $user->is_game_free = $request->is_game_free;
        $user->date_of_birth = $request->date_of_birth;


        $user->save();

        $notification = array(
            'message' => 'تم تعديل المستخدم',
            'alert-type' => 'success'
        );
        return redirect()->route('all.users')->with($notification);











    }
    public function userInactive($id){
        User::findOrFail($id)->update(['status' => 'inactive']);
        $notification = array(
            'message' => 'المستخدم غير مفعل',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }// End Method
      public function userActive($id){
        User::findOrFail($id)->update(['status' => 'active']);
        $notification = array(
            'message' => 'المستخدم مفعل',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }// End Method



    public function deleteUser($id){
        $user = User::findOrFail($id);
        $img = $user->photo;

        // unlink($img );

      //  return $user->photo;

        $path = 'upload/user_images/'.$user->photo;

        if ($user->photo && file_exists(public_path($path))) {
            unlink(public_path($path));
        }
        User::findOrFail($id)->delete();
        $notification = array(
            'message' => 'تم حذف المستخدم',
            'alert-type' => 'success'
        );
        return redirect()->route('all.users')->with($notification);

        // return redirect()->back()->with($notification);
    }// End Method

    public function approveUserPhoto($id){
        User::findOrFail($id)->update(['photo_approval_status' => 'approved']);
        $notification = array(
            'message' => 'تم قبول صورة المستخدم بنجاح',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }// End Method

    public function rejectUserPhoto($id){
        User::findOrFail($id)->update(['photo_approval_status' => 'rejected']);
        $notification = array(
            'message' => 'تم رفض صورة المستخدم بنجاح',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }// End Method


    /// API ///



    public function loginApi(Request $request) {
        $incomingFields = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (auth()->attempt($incomingFields)) {
            $user = auth()->user(); // Get authenticated user
            $token = $user->createToken('ourapptoken')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'user' => $user, // Return all user data
                'token' => $token
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials'
        ], 401);
    }



    /*
    public function registerApi(Request $request) {
        // Check if email already exists
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Email already exists'
            ], 409); // 409 Conflict status code
        }

        // Create user
        $userCreated = User::create([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'photo' => $request->photo,
            'is_game_free' => 'paid',


        ]);

        if ($userCreated) {
            $token = $userCreated->createToken('ourapptoken')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Registration successful',
                'user' => $userCreated,
                'token' => $token
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'فشل التسجيل'
        ], 500);
    }
        */

    /*

    public function registerApi(Request $request) {


        // 1. التحقق من البريد الإلكتروني (فقط إذا تم إرساله لتجنب مشاكل Apple التي قد تخفي الإيميل أحياناً)
        if ($request->filled('email')) {
            if (User::where('email', $request->email)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email already exists'
                ], 409); // 409 Conflict status code
            }
        }

        $passwordToSave = '';

        // 2. التحقق الأمني لتسجيل الدخول الاجتماعي (Google & Apple)
        if ($request->filled('provider') && $request->filled('firebase_token')) {
            try {
                $auth = Firebase::auth();
                $verifiedIdToken = $auth->verifyIdToken($request->firebase_token);
                $uid = $verifiedIdToken->claims()->get('sub');

                $firebaseEmail = $auth->getUser($uid)->email;

                // مطابقة إيميل فايربيز مع الإيميل المرسل من التطبيق لضمان الأمان
                if (!$request->filled('email') || strtolower($firebaseEmail) !== strtolower($request->email)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'بيانات البريد غير متطابقة، تم رفض الطلب أمنياً.'
                    ], 403);
                }

                // تعيين كلمة مرور عشوائية قوية للمسجلين عبر جوجل وآبل
                // $passwordToSave = Hash::make(\Illuminate\Support\Str::random(24));
                            $passwordToSave = Hash::make($request->password);


            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'توكن فايربيز غير صالح أو منتهي الصلاحية'
                ], 401);
            }
        } else {
            // 3. التسجيل العادي (الاحتفاظ بالمنطق الأصلي)
            $passwordToSave = Hash::make($request->password);
        }

        // 4. إنشاء المستخدم
        try {
            $userCreated = User::create([
                'fname' => $request->fname,
                'lname' => $request->lname,
                'email' => $request->filled('email') ? $request->email : null,
                'phone' => $request->phone,
                'password' => $passwordToSave,
                'photo' => $request->photo,
                'is_game_free' => 'paid',

                // يمكنك إزالة هذين السطرين إذا لم تكن هذه الحقول موجودة في جدول Users في قاعدة البيانات
                'provider' => $request->filled('provider') ? $request->provider : null,
                'firebase_token' => $request->filled('firebase_token') ? $request->firebase_token : null,
            ]);

            if ($userCreated) {
                $token = $userCreated->createToken('ourapptoken')->plainTextToken;

                return response()->json([
                    'success' => true,
                    'message' => 'Registration successful',
                    'user' => $userCreated,
                    'token' => $token
                ], 201);
            }
        } catch (\Exception $e) {
            // إضافة Try Catch هنا مهمة جداً لاصطياد أي خطأ في قاعدة البيانات بدلاً من تعطل التطبيق
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => false,
            'message' => 'فشل التسجيل'
        ], 500);
    }
        */




public function validateRegisterApi(Request $request)
{
    // 1. التحقق من اسم المستخدم
    if (!$request->filled('user_name')) {
        return response()->json([
            'success' => false,
            'message' => 'اسم المستخدم مطلوب'
        ], 200);
    }

    $userName = strtolower(trim($request->user_name));
    if (User::where('user_name', $userName)->exists()) {
        return response()->json([
            'success' => false,
            'message' => 'اسم المستخدم موجود بالفعل'
        ], 200);
    }

    // 2. التحقق من البريد الإلكتروني
    if ($request->filled('email')) {
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'البريد الإلكتروني موجود بالفعل'
            ], 200);
        }
    }

    // 3. التحقق من رقم الهاتف
    if ($request->filled('phone')) {
        if (User::where('phone', $request->phone)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'رقم الهاتف موجود بالفعل'
            ], 200);
        }
    }

    // إذا وصلنا هنا، يعني البيانات سليمة تماماً
    return response()->json([
        'success' => true,
        'message' => 'البيانات سليمة ومتاحة للتسجيل'
    ], 200);
}


    public function registerApi(Request $request)
{
    // 1. التحقق من اسم المستخدم (توليد تلقائي إذا لم يتم توفيره)
    if (!$request->filled('user_name')) {
        $baseName = '';
        if ($request->filled('fname')) {
            $baseName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $request->fname));
        }
        if (empty($baseName)) {
            $baseName = "user";
        }
        
        do {
            $userName = $baseName . rand(100, 9999);
        } while (User::where('user_name', $userName)->exists());
    } else {
        $userName = strtolower(trim($request->user_name));
        
        if (User::where('user_name', $userName)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'اسم المستخدم موجود بالفعل'
            ], 200);
        }
    }

    // 2. التحقق من البريد الإلكتروني (لو موجود)
    if ($request->filled('email')) {
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'البريد الإلكتروني موجود بالفعل'
            ], 200);
        }
    }

    // 3. التحقق من رقم الهاتف (لو موجود)
    if ($request->filled('phone')) {
        if (User::where('phone', $request->phone)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'رقم الهاتف موجود بالفعل'
            ], 200);
        }
    }

    $passwordToSave = '';

    // 4. التحقق الأمني لتسجيل الدخول الاجتماعي
    if ($request->filled('provider') && $request->filled('firebase_token')) {
        try {
            $auth = Firebase::auth();
            $verifiedIdToken = $auth->verifyIdToken($request->firebase_token);
            $uid = $verifiedIdToken->claims()->get('sub');

            $firebaseEmail = $auth->getUser($uid)->email;

            if (!$request->filled('email') || strtolower($firebaseEmail) !== strtolower($request->email)) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات البريد غير متطابقة، تم رفض الطلب أمنياً.'
                ], 200);
            }

            // كلمة مرور للمستخدمين عبر Google/Apple
            $passwordToSave = Hash::make($request->password);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'توكن فايربيز غير صالح أو منتهي الصلاحية'
            ], 200);
        }
    } else {
        // 5. التسجيل العادي
        $passwordToSave = Hash::make($request->password);
    }

    // 6. إنشاء المستخدم
    try {
        $userCreated = User::create([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'user_name' => $userName, // ✅ بعد الـ normalize
            'email' => $request->filled('email') ? $request->email : null,
            'phone' => $request->phone,
            'password' => $passwordToSave,
            'photo' => $request->photo,
            'is_game_free' => 'paid',
            'provider' => $request->filled('provider') ? $request->provider : null,
            'firebase_token' => $request->filled('firebase_token') ? $request->firebase_token : null,
            'date_of_birth' => $request->date_of_birth,
        ]);

        if ($userCreated) {
            $token = $userCreated->createToken('ourapptoken')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'تم التسجيل بنجاح',
                'user' => $userCreated,
                'token' => $token
            ], 200);
        }

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'فشل التسجيل',
            'error' => $e->getMessage()
        ], 200);
    }

    return response()->json([
        'success' => false,
        'message' => 'فشل التسجيل'
    ], 200);
}


    public function uploadUpadteImageApi(Request $request,$user_id)
    {


        $user = User::find($user_id);

        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/user_images/'.$user->photo));
            $filename = 'app-'.date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/user_images'),$filename);


            return response()->json(['link' => $filename], 200);

        }

      else {
            return response()->json(['error' => 'Image not provided'], 400);
        }




    }

    public function uploadImageApi(Request $request)
    {



        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/user_images/'.$user->photo));
            $filename = 'app-'.date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/user_images'),$filename);


            return response()->json(['link' => $filename], 200);

        }

      else {
            return response()->json(['error' => 'Image not provided'], 400);
        }




    }



/// editUserApi

    public function editUserApi(Request $request)
    {


        $user_id = $request->id;

        $user = User::findOrFail($user_id);





        if($request->password != "")
        {

            $user->password = Hash::make($request->password);

        }


        $user->fname = $request->fname;
        $user->lname = $request->lname;
        $user->phone = $request->phone;
        $user->photo = $request->photo;
        if ($request->has('photo_approval_status')) {
            $user->photo_approval_status = $request->photo_approval_status;
        }
        // $user->address = $request->address;
        $user->save();





        $token = "Non";

        return response()->json([
            'success' => true,
            'message' => 'updated user successful',
            'user' => $user, // Return all user data
            'token' => $token
        ], 200);







    }



        public function socialLoginApi(Request $request) {
    // 1. التحقق من وصول التوكن من فلاتر
    $request->validate([
        'firebase_token' => 'required|string',
        'provider' => 'nullable|string'
    ]);

    try {
        // 2. فحص التوكن عبر Firebase
        $auth = Firebase::auth();
        $verifiedIdToken = $auth->verifyIdToken($request->firebase_token);

        $uid = $verifiedIdToken->claims()->get('sub');
        $userRecord = $auth->getUser($uid);
        $email = $userRecord->email;

        // 3. البحث عن المستخدم في قاعدة البيانات
        $user = User::where('email', $email)->first();

        if ($user) {
            // 🟢 الحالة الأولى: المستخدم مسجل مسبقاً (تم الدخول بنجاح)
            $token = $user->createToken('ourapptoken')->plainTextToken;

            return response()->json([
                'success' => true,
                'is_new_user' => false,
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token
            ], 200);

        } else {
            // 🟡 الحالة الثانية: المستخدم جديد (نحتاج رقم الهاتف من فلاتر)
            return response()->json([
                'success' => true,
                'is_new_user' => true,
                'message' => 'Needs phone number to complete registration',
                'google_data' => [
                    'name' => $userRecord->displayName,
                    'email' => $email,
                    'avatar' => $userRecord->photoUrl ?? null
                ]
            ], 200);
        }

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid or expired Firebase token',
            'error_details' => $e->getMessage() // 👈 أضفنا هذا السطر لكشف الخطأ
        ], 401);
    }
}



    public function getUserByEmail($email)
    {
        $user = User::where('email', $email)->first(); // Returns true or false

        if ($user) {

            $token = $user->createToken('ourapptoken')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Email exists',
                'token' => $token,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Email not found',
                'token' => 'non',

            ], 404);
        }
    }





    public function editUserPasswordApi(Request $request)
    {

  // Retrieve the token from the request header
  $token = $request->bearerToken();

  if (!$token) {

    return response()->json([
        'success' => false,
        'message' => 'Token not provided',
        'token' => 'non',


    ], 401);
  }

  // Find the token in the database
  $accessToken = PersonalAccessToken::findToken($token);

  if (!$accessToken) {

      return response()->json([
        'success' => false,
        'message' => 'Invalid token',
        'token' => 'non',


    ], 401);
  }

        $email = $request->email;

        $user = User::where('email', $email)->first(); // Returns true or false


        // $user_id = $request->id;


        // $user = User::findOrFail($user_id);







        if($request->password != "")
        {

            $user->password = Hash::make($request->password);

        }



        $user->save();





        $token = "Non";

        return response()->json([
            'success' => true,
            'message' => 'updated password successful',
            'token' => 'non',


        ], 200);







    }



    public function updateUserGamesNumber(Request $request)
    {
        $checkForIncrementGameOrDecrement = $request->checkForIncrementGameOrDecrement;
        $user_id = $request->user_id;
        $numberRequestGame = $request->numberRequestGame;

        $user = User::findOrFail($user_id);
        $numberOfGames = $user->number_of_games;

        if ($checkForIncrementGameOrDecrement == 'increment') {
            $numberOfGames += $numberRequestGame;
            $user->number_of_games = $numberOfGames;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'increment games successful',
                'numberOfGames'=>$user->number_of_games,

            ], 200);
        } elseif ($checkForIncrementGameOrDecrement == 'decrement') {
            $numberOfGames -= $numberRequestGame;
            $user->number_of_games = $numberOfGames;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'decrement games successful',
                'numberOfGames'=>$user->number_of_games,

            ], 200);
        }

        // Fallback response if the value doesn't match 'increment' or 'decrement'
        return response()->json([
            'success' => false,
            'message' => 'Invalid action specified',
        ], 400);
    }







 public function deleteUserApi(Request $request){

    $id = $request->delet_user_id;


        $user = User::findOrFail($id);
        $img = $user->photo;

        // unlink($img );

      //  return $user->photo;

        $path = 'upload/user_images/'.$user->photo;

        if ($user->photo && file_exists(public_path($path))) {
            unlink(public_path($path));
        }
        User::findOrFail($id)->delete();

    return response()->json([
                'success' => true,
                'message' => 'user deleted successful',


            ], 200);

        // return redirect()->back()->with($notification);
    }// End Method


   public function updateOnlineUserPoints(Request $request)
    {
                    $user_id = $request->user_id;

            $newPoints = $request->new_points;

                    $user = User::findOrFail($user_id);


  $user->online_points = $newPoints;

        // $user->address = $request->address;
        $user->save();





        $token = "Non";

        return response()->json([
            'success' => true,
            'message' => 'updated user successful',
            'user' => $user, // Return all user data
            'token' => $token
        ], 200);
    }

    public function exportUsers()
    {
        return Excel::download(new UsersExport, 'users_' . date('Y_m_d_His') . '.xlsx');
    }
}
