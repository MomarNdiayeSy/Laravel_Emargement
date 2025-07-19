<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    use RegistersUsers;

    // Redirection après inscription (sera gérée dans redirectTo)
    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    protected function validator(array $data)
    {
        return \Validator::make($data, [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:professeur,gestionnaire'], // Pas admin pour l'inscription publique
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);
    }
    protected function redirectTo()
    {
        $role = Auth::user()->role;
        switch ($role) {
            case 'professeur':
                return route('professeur.cours.index');
            case 'gestionnaire':
                return route('gestionnaire.cours.index');
            default:
                return '/login'; // Fallback
        }
    }

    // Surcharge de la méthode registered pour une redirection immédiate
    protected function registered(Request $request, $user)
    {
        return redirect($this->redirectTo());
    }
}
