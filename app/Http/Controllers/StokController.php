<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\StokModel;
use App\Models\SupplierModel;
use App\Models\UserModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class StokController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Stok',
            'list' => ['Home', 'Stok']
        ];

        $page = (object)[
            'title' => 'Dafter stok yang terdaftar di dalam sistem',
        ];
        $active_menu = 'stok';

        return view('stok.index', compact('breadcrumb', 'page', 'active_menu'));
    }

    public function list(Request $request)
    {

        $stoks = StokModel::select('stok_id', 'supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah','stok_sisa')
            ->with('supplier')
            ->with('user')
            ->with('barang');

        return datatables()->of($stoks)
            ->addIndexColumn()
            ->addColumn('aksi', function ($stok) {
                $btn = '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id . '/delete_ajax') . '\')"  class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax()
    {
        $supplier = SupplierModel::select('supplier_id', 'supplier_nama')->orderBy('supplier_nama', 'asc')->get();
        $barang = BarangModel::select('barang_id', 'barang_nama')->orderBy('barang_nama', 'asc')->get();
        return view('stok.create_ajax', compact('supplier', 'barang'));
    }

    public function store_ajax(Request $request)
    {

        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'supplier_id' => 'required|integer',
                'user_id' => 'required|integer',
                'barang_id' => 'required|integer',
                'stok_jumlah' => 'required|integer|min:1',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors(),
                ]);
            }
            StokModel::create([
                'supplier_id' => $request->supplier_id,
                'user_id' => $request->user_id,
                'barang_id' => $request->barang_id,
                'stok_jumlah' => $request->stok_jumlah,
                'stok_sisa' => $request->stok_jumlah,
                'stok_tanggal' => now('Asia/Jakarta'),
                // 'stok_tanggal' => now('Asia/Jakarta')->format('H:i, l, d F Y'),
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Stok berhasil ditambahkan',
            ]);
        }
    }

    public function confirm_ajax(string $id)
    {
        $stok = StokModel::find($id);
        return view('stok.confirm_ajax', compact('stok'));
    }
    public function delete_ajax(Request $request, $id)
    {
        //cek apakh req berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $stok = StokModel::find($id);
                if ($stok) {
                    $stok->delete();
                    return response()->json([
                        'status' => true, 
                        'message' => 'Data berhasil dihapus',
                    ]);
                } else {
                    return response()->json([
                        'status' => false, 
                        'message' => 'Data tidak ditemukan',
                    ]);
                }
            } catch (\Exception $e) {
                if ($e->getCode() == '23000') { 
                    return response()->json([
                        'status' => false, 
                        'message' => 'Data tidak dapat dihapus karena masih terkait dengan data lain.',
                    ]);
                }
                return response()->json([
                    'status' => false, 
                    'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage(),
                ]);
            }
        }
        return redirect('/stok');
    }

    public function import()
    {
        return view('stok.import');
    }
    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_stok' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_stok');

            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();

            $data = $sheet->toArray(null, false, true, true);

            $insert = [];
            if (count($data) > 1) { 
                $insert = [];

                foreach ($data as $baris => $value) {
                    if ($baris > 1) { 
                        $insert[] = [
                            'supplier_id' => $value['A'],
                            'barang_id' => $value['B'],
                            'user_id' => $value['C'],
                            'stok_jumlah' => $value['D'],
                            'stok_sisa' => $value['D'],
                            'stok_tanggal' => now()->setTimezone('Asia/Jakarta'),
                            'created_at' => now()->setTimezone('Asia/Jakarta'),
                        ];
                    }
                }

                if (count($insert) > 0) {
                    foreach ($insert as $row) {
                        StokModel::create($row);
                    }
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil diimport'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }
        return redirect('/stok');
    }

    public function export_excel()
    {
        $Stok = StokModel::select('supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah', 'stok_sisa')
            ->orderBy('supplier_id', 'asc')
            ->with('supplier')
            ->with('user')
            ->with('barang')
            ->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Pengguna');
        $sheet->setCellValue('C1', 'Supplier');
        $sheet->setCellValue('D1', 'Barang');
        $sheet->setCellValue('E1', 'Jumlah Stok');
        $sheet->setCellValue('F1', 'Sisa Stok');
        $sheet->setCellValue('G1', 'Tanggal Stok');

        $sheet->getStyle('A1:G1')->getFont()->setBold(true); 

        $no = 1;   //nomor  dari 1
        $baris = 2; //baris  dari 2
        foreach ($Stok as $stok) {
            $sheet->setCellValue('A' . $baris, $no++); 
            $sheet->setCellValue('B' . $baris, $stok->user->nama);
            $sheet->setCellValue('C' . $baris, $stok->supplier->supplier_nama);
            $sheet->setCellValue('D' . $baris, $stok->barang->barang_nama);
            $sheet->setCellValue('E' . $baris, $stok->stok_jumlah);
            $sheet->setCellValue('F' . $baris, $stok->stok_sisa);
            $sheet->setCellValue('G' . $baris, $stok->stok_tanggal);
            $baris++;
        }
        foreach (range('A', 'G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Stok'); 
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx'); 
        $filename = 'Data_Stok_' . date('Y-m-d_H-i-s') . '.xlsx'; 

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output'); 
        exit; 

    }

    public function export_pdf()
    {
        $stok = StokModel::select('supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah', 'stok_sisa')
            ->orderBy('stok_tanggal', 'asc')
            ->with('supplier')
            ->with('user')
            ->with('barang')
            ->get();

        $pdf = Pdf::loadView('stok.export_pdf', compact('stok'));
        $pdf->setPaper('A4', 'potrait'); 
        $pdf->setOptions(["isRemoteEnabled"], true); //set true jika ada gambar
        $pdf->render();

        return $pdf->stream('Data_Stok_' . date('Y-m-d_H-i-s') . '.pdf');
    }
}
