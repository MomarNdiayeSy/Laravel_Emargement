<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    // Rediriger vers /login après réinitialisation
    protected $redirectTo = '/login';

    public function __construct()
    {
        $this->middleware('guest');
    }

    // Surcharge la méthode de réinitialisation pour éviter la connexion automatique
    public function reset(Request $request)
    {
        $request->validate($this->rules(), $this->validationErrorMessages());

        // Tente de réinitialiser le mot de passe
        $response = $this->broker()->reset(
            $this->credentials($request),
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );

        // Si succès, redirige vers /login avec un message
        return $response == Password::PASSWORD_RESET
            ? $this->sendResetResponse($request, $response)
            : $this->sendResetFailedResponse($request, $response);
    }

    // Réponse personnalisée pour succès
    protected function sendResetResponse(Request $request, $response)
    {
        return redirect($this->redirectTo)
            ->with('status', 'Votre mot de passe a été réinitialisé avec succès. Veuillez vous connecter avec votre nouveau mot de passe.');
    }

    // Réponse personnalisée pour échec
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => trans($response)]);
    }
}
