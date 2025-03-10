<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Kategori',
            'list' => ['Home', 'Kategori']
        ];

        $page = (object)[
            'title' => 'Dafter kategori yang terdaftar di dalam sistem',
        ];
        $active_menu = 'kategori'; //menu yg sedang aktif
        $kategori = KategoriModel::all();
        // dd($kategori);
        return view('kategori.index', compact('breadcrumb', 'page', 'kategori', 'active_menu'));
    }

    public function list(Request $request)
    {
        $kategoris = KategoriModel::select('kategori_id', 'kategori_kode',  'kategori_nama');
        return datatables()->of($kategoris)
            //menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kategori) {
                $btn = '<a href="' . url("/kategori/{$kategori->kategori_id}") . '" class="btn btn-info btn-sm">Detail</a>';
                $btn .= '<a href="' . url("/kategori/{$kategori->kategori_id}/edit") . '" class="btn btn-warning btn-sm">Edit</a>';
                $btn .= '<form class="d-inline-block" method="POST" action="' .
                    url("/kategori/{$kategori->kategori_id}") . '">'
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
            'title' => 'Kategori',
            'list' => ['Home', 'Kategori', 'Create']
        ];
        $page = (object)[
            'title' => 'Tambah Kategori',
        ];
        $active_menu = 'kategori'; //menu yg sedang aktif
        $kategori = KategoriModel::all();
        return view('kategori.create', compact('breadcrumb', 'page', 'kategori', 'active_menu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kategori_kode' => 'required',
            'kategori_nama' => 'required',
        ]);
        $exist = KategoriModel::where('kategori_kode', $request->kategori_kode)->first();
        if ($exist) {
            return redirect()->route('kategori.create')
                ->with('error', 'Kode Kategori sudah terdaftar');
        } else {
            KategoriModel::create($request->all());
            return redirect()->route('kategori')
                ->with('success', 'Kategori created successfully');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $breadcrumb = (object)[
            'title' => 'Kategori',
            'list' => ['Home', 'Kategori', 'Detail']
        ];
        $page = (object)[
            'title' => 'Detail Kategori',
        ];
        $active_menu = 'kategori'; //menu yg sedang aktif
        $kategori = KategoriModel::find($id);
        return view('kategori.show', compact('breadcrumb', 'page', 'kategori', 'active_menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $breadcrumb = (object)[
            'title' => 'Kategori',
            'list' => ['Home', 'Kategori', 'Edit']
        ];
        $page = (object)[
            'title' => 'Edit Kategori',
        ];
        $active_menu = 'kategori'; //menu yg sedang aktif
        $kategori = KategoriModel::find($id);
        return view('kategori.edit', compact('breadcrumb', 'page', 'kategori', 'active_menu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'kategori_kode' => 'required',
            'kategori_nama' => 'required',
        ]);

        KategoriModel::find($id)->update($request->all());
        return redirect()->route('kategori',)
            ->with('success', 'Kategori updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            KategoriModel::find($id)->delete();
            return redirect('kategori')->with('success', 'Kategori deleted successfully');
        } catch (\Exception $e) {
            return redirect('kategori')->with('error', 'Kategori gagal dihapus karena masih terdapat barang yang terkait');
        }
    }
}
