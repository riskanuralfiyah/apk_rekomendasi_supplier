<?php

namespace App\Http\Controllers\PemilikMebel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KelolaPenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.PemilikMebel.KelolaPengguna.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.PemilikMebel.KelolaPengguna.create');
    }

    public function edit()
    {
        // Data statis (contoh)
        $pengguna = (object) [
            'id' => 1, // ID pengguna
            'nama' => 'Pengguna A', // Nama pengguna
            'email' => 'pengguna1@gmail.com', 
            'role' => 'Pemilik Mebel', 
        ];

        // Tampilkan view edit dengan data statis
        return view('pages.PemilikMebel.KelolaPengguna.edit', compact('pengguna'));
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
