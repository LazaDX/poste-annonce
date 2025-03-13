<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PostController extends Controller
{
   // GET All api\posts
    public function index()
    {
        $posts = Post::with(['user', 'category', 'comments', 'favorites', 'images'])->get();

        return response()->json($posts);
    }

   // POST api\posts
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'title_price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        $post = Post::create([
            'title' => $request->title,
            'title_price' => $request->title_price,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'category_id' => $request->category_id,
        ]);

        return response()->json([
            'message' => 'Annonce créée avec succès !',
            'data' => $post
        ], 201);
    }

    // GET api\posts\{id}
    public function show(string $id)
    {
        $post = Post::with(['user', 'category', 'comment', 'favoris', 'image'])->find($id);

        if (!$post) {
            return response()->json(['message' => 'Annonce non trouvée'], 404);
        }

        return response()->json($post);
    }

    // PUT api\posts\{id}
    public function update(Request $request, string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Annonce non trouvée'], 404);
        }

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'title_price' => 'sometimes|required|numeric|min:0',
            'description' => 'sometimes|required|string',
            'category_id' => 'sometimes|required|exists:categories,id',
        ]);

        $post->update([
            'title' => $request->title ?? $post->title,
            'title_price' => $request->title_price ?? $post->title_price,
            'description' => $request->description ?? $post->description,
            'category_id' => $request->category_id ?? $post->category_id,
        ]);

        return response()->json([
            'message' => 'Annonce mise à jour avec succès !',
            'data' => $post
        ]);
    }

    // DELETE
    public function destroy(string $id)
    {
        $post= Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Annonce non trouvée'], 404);
        }

        $post->delete();

        return response()->json(['message' => 'Annonce supprimée avec succès !']);
    }
}
