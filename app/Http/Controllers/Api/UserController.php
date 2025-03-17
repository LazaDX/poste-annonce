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
    try {
        // Recherche de l'utilisateur
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        // Validation des données
        $validatedData = $request->validate([
            'first_name'    => 'sometimes|required|string|max:255',
            'last_name'     => 'sometimes|required|string|max:255',
            'email'         => 'sometimes|required|email|unique:users,email,' . $user->id,
            'contact'       => 'sometimes|required|string|max:10',
            'password'      => 'sometimes|required|min:6',
            'profile_image' => 'sometimes|required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            if ($file->isValid()) {
                $imageName = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images'), $imageName);
                $validatedData['profile_image'] = 'images/' . $imageName; // Mettre à jour le chemin de l'image
            } else {
                return response()->json(['message' => 'Fichier invalide'], 400);
            }
        }

        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        $user->update($validatedData);

        return response()->json([
            'message' => 'Utilisateur mis à jour avec succès',
            'data' => $user
        ], 200);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'message' => 'Erreur de validation',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Une erreur est survenue',
            'error' => $e->getMessage()
        ], 500);
    }
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
