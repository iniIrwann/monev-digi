@extends('layout.app')

@section('title', 'Dashboard - Monev Digi Dana Desa')


@section('main')
  <div class="main-content ps-3 pe-3 pt-4">
    <div class="row g-3 text-center">
        <!-- Logo -->
        <div class="col-12 mb-3 pt-3">
            <img src="{{ asset('assets/images/logo.png') }}" 
                 alt="Logo Desa" 
                 class="img-fluid" style="max-height: 120px;">
        </div>

        <!-- Judul -->
        <div class="col-12 mb-2">
            <h2 class="sb tx-green">MONEV DIGI DANA DESA</h2>
            <h5 class="text-secondary">Monitoring dan Evaluasi Dana Desa</h5>
        </div>

        <!-- Deskripsi -->
        <div class="col-12">
            <p class="text-muted px-4">
                Aplikasi <b>Monev Digi Dana Desa</b> adalah sistem yang digunakan untuk 
                memantau dan mengevaluasi capaian pembangunan desa berbasis Dana Desa. 
                Dashboard ini menampilkan informasi mengenai jumlah target, realisasi, 
                serta capaian sempurna yang telah dicapai. Data divisualisasikan dalam 
                bentuk grafik untuk memudahkan analisis dan pengambilan keputusan 
                terkait pengelolaan Dana Desa secara transparan dan akuntabel.
            </p>
        </div>
    </div>
</div>

@endsection
