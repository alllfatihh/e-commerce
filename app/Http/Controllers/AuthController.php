<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\CartController;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember', false);

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();
            CartController::mergeGuestCart($user->id, $request->cookie('notisse_cart_session'));

            $redirectUrl = $user->isAdmin() ? '/admin' : '/account';

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil.',
                'user' => $user,
                'redirect' => $redirectUrl,
            ]);
        }

        // Check if user exists but has no password (e.g. Google-registered)
        $user = User::where('email', $credentials['email'])->first();
        if ($user && empty($user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Akun ini terdaftar via Google. Silakan klik "Sign in with Google" untuk masuk.',
            ], 422);
        }

        return response()->json([
            'success' => false,
            'message' => 'Email atau password yang Anda masukkan salah.',
        ], 422);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'user',
        ]);

        Auth::login($user, true);
        CartController::mergeGuestCart($user->id, $request->cookie('notisse_cart_session'));
        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran akun berhasil.',
            'user' => $user,
            'redirect' => '/account',
        ]);
    }

    public function showCheckoutAuth()
    {
        $addresses = Auth::check() ? Auth::user()->addresses : collect();
        return view('checkout-auth', compact('addresses'));
    }

    public function seamlessRegisterOrLogin(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'phone' => 'required|string',
            'birthdate' => 'nullable|date',
            'address' => 'required|string',
            'area_id' => 'required|string',
        ]);

        $updateFields = [
            'phone' => $request->phone,
            'address' => $request->address,
            'area_id' => $request->area_id,
        ];
        if ($request->filled('birthdate')) {
            $updateFields['birthdate'] = $request->birthdate;
        }

        if (Auth::check()) {
            $user = Auth::user();
            $user->update($updateFields);
            if ($user->addresses()->count() === 0) {
                $user->addresses()->create([
                    'label' => 'Utama',
                    'recipient_name' => $user->name,
                    'phone' => $user->phone ?? '',
                    'address' => $user->address,
                    'area_id' => $user->area_id,
                    'area_name' => $request->input('area_name'),
                    'is_primary' => true,
                ]);
            }
            return response()->json(['success' => true]);
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Coba login
            if (Hash::check($request->password, $user->password)) {
                // Update detail jika kosong
                $user->update($updateFields);
                if ($user->addresses()->count() === 0) {
                    $user->addresses()->create([
                        'label' => 'Utama',
                        'recipient_name' => $user->name,
                        'phone' => $user->phone ?? '',
                        'address' => $user->address,
                        'area_id' => $user->area_id,
                        'area_name' => $request->input('area_name'),
                        'is_primary' => true,
                    ]);
                }
                Auth::login($user);
                CartController::mergeGuestCart($user->id, $request->cookie('notisse_cart_session'));
                $request->session()->regenerate();
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Email sudah terdaftar namun password salah.'], 401);
            }
        } else {
            // Registrasi baru
            $createFields = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
                'area_id' => $request->area_id,
            ];
            if ($request->filled('birthdate')) {
                $createFields['birthdate'] = $request->birthdate;
            }
            $user = User::create($createFields);
            $user->addresses()->create([
                'label' => 'Utama',
                'recipient_name' => $user->name,
                'phone' => $user->phone ?? '',
                'address' => $user->address,
                'area_id' => $user->area_id,
                'area_name' => $request->input('area_name'),
                'is_primary' => true,
            ]);
            Auth::login($user);
            CartController::mergeGuestCart($user->id, $request->cookie('notisse_cart_session'));
            $request->session()->regenerate();
            return response()->json(['success' => true]);
        }
    }

    public function googleRedirect(Request $request)
    {
        try {
            if ($request->has('from')) {
                session(['google_auth_from' => $request->get('from')]);
                session()->save();
            }
            return Socialite::driver('google')
                ->stateless()
                ->with(['prompt' => 'select_account'])
                ->redirect();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Google redirect error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Gagal menghubungkan ke Google: ' . $e->getMessage());
        }
    }

    public function googleCallback(Request $request)
    {
        try {
            if (!$request->has('code') || $request->has('error')) {
                $errorMsg = $request->get('error_description', $request->get('error', 'Login Google dibatalkan atau ditolak.'));
                \Illuminate\Support\Facades\Log::warning('Google callback canceled or missing code: ' . $errorMsg);
                return redirect('/login')->with('error', $errorMsg);
            }

            $googleUser = Socialite::driver('google')->stateless()->user();
            
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName() ?? 'User',
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => null, 
                ]);
            } else {
                $updates = [];
                if (!$user->google_id) {
                    $updates['google_id'] = $googleUser->getId();
                }
                if (!$user->getRawOriginal('avatar') && $googleUser->getAvatar()) {
                    $updates['avatar'] = $googleUser->getAvatar();
                }
                if (!empty($updates)) {
                    $user->update($updates);
                }
            }

            Auth::login($user, true);
            CartController::mergeGuestCart($user->id, request()->cookie('notisse_cart_session'));
            request()->session()->regenerate();

            $from = session()->pull('google_auth_from');
            if ($from === 'login' || $from === 'account') {
                return redirect('/account')->with('success', 'Berhasil masuk dengan akun Google!');
            }

            if ($from === 'checkout') {
                return redirect('/checkout')->with('success', 'Berhasil masuk dengan akun Google!');
            }

            if (!$user->area_id) {
                return redirect('/checkout')->with('success', 'Berhasil masuk dengan akun Google! Silakan lengkapi detail pengiriman.');
            }

            return redirect('/checkout')->with('success', 'Berhasil masuk dengan akun Google!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Google login error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Login via Google gagal: ' . $e->getMessage());
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
            'birthdate' => 'nullable|date',
            'address' => 'required|string',
            'area_id' => 'required|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096'
        ]);

        $user = Auth::user();
        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'birthdate' => $request->birthdate,
            'address' => $request->address,
            'area_id' => $request->area_id,
        ];

        if ($request->hasFile('avatar')) {
            if ($user->getRawOriginal('avatar')) {
                $oldAvatar = ltrim($user->getRawOriginal('avatar'), '/');
                if (Str::startsWith($oldAvatar, 'storage/')) {
                    $oldAvatar = substr($oldAvatar, 8);
                }
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($oldAvatar)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldAvatar);
                }
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        $user->update($data);

        return response()->json(['success' => true, 'user' => $user]);
    }
}
