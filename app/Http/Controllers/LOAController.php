<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LOA;
use App\Models\Signature;
use Illuminate\Support\Facades\Validator;

class LOAController extends Controller
{
    // Create LOA
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'paper_id' => 'required|string|unique:loas,paper_id',
            'paper_title' => 'required|string',
            'author_names' => 'required|array|min:1|max:5', // Penulis 1-5
            'status' => 'required|in:Accepted,Rejected',
            'tempat_tanggal' => 'required|string',
            'signature_id' => 'required|exists:signatures,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $loa = LOA::create([
            'paper_id' => $request->paper_id,
            'paper_title' => $request->paper_title,
            'author_names' => $request->author_names,
            'status' => $request->status,
            'tempat_tanggal' => $request->tempat_tanggal,
            'signature_id' => $request->signature_id
        ]);

        return response()->json(['message' => 'LOA created successfully', 'loa' => $loa], 201);
    }

    // Get all LOAs
    public function index()
    {
        $loas = LOA::with('signature')->get();
        return response()->json(['success' => true, 'loas' => $loas], 200);
    }

    // Get single LOA
    public function show($id)
    {
        $loa = LOA::with('signature')->find($id);

        if (!$loa) {
            return response()->json(['message' => 'LOA not found'], 404);
        }

        return response()->json(['success' => true, 'loa' => $loa], 200);
    }

    // Update LOA
    public function update(Request $request, $id)
    {
        $loa = LOA::find($id);

        if (!$loa) {
            return response()->json(['message' => 'LOA not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'paper_title' => 'sometimes|string',
            'author_names' => 'sometimes|array|min:1|max:5',
            'status' => 'sometimes|in:Accepted,Rejected',
            'tempat_tanggal' => 'sometimes|string',
            'signature_id' => 'sometimes|exists:signatures,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $loa->update($request->all());

        return response()->json(['message' => 'LOA updated successfully', 'loa' => $loa], 200);
    }

    // Delete LOA
    public function destroy($id)
    {
        $loa = LOA::find($id);

        if (!$loa) {
            return response()->json(['message' => 'LOA not found'], 404);
        }

        $loa->delete();
        return response()->json(['message' => 'LOA deleted successfully'], 200);
    }
}

