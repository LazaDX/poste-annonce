<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
}
