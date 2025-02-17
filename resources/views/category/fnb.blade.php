@extends('layouts.app')

@section('title', 'Home Page')

@section('content')

<body>
    <div class="container mt-5">
        <h2 class="mb-4 pt-4">Food & Beverages</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="path/to/food1.jpg" class="card-img-top" alt="Food Product 1">
                    <div class="card-body">
                        <h5 class="card-title">Food Product 1</h5>
                        <p class="card-text">Description of food product 1.</p>
                        <a href="#" class="btn btn-primary">Buy Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="path/to/beverage1.jpg" class="card-img-top" alt="Beverage Product 1">
                    <div class="card-body">
                        <h5 class="card-title">Beverage Product 1</h5>
                        <p class="card-text">Description of beverage product 1.</p>
                        <a href="#" class="btn btn-primary">Buy Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="path/to/food2.jpg" class="card-img-top" alt="Food Product 2">
                    <div class="card-body">
                        <h5 class="card-title">Food Product 2</h5>
                        <p class="card-text">Description of food product 2.</p>
                        <a href="#" class="btn btn-primary">Buy Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

@endsection
