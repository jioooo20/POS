@extends('layouts.app')

@section('title', 'Home Page')

@section('content')
<body>
    <div class="container mt-5">
        <h1 class="text-center pt-4">Product Categories</h1>
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Baby & Kid</h5>
                        <a href="{{ url('/category/baby-kid') }}" class="btn btn-primary">View Products</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Home Care</h5>
                        <a href="{{ url('/category/home-care') }}" class="btn btn-primary">View Products</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Beauty & Health</h5>
                        <a href="{{ url('/category/beauty-health') }}" class="btn btn-primary">View Products</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Food & Beverage</h5>
                        <a href="{{ url('/category/food-beverage') }}" class="btn btn-primary">View Products</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

@endsection
