<?php

namespace App\Http\Controllers\PemilikMebel;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\HasilRekomendasi;
use Illuminate\Support\Facades\DB;

class DataPerhitunganController extends Controller
{
    public function hitung()
    {
        $jumlahKriteria = Kriteria::count();

        // jika data kriteria belum lengkap
        if ($jumlahKriteria == 0) {
            return view('pages.PemilikMebel.DataPerhitungan.index', [
                'errorMessage' => 'Tidak dapat menampilkan data perhitungan karena belum adanya kriteria.',
                'hasil' => [],
                'suppliers' => collect(),
                'kriterias' => collect(),
                'penilaianData' => [],
                'utilityData' => [],
                'jumlahKriteria' => $jumlahKriteria,
                'jumlahPenilaian' => 0
            ]);
        }

        // Cek apakah ada data penilaian
        $jumlahPenilaian = Penilaian::count();
        $adaPenilaian = $jumlahPenilaian > 0;

        // Ambil semua supplier yang memiliki penilaian
        $suppliers = $adaPenilaian ? Supplier::whereHas('penilaians')->get() : collect();

        // // Ambil semua supplier yang memiliki penilaian
        // $suppliers = Supplier::whereHas('penilaians')->get();

        // Ambil semua kriteria beserta subkriterianya
        $kriterias = Kriteria::with('subkriterias')->get();

        // Ambil data penilaian per supplier per kriteria
        $penilaianData = [];
        foreach ($suppliers as $supplier) {
            foreach ($kriterias as $kriteria) {
                $penilaian = Penilaian::where('id_supplier', $supplier->id)
                    ->where('id_kriteria', $kriteria->id)
                    ->first();

                if ($penilaian && $penilaian->subkriteria) {
                    $penilaianData[$supplier->id][$kriteria->id] = $penilaian->subkriteria->nilai;
                }
            }
        }

        // Hitung nilai min & max untuk setiap kriteria
        $minMax = [];
        foreach ($kriterias as $kriteria) {
            $nilaiKriteria = [];
            foreach ($suppliers as $supplier) {
                if (isset($penilaianData[$supplier->id][$kriteria->id])) {
                    $nilaiKriteria[] = $penilaianData[$supplier->id][$kriteria->id];
                }
            }

            if (!empty($nilaiKriteria)) {
                $minMax[$kriteria->id] = [
                    'min' => min($nilaiKriteria),
                    'max' => max($nilaiKriteria),
                    'kategori' => $kriteria->kategori, // 'cost' atau 'benefit'
                    'bobot' => $kriteria->bobot,
                ];
            }
        }

        $utilityData = [];
        $hasil = [];

        // Hitung skor akhir setiap supplier
        foreach ($suppliers as $supplier) {
            $totalSkor = 0;
            $adaPenilaian = false;

            foreach ($kriterias as $kriteria) {
                if (!isset($penilaianData[$supplier->id][$kriteria->id]) || !isset($minMax[$kriteria->id])) {
                    continue;
                }

                $adaPenilaian = true;
                $nilai = $penilaianData[$supplier->id][$kriteria->id];
                $min = $minMax[$kriteria->id]['min'];
                $max = $minMax[$kriteria->id]['max'];
                $bobot = $minMax[$kriteria->id]['bobot'];
                $kategori = $minMax[$kriteria->id]['kategori'];

                // Hindari pembagian nol
                if ($max == $min) {
                    $utility = 1;
                } else {
                    $utility = ($kategori === 'cost')
                        ? ($max - $nilai) / ($max - $min)
                        : ($nilai - $min) / ($max - $min);
                }

                $utility = round($utility, 2);
                $utilityData[$supplier->id][$kriteria->id] = $utility;
                $totalSkor += $utility * $bobot;
            }

            $hasil[] = [
                'id_supplier' => $supplier->id,
                'nama_supplier' => $supplier->nama_supplier,
                'skor_akhir' => round($totalSkor, 2),
            ];
        }

        // Urutkan berdasarkan skor akhir
        usort($hasil, fn ($a, $b) => $b['skor_akhir'] <=> $a['skor_akhir']);

        
        // ubah ke array numerik untuk bisa diurutkan
        $hasil = array_values($hasil);

        // Tambahkan peringkat
        foreach ($hasil as $index => $row) {
            $hasil[$index]['peringkat'] = $index + 1;
        }
        
        // Simpan ke tabel hasil_rekomendasis
        DB::table('hasil_rekomendasis')->truncate();
        foreach ($hasil as $row) {
            HasilRekomendasi::create([
                'id_supplier' => $row['id_supplier'],
                'skor_akhir' => $row['skor_akhir'],
                'peringkat' => $row['peringkat'],
            ]);
        }

        // Kirim ke view
        return view('pages.PemilikMebel.DataPerhitungan.index', [
            'hasil' => $hasil,
            'suppliers' => $suppliers,
            'kriterias' => $kriterias,
            'penilaianData' => $penilaianData,
            'utilityData' => $utilityData,
            'jumlahKriteria' => $jumlahKriteria,
            'jumlahPenilaian' => $jumlahPenilaian // Pastikan ini ditambahkan
        ]);
    }
}