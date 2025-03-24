<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{

    public function index()
    {
        $favorites = Favorite::with(['user', 'post'])->get();

        return response()->json($favorites);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
        ]);

        $exists = Favorite::where('user_id', $request->user_id)
                          ->where('post_id', $request->post_id)
                          ->exists();

        if ($exists) {
            return response()->json(['message' => 'Ce post est déjà dans vos favoris'], 409);
        }

        $favorite = Favorite::create([
            'user_id' => $request->user_id,
            'post_id' => $request->post_id,
        ]);

        return response()->json([
            'message' => 'Favori ajouté avec succès !',
            'data' => $favorite
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $favorite = Favorite::with(['user', 'post'])->find($id);

        if (!$favorite) {
            return response()->json(['message' => 'Favori non trouvé'], 404);
        }

        return response()->json($favorite);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $favorite = Favorite::find($id);

        if (!$favorite) {
            return response()->json(['message' => 'Favori non trouvé'], 404);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
        ]);

        $favorite->update([
            'user_id' => $request->user_id,
            'post_id' => $request->post_id,
        ]);

        return response()->json([
            'message' => 'Favori mis à jour avec succès',
            'data' => $favorite
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $favorite = Favorite::find($id);

        if (!$favorite) {
            return response()->json(['message' => 'Favori non trouvé'], 404);
        }

        $favorite->delete();

        return response()->json(['message' => 'Favori supprimé avec succès']);
    }

    public function getUserFavoritesPosts(Request $request)
    {
        // Récupérer l'utilisateur connecté
        $user = auth()->user();

        // Vérification : utilisateur connecté ?
        if (!$user) {
            return response()->json(['message' => 'Non autorisé'], 401);
        }

        // Récupérer les favoris de l'utilisateur AVEC les annonces (posts)
        $favoritesPosts = Favorite::with('post')
                            ->where('user_id', $user->id)
                            ->get()
                            ->pluck('post') // On récupère uniquement les posts
                            ->filter();     // Supprime les valeurs nulles si jamais

        // Retourner les posts favoris
        return response()->json([
            'posts' => $favoritesPosts
        ]);
    }

    // public function getUserFavoritesPosts(Request $request)
    // {
    //     // Récupérer l'utilisateur connecté
    //     $user = auth()->user();

    //     // Vérification : utilisateur connecté ?
    //     if (!$user) {
    //         return response()->json(['message' => 'Non autorisé'], 401);
    //     }

    //     // Récupérer les favoris de l'utilisateur AVEC les annonces (posts)
    //     $favorites = Favorite::with('post')
    //                         ->where('user_id', $user->id)
    //                         ->get();

    //     // Retourner les favoris avec leurs IDs et les détails des posts
    //     return response()->json([
    //         'favorites' => $favorites
    //     ]);
    // }

    public function removeByPost($postId)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non authentifié'], 401);
        }

        $deleted = Favorite::where('user_id', $user->id)
                           ->where('post_id', $postId)
                           ->delete();

        if ($deleted) {
            return response()->json(['message' => 'Favori supprimé avec succès']);
        } else {
            return response()->json(['message' => 'Favori non trouvé'], 404);
        }
    }
}
