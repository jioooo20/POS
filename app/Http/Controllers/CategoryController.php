<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function fnb()
    {
        return view('category.fnb');
    }
    public function beauty()
    {
        return view('category.beautyhealth');
    }
    public function homecare()
    {
        return view('category.homecare');
    }
    public function babykid()
    {
        return view('category.babykid');
    }
}
