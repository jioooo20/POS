@extends('layouts.app')

@section('title', 'Home Page')

@section('content')

<body>

<div class="container">
    <h1 class="my-4 pt-5">Point of Sales Transactions</h1>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Baby Diapers</td>
                        <td>2</td>
                        <td>IDR 10000</td>
                        <td>IDR 20000</td>
                        <td>2023-10-01</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Laundry Detergent</td>
                        <td>1</td>
                        <td>IDR 15000</td>
                        <td>IDR 15000</td>
                        <td>2023-10-02</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row my-4">
        <div class="col-md-12 text-right">
            <button class="btn btn-primary">Checkout</button>
            <button class="btn btn-secondary">Add Transaction</button>
        </div>
    </div>
</div>
</body>

@endsection
