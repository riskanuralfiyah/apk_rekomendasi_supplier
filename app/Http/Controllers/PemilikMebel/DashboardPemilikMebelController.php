<?php

namespace App\Http\Controllers\PemilikMebel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Supplier;
use App\Models\Kriteria;
use App\Models\Subkriteria;
use App\Models\BahanBaku;

class DashboardPemilikMebelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalSupplier = Supplier::count();
        $totalKriteria = Kriteria::count();
        $totalSubkriteria = Subkriteria::count();
        $totalBahanBaku = BahanBaku::count();

        return view('pages.PemilikMebel.index', compact(
            'totalSupplier',
            'totalKriteria',
            'totalSubkriteria',
            'totalBahanBaku'
        ));
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
