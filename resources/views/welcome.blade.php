@extends('layouts.template')
@section('content')
    <div class="row mx-2">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalSupplier }}</h3>
                    <p>Jumlah Supplier</p>
                </div>
                <div class="icon">
                    <i class="fas fa-truck"></i>
                </div>
                <a href="{{ route('supplier') }}" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalBarang }}</h3>
                    <p>Jumlah Barang</p>
                </div>
                <div class="icon">
                    <i class="fas fa-box"></i>
                </div>
                <a href="{{ route('barang') }}" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $totalStok }}</h3>
                    <p>Stok Barang</p>
                </div>
                <div class="icon">
                    <i class="fas fa-cubes"></i>
                </div>
                <a href="{{ route('stok') }}" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalTransaksi }}</h3>
                    <p>Total Transaksi</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <a href="{{ route('penjualan') }}" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Video Panduan Aplikasi ini</h3>
                </div>
                <div class="card-body">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/y14DW-eHZy0?si=2AxQnelccUwoRdQ8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Stok Ready Dijual</h3>

                    <div class="card-tools">
                        <ul class="pagination pagination-sm float-right">
                            <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                        </ul>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Barang</th>
                                <th>Stok Ready</th>
                                <th style="width: 40px">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stok_ready as $key => $value)
                                <tr>
                                    <td>{{$key + 1}}</td>
                                    <td>{{$stok_ready[$key]['barang']['barang_nama']}}</td>
                                    <td>
                                        <div class="progress progress-xs">
                                            <div class="progress-bar bg-success" style="width: {{$stok_ready[$key]['persen']}}%"></div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-success">{{ round($stok_ready[$key]['persen'], 2) }}%</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
@endsection
