<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Valider les données envoyées
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Tenter l'authentification pour l'admin
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return response()->json([
                'message' => 'Connexion admin réussie',
                'admin'   => Auth::guard('admin')->user(),
            ]);
        }
        // Sinon, tenter l'authentification pour l'utilisateur
        elseif (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            return response()->json([
                'message' => 'Connexion utilisateur réussie',
                'user'    => Auth::guard('web')->user(),
            ]);
        }
        // Si aucun n'a fonctionné
        else {
            return response()->json(['message' => 'Identifiants invalides'], 401);
        }
    }

    public function logout(Request $request)
    {
        // Pour la déconnexion, tu peux utiliser le guard qui est connecté
        Auth::guard('web')->logout();
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Déconnexion réussie']);
    }
}
