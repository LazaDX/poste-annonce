<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
   // Get All Admin
    public function index()
    {
        $admins = Admin::all();
        return response()->json($admins);
    }

   // Post
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins',
            'password' => 'required|min:6',
        ]);

        $admin = Admin::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Adminitrateur crée avec succès !',
            'data' => $admin
        ], 201);
    }

     // Get User by Id
    public function show(string $id)
    {
        $admin = Admin::find($id);
        if(!$admin) {
            return response()->json(['message' => 'Administrateur non trouvé']);
        }

        return response()->json($admin);
    }

   // Put Admin by Id
    public function update(Request $request, string $id)
    {
        $admin = Admin::find($id);
        if(!$admin) {
            return response()->json(['message' => 'Administrateur non trouvé'], 404);
        }

        $admin->update([
            'first_name' => $request->first_name ?? $admin->first_name,
            'last_name' => $request->last_name ?? $admin->last_name,
            'email' => $request->email ?? $admin->email,
            'password' => $request->password ? Hash::make($request->password) : $admin->password,
        ]);

        return response()->json($admin);
    }

    // Delete
    public function destroy(string $id)
    {
        $admin = Admin::findOrFail($id);

        $admin->delete();

        return response()->json(['message' => 'Administrateur supprimé']);
    }

    public function getOnlineUsers()
    {
        $users = User::where('is_online', true)->get();

        return response()->json([
            'online_users' => $users
        ]);
    }

    public function getNewUsers()
    {
        // Aujourd'hui
        $today = Carbon::today();

        // Hier
        $yesterday = Carbon::yesterday();

        // Récupérer les utilisateurs inscrits hier et aujourd'hui
        $users = User::whereDate('created_at', $today)
                    ->orWhereDate('created_at', $yesterday)
                    ->get();

        return response()->json($users);
    }
}
