<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Str;

class SocialLoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'provider' => 'required|in:google,facebook,apple',
            'token' => 'required'
        ]);

        return match ($request->provider) {
            'google'   => $this->google($request->token),
            'facebook' => $this->facebook($request->token),
            'apple'    => $this->apple($request->token),
        };
    }

    /* ================= GOOGLE ================= */

    private function google($token)
    {
        $res = Http::get(
            'https://oauth2.googleapis.com/tokeninfo',
            ['id_token' => $token]
        );

        if ($res->failed()) {
            return response()->json(['message' => 'Invalid Google token'], 401);
        }

        $data = $res->json();

        return $this->handleUser(
            email: $data['email'],
            fname: $data['given_name'] ?? 'Google',
            lname: $data['family_name'] ?? 'User',
            photo: $data['picture'] ?? null,
            type: 'google'
        );
    }

    /* ================= FACEBOOK ================= */

    private function facebook($token)
    {
        $res = Http::get('https://graph.facebook.com/me', [
            'fields' => 'id,first_name,last_name,email,picture',
            'access_token' => $token
        ]);

        if ($res->failed()) {
            return response()->json(['message' => 'Invalid Facebook token'], 401);
        }

        $data = $res->json();

        return $this->handleUser(
            email: $data['email'] ?? $data['id'].'@facebook.com',
            fname: $data['first_name'] ?? 'Facebook',
            lname: $data['last_name'] ?? 'User',
            photo: $data['picture']['data']['url'] ?? null,
            type: 'facebook'
        );
    }

    /* ================= APPLE ================= */

    private function apple($token)
    {
        return $this->handleUser(
            email: $token.'@apple.com',
            fname: 'Apple',
            lname: 'User',
            photo: null,
            type: 'apple'
        );
    }

    /* ================= COMMON ================= */

    private function handleUser($email, $fname, $lname, $photo, $type)
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = User::create([
                'fname' => $fname,
                'lname' => $lname,
                'email' => $email,
                'photo' => $photo,
                'password' => bcrypt(Str::random(20)),
                'register_type' => $type,
                'status' => 'active',
                'role' => 'user',
                'points' => 0,
            ]);
        }

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => $user
        ]);
    }
}
