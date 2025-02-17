@extends('layouts.app')

@section('title', 'Home Page')

@section('content')

<body>
    <div class="container mt-5">
        <h2 class="mb-4 pt-4">Home Care Products</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="path/to/homecare1.jpg" class="card-img-top" alt="Home Care Product 1">
                    <div class="card-body">
                        <h5 class="card-title">Home Care Product 1</h5>
                        <p class="card-text">Description of home care product 1.</p>
                        <a href="#" class="btn btn-primary">Buy Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="path/to/homecare2.jpg" class="card-img-top" alt="Home Care Product 2">
                    <div class="card-body">
                        <h5 class="card-title">Home Care Product 2</h5>
                        <p class="card-text">Description of home care product 2.</p>
                        <a href="#" class="btn btn-primary">Buy Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="path/to/homecare3.jpg" class="card-img-top" alt="Home Care Product 3">
                    <div class="card-body">
                        <h5 class="card-title">Home Care Product 3</h5>
                        <p class="card-text">Description of home care product 3.</p>
                        <a href="#" class="btn btn-primary">Buy Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

@endsection
