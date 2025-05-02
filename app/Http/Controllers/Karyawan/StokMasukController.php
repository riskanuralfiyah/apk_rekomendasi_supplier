<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StokMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.Karyawan.StokMasuk.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.Karyawan.StokMasuk.create');
    }

    public function edit()
    {
        // Data statis (contoh)
        $stokmasuk = (object) [
            'id' => 1, 
            'tanggal' => '03-09-2024', 
            'namaBahanBaku' => 'Kayu Jati', 
            'jumlahStokMasuk' => '50', 
            'namaSupplier' => 'Supplier A',
        ];

        // Tampilkan view edit dengan data statis
        return view('pages.Karyawan.StokMasuk.edit', compact('stokmasuk'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(string $id)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
