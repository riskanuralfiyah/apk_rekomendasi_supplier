<?php

namespace App\Http\Controllers\PemilikMebel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HasilRekomendasi;
use Barryvdh\DomPDF\Facade\Pdf;

class HasilRekomendasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data hasil rekomendasi dari database
        $hasilRekomendasi = HasilRekomendasi::with('supplier')->get();

        // Kirim data ke view
        return view('pages.PemilikMebel.HasilRekomendasi.index', [
            'hasilRekomendasi' => $hasilRekomendasi
        ]);
    }

    public function exportToPdf()
    {
        // Ambil semua data hasil rekomendasi dari database
        $hasilRekomendasi = HasilRekomendasi::with('supplier')->get();

        // Generate PDF
        $pdf = PDF::loadView('pages.PemilikMebel.HasilRekomendasi.pdf', [
            'hasilRekomendasi' => $hasilRekomendasi
        ]);

        // Kembalikan PDF untuk di-download
        return $pdf->download('Laporan Hasil Rekomendasi Supplier.pdf');
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
