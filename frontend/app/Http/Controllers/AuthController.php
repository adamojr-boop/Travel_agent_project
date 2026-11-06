<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function registerForm()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);
        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages(['name' => 'Credenziali non valide.']);
        }
        $request->session()->regenerate();

        return redirect()->intended(route('chat.index'));
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255', 'unique:users,name'],
            'password' => ['required', 'confirmed', Password::min(12)],
            'invite_code' => ['required', 'string'],
        ]);
        $invite = config('services.registration.invite_code');
        if (! is_string($invite) || $invite === '' || ! hash_equals($invite, $data['invite_code'])) {
            throw ValidationException::withMessages(['invite_code' => 'Codice di invito non valido.']);
        }

        $user = DB::transaction(function () use ($data) {
            $owner = DB::table('initial_data_owner')->where('id', 1)->lockForUpdate()->first();
            $user = User::create(['name' => $data['name'], 'password' => $data['password']]);
            if ($owner->user_id === null) {
                foreach (['conversations', 'itinerari', 'fornitori'] as $table) {
                    DB::table($table)->whereNull('user_id')->update(['user_id' => $user->id]);
                }
                DB::table('initial_data_owner')->where('id', 1)->update(['user_id' => $user->id]);
            }
            return $user;
        });
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('chat.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
