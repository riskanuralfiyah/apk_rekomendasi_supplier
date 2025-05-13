<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\StokMasuk;
use App\Models\BahanBaku;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StokMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $perPage = request('per_page', 10);
    $stokMasuks = StokMasuk::with(['bahanBaku', 'supplier'])
                    ->orderBy('created_at', 'desc')
                    ->paginate($perPage);

    return view('pages.Karyawan.StokMasuk.index', compact('stokMasuks'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bahanBakus = BahanBaku::all();
        $suppliers = Supplier::all();
        
        return view('pages.Karyawan.StokMasuk.create', compact('bahanBakus', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'id_bahan_baku' => 'required|exists:bahan_bakus,id',
            'jumlah_stok_masuk' => 'required|integer|min:1',
            'id_supplier' => 'required|exists:suppliers,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create stok masuk
        $stokMasuk = StokMasuk::create($request->all());

        // Update bahan baku stock
        $bahanBaku = BahanBaku::find($request->id_bahan_baku);
        $bahanBaku->jumlah_stok += $request->jumlah_stok_masuk;
        $bahanBaku->save();

        return redirect()->route('stokmasuk.karyawan')
            ->with('success', 'Data stok masuk berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $stokMasuk = StokMasuk::findOrFail($id);
        $bahanBakus = BahanBaku::all();
        $suppliers = Supplier::all();
        
        return view('pages.Karyawan.StokMasuk.edit', compact('stokMasuk', 'bahanBakus', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'id_bahan_baku' => 'required|exists:bahan_bakus,id',
            'jumlah_stok_masuk' => 'required|integer|min:1',
            'id_supplier' => 'required|exists:suppliers,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $stokMasuk = StokMasuk::findOrFail($id);
        $oldJumlah = $stokMasuk->jumlah_stok_masuk;
        $oldBahanBakuId = $stokMasuk->id_bahan_baku;

        // Update stok masuk
        $stokMasuk->update($request->all());

        // Update bahan baku stock
        if ($oldBahanBakuId == $request->id_bahan_baku) {
            // Same bahan baku, adjust the difference
            $difference = $request->jumlah_stok_masuk - $oldJumlah;
            $bahanBaku = BahanBaku::find($request->id_bahan_baku);
            $bahanBaku->jumlah_stok += $difference;
            $bahanBaku->save();
        } else {
            // Different bahan baku, subtract from old and add to new
            $oldBahanBaku = BahanBaku::find($oldBahanBakuId);
            $oldBahanBaku->jumlah_stok -= $oldJumlah;
            $oldBahanBaku->save();

            $newBahanBaku = BahanBaku::find($request->id_bahan_baku);
            $newBahanBaku->jumlah_stok += $request->jumlah_stok_masuk;
            $newBahanBaku->save();
        }

        return redirect()->route('stokmasuk.karyawan')
            ->with('success', 'Data stok masuk berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $stokMasuk = StokMasuk::findOrFail($id);
        $bahanBakuId = $stokMasuk->id_bahan_baku;
        $jumlah = $stokMasuk->jumlah_stok_masuk;

        // Delete stok masuk
        $stokMasuk->delete();

        // Update bahan baku stock
        $bahanBaku = BahanBaku::find($bahanBakuId);
        $bahanBaku->jumlah_stok -= $jumlah;
        $bahanBaku->save();

        return redirect()->route('stokmasuk.karyawan')
            ->with('success', 'Data stok masuk berhasil dihapus');
    }
}