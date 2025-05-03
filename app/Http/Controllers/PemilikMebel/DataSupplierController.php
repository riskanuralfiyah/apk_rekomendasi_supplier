<?php

// app/Http/Controllers/PemilikMebel/DataSupplierController.php
namespace App\Http\Controllers\PemilikMebel;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DataSupplierController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter dari request
        $perPage = $request->input('per_page', 10); // Default 10 item per halaman
        $searchTerm = $request->input('search', ''); // Term pencarian
        
        // Query dasar
        $query = Supplier::query();
        $query->withCount('penilaians');
        
        // Jika ada pencarian
        if (!empty($searchTerm)) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama_supplier', 'like', '%' . $searchTerm . '%')
                  ->orWhere('alamat', 'like', '%' . $searchTerm . '%')
                  ->orWhere('no_telpon', 'like', '%' . $searchTerm . '%');
            });
        }
        
        // Urutkan berdasarkan yang terbaru dan paginasi
        $suppliers = $query->orderBy('created_at', 'desc')
                         ->paginate($perPage)
                         ->appends([
                             'per_page' => $perPage,
                             'search' => $searchTerm
                         ]);
        
        return view('pages.PemilikMebel.DataSupplier.index', compact('suppliers'));
    }


    public function create()
    {
        return view('pages.PemilikMebel.DataSupplier.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_supplier' => 'required|string|max:100',
            'alamat' => 'required|string',
            'no_telpon' => 'required|string|max:20'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Supplier::create([
            'nama_supplier' => $request->nama_supplier,
            'alamat' => $request->alamat,
            'no_telpon' => $request->no_telpon
        ]);

        return redirect()->route('datasupplier.pemilikmebel')
            ->with('success', 'Data supplier berhasil ditambahkan');
    }

    public function show($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('pages.PemilikMebel.DataSupplier.detail', compact('supplier'));
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('pages.PemilikMebel.DataSupplier.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_supplier' => 'required|string|max:100',
            'alamat' => 'required|string', 
            'no_telpon' => 'required|string|max:20'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $supplier = Supplier::findOrFail($id);
        $supplier->update([
            'nama_supplier' => $request->nama_supplier,
            'alamat' => $request->alamat,
            'no_telpon' => $request->no_telpon
        ]);

        return redirect()->route('datasupplier.pemilikmebel')
            ->with('success', 'Data supplier berhasil diperbarui');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->route('datasupplier.pemilikmebel')
            ->with('success', 'Data supplier berhasil dihapus');
    }
}