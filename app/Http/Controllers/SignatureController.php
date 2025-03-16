<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Signature;
use Illuminate\Support\Facades\Storage;

class SignatureController extends Controller
{
    // Upload Tanda Tangan (Gambar)
    public function store(Request $request)
    {
        $request->validate([
            'nama_penandatangan' => 'required|string',
            'jabatan_penandatangan' => 'required|string',
            'picture' => 'required|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $path = $file->store('signatures', 'public');
        }

        $signature = Signature::create([
            'nama_penandatangan' => $request->nama_penandatangan,
            'jabatan_penandatangan' => $request->jabatan_penandatangan,
            'picture' => $path
        ]);

        return response()->json(['message' => 'Signature uploaded successfully', 'data' => $signature]);
    }

    // // Simpan Tanda Tangan Digital (Base64)
    // public function storeDigitalSignature(Request $request)
    // {
    //     $request->validate([
    //         'nama_penandatangan' => 'required|string',
    //         'jabatan_penandatangan' => 'required|string',
    //         'picture' => 'required|string'
    //     ]);

    //     // Simpan base64 sebagai file gambar
    //     $image = $request->picture;
    //     $image = str_replace('data:image/png;base64,', '', $image);
    //     $image = str_replace(' ', '+', $image);
    //     $imageName = 'signatures/' . uniqid() . '.png';

    //     Storage::disk('public')->put($imageName, base64_decode($image));

    //     $signature = Signature::create([
    //         'nama_penandatangan' => $request->nama_penandatangan,
    //         'jabatan_penandatangan' => $request->jabatan_penandatangan,
    //         'picture' => $imageName
    //     ]);

    //     return response()->json(['message' => 'Digital signature saved successfully', 'data' => $signature]);
    // }
}

