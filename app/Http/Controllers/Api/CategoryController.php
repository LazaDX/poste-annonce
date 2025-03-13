<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CategoryController extends Controller
{
   // Get All api\categories
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

   // Post api\categories
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category = Category::create([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => 'Categorie crée avec succès',
            'data' => $category
        ], 201);
    }

    // Get by Id api\categories\{id}
    public function show(string $id)
    {
        $category = Category::find($id);
        if(!$category) {
            return response()->json(['message' => 'Categorie non trouvé']);
        }
        
        return response()->json($category);
    }

    // Put by Id api\categories\{id}
    public function update(Request $request, string $id)
    {
        $category = Category::find($id);
        if(!$category) {
            return response()->json(['message' => 'Catégorie non trouvé'], 404);
        }

        $category->update([
            'name' => $request->name ?? $category->name,
        ]);

        return response()->json($category);
    }

    // Delete by Id 
    public function destroy(string $id)
    {
        $category = Category::findOrdFail($id);
        $category->delete();

        return response()->json([
            'message' => "Catégroie supprimé"
        ]);
    }
}
