<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index($id = null, $name = null)
    {
        if ($id == null || $name == null) {
            return view('userpage')
                ->with('id', '2341720096')
                ->with('name', 'gigioo');
        } else{
            return view('userpage')
                ->with('id', $id)
                ->with('name', $name);
        }
    }
}
