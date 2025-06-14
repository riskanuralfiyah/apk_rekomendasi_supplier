<?php

namespace App\Http\Controllers\PemilikMebel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BahanBaku;

class DataBahanBakuPemilikMebelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $searchTerm = $request->input('search', '');
        $statusFilter = $request->input('status', '');
    
        $query = BahanBaku::query();
    
        if (!empty($searchTerm)) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_bahan_baku', 'like', '%' . $searchTerm . '%')
                  ->orWhere('ukuran', 'like', '%' . $searchTerm . '%');
            });
        }
    
        // filter status: aman atau perlu restock
        if ($statusFilter === 'aman') {
            $query->whereColumn('jumlah_stok', '>', 'stok_minimum');
        } elseif ($statusFilter === 'perlu_restock') {
            $query->whereColumn('jumlah_stok', '<=', 'stok_minimum');
        }
    
        $bahanbakus = $query->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends([
                'per_page' => $perPage,
                'search' => $searchTerm,
                'status' => $statusFilter,
            ]);
    
        return view('pages.PemilikMebel.DataBahanBaku.index', compact('bahanbakus'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
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
