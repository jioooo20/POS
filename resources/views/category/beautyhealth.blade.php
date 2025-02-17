@extends('layouts.app')

@section('title', 'Home Page')

@section('content')

<body>
    <div class="container mt-5">
        <h2 class="mb-4 pt-4">Beauty & Health</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="path/to/beauty1.jpg" class="card-img-top" alt="Beauty Product 1">
                    <div class="card-body">
                        <h5 class="card-title">Beauty Product 1</h5>
                        <p class="card-text">Description of beauty product 1.</p>
                        <a href="#" class="btn btn-primary">Buy Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="path/to/health1.jpg" class="card-img-top" alt="Health Product 1">
                    <div class="card-body">
                        <h5 class="card-title">Health Product 1</h5>
                        <p class="card-text">Description of health product 1.</p>
                        <a href="#" class="btn btn-primary">Buy Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="path/to/beauty2.jpg" class="card-img-top" alt="Beauty Product 2">
                    <div class="card-body">
                        <h5 class="card-title">Beauty Product 2</h5>
                        <p class="card-text">Description of beauty product 2.</p>
                        <a href="#" class="btn btn-primary">Buy Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

@endsection
