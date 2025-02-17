@extends('layouts.app')

@section('title', 'User Page')

@section('content')

@php
    $id = request()->route('id');
    $name = request()->route('name');
@endphp

<body>
    <div class="container">
        <br>
        <h1 class="text-center mt-5 ">User Page</h1>
        <p>ID: {{ $id }}</p>
        <p>Name: {{ $name }}</p>
    </div>
</body>

@endsection
