<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PenggunaController extends Controller
{
    //
    public function index(){
        $pengguna = User::all();
        return view('pengguna.index')->with(compact('pengguna'));
    }
    public function create(Request $request){
        User::create($request->all());
        return redirect()->route('pengguna')->with('success','Data berhasil ditambahkan');
    }
    public function showpengguna($user_id){
        $pengguna = User::findOrFail($user_id);
        return view('pengguna.update')->with(compact('pengguna'));
    }
    public function update(Request $request, $user_id){
        $pengguna = User::findOrFail($user_id);
        $pengguna->update($request->all());
        return redirect()->route('pengguna')->with('success','Data berhasil diupdate');
    }
    public function delete($user_id){
        $pengguna = User::findOrFail($user_id);
        $pengguna->delete();
        return redirect()->route('pengguna')->with('error','Data berhasil dihapus');
    }
}
