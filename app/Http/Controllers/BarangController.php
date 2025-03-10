<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\KategoriModel;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Barang',
            'list' => ['Home', 'Barang']
        ];

        $page = (object)[
            'title' => 'Dafter barang yang terdaftar di dalam sistem',
        ];
        $active_menu = 'barang'; //menu yg sedang aktif
        $kategori = KategoriModel::all();
        return view('barang.index', compact('breadcrumb', 'page', 'kategori', 'active_menu'));
    }

    public function list(Request $request)
    {
        $barangs = BarangModel::select('barang_id', 'kategori_id', 'barang_kode',  'barang_nama', 'harga_beli', 'harga_jual')->with('kategori');
         //filter data user berdasarkan kategori_id
         if ($request->kategori_id) {
            $barangs->where('kategori_id', $request->kategori_id);
        }
        return datatables()->of($barangs)
            //menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($barang) {
                $btn = '<a href="' . url("/barang/{$barang->barang_id}") . '" class="btn btn-info btn-sm">Detail</a>';
                $btn .= '<a href="' . url("/barang/{$barang->barang_id}/edit") . '" class="btn btn-warning btn-sm">Edit</a>';
                $btn .= '<form class="d-inline-block" method="POST" action="' .
                    url("/barang/{$barang->barang_id}") . '">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function create()
    {
        $breadcrumb = (object)[
            'title' => 'Barang',
            'list' => ['Home', 'Barang', 'Tambah Barang']
        ];
        $page = (object)[
            'title' => 'Tambah Barang',
        ];
        $active_menu = 'barang'; //menu yg sedang aktif
        $kategori = KategoriModel::all();
        return view('barang.create', compact('breadcrumb', 'page', 'kategori', 'active_menu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required',
            'barang_kode' => 'required',
            'barang_nama' => 'required',
            'harga_beli' => 'required',
            'harga_jual' => 'required',
        ]);

        BarangModel::create($request->all());
        return redirect()->route('barang')->with('success', 'Barang berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $breadcrumb = (object)[
            'title' => 'Barang',
            'list' => ['Home', 'Barang', 'Detail Barang']
        ];
        $page = (object)[
            'title' => 'Detail Barang',
        ];
        $active_menu = 'barang'; //menu yg sedang aktif
        $barang = BarangModel::find($id);
        return view('barang.show', compact('breadcrumb', 'page', 'barang', 'active_menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $breadcrumb = (object)[
            'title' => 'Barang',
            'list' => ['Home', 'Barang', 'Edit Barang']
        ];
        $page = (object)[
            'title' => 'Edit Barang',
        ];
        $active_menu = 'barang'; //menu yg sedang aktif
        $kategori = KategoriModel::all();
        $barang = BarangModel::find($id);
        return view('barang.edit', compact('breadcrumb', 'page', 'barang', 'kategori', 'active_menu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'kategori_id' => 'required',
            'barang_kode' => 'required',
            'barang_nama' => 'required',
            'harga_beli' => 'required',
            'harga_jual' => 'required',
        ]);

        BarangModel::find($id)->update($request->all());
        return redirect()->route('barang')->with('success', 'Barang berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $check = BarangModel::find($id);
        if (!$check) {
            return redirect()->route('barang')->with('error', 'Barang tidak ditemukan');
        }
        try {
            BarangModel::destroy($id);
            return redirect()->route('barang')->with('success', 'Barang berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('barang')->with('error', 'Barang gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
