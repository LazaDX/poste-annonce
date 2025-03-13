<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class CommentController extends Controller
{
    // GET All api\comments
    public function index()
    {
        $comments = Comment::with(['users', 'posts'])->get();
        return response()->json($comments);
    }

    // POST api\comments
    public function store(Request $request)
    {
        $request->validate([
            
            'user_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|string',
        ]);

        $comment= Comment::create([
            'user_id' => $request->user_id,
            'post_id' => $request->post_id,
            'content' => $request->content,
        ]);

        return response()->json([
            'message' => 'Commentaire ajouté avec succès',
            'data' => $comment
        ], 201);
    }

   // GET by Id api\comments\{id}
    public function show(string $id)
    {
        $comment = Comment::with(['user', 'post'])->find($id);

        if (!$comment) {
            return response()->json(['message' => 'Commentaire non trouvé'], 404);
        }

        return response()->json($comment);
    }

    // PUT api\comments\{id}
    public function update(Request $request, string $id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Commentaire non trouvé'], 404);
        }

        $request->validate([
            'content' => 'sometimes|required|string',
        ]);

        $comment->update([
            'content' => $request->content ?? $comment->content
        ]);

        return response()->json([
            'message' => 'Commentaire mis à jour avec succès',
            'data' => $comment
        ]);
    }

    // DELETE api\comments\{id}
    public function destroy(string $id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Commentaire non trouvé'], 404);
        }

        $comment->delete();

        return response()->json(['message' => 'Commentaire supprimé avec succès']);
    }
}
