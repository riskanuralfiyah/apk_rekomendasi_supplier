<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\BahanBaku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DataBahanBakuKaryawanController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $searchTerm = $request->input('search', '');
        $statusFilter = $request->input('status', '');

        $query = BahanBaku::query();

        if (!empty($searchTerm)) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_bahan_baku', 'like', '%' . $searchTerm . '%')
                  ->orWhere('satuan', 'like', '%' . $searchTerm . '%');
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

        return view('pages.Karyawan.DataBahanBaku.index', compact('bahanbakus'));
    }

    public function create()
    {
        return view('pages.Karyawan.DataBahanBaku.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_bahan_baku' => 'required|string|max:100',
            'satuan' => 'required|string|max:20',
            'stok_minimum' => 'required|integer|min:0',
            'jumlah_stok' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        BahanBaku::create([
            'nama_bahan_baku' => $request->nama_bahan_baku,
            'satuan' => $request->satuan,
            'stok_minimum' => $request->stok_minimum,
            'jumlah_stok' => $request->jumlah_stok,
        ]);

        return redirect()->route('databahanbaku.karyawan')
            ->with('success', 'Data bahan baku berhasil ditambahkan');
    }

    public function show($id)
    {
        $bahanbaku = BahanBaku::findOrFail($id);
        return view('pages.Karyawan.DataBahanBaku.detail', compact('bahanbaku'));
    }

    public function edit($id)
    {
        $bahanbaku = BahanBaku::findOrFail($id);
        return view('pages.Karyawan.DataBahanBaku.edit', compact('bahanbaku'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_bahan_baku' => 'required|string|max:100',
            'satuan' => 'required|string|max:20',
            'stok_minimum' => 'required|integer|min:0',
            'jumlah_stok' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $bahanbaku = BahanBaku::findOrFail($id);
        $bahanbaku->update([
            'nama_bahan_baku' => $request->nama_bahan_baku,
            'satuan' => $request->satuan,
            'stok_minimum' => $request->stok_minimum,
            'jumlah_stok' => $request->jumlah_stok,
        ]);

        return redirect()->route('databahanbaku.karyawan')
            ->with('success', 'Data bahan baku berhasil diperbarui');
    }

    public function destroy($id)
    {
        $bahanbaku = BahanBaku::findOrFail($id);
        $bahanbaku->delete();

        return redirect()->route('databahanbaku.karyawan')
            ->with('success', 'Data bahan baku berhasil dihapus');
    }
}
