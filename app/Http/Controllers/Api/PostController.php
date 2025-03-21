<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
            'type'  => 'string|max:255|nullable',
            'surface'  => 'string|max:255|nullable',
            'city'  => 'string|max:255|nullable',
            'phone'  => 'string|max:255|nullable',
            'whatsapp'  => 'string|max:255|nullable',
            'payment_method'  => 'string|max:255|nullable',
            'vitesse' => 'string|max:255|nullable',
            'moteur'  => 'string|max:255|nullable',
            'nombre_etages'  => 'string|max:255|nullable',
            'nombre_chambres'  => 'string|max:255|nullable',
            'nombre_pieces'  => 'string|max:255|nullable',
            'type_activite' => 'string|max:255|nullable',
            'nombre_couchages'  => 'string|max:255|nullable',
            'commodites'  => 'string|max:255|nullable',
            'location'  => 'string|max:255|nullable',
            'condition'  => 'string|max:255|nullable',
            'type_culture'  => 'string|max:255|nullable',
            'equipements'  => 'string|max:255|nullable',
            'type_exploitation'  => 'string|max:255|nullable',
            'title' => 'required|string|max:255',
            'title_price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        $post = Post::create([
            'type'  => $request->type,
            'surface'  =>$request->surface,
            'city'  => $request->city,
            'phone'  => $request->phone,
            'whatsapp'  => $request->whatsapp,
            'payment_method'  => $request->payment_method,
            'vitesse' => $request->vitesse,
            'moteur'  => $request->moteur,
            'nombre_etages'  => $request->nombre_etages,
            'nombre_chambres'  =>$request->nombre_chambres,
            'nombre_pieces'  => $request->nombre_pieces,
            'type_activite' => $request->type_activite,
            'nombre_couchages'  => $request->nombre_couchages,
            'commodites'  => $request->commodites,
            'location' => $request->location,
            'condition' => $request->condition,
            'type_culture'  => $request->type_culture,
            'equipements'  =>$request->equipements,
            'type_exploitation'  => $request->type_exploitation,
            'title' => $request->title,
            'title_price' => $request->title_price,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'category_id' => $request->category_id
        ]);

        return response()->json([
            'message' => 'Annonce créée avec succès !',
            'data' => $post
        ], 201);
    }

    // GET api\posts\{id}
    public function show(string $id)
    {
        $post = Post::with(['user', 'category', 'comments', 'favorites', 'images'])->find($id);

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
            'type'  => 'sometimes|string|max:255',
            'surface'  => 'sometimes|string|max:255',
            'city'  => 'sometimes|string|max:255',
            'phone'  => 'sometimes|string|max:255',
            'whatsapp'  => 'sometimes|string|max:255',
            'payment_method'  => 'sometimes|string|max:255',
            'vitesse' => 'sometimes|string|max:255',
            'moteur'  => 'sometimes|string|max:255',
            'nombre_etages'  => 'sometimes|string|max:255',
            'nombre_chambres'  => 'sometimes|string|max:255',
            'nombre_pieces'  => 'sometimes|string|max:255',
            'type_activite' => 'sometimes|string|max:255',
            'nombre_couchages'  => 'sometimes|string|max:255',
            'commodites'  => 'sometimes|string|max:255',
            'location'  => 'sometimes|string|max:255',
            'condition'  => 'sometimes|string|max:255',
            'type_culture'  => 'sometimes|string|max:255',
            'equipements'  => 'sometimes|string|max:255',
            'type_exploitation'  => 'sometimes|string|max:255',
            'title' => 'sometimes|required|string|max:255',
            'title_price' => 'sometimes|required|numeric|min:0',
            'description' => 'sometimes|required|string',
            'user_id' => 'sometimes|required|exists:users,id',
            'category_id' => 'sometimes|required|exists:categories,id',
        ]);

        $post->update([
            'type'  =>  $request->type  ?? $post->type,
            'surface'  =>  $request->surface  ?? $post->surface,
            'city'  =>  $request->city  ?? $post->city,
            'phone'  =>  $request->phone  ?? $post->phone,
            'whatsapp'  =>  $request->whatsapp  ?? $post->whatsapp,
            'payment_method'  =>  $request->payment_method  ?? $post->payment_method,
            'vitesse' =>  $request->vitesse ?? $post->vitesse,
            'moteur'  =>  $request->moteur  ?? $post->moteur,
            'nombre_etages'  =>  $request->nombre_etages  ?? $post->nombre_etages,
            'nombre_chambres'  =>  $request->nombre_chambres  ?? $post->nombre_chambres,
            'nombre_pieces'  =>  $request->nombre_pieces  ?? $post->nombre_pieces,
            'type_activite' =>  $request->type_activite  ?? $post->type_activite,
            'nombre_couchages'  =>  $request->nombre_couchages  ?? $post->nombre_couchages,
            'commodites'  =>  $request->commodites  ?? $post->commodites,
            'location'  => $request->location  ?? $post->location,
            'condition'  => $request->condition  ?? $post->condition,
            'type_culture'  =>  $request->type_culture  ?? $post->type_culture ,
            'equipements'  =>  $request->equipements  ?? $post->equipements,
            'type_exploitation'  =>  $request->type_exploitation  ?? $post->type_exploitation,
            'title' => $request->title ?? $post->title,
            'title_price' => $request->title_price ?? $post->title_price,
            'description' => $request->description ?? $post->description,
            'user_id' => $request->user_id ?? $post->user_id,
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

    public function getTotalPosts()
    {
        $totalPosts = Post::count();
        return response()->json(['totalPosts' => $totalPosts]);
    }

    public function getPostsByMonth()
    {
        $postsByMonth = DB::table('posts')
        ->selectRaw('EXTRACT(MONTH FROM created_at) AS month, COUNT(*) AS count')
        ->groupByRaw('EXTRACT(MONTH FROM created_at)')
        ->orderBy('month', 'asc')
        ->get();

        return response()->json([
            'status' => true,
            'data' => $postsByMonth
        ], 200);
    }
}
