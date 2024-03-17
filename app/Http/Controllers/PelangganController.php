<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    //
    public function index(){
        $pelanggan = Pelanggan::all();
        return view('pelanggan.index')->with(compact('pelanggan'));
    }
    public function create(Request $request){
        Pelanggan::create($request->all());
        return redirect()->route('pelanggan')->with('success','Data berhasil ditambahkan');
    }
    public function delete($pelanggan_id){
        $pelanggan = Pelanggan::findOrFail($pelanggan_id);
        $pelanggan->delete();
        return redirect()->route('pelanggan')->with('error','Data berhasil dihapus');
    }
}
