<?php


namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register()
    {
        $levels = LevelModel::all();
        return view('auth.regis', compact('levels'));
    }

    public function postregister(Request $request)
    {
        $request->validate([
            'username' => 'required|min:3|max:20',
            'password' => 'required|min:6|max:20',
            'nama' => 'required|max:100',
            'level_id' => 'required',
        ]);


        if (UserModel::where('username', $request->username)->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Username telah digunakan'
            ], 400);
        }

        UserModel::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'nama' => $request->nama,
            'level_id' => $request->level_id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User berhasil ditambahkan',
            'redirect' => route('login')
        ]);
    }


    public function login()
    {
        if (Auth::check()) { // jika sudah login, maka redirect ke halaman home
            return redirect('/');
        }
        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $credentials = $request->only('username', 'password');

            if (Auth::attempt($credentials)) {
                return response()->json([
                    'status' => true,
                    'message' => 'Login Berhasil',
                    'redirect' => url('/')
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Login Gagal'
            ]);
        }

        return redirect('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }
}
