<?php

namespace App\Http\Controllers;

use App\Models\SupplierModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Supplier',
            'list' => ['Home', 'Supplier']
        ];

        $page = (object)[
            'title' => 'Dafter supplier yang terdaftar di dalam sistem',
        ];
        $active_menu = 'supplier'; //menu yg sedang aktif
        $supplier = SupplierModel::all();
        // dd($supplier);
        return view('supplier.index', compact('breadcrumb', 'page', 'supplier', 'active_menu'));
    }

    public function list(Request $request)
    {
        $suppliers = SupplierModel::select('supplier_id', 'supplier_kode',  'supplier_nama', 'supplier_alamat');
        return datatables()->of($suppliers)
            //menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($supplier) {
                // $btn = '<a href="' . url("/supplier/{$supplier->supplier_id}") . '" class="btn btn-info btn-sm">Detail</a>';
                // $btn .= '<a href="' . url("/supplier/{$supplier->supplier_id}/edit") . '" class="btn btn-warning btn-sm">Edit</a>';
                // $btn .= '<form class="d-inline-block" method="POST" action="' .
                //     url("/supplier/{$supplier->supplier_id}") . '">'
                //     . csrf_field() . method_field('DELETE') .
                //     '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                $btn  = '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id . '/delete_ajax') . '\')"  class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object)[
            'title' => 'Supplier',
            'list' => ['Home', 'Supplier', 'Tambah Supplier']
        ];
        $page = (object)[
            'title' => 'Tambah Supplier',
        ];
        $active_menu = 'supplier'; //menu yg sedang aktif
        $supplier = SupplierModel::all();
        return view('supplier.create', compact('breadcrumb', 'page', 'supplier', 'active_menu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_kode' => 'required',
            'supplier_nama' => 'required',
            'supplier_alamat' => 'required',
        ]);
        $exist = SupplierModel::where('supplier_kode', $request->supplier_kode)->first();
        if ($exist) {
            return redirect()->back()->with('error', 'Kode Supplier sudah terdaftar');
        } else {
            SupplierModel::create($request->all());
            return redirect()->route('supplier')->with('success', 'Data Supplier berhasil ditambahkan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $breadcrumb = (object)[
            'title' => 'Supplier',
            'list' => ['Home', 'Supplier', 'Detail Supplier']
        ];
        $page = (object)[
            'title' => 'Detail Supplier',
        ];
        $active_menu = 'supplier'; //menu yg sedang aktif
        $supplier = SupplierModel::find($id);
        return view('supplier.show', compact('breadcrumb', 'page', 'supplier', 'active_menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $breadcrumb = (object)[
            'title' => 'Supplier',
            'list' => ['Home', 'Supplier', 'Edit Supplier']
        ];
        $page = (object)[
            'title' => 'Edit Supplier',
        ];
        $active_menu = 'supplier'; //menu yg sedang aktif
        $supplier = SupplierModel::find($id);
        return view('supplier.edit', compact('breadcrumb', 'page', 'supplier', 'active_menu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'supplier_kode' => 'required',
            'supplier_nama' => 'required',
            'supplier_alamat' => 'required',
        ]);
        SupplierModel::find($id)->update($request->all());
        return redirect()->route('supplier')->with('success', 'Data Supplier berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            SupplierModel::find($id)->delete();
            return redirect('supplier')->with('success', 'Supplier deleted successfully');
        } catch (\Exception $e) {
            return redirect('supplier')->with('error', 'Supplier gagal dihapus karena masih terdapat barang yang terkait');
        }
    }

    public function create_ajax()
    {
        $supplier = SupplierModel::select('supplier_kode', 'supplier_nama', 'supplier_alamat')->get();

        return view('supplier.create_ajax')->with('supplier', $supplier);
    }

    public function store_ajax(Request $request)
    {
        //cek apakah req berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'supplier_kode' => 'required|string|max:10|unique:m_supplier,supplier_kode',
                'supplier_nama' => 'required|string|min:3|max:100',
                'supplier_alamat' => 'required|string|max:255',
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
            SupplierModel::create([
                'supplier_kode' => $request->supplier_kode,
                'supplier_nama' => $request->supplier_nama,
                'supplier_alamat' => $request->supplier_alamat,
            ]);
            return response()->json([
                'status' => true, //status berhasil
                'message' => 'Supplier berhasil ditambahkan',
            ]);
        }
    }

    public function show_ajax(string $id)
    {
        $supplier = SupplierModel::find($id);
        return view('supplier.show_ajax', compact('supplier'));
    }


    public function edit_ajax(string $id)
    {
        $supplier = SupplierModel::find($id);
        return view('supplier.edit_ajax', compact('supplier'));
    }


    public function update_ajax(Request $request, $id)
    {
        //cek apakah req berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'supplier_kode' => 'required|string|max:10|unique:m_supplier,supplier_kode,' . $id . ',supplier_id',
                'supplier_nama' => 'required|string|min:3|max:100',
                'supplier_alamat' => 'required|string|max:255',
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

            $supplier = SupplierModel::find($id);
            if ($supplier) {
                $supplier->update([
                    'supplier_kode' => $request->supplier_kode,
                    'supplier_nama' => $request->supplier_nama,
                    'supplier_alamat' => $request->supplier_alamat,
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
        $supplier = SupplierModel::find($id);
        return view('supplier.confirm_ajax', compact('supplier'));
    }

    public function delete_ajax(Request $request, $id)
    {
        //cek apakh req berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $supplier = SupplierModel::find($id);
                if ($supplier) {
                    $supplier->delete();
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
        return redirect('/supplier');
    }
}
