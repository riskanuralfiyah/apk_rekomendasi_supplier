<?php

namespace App\Http\Controllers\PemilikMebel;

use App\Http\Controllers\Controller;
use App\Models\{Penilaian, Supplier, Kriteria, Subkriteria};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenilaianSupplierController extends Controller
{
    public function index(Request $request, $supplierId)
    {
        $supplier = Supplier::find($supplierId);
    
        if (!$supplier) {
            return view('pages.PemilikMebel.PenilaianSupplier.index', [
                'errorMessage' => 'Data supplier tidak ditemukan.',
                'supplier' => null,
                'penilaians' => collect(),
                'jumlahKriteria' => 0,
                'jumlahSubkriteria' => 0,
                'kriteriaTanpaSub' => 0,
            ]);
        }
    
        $jumlahKriteria = Kriteria::count();
        $jumlahSubkriteria = Subkriteria::count();
    
        if ($jumlahKriteria == 0 || $jumlahSubkriteria == 0) {
            return view('pages.PemilikMebel.PenilaianSupplier.index', [
                'errorMessage' => 'Harap lengkapi data kriteria dan subkriteria terlebih dahulu sebelum melakukan penilaian.',
                'supplier' => $supplier,
                'penilaians' => collect(),
                'jumlahKriteria' => $jumlahKriteria,
                'jumlahSubkriteria' => $jumlahSubkriteria,
                'kriteriaTanpaSub' => 0,
            ]);
        }
    
        $kriteriaTanpaSub = Kriteria::whereDoesntHave('subkriterias')->count();
    
        if ($kriteriaTanpaSub > 0) {
            return view('pages.PemilikMebel.PenilaianSupplier.index', [
                'errorMessage' => 'Terdapat kriteria yang belum memiliki subkriteria. Harap lengkapi terlebih dahulu.',
                'supplier' => $supplier,
                'penilaians' => collect(),
                'jumlahKriteria' => $jumlahKriteria,
                'jumlahSubkriteria' => $jumlahSubkriteria,
                'kriteriaTanpaSub' => $kriteriaTanpaSub,
            ]);
        }
    
        $penilaians = $supplier->penilaians()
            ->with(['kriteria', 'subkriteria'])
            ->orderBy('created_at', 'desc')
            ->get();
    
        return view('pages.PemilikMebel.PenilaianSupplier.index', [
            'errorMessage' => null,
            'supplier' => $supplier,
            'penilaians' => $penilaians,
            'jumlahKriteria' => $jumlahKriteria,
            'jumlahSubkriteria' => $jumlahSubkriteria,
            'kriteriaTanpaSub' => $kriteriaTanpaSub,
        ]);
    }
    
    

    public function create($supplierId)
    {
        $supplier = Supplier::findOrFail($supplierId);
        
        // Cek apakah kriteria dan subkriteria sudah ada
        $kriterias = Kriteria::with(['subkriterias' => function($query) {
            $query->orderBy('nilai', 'desc');
        }])->get();
    
        // Filter kriteria yang belum memiliki subkriteria atau subkriteria yang belum ada
        $availableKriterias = $kriterias->filter(function($kriteria) {
            return $kriteria->subkriterias->isNotEmpty();
        });
    
        if ($availableKriterias->isEmpty()) {
            return redirect()
                ->route('penilaiansupplier.pemilikmebel', $supplierId)
                ->with('warning', 'Data kriteria dan subkriteria belum lengkap, tidak bisa melakukan penilaian');
        }
    
        // Filter kriteria yang belum dinilai
        $existingKriteriaIds = $supplier->penilaians()->pluck('id_kriteria')->toArray();
        $availableKriterias = $availableKriterias->reject(fn($kriteria) => in_array($kriteria->id, $existingKriteriaIds));
    
        if ($availableKriterias->isEmpty()) {
            return redirect()
                ->route('penilaiansupplier.pemilikmebel', $supplierId)
                ->with('warning', 'Supplier sudah memiliki penilaian untuk semua kriteria');
        }
    
        return view('pages.PemilikMebel.PenilaianSupplier.create', [
            'supplier' => $supplier,
            'kriterias' => $availableKriterias
        ]);
    }
    

public function store(Request $request, $supplierId)
{
    // Validasi bahwa ada data kriteria yang dikirim
    $validator = Validator::make($request->all(), [
        'kriteria' => 'required|array',
        'kriteria.*' => 'required|exists:subkriterias,id'
    ]);

    if ($validator->fails()) {
        return back()
            ->withErrors($validator)
            ->withInput();
    }

    try {
        $supplier = Supplier::findOrFail($supplierId);
        $errors = [];

        // Proses setiap kriteria yang dipilih
        foreach ($request->kriteria as $kriteriaId => $subkriteriaId) {
            // Validasi tambahan untuk setiap pasangan kriteria-subkriteria
            $subkriteria = Subkriteria::where('id', $subkriteriaId)
                ->where('id_kriteria', $kriteriaId)
                ->first();

            if (!$subkriteria) {
                $errors["kriteria.$kriteriaId"] = 'Subkriteria tidak valid untuk kriteria yang dipilih';
                continue;
            }

            // Cek apakah kriteria sudah dinilai sebelumnya
            $exists = Penilaian::where('id_supplier', $supplierId)
                ->where('id_kriteria', $kriteriaId)
                ->exists();

            if ($exists) {
                $errors["kriteria.$kriteriaId"] = 'Kriteria ini sudah dinilai sebelumnya';
                continue;
            }

            // Simpan penilaian
            Penilaian::create([
                'id_supplier' => $supplier->id,
                'id_kriteria' => $kriteriaId,
                'id_subkriteria' => $subkriteriaId,
                'nilai_subkriteria' => (int) $subkriteria->nilai
            ]);
        }

        if (!empty($errors)) {
            return back()
                ->withErrors($errors)
                ->withInput()
                ->with('warning', 'Beberapa data tidak tersimpan karena masalah validasi');
        }

        return redirect()
            ->route('penilaiansupplier.pemilikmebel', $supplierId)
            ->with('success', 'Penilaian berhasil ditambahkan');

    } catch (\Exception $e) {
        \Log::error('Error creating penilaian: ' . $e->getMessage(), [
            'supplier_id' => $supplierId,
            'input' => $request->all(),
            'error' => $e->getTraceAsString()
        ]);
        
        return back()
            ->with('error', 'Terjadi kesalahan saat menyimpan penilaian')
            ->withInput();
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

    public function edit($supplierId)
    {
        $supplier = Supplier::findOrFail($supplierId);
        
        $penilaians = $supplier->penilaians()
                        ->with(['kriteria.subkriterias'])
                        ->get();
    
        if ($penilaians->isEmpty()) {
            return redirect()
                ->route('penilaiansupplier.pemilikmebel', $supplierId)
                ->with('warning', 'Belum ada penilaian untuk supplier ini');
        }
    
        return view('pages.PemilikMebel.PenilaianSupplier.edit', [
            'supplier' => $supplier,
            'penilaians' => $penilaians
        ]);
    }

public function update(Request $request, $supplierId)
{
    $validator = Validator::make($request->all(), [
        'penilaian' => 'required|array',
        'penilaian.*.id_subkriteria' => 'required|exists:subkriterias,id'
    ]);

    if ($validator->fails()) {
        return back()
            ->withErrors($validator)
            ->withInput();
    }

    try {
        $supplier = Supplier::findOrFail($supplierId);
        $errors = [];

        foreach ($request->penilaian as $penilaianId => $data) {
            $penilaian = Penilaian::where('id', $penilaianId)
                            ->where('id_supplier', $supplierId)
                            ->first();

            if (!$penilaian) {
                $errors["penilaian.$penilaianId"] = 'Penilaian tidak ditemukan';
                continue;
            }

            try {
                $subkriteria = Subkriteria::findOrFail($data['id_subkriteria']);
            } catch (ModelNotFoundException $e) {
                $errors["penilaian.$penilaianId"] = 'Subkriteria tidak ditemukan';
                continue;
            }
            

            $penilaian->update([
                'id_subkriteria' => $data['id_subkriteria'],
                'nilai_subkriteria' => $subkriteria->nilai
            ]);
        }

        if (!empty($errors)) {
            return back()
                ->withErrors($errors)
                ->withInput()
                ->with('warning', 'Beberapa penilaian gagal diperbarui');
        }

        return redirect()
            ->route('penilaiansupplier.pemilikmebel', $supplierId)
            ->with('success', 'Semua penilaian berhasil diperbarui');

    } catch (\Exception $e) {
        \Log::error('Error updating penilaian: ' . $e->getMessage(), [
            'supplier_id' => $supplierId,
            'input' => $request->all(),
            'error' => $e->getTraceAsString()
        ]);
        
        return back()
            ->with('error', 'Terjadi kesalahan saat memperbarui penilaian')
            ->withInput();
    }
}

public function destroy($supplierId)
{
    // Temukan supplier berdasarkan ID
    $supplier = Supplier::findOrFail($supplierId);

    // Hapus semua penilaian terkait supplier
    $penilaians = $supplier->penilaians();

    // Periksa apakah ada penilaian yang terkait dengan supplier ini
    if ($penilaians->count() == 0) {
        return redirect()->route('penilaiansupplier.pemilikmebel', $supplierId)
            ->with('error', 'Tidak ada penilaian untuk dihapus');
    }

    // Hapus semua penilaian yang terkait dengan supplier
    $penilaians->delete();

    return redirect()->route('penilaiansupplier.pemilikmebel', $supplierId)
        ->with('success', 'Data penilaian berhasil dihapus');
}


    public function getSubkriteria($kriteriaId)
    {
        $subkriterias = Subkriteria::where('id_kriteria', $kriteriaId)
                                ->orderBy('nilai', 'desc')
                                ->get();

        return response()->json($subkriterias);
    }
}
