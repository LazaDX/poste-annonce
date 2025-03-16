<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
   // GET All api\images
    public function index()
    {
        $images = Image::with('post')->get();

        return response()->json($images);
    }

    // POST api\images
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'post_id' => 'required|exists:posts,id',
        ]);

        // Vérification et traitement du fichier
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);

            // Création de l'entrée dans la base de données
            $image = Image::create([
                'image' => 'images/' . $imageName,
                'post_id' => $request->post_id,
            ]);

            return response()->json([
                'message' => 'Image ajoutée avec succès',
                'data' => $image,
            ], 201);
        }

        return response()->json(['error' => 'Aucun fichier téléchargé.'], 400);
    }

    // GET api\images\{id}
    public function show(string $id)
    {
        $image = Image::find($id);

        if (!$image) {
            return response()->json(['message' => 'Image non trouvée'], 404);
        }

        return response()->json($image);
    }

    /// PUT api\images\{id}
    public function update(Request $request, string $id)
    {
        $image = Image::find($id);

        if (!$image) {
            return response()->json(['message' => 'Image non trouvée'], 404);
        }

        // Validation des données
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Vérification et suppression de l'ancien fichier
        if (file_exists(public_path($image->image))) {
            unlink(public_path($image->image));
        }

        // Traitement du nouveau fichier
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        // Mise à jour de l'entrée dans la base de données
        $image->update([
            'image' => 'images/' . $imageName,
        ]);

        return response()->json([
            'message' => 'Image mise à jour avec succès',
            'data' => $image
        ]);
    }

    // DELETE api\images\{id}
    public function destroy(string $id)
{
    $image = Image::find($id);

    if (!$image) {
        return response()->json(['message' => 'Image non trouvée'], 404);
    }

    // Suppression de l'image physique dans le dossier public/images
    if (file_exists(public_path($image->image))) {
        unlink(public_path($image->image));
    }

    // Suppression dans la BDD
    $image->delete();

    return response()->json(['message' => 'Image supprimée avec succès']);
}

}
