<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Mettre à jour last_login_at ici
            $user = Auth::user();
            $user->update(['last_login_at' => now()]);
            $this->authenticated($request, $user);

            // Redirection selon le rôle
            $role = $user->role;
            switch ($role) {
                case 'admin':
                    return redirect()->intended(route('admin.users.index'));
                case 'professeur':
                    return redirect()->intended(route('professeur.cours.index'));
                case 'gestionnaire':
                    return redirect()->intended(route('gestionnaire.cours.index'));
                default:
                    return redirect('/login'); // Fallback
            }
        }

        return back()->withErrors(['email' => 'Identifiants incorrects.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    protected function authenticated(Request $request, $user)
    {
        $user->update(['last_login_at' => now()]);
    }
}
