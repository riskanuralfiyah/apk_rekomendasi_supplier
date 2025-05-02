<?php

namespace App\Http\Controllers\PemilikMebel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PenilaianSupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.PemilikMebel.PenilaianSupplier.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.PemilikMebel.PenilaianSupplier.create');
    }

    public function edit()
{
    // Data penilaian statis (contoh)
    $penilaian = (object) [
        'id' => 1,
        'supplier_id' => 1,
        'kualitas' => 2, // nilai dari dropdown kualitas
        'harga' => 1,    // nilai dari dropdown harga
        'pelayanan' => 3, // nilai dari dropdown pelayanan
        'created_at' => now(),
        'updated_at' => now()
    ];

    return view('pages.PemilikMebel.PenilaianSupplier.edit', compact('penilaian'));
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
