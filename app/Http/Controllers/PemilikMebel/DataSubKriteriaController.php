<?php

namespace App\Http\Controllers\PemilikMebel;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DataSubKriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $kriteriaId)
    {
        // Ambil parameter dari request
        $perPage = $request->input('per_page', 10);
        $searchTerm = $request->input('search', '');
        
        // Dapatkan data kriteria
        $kriteria = Kriteria::findOrFail($kriteriaId);
        
        // Query dasar untuk subkriteria
        $query = SubKriteria::where('id_kriteria', $kriteriaId);
        
        // Jika ada pencarian
        if (!empty($searchTerm)) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama_subkriteria', 'like', '%' . $searchTerm . '%');
            });
        }
        
        // Urutkan berdasarkan yang terbaru dan paginasi
        $subkriterias = $query->orderBy('created_at', 'desc')
                            ->paginate($perPage)
                            ->appends([
                                'per_page' => $perPage,
                                'search' => $searchTerm
                            ]);
        
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
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        SubKriteria::create([
            'id_kriteria' => $kriteriaId,
            'nama_subkriteria' => $request->nama_subkriteria,
            'nilai' => $request->nilai
        ]);

        return redirect()->route('datasubkriteria.pemilikmebel', $kriteriaId)
            ->with('success', 'Data subkriteria berhasil ditambahkan');
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
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $subkriteria = SubKriteria::where('id_kriteria', $kriteriaId)
                                ->findOrFail($id);
        $subkriteria->update([
            'nama_subkriteria' => $request->nama_subkriteria,
            'nilai' => $request->nilai
        ]);

        return redirect()->route('datasubkriteria.pemilikmebel', $kriteriaId)
            ->with('success', 'Data subkriteria berhasil diperbarui');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($kriteriaId, $subkriteriaId)
{
    $subkriteria = SubKriteria::where('id_kriteria', $kriteriaId)
                            ->findOrFail($subkriteriaId);
    $subkriteria->delete();

    return redirect()->route('datasubkriteria.pemilikmebel', $kriteriaId)
        ->with('success', 'Data subkriteria berhasil dihapus');
}
}