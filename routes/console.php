<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\GenerateLaporanBulanan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Ini menjalankan setiap awal bulan
Artisan::command('generate:laporan-bulanan', function () {
    $this->call(GenerateLaporanBulanan::class);
})->monthly();
