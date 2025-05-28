<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\BahanBaku;
use App\Models\StokMasuk;
use App\Models\StokKeluar;
use Carbon\Carbon;

class DashboardKaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalBahanBaku = BahanBaku::count();

        $today = Carbon::today()->toDateString();

        $stokMasukHariIni = StokMasuk::whereDate('created_at', $today)->sum('jumlah_stok_masuk'); 
        // asumsikan kolom jumlah untuk qty stok masuk

        $stokKeluarHariIni = StokKeluar::whereDate('created_at', $today)->sum('jumlah_stok_keluar'); 
        // asumsikan kolom jumlah untuk qty stok keluar

        return view('pages.Karyawan.index', compact('totalBahanBaku', 'stokMasukHariIni', 'stokKeluarHariIni'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function edit(string $id)
    {
        //
    }

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
