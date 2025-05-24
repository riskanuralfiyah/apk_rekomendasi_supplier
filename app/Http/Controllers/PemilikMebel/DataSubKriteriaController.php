<?php

namespace App\Http\Controllers\PemilikMebel;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\Subkriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DataSubKriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $kriteriaId)
    {
        // Dapatkan data kriteria
        $kriteria = Kriteria::findOrFail($kriteriaId);

        // Ambil semua subkriteria berdasarkan id_kriteria tanpa search dan paginate
        $subkriterias = SubKriteria::where('id_kriteria', $kriteriaId)
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('pages.PemilikMebel.DataSubKriteria.index', compact('kriteria', 'subkriterias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($kriteriaId)
    {
        $kriteria = Kriteria::findOrFail($kriteriaId);
        return view('pages.PemilikMebel.DataSubKriteria.create', compact('kriteria'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $kriteriaId)
    {
        $validator = Validator::make($request->all(), [
            'nama_subkriteria' => 'required|string|max:100',
            'nilai' => 'required|numeric|min:1'
        ], [
            'nama_subkriteria.required' => 'Nama subkriteria harus diisi.',
            'nama_subkriteria.string' => 'Nama subkriteria harus berupa teks.',
            'nama_subkriteria.max' => 'Nama subkriteria maksimal 100 karakter.',
            'nilai.required' => 'Nilai harus diisi.',
            'nilai.numeric' => 'Nilai harus berupa angka.',
            'nilai.min' => 'Nilai minimal adalah 1.'
        ]);        

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400); // status 400 bad request
        }

         // cek duplikat
        $existing = Subkriteria::where('id_kriteria', $kriteriaId)
        ->where('nama_subkriteria', $request->nama_subkriteria)
        ->first();

        if ($existing) {
            return response()->json([
                'errors' => [
                    'duplicate' => ['Nama subkriteria sudah digunakan untuk kriteria ini.']
                ]
            ], 400);
        }

        try {
            Subkriteria::create([
                'id_kriteria' => $kriteriaId,
                'nama_subkriteria' => $request->nama_subkriteria,
                'nilai' => $request->nilai
            ]);

            return response()->json([
                'message' => 'Data subkriteria berhasil ditambahkan'
            ], 200); // OK
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menambahkan data subkriteria. Silakan coba lagi.'
            ], 400); // Internal Server Error
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($kriteriaId, $id)
    {
        $kriteria = Kriteria::findOrFail($kriteriaId);
        $subkriteria = SubKriteria::where('id_kriteria', $kriteriaId)
                                ->findOrFail($id);
        return view('pages.PemilikMebel.DataSubKriteria.detail', compact('kriteria', 'subkriteria'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($kriteriaId, $id)
    {
        $kriteria = Kriteria::findOrFail($kriteriaId);
        $subkriteria = SubKriteria::where('id_kriteria', $kriteriaId)
                                ->findOrFail($id);
        return view('pages.PemilikMebel.DataSubKriteria.edit', compact('kriteria', 'subkriteria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $kriteriaId, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_subkriteria' => 'required|string|max:100',
            'nilai' => 'required|numeric|min:1'
        ], [
            'nama_subkriteria.required' => 'Nama subkriteria harus diisi.',
            'nama_subkriteria.string' => 'Nama subkriteria harus berupa teks.',
            'nama_subkriteria.max' => 'Nama subkriteria maksimal 100 karakter.',
            'nilai.required' => 'Nilai harus diisi.',
            'nilai.numeric' => 'Nilai harus berupa angka.',
            'nilai.min' => 'Nilai minimal adalah 1.'
        ]);  

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        // cek duplikat
        $existing = Subkriteria::where('id_kriteria', $kriteriaId)
        ->where('nama_subkriteria', $request->nama_subkriteria)
        ->where('id', '!=', $id)
        ->first();

        if ($existing) {
            return response()->json([
                'errors' => [
                    'duplicate' => ['Nama subkriteria sudah digunakan untuk kriteria ini.']
                ]
            ], 400);
        }

        try {
            $subkriteria = Subkriteria::findOrFail($id);
            $subkriteria->update([
                'id_kriteria' => $kriteriaId,
                'nama_subkriteria' => $request->nama_subkriteria,
                'nilai' => $request->nilai
            ]);

            return response()->json([
                'message' => 'Data subkriteria berhasil diperbarui'
            ], 200); // OK
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui data subkriteria. Silakan coba lagi.'
            ], 400); // Internal Server Error
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($kriteriaId, $subkriteriaId)
    {
        $subkriteria = SubKriteria::where('id_kriteria', $kriteriaId)
                                ->findOrFail($subkriteriaId);

        // Cek jika subkriteria memiliki relasi dengan data lain
        if (
            $subkriteria->penilaians()->exists()
        ) {
            return response()->json([
                'message' => 'Tidak dapat menghapus data subkriteria karena masih memiliki data terkait.'
            ], 400);
        }

        $subkriteria->delete();

        return response()->json([
            'message' => 'Data subkriteria berhasil dihapus.'
        ]);
    }
}
