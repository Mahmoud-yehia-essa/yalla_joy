<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;


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
            'message' => 'Registration failed'
        ], 500);
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


}
