<?php

namespace App\Http\Controllers;

use App\Models\SupplierModel;
use Illuminate\Http\Request;

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
                $btn = '<a href="' . url("/supplier/{$supplier->supplier_id}") . '" class="btn btn-info btn-sm">Detail</a>';
                $btn .= '<a href="' . url("/supplier/{$supplier->supplier_id}/edit") . '" class="btn btn-warning btn-sm">Edit</a>';
                $btn .= '<form class="d-inline-block" method="POST" action="' .
                    url("/supplier/{$supplier->supplier_id}") . '">'
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
}
