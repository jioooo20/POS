<?php

namespace App\Http\Controllers;

use App\Models\SupplierModel;
use App\Models\BarangModel;
use App\Models\StokModel;
use App\Models\PenjualanModel;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Selamat Datang',
            'list' => ['Home', 'Welcome']
        ];
        $totalSupplier = SupplierModel::count();
        $totalBarang = BarangModel::count();
        $totalStok = StokModel::count();
        $totalTransaksi = PenjualanModel::count();

        $stok_ready = StokModel::selectRaw('barang_id, MAX(stok_sisa) as stok_sisa, MAX(stok_jumlah) as stok_jumlah')
            ->where('stok_sisa', '>', 0)
            ->groupBy('barang_id')
            ->get();

        $stok_ready->map(function ($item) {
                $item->persen = ($item->stok_sisa / $item->stok_jumlah) *100;
            return $item;
        });
        // dd($stok_ready);

        $active_menu = 'dashboard';
        return view('welcome', compact('breadcrumb', 'active_menu', 'totalSupplier', 'totalBarang', 'totalStok', 'totalTransaksi', 'stok_ready'));
        // return view('welcome', ['breadcumb' => $breadcumb, 'active_menu' => $active_menu]); sm s
    }
}
