<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Get All User
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Post
    public function store(Request $request)
    {
        
        $request->validate([
           'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'contact' => 'required|string|max:10',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'contact' => $request->contact, 
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Utilisateur créé avec succès !',
            'data' => $user
        ], 201);
        
    }

    // Get User by Id
    public function show(string $id)
    {
        $user = User::find($id);
        if(!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé']);
        }

        return response()->json($user);
    }

    // Put User by Id
    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        if(!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        $user->update([
            'first_name' => $request->first_name ?? $user->first_name,
            'last_name' => $request->last_name ?? $user->last_name,
            'contact' => $request->contact ?? $user->contact,
            'email' => $request->email ?? $user->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return response()->json($user);
    }

   
    // Delete
    public function destroy(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé']);
    }
}
