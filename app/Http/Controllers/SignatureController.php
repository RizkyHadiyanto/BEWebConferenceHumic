<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Signature;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;



class SignatureController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // List semua tanda tangan
    public function index()
    {
        try {
            $signatures = Signature::all();
            return response()->json($signatures, 200);
        } catch (\Exception $e) {
            Log::error('Error fetching signatures', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }

    
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'picture' => 'required|image|mimes:jpg,png,jpeg|max:2048',
                'nama_penandatangan' => 'required|string',
                'jabatan_penandatangan' => 'required|string',
                'tanggal_dibuat' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed', ['errors' => $validator->errors()]);
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $path = $request->file('picture')->store('signatures', 'public');

            $signature = Signature::create([
                'picture' => $path,
                'nama_penandatangan' => $request->nama_penandatangan,
                'jabatan_penandatangan' => $request->jabatan_penandatangan,
                'tanggal_dibuat' =>  now()->format('Y-m-d')
            ]);

            Log::info('Signature created successfully', ['signature' => $signature]);

            return response()->json([
                'message' => 'Signature created successfully',
                'signature' => $signature
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating signature', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }

    
    public function show($id)
    {
        try {
            $signature = Signature::find($id);

            if (!$signature) {
                return response()->json(['message' => 'Signature not found'], 404);
            }

            return response()->json($signature, 200);
        } catch (\Exception $e) {
            Log::error('Error fetching signature', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }

    
    // public function update(Request $request, $id)
    // {
    //     try {
    //         $signature = Signature::find($id);

    //         if (!$signature) {
    //             return response()->json(['message' => 'Signature not found'], 404);
    //         }

    //         $validator = Validator::make($request->all(), [
    //             'picture' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
    //             'nama_penandatangan' => 'nullable|string',
    //             'jabatan_penandatangan' => 'nullable|string',
    //             'tanggal_dibuat' => 'nullable|date' // Validasi untuk tanggal
    //         ]);

    //         if ($validator->fails()) {
    //             Log::warning('Validation failed', ['errors' => $validator->errors()]);
    //             return response()->json(['errors' => $validator->errors()], 400);
    //         }

    //         if ($request->hasFile('picture')) {
    //             if ($signature->picture && Storage::exists('public/' . $signature->picture)) {
    //                 Storage::delete('public/' . $signature->picture);
    //             }
    //             $path = $request->file('picture')->store('signatures', 'public');
    //             $signature->picture = $path;
    //         }

    //         if ($request->filled('nama_penandatangan')) {
    //             $signature->nama_penandatangan = $request->nama_penandatangan;
    //         }
    //         if ($request->filled('jabatan_penandatangan')) {
    //             $signature->jabatan_penandatangan = $request->jabatan_penandatangan;
    //         }
    //         if ($request->filled('tanggal_dibuat')) {
    //             $signature->tanggal_dibuat = $request->tanggal_dibuat;
    //         }

    //         $signature->save();

    //         Log::info('Signature updated successfully', ['signature' => $signature]);

    //         return response()->json([
    //             'message' => 'Signature updated successfully',
    //             'signature' => $signature
    //         ], 200);
    //     } catch (\Exception $e) {
    //         Log::error('Error updating signature', ['error' => $e->getMessage()]);
    //         return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
    //     }
    // }
    public function update(Request $request, $id)
{
    try {
        Log::info('Request all data', $request->all());

        $signature = Signature::find($id);

        if (!$signature) {
            return response()->json(['message' => 'Signature not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'picture' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'nama_penandatangan' => 'nullable|string',
            'jabatan_penandatangan' => 'nullable|string',
            'tanggal_dibuat' => 'nullable|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Kalau ada upload file baru
        if ($request->hasFile('picture')) {
            if ($signature->picture && Storage::disk('public')->exists($signature->picture)) {
                Storage::disk('public')->delete($signature->picture);
            }
            $path = $request->file('picture')->store('signatures', 'public');
            $signature->picture = $path;
        }

        // Ini bagian terpenting untuk update field:
        if ($request->filled('nama_penandatangan')) {
            $signature->nama_penandatangan = $request->nama_penandatangan;
        }
        if ($request->filled('jabatan_penandatangan')) {
            $signature->jabatan_penandatangan = $request->jabatan_penandatangan;
        }
        if ($request->filled('tanggal_dibuat')) {
            $signature->tanggal_dibuat = $request->tanggal_dibuat;
        }

        $signature->save();

        return response()->json([
            'message' => 'Signature updated successfully',
            'signature' => $signature
        ], 200);

        Log::info('Updating signature', [
            'id' => $id,
            'new_data' => [
                'nama_penandatangan' => $request->nama_penandatangan,
                'jabatan_penandatangan' => $request->jabatan_penandatangan,
                'tanggal_dibuat' => $request->tanggal_dibuat,
            ]
        ]);
        

    } catch (\Exception $e) {
        return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
    }
}

    




    
    public function destroy($id)
    {
        try {
            $signature = Signature::find($id);

            if (!$signature) {
                return response()->json(['message' => 'Signature not found'], 404);
            }

            Storage::delete('public/' . $signature->picture);
            $signature->delete();

            Log::info('Signature deleted successfully', ['signature_id' => $id]);

            return response()->json(['message' => 'Signature deleted successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting signature', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }
}
