<?php

namespace App\Http\Controllers\Admin; // <--- UBAH INI (Tambahkan \Admin)

use App\Http\Controllers\Controller;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServicesController extends Controller
{
    // READ (Bisa diakses Admin & Member)
    public function index()
    {
        // JANGAN ADA LOGIKA IF ADMIN DISINI
        $services = Service::all();
        return response()->json($services, 200);
    }

    // CREATE (Hanya Admin)
    public function store(Request $request)
    {
        // Cek apakah user adalah admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Forbidden: Only admin can create services'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $service = Service::create($validated);

        return response()->json([
            'message' => 'Service created successfully',
            'data' => $service
        ], 201);
    }

    // READ DETAIL (Bisa diakses Admin & Member)
    public function show($id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        return response()->json($service, 200);
    }

    // UPDATE (Hanya Admin)
    public function update(Request $request, $id)
    {
        // Cek apakah user adalah admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Forbidden: Only admin can update services'], 403);
        }

        $service = Service::find($id);

        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $service->update($validated);

        return response()->json([
            'message' => 'Service updated successfully',
            'data' => $service
        ], 200);
    }

    // DELETE (Hanya Admin)
    public function destroy($id)
    {
        // Cek apakah user adalah admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Forbidden: Only admin can delete services'], 403);
        }

        $service = Service::find($id);

        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        $service->delete();

        return response()->json(['message' => 'Service deleted successfully'], 200);
    }
}

