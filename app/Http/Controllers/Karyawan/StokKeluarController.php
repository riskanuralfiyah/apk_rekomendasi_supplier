<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\StokKeluar;
use App\Models\BahanBaku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StokKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request('per_page', 10);
        $stokKeluars = StokKeluar::with(['bahanBaku'])
                        ->orderBy('created_at', 'desc')
                        ->paginate($perPage);

        return view('pages.Karyawan.StokKeluar.index', compact('stokKeluars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bahanBakus = BahanBaku::all();
        return view('pages.Karyawan.StokKeluar.create', compact('bahanBakus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'id_bahan_baku' => 'required|exists:bahan_bakus,id',
            'jumlah_stok_keluar' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check available stock
        $bahanBaku = BahanBaku::find($request->id_bahan_baku);
        
        if ($bahanBaku->jumlah_stok < $request->jumlah_stok_keluar) {
            return redirect()->back()
                ->with('error', 'Jumlah stok keluar ('.$request->jumlah_stok_keluar.') melebihi stok tersedia ('.$bahanBaku->jumlah_stok.')')
                ->withInput();
        }

        // Create stok keluar
        $stokKeluar = StokKeluar::create($request->all());

        // Update bahan baku stock
        $bahanBaku->jumlah_stok -= $request->jumlah_stok_keluar;
        $bahanBaku->save();

        return redirect()->route('stokkeluar.karyawan')
            ->with('success', 'Data stok keluar berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $stokKeluar = StokKeluar::findOrFail($id);
        $bahanBakus = BahanBaku::all();
        
        return view('pages.Karyawan.StokKeluar.edit', compact('stokKeluar', 'bahanBakus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'id_bahan_baku' => 'required|exists:bahan_bakus,id',
            'jumlah_stok_keluar' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $stokKeluar = StokKeluar::findOrFail($id);
        $oldJumlah = $stokKeluar->jumlah_stok_keluar;
        $oldBahanBakuId = $stokKeluar->id_bahan_baku;

        // Find the new bahan baku
        $bahanBaku = BahanBaku::find($request->id_bahan_baku);

        // Calculate available stock for validation
        if ($oldBahanBakuId == $request->id_bahan_baku) {
            // Same bahan baku - available stock is current stock + old jumlah (since we'll subtract it first)
            $availableStock = $bahanBaku->jumlah_stok + $oldJumlah;
        } else {
            // Different bahan baku - available stock is the new bahan baku's stock
            $availableStock = $bahanBaku->jumlah_stok;
            
            // Get the old bahan baku to restore its stock
            $oldBahanBaku = BahanBaku::find($oldBahanBakuId);
        }

        // Validate stock availability
        if ($request->jumlah_stok_keluar > $availableStock) {
            return redirect()->back()
                ->with('error', 'Jumlah stok keluar ('.$request->jumlah_stok_keluar.') melebihi stok tersedia ('.$availableStock.')')
                ->withInput();
        }

        // Update stok keluar record
        $stokKeluar->update($request->all());

        // Update bahan baku stock
        if ($oldBahanBakuId == $request->id_bahan_baku) {
            // Same bahan baku, adjust the difference
            $difference = $oldJumlah - $request->jumlah_stok_keluar;
            $bahanBaku->jumlah_stok += $difference;
            $bahanBaku->save();
        } else {
            // Different bahan baku - handle both bahan bakus
            // 1. Add back to old bahan baku
            $oldBahanBaku->jumlah_stok += $oldJumlah;
            $oldBahanBaku->save();
            
            // 2. Subtract from new bahan baku
            $bahanBaku->jumlah_stok -= $request->jumlah_stok_keluar;
            $bahanBaku->save();
        }

        return redirect()->route('stokkeluar.karyawan')
            ->with('success', 'Data stok keluar berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $stokKeluar = StokKeluar::findOrFail($id);
        $bahanBakuId = $stokKeluar->id_bahan_baku;
        $jumlah = $stokKeluar->jumlah_stok_keluar;

        // Delete stok keluar record
        $stokKeluar->delete();

        // Update bahan baku stock (add back the stock)
        $bahanBaku = BahanBaku::find($bahanBakuId);
        $bahanBaku->jumlah_stok += $jumlah;
        $bahanBaku->save();

        return redirect()->route('stokkeluar.karyawan')
            ->with('success', 'Data stok keluar berhasil dihapus');
    }
}
