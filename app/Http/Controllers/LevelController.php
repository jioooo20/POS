<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $btn = '<a href="' . url("/level/{$level->level_id}") . '" class="btn btn-info btn-sm">Detail</a>';
            $btn .= '<a href="' . url("/level/{$level->level_id}/edit") . '" class="btn btn-warning btn-sm">Edit</a>';
            $btn .= '<form class="d-inline-block" method="POST" action="' .
                url("/level/{$level->level_id}") . '">'
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
}
