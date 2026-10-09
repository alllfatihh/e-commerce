<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function showCheckoutAuth()
    {
        return view('checkout-auth');
    }

    public function seamlessRegisterOrLogin(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'phone' => 'required|string',
            'address' => 'required|string',
            'area_id' => 'required|string',
        ]);

        if (Auth::check()) {
            $user = Auth::user();
            $user->update([
                'phone' => $request->phone,
                'address' => $request->address,
                'area_id' => $request->area_id,
            ]);
            return response()->json(['success' => true]);
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Coba login
            if (Hash::check($request->password, $user->password)) {
                // Update detail jika kosong
                $user->update([
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'area_id' => $request->area_id,
                ]);
                Auth::login($user);
                $request->session()->regenerate();
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Email sudah terdaftar namun password salah.'], 401);
            }
        } else {
            // Registrasi baru
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
                'area_id' => $request->area_id,
            ]);
            Auth::login($user);
            $request->session()->regenerate();
            return response()->json(['success' => true]);
        }
    }

    public function googleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => null, 
                ]);
            } else {
                // Update google_id if empty
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->getId()]);
                }
            }

            Auth::login($user);
            request()->session()->regenerate();

            if (!$user->area_id) {
                return redirect('/auth/checkout');
            }

            return redirect('/?open_checkout=true');
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Login via Google gagal.');
        }
    }

    public function updateProfile(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Not authenticated.'], 401);
        }

        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'area_id' => 'required|string',
            'avatar' => 'nullable|image|max:4096'
        ]);

        $user = Auth::user();
        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'area_id' => $request->area_id,
        ];

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        $user->update($data);

        return response()->json(['success' => true]);
    }
}
