<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
                // $btn = '<a href="' . url("/kategori/{$kategori->kategori_id}") . '" class="btn btn-info btn-sm">Detail</a>';
                // $btn .= '<a href="' . url("/kategori/{$kategori->kategori_id}/edit") . '" class="btn btn-warning btn-sm">Edit</a>';
                // $btn .= '<form class="d-inline-block" method="POST" action="' .
                //     url("/kategori/{$kategori->kategori_id}") . '">'
                //     . csrf_field() . method_field('DELETE') .
                //     '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                $btn  = '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id . '/delete_ajax') . '\')"  class="btn btn-danger btn-sm">Hapus</button> ';
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

    public function create_ajax()
    {
        return view('kategori.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        //cek apakah req berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_kode' => 'required|string|unique:m_kategori,kategori_kode',
                'kategori_nama' => 'required|string|max:100',
            ];
            //use validator
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, //status gagal (kalo true ya berhasil )
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors(), //pesan error validasi
                ]);
            }
            KategoriModel::create([
                'kategori_kode' => $request->kategori_kode,
                'kategori_nama' => $request->kategori_nama,
            ]);
            return response()->json([
                'status' => true, //status berhasil
                'message' => 'Kategori berhasil ditambahkan',
            ]);
        }
    }

    public function show_ajax(string $id)
    {
        $kategori = KategoriModel::find($id);
        return view('kategori.show_ajax', compact('kategori'));
    }


    public function edit_ajax(string $id)
    {
        $kategori = KategoriModel::find($id);
        return view('kategori.edit_ajax', compact('kategori'));
    }


    public function update_ajax(Request $request, $id)
    {
        //cek apakah req berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_kode' => 'required|string|unique:m_kategori,kategori_kode,' . $id . ',kategori_id',
                'kategori_nama' => 'required|string|max:100',
            ];
            //use validator
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, //status gagal (kalo true ya berhasil )
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors(), //pesan error validasi
                ]);
            }

            $kategori = KategoriModel::find($id);
            if ($kategori) {
                $kategori->update([
                    'kategori_kode' => $request->kategori_kode,
                    'kategori_nama' => $request->kategori_nama,
                ]);
                return response()->json([
                    'status' => true, //status berhasil
                    'message' => 'Data berhasil diubah',
                ]);
            } else {
                return response()->json([
                    'status' => false, //status gagal
                    'message' => 'Data tidak ditemukan',
                ]);
            }
        }
    }

    public function confirm_ajax(string $id)
    {
        $kategori = KategoriModel::find($id);
        return view('kategori.confirm_ajax', compact('kategori'));
    }

    public function delete_ajax(Request $request, $id){
        //cek apakah req berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $kategori = KategoriModel::find($id);
                if ($kategori) {
                    $kategori->delete();
                    return response()->json([
                        'status' => true, //status berhasil
                        'message' => 'Data berhasil dihapus',
                    ]);
                } else {
                    return response()->json([
                        'status' => false, //status gagal
                        'message' => 'Data tidak ditemukan',
                    ]);
                }
            } catch (\Exception $e) {
                if ($e->getCode() == '23000') { //sqlstate:23000
                    return response()->json([
                        'status' => false, //status gagal
                        'message' => 'Data tidak dapat dihapus karena masih terkait dengan data lain.',
                    ]);
                }
                return response()->json([
                    'status' => false, //status gagal
                    'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage(),
                ]);
            }
        }
        return redirect('/kategori');
    }
}
