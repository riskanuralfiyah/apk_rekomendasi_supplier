<?php

namespace App\Http\Controllers\PemilikMebel;

use App\Http\Controllers\Controller;
use App\Models\{Penilaian, Supplier, Kriteria, SubKriteria};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenilaianSupplierController extends Controller
{
    public function index(Request $request, $supplierId)
    {
        $supplier = Supplier::findOrFail($supplierId);

        $query = $supplier->penilaians()->with(['kriteria', 'subkriteria']);

        $penilaians = $query->orderBy('created_at', 'desc')->get();

        return view('pages.PemilikMebel.PenilaianSupplier.index', [
            'supplier' => $supplier,
            'penilaians' => $penilaians
        ]);
    }

    public function create($supplierId)
    {
        $supplier = Supplier::findOrFail($supplierId);
        $kriterias = Kriteria::with('subkriterias')->get();
        $suppliers = Supplier::all();

        $existingKriteriaIds = $supplier->penilaians()->pluck('id_kriteria')->toArray();
        $availableKriterias = $kriterias->reject(fn($kriteria) => in_array($kriteria->id, $existingKriteriaIds));

        if ($availableKriterias->isEmpty()) {
            return redirect()->route('penilaiansupplier.pemilikmebel', $supplierId)
                ->with('warning', 'Supplier sudah memiliki penilaian untuk semua kriteria');
        }

        return view('pages.PemilikMebel.PenilaianSupplier.create', [
            'supplier' => $supplier,
            'suppliers' => $suppliers,
            'kriterias' => $availableKriterias
        ]);
    }

    public function store(Request $request, $supplierId)
    {
        // Validasi input
        $validated = $request->validate([
            'id_kriteria' => 'required|exists:kriterias,id',  // Validasi bahwa id_kriteria ada di tabel kriteria
            'id_subkriteria' => 'required|exists:subkriterias,id',  // Validasi bahwa id_subkriteria ada di tabel subkriteria
        ]);
    
        try {
            // Ambil supplier berdasarkan ID
            $supplier = Supplier::findOrFail($supplierId);
    
            // Ambil subkriteria berdasarkan id
            $subkriteria = SubKriteria::findOrFail($validated['id_subkriteria']);
    
            // Simpan penilaian, pastikan nilai_subkriteria disimpan sebagai integer
            $supplier->penilaians()->create([
                'id_kriteria' => $validated['id_kriteria'],
                'id_subkriteria' => $validated['id_subkriteria'],
                'nilai_subkriteria' => (int) $subkriteria->nilai, // Pastikan nilai disimpan sebagai integer
            ]);
    
            // Redirect atau respons sukses
            return redirect()->route('penilaiansupplier.pemilikmebel', $supplierId)
                ->with('success', 'Penilaian berhasil ditambahkan');
        } catch (\Exception $e) {
            // Menangani error
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function show($supplierId, $id)
    {
        $supplier = Supplier::findOrFail($supplierId);
        $penilaian = $supplier->penilaians()
                            ->with(['kriteria', 'subkriteria'])
                            ->findOrFail($id);

        return view('pages.PemilikMebel.PenilaianSupplier.show', [
            'supplier' => $supplier,
            'penilaian' => $penilaian
        ]);
    }

    public function edit($supplierId, $id)
    {
        $supplier = Supplier::findOrFail($supplierId);
        $penilaian = $supplier->penilaians()
                            ->with(['kriteria.subkriterias'])
                            ->findOrFail($id);

        return view('pages.PemilikMebel.PenilaianSupplier.edit', [
            'supplier' => $supplier,
            'penilaian' => $penilaian,
            'subkriterias' => $penilaian->kriteria->subkriterias
        ]);
    }

    public function update(Request $request, $supplierId, $id)
    {
        $validated = $request->validate([
            'id_subkriteria' => 'required|exists:subkriterias,id',
        ]);

        $supplier = Supplier::findOrFail($supplierId);
        $penilaian = $supplier->penilaians()->findOrFail($id);
        $subkriteria = SubKriteria::findOrFail($validated['id_subkriteria']);

        $penilaian->update([
            'id_subkriteria' => $validated['id_subkriteria'],
            'nilai_subkriteria' => $subkriteria->nilai
        ]);

        return redirect()->route('penilaiansupplier.pemilikmebel', $supplierId)
            ->with('success', 'Penilaian berhasil diperbarui');
    }

    public function destroy($supplierId, $id)
    {
        $supplier = Supplier::findOrFail($supplierId);
        $penilaian = $supplier->penilaians()->findOrFail($id);
        
        $penilaian->delete();
        
        return redirect()->route('penilaiansupplier.pemilikmebel', $supplierId)
            ->with('success', 'Penilaian berhasil dihapus');
    }

    public function getSubkriteria($kriteriaId)
    {
        $subkriterias = SubKriteria::where('id_kriteria', $kriteriaId)
                                ->orderBy('nilai', 'desc')
                                ->get();

        return response()->json($subkriterias);
    }
}
