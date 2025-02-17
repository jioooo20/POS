@extends('layouts.app')

@section('title', 'Home Page')

@section('content')

<body>
    <div class="container mt-5 pt-3">
        <h1 class="text-center">Point of Sales Vaject</h1>
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Products</h5>
                        <a href="{{ url('/products') }}" class="btn btn-primary">Go to Products</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">User</h5>
                        <a href="{{ url('/user') }}" class="btn btn-primary">Go to User</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Sales</h5>
                        <a href="{{ url('/sales') }}" class="btn btn-primary">Go to Sales</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

@endsection
