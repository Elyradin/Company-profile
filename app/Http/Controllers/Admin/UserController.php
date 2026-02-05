<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // List users
    public function index(Request $request)
    {
        // ensure admin
        $user = $request->user();
        if (!$user || $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $users = User::orderBy('id')->get();
        return response()->json($users);
    }

    // store new user
    public function store(Request $request)
    {
        $user = $request->user();
        if (!$user || $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'nullable|string|in:admin,member',
        ]);

        $newUser = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? 'member',
        ]);

        return response()->json(['message' => 'User created', 'user' => $newUser], 201);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        if (!$user || $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $u = User::find($id);
        if (!$u) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json($u);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        if (!$user || $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $u = User::find($id);
        if (!$u) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $u->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'nullable|string|in:admin,member',
        ]);

        if (isset($validated['name'])) $u->name = $validated['name'];
        if (isset($validated['email'])) $u->email = $validated['email'];
        if (!empty($validated['password'])) $u->password = Hash::make($validated['password']);
        if (isset($validated['role'])) $u->role = $validated['role'];

        $u->save();

        return response()->json(['message' => 'User updated', 'user' => $u]);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        if (!$user || $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $u = User::find($id);
        if (!$u) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $u->delete();

        return response()->json(['message' => 'User deleted']);
    }
}
