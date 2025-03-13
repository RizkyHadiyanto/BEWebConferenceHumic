<?php
// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\User;

// class UserController extends Controller
// {
    // Ambil daftar user
    // public function index()
    // {
    //     return response()->json(User::all(), 200);
    // }

    // Tambah user baru
    // public function store(Request $request)
    // {
    //     $user = User::create($request->all());
    //     return response()->json($user, 201);
    // }

    // Lihat user berdasarkan ID
    // public function show($id)
    // {
    //     $user = User::find($id);
    //     if (!$user) {
    //         return response()->json(['message' => 'User tidak ditemukan'], 404);
    //     }
    //     return response()->json($user, 200);
    // }

    // Update data user
    // public function update(Request $request, $id)
    // {
    //     $user = User::find($id);
    //     if (!$user) {
    //         return response()->json(['message' => 'User tidak ditemukan'], 404);
    //     }
    //     $user->update($request->all());
    //     return response()->json($user, 200);
    // }

    // Hapus user
//     public function destroy($id)
//     {
//         $user = User::find($id);
//         if (!$user) {
//             return response()->json(['message' => 'User tidak ditemukan'], 404);
//         }
//         $user->delete();
//         return response()->json(['message' => 'User berhasil dihapus'], 200);
//     }
// }
