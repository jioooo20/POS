<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LevelController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Level',
            'list' => ['Home', 'Level']
        ];

        $page = (object)[
            'title' => 'Dafter level yang terdaftar di dalam sistem',
        ];
        $active_menu = 'level'; //menu yg sedang aktif
        $level = LevelModel::all();
        // dd($level);
        return view('level.index', compact('breadcrumb', 'page', 'level', 'active_menu'));
    }

    public function list(Request $request){
        $levels = LevelModel::select('level_id', 'level_kode', 'level_nama');
        return datatables()->of($levels)
        //menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
        ->addIndexColumn()
        ->addColumn('aksi', function ($level) {
            // $btn = '<a href="' . url("/level/{$level->level_id}") . '" class="btn btn-info btn-sm">Detail</a>';
            // $btn .= '<a href="' . url("/level/{$level->level_id}/edit") . '" class="btn btn-warning btn-sm">Edit</a>';
            // $btn .= '<form class="d-inline-block" method="POST" action="' .
            //     url("/level/{$level->level_id}") . '">'
            //     . csrf_field() . method_field('DELETE') .
            //     '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
            $btn  = '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
            $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
            $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/delete_ajax') . '\')"  class="btn btn-danger btn-sm">Hapus</button> ';
            return $btn;
        })
        ->rawColumns(['aksi'])
        ->make(true);

    }

    public function create()
    {
        $breadcrumb = (object)[
            'title' => 'level',
            'list' => ['Home', 'level', 'Create']
        ];
        $page = (object)[
            'title' => 'Tambah Level Baru',
        ];
        $active_menu = 'level'; //menu yg sedang aktif
        $level = LevelModel::all();
        return view('level.create', compact('breadcrumb', 'page', 'level', 'active_menu'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'level_kode' => 'required',
            'level_nama' => 'required',
        ]);
        $exist = LevelModel::where('level_kode', $request->level_kode)->first();
        if ($exist) {
            return redirect()->route('level.create')
                ->with('error', 'Kode level sudah terdaftar');
        }else{
            LevelModel::create($request->all());
            return redirect()->route('level')
                ->with('success', 'level created successfully');
        }
    }

    public function show(string $id)
    {
        $breadcrumb = (object)[
            'title' => 'Level',
            'list' => ['Home', 'Level', 'Detail']
        ];
        $page = (object)[
            'title' => 'Detail Level',
        ];
        $active_menu = 'level'; //menu yg sedang aktif
        $level = LevelModel::find($id);
        return view('level.show', compact('breadcrumb', 'page', 'level', 'active_menu'));
    }

    public function edit(string $id)
    {
        $breadcrumb = (object)[
            'title' => 'Level',
            'list' => ['Home', 'Level', 'Detail']
        ];
        $page = (object)[
            'title' => 'Edit Level',
        ];
        $active_menu = 'level'; //menu yg sedang aktif
        $level = LevelModel::find($id);
        return view('level.edit', compact('breadcrumb', 'page', 'level', 'active_menu'));
    }


    public function update(Request $request, string $id)
    {
        $request->validate([
            'level_kode' => 'required',
            'level_nama' => 'required',
        ]);

        LevelModel::find($id)->update($request->all());
        return redirect()->route('level', )
            ->with('success', 'Level updated successfully');
    }

    public function destroy(string $id)
    {
        try {
            LevelModel::find($id)->delete();
            return redirect('level')->with('success', 'Level deleted successfully');
        } catch (\Exception $e) {
            return redirect('level')->with('error', 'Level gagal dihapus karena masih terdapat barang yang terkait');
        }
    }

    public function create_ajax()
    {
        return view('level.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        //cek apakah req berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_kode' => 'required|string|unique:m_level,level_kode',
                'level_nama' => 'required|string|max:100',
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
            LevelModel::create([
                'level_kode' => $request->level_kode,
                'level_nama' => $request->level_nama,
            ]);
            return response()->json([
                'status' => true, //status berhasil
                'message' => 'Level berhasil ditambahkan',
            ]);
        }
    }

    public function show_ajax(string $id)
    {
        $level = LevelModel::find($id);
        return view('level.show_ajax', compact('level'));
    }


    public function edit_ajax(string $id)
    {
        $level = LevelModel::find($id);
        return view('level.edit_ajax', compact('level'));
    }


    public function update_ajax(Request $request, $id)
    {
        //cek apakah req berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_kode' => 'required|string|unique:m_level,level_kode,' . $id . ',level_id',
                'level_nama' => 'required|string|max:100',
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

            $level = LevelModel::find($id);
            if ($level) {
                $level->update([
                    'level_kode' => $request->level_kode,
                    'level_nama' => $request->level_nama,
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
        $level = LevelModel::find($id);
        return view('level.confirm_ajax', compact('level'));
    }

    public function delete_ajax(Request $request, $id){
        //cek apakah req berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $level = LevelModel::find($id);
                if ($level) {
                    $level->delete();
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
        return redirect('/level');
    }
}
