<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class UserController extends Controller
{
    public function login()
    {
        return  view('auth.user.login');
    }

    public function google()
    {
        return Socialite::driver('google')->redirect();
    }


    // Handle Jika Berhasil Login Menggunakan Google Melakukan Function ini 
    public function handleProviderCallback()
    {
        try {
            $callback = Socialite::driver('google')->stateless()->user();

            $data = [
                'name' => $callback->getName(),
                'email' => $callback->getEmail(),
                'avatar' => $callback->getAvatar(),
                'email_verified' => date('Y-m-d H:i:s', time())
            ];

            $user = User::firstOrCreate(['email' => $data['email']], $data);

            Auth::login($user, true);
            return redirect(route('welcome'));
        } catch (\Exception $e) {
            return redirect()->route('user.login.google')->withErrors(['msg' => 'Gagal login dengan Google: ' . $e->getMessage()]);
        }
    }
}
