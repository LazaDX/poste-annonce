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

        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        $path = $request->file('image')->store('images', 'public');

        $image->update([
            'image_path' => $path,
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

        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();
    }
}
