<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataBahanBakuKaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.Karyawan.DataBahanBaku.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.Karyawan.DataBahanBaku.create');
    }

    public function edit()
    {
        // Data statis (contoh)
        $bahanbaku = (object) [
            'id' => 1, // ID bahan baku
            'nama' => 'Kayu Jati', 
            'satuan' => 'm³', 
            'stokMinimum' => '10', 
            'jumlahStok' => '85',
        ];

        // Tampilkan view edit dengan data statis
        return view('pages.Karyawan.DataBahanBaku.edit', compact('bahanbaku'));
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
