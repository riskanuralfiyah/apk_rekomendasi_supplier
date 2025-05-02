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
                    $fail('Total bobot semua kriteria tidak boleh melebihi 100%');
                }
            }
        ]
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    Kriteria::create([
        'nama_kriteria' => $request->nama_kriteria,
        'kategori' => $request->kategori,
        'bobot' => $request->bobot // mutator otomatis ubah ke 0.5
    ]);

    return redirect()->route('datakriteria.pemilikmebel')
        ->with('success', 'Data kriteria berhasil ditambahkan');
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
        'nama_kriteria' => 'required|string|max:100|unique:kriterias,nama_kriteria,' . $id,
        'kategori' => 'required|in:benefit,cost',
        'bobot' => [
            'required',
            'numeric',
            'min:1',
            'max:100',
            function ($attribute, $value, $fail) use ($currentTotal) {
                if (($currentTotal + ($value / 100)) > 1) {
                    $fail('Total bobot semua kriteria tidak boleh melebihi 100%');
                }
            }
        ]
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    $kriteria->update([
        'nama_kriteria' => $request->nama_kriteria,
        'kategori' => $request->kategori,
        'bobot' => $request->bobot // mutator otomatis ubah ke 0.5
    ]);

    return redirect()->route('datakriteria.pemilikmebel')
        ->with('success', 'Data kriteria berhasil diperbarui');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        $kriteria->delete();

        return redirect()->route('datakriteria.pemilikmebel')
            ->with('success', 'Data kriteria berhasil dihapus');
    }
}