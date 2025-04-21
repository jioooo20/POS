@empty($penjualan)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/penjualan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Transaksi Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">Kode Penjualan :</th>
                        <td class="col-9">{{ $penjualan->penjualan_kode }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Kasir :</th>
                        <td class="col-9">{{ $penjualan->user->nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Pembeli :</th>
                        <td class="col-9">{{ $penjualan->pembeli }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal Penjualan :</th>
                        <td class="col-9">{{ $penjualan->penjualan_tanggal }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Status :</th>
                        <td class="col-9">{{ $penjualan->status }}</td>
                    </tr>
                </table>
                {{-- <h6 class="mt-4 mb-3">Detail Barang:</h6> --}}
                <div class="form-row mb-2">
                    <div class="col-7">Nama Barang</div>
                    <div class="col-4">Harga</div>
                    <div class="text-center col-1">Jumlah</div>
                </div>
                @foreach ($penjualan->detail as $item)
                    <div class="form-row mb-2 barang-row">
                        <div class="col-7">
                            <div class="form-control bg-light">{{ $item->barang->barang_nama }}</div>
                        </div>
                        <div class="col-4">
                            <div class="form-control bg-light">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                        </div>
                        <div class="text-center col-1">
                            <div class="form-control bg-light">{{ $item->jumlah }}</div>
                        </div>
                    </div>
                @endforeach
                <hr style="height:2px;border-width:0;color:rgb(194, 194, 194);background-color:rgb(182, 182, 182)">
                <div class="form-row mb-2 justify-content-end">
                    <div class="col-3 align-self-center">
                        <div class="text-right font-weight-bold">Total </div>
                    </div>
                    <div class="col-5">
                        <div class="form-control bg-light text-right">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary">Tutup</button>
            </div>
        </div>
    </div>
@endempty
