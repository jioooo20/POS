<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\DetailPenjualanModel;
use App\Models\PenjualanModel;
use App\Models\StokModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class PenjualanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Transaksi Penjualan',
            'list' => ['Home', 'Transaksi Penjualan']
        ];

        $page = (object)[
            'title' => 'Kasir Transaksi Penjualan',
            // 'url' => url('/penjualan'),
        ];
        $active_menu = 'penjualan';
        $penjualan = PenjualanModel::all();
        return view('penjualan.index', compact('breadcrumb', 'page', 'penjualan', 'active_menu'));
    }

    public function list(Request $request)
    {
        $penjualans = PenjualanModel::select('penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'status', 'penjualan_tanggal')->with('user');
        return datatables()->of($penjualans)
            ->addIndexColumn()
            ->addColumn('aksi', function ($penjualan) {
                $btn  = '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $user = auth()->user();
                if (in_array($user->level->level_kode, ['ADM', 'MNG'])) { //yg bs hps cuma admin sm manajer (soft delete)
                    if ($penjualan->status == 'berhasil') {
                        $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Batalkan</button> ';
                    }
                }
                return $btn;
            })

            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax()
    {
        // $barang = BarangModel::all();
        // $barang = BarangModel::all()->with('stok');
        // $barang = BarangModel::select('barang_id','barang_kode','barang_nama','harga_jual');
        $barang = BarangModel::select('barang_id', 'barang_kode', 'barang_nama', 'harga_jual')
            ->with('stok')
            ->orderBy('barang_nama', 'asc')
            ->get();

        return view('penjualan.create_ajax')->with(compact('barang'));
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'user_id' => 'required|integer',
                'pembeli' => 'required|string|max:100',
                'barang.*' => 'required|integer',
                'harga.*' => 'required|numeric',
                'jumlah.*' => 'required|integer|min:1',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal, pastikan semua telah terisi',
                    'msgField' => $validator->errors(),
                ]);
            }
            //cek stok < jum dibeli
            foreach ($request->barang as $key => $value) {
                $jum_dibeli = $request->jumlah[$key];
                $total_stok = StokModel::where('barang_id', $value)->sum('stok_sisa');

                if ($jum_dibeli > $total_stok) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Stok barang tidak cukup : ' . BarangModel::find($value)->barang_nama,
                    ]);
                }
            }

            $penjualan = PenjualanModel::create([
                'user_id' => $request->user_id,
                'pembeli' => $request->pembeli,
                'penjualan_kode' => now()->setTimezone('Asia/Jakarta')->format('sidmY') . Str::random(4),
                'penjualan_tanggal' => now()->setTimezone('Asia/Jakarta'),
            ]);

            foreach ($request->barang as $key => $value) {
                DetailPenjualanModel::create([
                    'penjualan_id' => $penjualan->penjualan_id,
                    'barang_id' => $value,
                    'harga' => $request->harga[$key],
                    'jumlah' => $request->jumlah[$key],
                ]);
                //update kurangi sisa stok di table stok dengan req stok[]
                //1 cari semua barang_id di table stok (anggap lebih dr 1 id stok )
                //2 ambil value sisa stok dari stok_id barang_id yang pertama
                //3 kurangi jumlah stok_sisa dengan jumlah stok[]
                //3.1 jika stok_sisa stok_id pertama < jumlah stok[], maka sisa_jumlah = jumlah - stok_2
                //3.2 stok_1 = 0 && stok_2 - sisa_jumlah

                //semua barang_id di t.stok stok_sisa > 0
                $stok_semua = StokModel::where('barang_id', $value)
                    ->where('stok_sisa', '>', 0)
                    ->orderBy('stok_id', 'asc')
                    ->get();

                if ($stok_semua->count() > 0) {
                    $jum_dibeli = $request->jumlah[$key]; //jum dibeli
                    foreach ($stok_semua as $stok_per_row) {
                        if ($jum_dibeli <= 0) {
                            break;
                        }

                        if ($stok_per_row->stok_sisa >= $jum_dibeli) { //1row
                            $stok_per_row->update([
                                'stok_sisa' => $stok_per_row->stok_sisa - $jum_dibeli
                            ]);
                            $jum_dibeli = 0;
                        } else { //>1row
                            $jum_dibeli -= $stok_per_row->stok_sisa;
                            $stok_per_row->update([
                                'stok_sisa' => 0
                            ]);
                        }
                    }
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Barang berhasil ditambahkan',
            ]);
        }
    }

    static function total_belanja($harga, $jumlah)
    {
        return $harga * $jumlah;
    }

    public function show_ajax(string $id)
    {
        $penjualan = PenjualanModel::with(['user', 'detail'])->find($id);
        if (!$penjualan) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        $harga = [];
        $jumlah = [];
        foreach ($penjualan->detail as $key => $value) {
            $harga[] = $value->harga;
            $jumlah[] = $value->jumlah;
        }
        $total = array_map([$this, 'total_belanja'], $harga, $jumlah);
        $total = array_sum($total);
        $penjualan->total = $total;
        return view('penjualan.show_ajax', compact('penjualan'));
    }

    public function confirm_ajax(string $id)
    {
        $penjualan = PenjualanModel::with(['user', 'detail'])->find($id);

        if (!$penjualan) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return view('penjualan.confirm_ajax', compact('penjualan'));
    }


    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $penjualan = PenjualanModel::find($id);
            $penjualan->status = 'dibatalkan';
            $penjualan->save();

            // update stok dikembalikan
            // ambil semua barang(row) pake penjualan_id ($id di atas itu)
            // (masuk loop setiap barang)
            //   ambil jumlah yg dikembalikan dari detail.jumlah (jum_kembali)
            //   (masuk loop stok barang)
            //     (jika jum_kembali <= 0) break; !!!
            //     ambil slot stok tersedia (dapat_diisi = jum-sisa) //ditaruh sini biar slot nya update
            //     jika dapat_diisi <= jum_kembali
            //       $stok_per_row update = stok_sisa => $per_row=>stok_sisa + $jum_kembali
            //
            //       jum_kembali = 0
            //     else
            //       $stok_per_row update =
            //       jum_kembali -= $dapat_diisi
            //
            //

            $detail_semua = DetailPenjualanModel::where('penjualan_id', $id)->orderBy('detail_id', 'desc')->get();
            foreach ($detail_semua as $value) {
                $stok_semua = StokModel::where('barang_id', $value->barang_id)->orderBy('stok_id', 'desc')->get(); //ambil semua row di 1 barang _id
                $jum_kembali = $value->jumlah;
                // $dapat_diisi = $stok_semua[$key]->stok_jumlah - $stok_semua[$key]->stok_sisa;
                foreach ($stok_semua as $stok_per_row) {
                    if ($jum_kembali <= 0) break; //muleh
                    $dapat_diisi = $stok_per_row->stok_jumlah - $stok_per_row->stok_sisa;

                    if ($dapat_diisi >= $jum_kembali) { //1 row
                        $stok_per_row->update([
                            'stok_sisa' => $stok_per_row->stok_sisa + $jum_kembali
                        ]);
                        $jum_kembali = 0;
                    } else { //>1row
                        $stok_per_row->update([
                            'stok_sisa' => $stok_per_row->stok_sisa + $dapat_diisi
                        ]);
                        $jum_kembali -= $dapat_diisi;
                    }
                }
            }

            //#kesalahan berpikir

            // foreach ($detail_semua as $key => $value) {
            //     $stok = StokModel::where('barang_id', $value->barang_id)->first();
            //     $stok->update([
            //         'stok_sisa' => $stok->stok_sisa + $value->jumlah
            //     ]);
            // }

            return response()->json([
                'url' => url('/penjualan'),
                'status' => true,
                'message' => 'Transaksi berhasil dibatalkan, dan stok dikembalikan',
            ]);
        }
    }

    public function export_excel()
    {
        $penjualan_semua = PenjualanModel::with('detail')->with('user')->orderBy('penjualan_tanggal', 'asc')->get();
        $penjualan = [];

        foreach ($penjualan_semua as $data) {
            foreach ($data->detail as $item) {
                $penjualan[] = [
                    'nama' => $data->user->nama,
                    'pembeli' => $data->pembeli,
                    'penjualan_kode' => $data->penjualan_kode,
                    'tgl_penjualan' => $data->penjualan_tanggal,
                    'barang' => $item->barang->barang_nama,
                    'harga' => $item->harga,
                    'jumlah' => $item->jumlah,
                    'total' => $item->jumlah * $item->harga,
                ];
            }
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Stok');
        $headers = ['Nama', 'Pembeli', 'Kode Penjualan', 'Tanggal', 'Barang', 'Harga', 'Jumlah', 'Total'];
        $sheet->fromArray($headers, null, 'A1');
        $row = 2;
        $startMerge = $row;
        $lastKode = null;
        foreach ($penjualan as $index => $item) {
            $sheet->setCellValue("A{$row}", $item['nama']);
            $sheet->setCellValue("B{$row}", $item['pembeli']);
            $sheet->setCellValue("C{$row}", $item['penjualan_kode']);
            $sheet->setCellValue("D{$row}", $item['tgl_penjualan']);
            $sheet->setCellValue("E{$row}", $item['barang']);
            $sheet->setCellValue("F{$row}", $item['harga']);
            $sheet->setCellValue("G{$row}", $item['jumlah']);
            $sheet->setCellValue("H{$row}", $item['total']);

            // Jika penjualan_kode berubah (atau akhir data), merge yang sebelumnya
            $next = $penjualan[$index + 1]['penjualan_kode'] ?? null;
            if ($item['penjualan_kode'] !== $next) {
                if ($startMerge !== $row) {
                    $sheet->mergeCells("A{$startMerge}:A{$row}");
                    $sheet->mergeCells("B{$startMerge}:B{$row}");
                    $sheet->mergeCells("C{$startMerge}:C{$row}");

                    $sheet->getStyle("A{$startMerge}:C{$row}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                    $sheet->getStyle("A{$startMerge}:C{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
                $startMerge = $row + 1; // reset ke baris berikutnya
            }

            $row++;
        }
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data_Penjualan_' . date('Y-m-d_H-i-s') . '.xlsx';

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
        $penjualan_semua = PenjualanModel::with('detail')->with('user')->orderBy('penjualan_tanggal', 'asc')->get();

        $rekap = [];
        foreach ($penjualan_semua as $data) {
            foreach ($data->detail as $item) {
                $rekap[] = [
                    'nama' => $data->user->nama,
                    'pembeli' => $data->pembeli,
                    'penjualan_kode' => $data->penjualan_kode,
                    'tgl_penjualan' => $data->penjualan_tanggal,
                    'barang' => $item->barang->barang_nama,
                    'harga' => $item->harga,
                    'jumlah' => $item->jumlah,
                    'total' => $item->jumlah * $item->harga,
                ];
            }
        }

        $pdf = Pdf::loadView('penjualan.export_pdf', compact('rekap'));
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions(["isRemoteEnabled"], true); //set true jika ada gambar
        $pdf->render();
        return $pdf->stream('Data_Penjualan_' . date('Y-m-d_H-i-s') . '.pdf');
    }
}
