<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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

       $validated = $request->validate([
           'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'contact' => 'required|string|max:10',
            'localisation' => 'nullable|string|max:255',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'contact' => $validated['contact'],
            'localisation' => $validated['localisation'] ?? 'Non spécifiée',
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'message' => 'Utilisateur créé avec succès !',
            'data' => $user
        ], 201);

    }

    // Get User by Id
    public function show(string $id)
    {
        $user = User::withCount('posts')->find($id);
        if(!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé']);
        }

        return response()->json($user);
    }


    public function update(Request $request, string $id)
    {
        try {
            // 1. Recherche de l'utilisateur
            $user = User::find($id);

            if (!$user) {
                return response()->json(['message' => 'Utilisateur non trouvé'], 404);
            }

            // 2. Validation des données
            $validatedData = $request->validate([
                'first_name'    => 'sometimes|required|string|max:255',
                'last_name'     => 'sometimes|required|string|max:255',
                'email'         => 'sometimes|required|email|unique:users,email,' . $user->id,
                'contact'       => 'sometimes|required|string|max:15', // Au cas où le numéro est plus long
                'localisation'  => 'sometimes|required|string|max:255',
                'password'      => 'sometimes|required|min:6',
                'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            // 3. Gestion du mot de passe
            if (!empty($validatedData['password'])) {
                $validatedData['password'] = Hash::make($validatedData['password']);
            }

            // 4. Gestion de l'image de profil
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');

                if ($file->isValid()) {
                    $imageName = time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('images'), $imageName);

                    // Ici, on met à jour directement l'utilisateur avec le chemin de l'image
                    $validatedData['profile_image'] = 'images/' . $imageName;
                } else {
                    return response()->json(['message' => 'Fichier image invalide'], 400);
                }
            }

            // 5. Mise à jour des données utilisateur
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

    public function getTotalUsers()
    {
        $totalUsers = User::count();
        return response()->json(['totalUsers' => $totalUsers]);
    }

    public function getUsersByMonth()
    {
        $usersByMonth = DB::table('users')
        ->selectRaw('EXTRACT(MONTH FROM created_at) AS month, COUNT(*) AS count')
        ->groupByRaw('EXTRACT(MONTH FROM created_at)')
        ->orderBy('month', 'asc')
        ->get();

        return response()->json([
            'status' => true,
            'data' => $usersByMonth
        ], 200);
    }
}
