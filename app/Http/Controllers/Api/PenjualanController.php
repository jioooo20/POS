<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PenjualanModel;
use App\Models\DetailPenjualanModel;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class PenjualanController extends Controller
{
    public function index()
    {
        return PenjualanModel::with('detail')->get();
    }

    public function storeapi(Request $request)
    {
        // Create penjualan with current date
        $data = array_merge($request->except('detail_penjualan'), [
            'penjualan_tanggal' => now()
        ]);

        $penjualan = PenjualanModel::create($data);

        // Create details
        foreach ($request->detail_penjualan as $detail) {
            $detail['penjualan_id'] = $penjualan->penjualan_id;
            DetailPenjualanModel::create($detail);
        }

        return response()->json($penjualan->load('detail'), 201);
    }

    public function show(PenjualanModel $penjualan)
    {
        return $penjualan->load('detail');
    }

    public function update(Request $request, PenjualanModel $penjualan)
    {
        // Update penjualan data
        $penjualan->update($request->except('detail_penjualan'));

        // Update detail penjualan if provided
        if ($request->has('detail_penjualan')) {
            foreach ($request->detail_penjualan as $detail) {
                if (isset($detail['detail_id'])) {
                    DetailPenjualanModel::where('detail_id', $detail['detail_id'])
                        ->update(array_merge(
                            ['penjualan_id' => $penjualan->penjualan_id],
                            Arr::except($detail, ['detail_id'])
                        ));
                }
            }
        }

        return $penjualan->fresh('detail');
    }


    public function destroy(PenjualanModel $penjualan)
    {
        // First delete all related detail_penjualan records
        $penjualan->detail()->delete();

        // Then delete the penjualan record itself
        $penjualan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data terhapus',
        ]);
    }
}
