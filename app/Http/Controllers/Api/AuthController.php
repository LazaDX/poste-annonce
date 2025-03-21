<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();

            return response()->json([
                'message' => 'Connexion admin réussie',
                'admin'   => Auth::guard('admin')->user(),
            ]);
        }

        elseif (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            $this->incrementVisitCount();

            return response()->json([
                'message' => 'Connexion utilisateur réussie',
                'user'    => Auth::guard('web')->user(),
            ]);
        }

        else {
            return response()->json(['message' => 'Identifiants invalides'], 401);
        }
    }

    private function incrementVisitCount()
    {
        // $visit = Visit::firstOrCreate(['id' => 1], ['count' => 0]);
        // $visit->increment('count');
        $filePath = storage_path('app/visits.txt');
        $count = (int) file_get_contents($filePath);
        $count++;
        file_put_contents($filePath, $count);
    }

    public function getVisitCount()
    {
        // $visit = Visit::first();
        // return response()->json(['visitCount' => $visit ? $visit->count : 0]);
        $filePath = storage_path('app/visits.txt');
        $count = (int) file_get_contents($filePath);
        return response()->json(['visitCount' => $count]);
    }

    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }

        if(Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Déconnexion réussie']);
    }




}
