<?php

namespace App\Http\Controllers\PemilikMebel;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DataKriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil parameter dari request
        $perPage = $request->input('per_page', 10);
        $searchTerm = $request->input('search', '');
        
        // Query dasar
        $query = Kriteria::query();
        
        // Jika ada pencarian
        if (!empty($searchTerm)) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama_kriteria', 'like', '%' . $searchTerm . '%')
                  ->orWhere('kategori', 'like', '%' . $searchTerm . '%');
            });
        }
        
        // Urutkan berdasarkan yang terbaru dan paginasi
        $kriterias = $query->orderBy('created_at', 'desc')
                         ->paginate($perPage)
                         ->appends([
                             'per_page' => $perPage,
                             'search' => $searchTerm
                         ]);
        
        return view('pages.PemilikMebel.DataKriteria.index', compact('kriterias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.PemilikMebel.DataKriteria.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $currentTotal = Kriteria::sum('bobot');

        $validator = Validator::make($request->all(), [
            'nama_kriteria' => 'required|string|max:100|unique:kriterias,nama_kriteria',
            'kategori' => 'required|in:benefit,cost',
            'bobot' => [
                'required',
                'numeric',
                'min:1',
                'max:100',
                function ($attribute, $value, $fail) use ($currentTotal) {
                    if (($currentTotal + ($value / 100)) > 1) {
                        $fail('Total bobot semua kriteria tidak boleh melebihi 100%.');
                    }
                }
            ]
        ], [
            'nama_kriteria.required' => 'Nama kriteria harus diisi.',
            'nama_kriteria.max' => 'Nama kriteria maksimal 100 karakter.',
            'nama_kriteria.unique' => 'Nama kriteria sudah digunakan.',
            'kategori.required' => 'Kategori harus diisi.',
            'kategori.in' => 'Kategori harus bernilai benefit atau cost.',
            'bobot.required' => 'Bobot harus diisi.',
            'bobot.numeric' => 'Bobot harus berupa angka.',
            'bobot.min' => 'Bobot minimal 1.',
            'bobot.max' => 'Bobot maksimal 100.'
        ]);
        

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400); // status 400 bad request
        }

        $existing = Kriteria::where('nama_kriteria', $request->nama_kriteria)
                ->where('kategori', $request->kategori)
                ->where('bobot', $request->bobot)
                ->first();
        
            if ($existing) {
                return response()->json([
                    'errors' => [
                        'duplicate' => ['Data kriteria dengan nama, kategori, dan bobot yang sama sudah ada.']
                    ]
                ], 400);
            }
        
            try {
                Kriteria::create([
                    'nama_kriteria' => $request->nama_kriteria,
                    'kategori' => $request->kategori,
                    'bobot' => $request->bobot
                ]);
        
                return response()->json([
                    'message' => 'Data kriteria berhasil ditambahkan'
                ], 200); // OK
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Gagal menambahkan data kriteria. Silakan coba lagi.'
                ], 400); // Internal Server Error
            }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        return view('pages.PemilikMebel.DataKriteria.detail', compact('kriteria'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        return view('pages.PemilikMebel.DataKriteria.edit', compact('kriteria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $kriteria = Kriteria::findOrFail($id);
    $currentTotal = Kriteria::where('id', '!=', $id)->sum('bobot');

    $validator = Validator::make($request->all(), [
        'nama_kriteria' => 'required|string|max:100|unique:kriterias,nama_kriteria',
        'kategori' => 'required|in:benefit,cost',
        'bobot' => [
            'required',
            'numeric',
            'min:1',
            'max:100',
            function ($attribute, $value, $fail) use ($currentTotal) {
                if (($currentTotal + ($value / 100)) > 1) {
                    $fail('Total bobot semua kriteria tidak boleh melebihi 100%.');
                }
            }
        ]
    ], [
        'nama_kriteria.required' => 'Nama kriteria harus diisi.',
        'nama_kriteria.max' => 'Nama kriteria maksimal 100 karakter.',
        'nama_kriteria.unique' => 'Nama kriteria sudah digunakan.',
        'kategori.required' => 'Kategori harus diisi.',
        'kategori.in' => 'Kategori harus bernilai benefit atau cost.',
        'bobot.required' => 'Bobot harus diisi.',
        'bobot.numeric' => 'Bobot harus berupa angka.',
        'bobot.min' => 'Bobot minimal 1.',
        'bobot.max' => 'Bobot maksimal 100.'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'errors' => $validator->errors()
        ], 400);
    }

    $existing = Kriteria::where('nama_kriteria', $request->nama_kriteria)
                ->where('kategori', $request->kategori)
                ->where('bobot', $request->bobot)
                ->where('id', '!=', $id)
                ->first();
        
            if ($existing) {
                return response()->json([
                    'errors' => [
                        'duplicate' => ['Data kriteria dengan nama, kategori, dan bobot yang sama sudah ada.']
                    ]
                ], 400);
            }
        
            try {
                $kriteria = Kriteria::findOrFail($id);
                $kriteria->update([
                    'nama_kriteria' => $request->nama_kriteria,
                    'kategori' => $request->kategori,
                    'bobot' => $request->bobot
                ]);
        
                return response()->json([
                    'message' => 'Data kriteria berhasil diperbarui'
                ], 200); // OK
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Gagal memperbarui data kriteria. Silakan coba lagi.'
                ], 400); // Internal Server Error
            }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Ambil data kriteria
        $kriteria = Kriteria::findOrFail($id);

        // Cek jika kriteria memiliki relasi dengan data lain
        if (
            $kriteria->penilaians()->exists() ||
            $kriteria->subkriterias()->exists()
        ) {
            return response()->json([
                'message' => 'Tidak dapat menghapus data kriteria karena masih memiliki data terkait.'
            ], 400);
        }

        // Hapus kriteria jika tidak ada relasi yang terdeteksi
        $kriteria->delete();

        return response()->json([
            'message' => 'Data kriteria berhasil dihapus.'
        ]);
    }
}