<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function show(Request $request): View|JsonResponse
    {
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Endpoint autentikasi. Kirimkan HTTP POST dengan payload email dan password.',
            ]);
        }

        return view('auth.login');
    }

    public function login(Request $request): JsonResponse|RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = Str::transliterate(Str::lower($credentials['email']).'|'.$request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.",
                ], 429);
            }

            return back()
                ->withErrors(['email' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik."])
                ->onlyInput('email');
        }

        $user = User::query()->where('email', $credentials['email'])->first();

        if ($user && ! $user->is_active) {
            RateLimiter::hit($throttleKey, 60);

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Akun Anda sedang dinonaktifkan. Hubungi Atasan / Admin.',
                ], 403);
            }

            return back()
                ->withErrors(['email' => 'Akun Anda sedang dinonaktifkan. Hubungi Atasan / Admin.'])
                ->onlyInput('email');
        }

        if (! Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'is_active' => true,
        ], $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, 60);

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Email atau password tidak sesuai.',
                ], 422);
            }

            return back()
                ->withErrors(['email' => 'Email atau password tidak sesuai.'])
                ->onlyInput('email');
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        $authenticated = $request->user();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Login Berhasil',
                'user' => $authenticated,
            ]);
        }

        return redirect()->route('account.edit');
    }

    public function logout(Request $request): JsonResponse|RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Anda berhasil keluar dari aplikasi.',
            ]);
        }

        return redirect()->route('login')->with('status', 'Anda berhasil keluar dari aplikasi.');
    }
}
