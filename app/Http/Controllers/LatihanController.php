<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use App\Models\Temp;
use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LatihanController extends Controller
{
    //
    public function index(){
        $penjualan = Penjualan::with('pelanggans')
                    ->where('tanggal_penjualan',date('y-m-d'))
                    ->get();
        $pelanggan = Pelanggan::all();
        return view('penjualan.index')->with(compact('penjualan','pelanggan'));
    }
    public function transaksi($pelanggan_id){
        $produk = Produk::where('stok','>',0)->orderBy('nama_produk','ASC')->get();
        $pelanggan = Pelanggan::findOrFail($pelanggan_id);
        $temp = DB::table('temps as a')
                ->leftJoin('produks as b','a.produk_id','=','b.produk_id')
                ->where('a.pelanggan_id',$pelanggan_id)
                ->where('a.user_id',auth()->user()->user_id)
                ->get();
        $data = [
            'pelanggan' => $pelanggan, 
            'pelanggan_id' => $pelanggan_id, 
            'produk' => $produk, 
            'temp' => $temp, 
        ];
        return view('penjualan.transaksi',$data);
    }
    public function addtemp(Request $request,$pelanggan_id){
        $produk = Produk::where('produk_id',$request->produk_id)->first();
        $stoklama = $produk->stok;
        $temp = Temp::where('pelanggan_id',$request->pelanggan_id)
                   ->where('user_id',auth()->user()->user_id)
                   ->where('produk_id',$request->produk_id)
                   ->first();
            if ($stoklama < $request->jumlah_produk) {
                # code...
                return redirect()->back()->with('error','stok tidak mencukupi');
            } elseif($temp !== NULL) {
                # code...
                $temp->jumlah_produk+=$request->jumlah_produk;
                $temp->save();
                return redirect()->back()->with('success','jumlah produk diperbarui');
            }else{
                $data = [
                    'pelanggan_id' => $request->pelanggan_id,
                    'user_id' =>auth()->user()->user_id,
                    'produk_id' => $request->produk_id,
                    'jumlah_produk' => $request->jumlah_produk,
                ];
                Temp::create($data);
                return redirect()->back()->with('success','produk ditambah keranjang');
            }
            
        
    }
    public function deltemp($pelanggan_id){
        $temp = Temp::findOrFail($pelanggan_id);
        $temp->delete();
        return redirect()->back()->with('error','produk dihapus dari keranjang');
    }
    public function bayar(Request $request, $pelanggan_id){
        $jumlah = Penjualan::whereRaw("DATE_FORMAT(tanggal_penjualan,'%Y-%m')=?",date('y-m'))->count();
        $nota   = date('ymd').($jumlah+1);
        $temp = DB::table('temps as a')
                ->leftJoin('produks as b','a.produk_id','=','b.produk_id')
                ->where('a.pelanggan_id',$pelanggan_id)
                ->where('a.user_id',auth()->user()->user_id)
                ->first();
        foreach ($temp as $item) {
            # code...\
            if ($temp->stok < $temp->jumlah_produk) {
                # code...
                return redirect()->back()->with('error','stok tidak mencukupi');
            } 
            $data = [
                'kode_penjualan' => $nota, 
                'produk_id' => $temp->produk_id, 
                'subtotal' => $item->harga*$item->jumlah_produk, 
                'jumlah_produk' => $item->jumlah_produk, 
            ];
            DetailPenjualan::create($data);
            Produk::where('produk_id',$request->produk_id)
            ->update(['stok'=>$item->stok-$item->jumlah_produk]);   
        }
        Penjualan::create([
            'kode_penjualan' => $nota,
            'tanggal_penjualan' => date('y-m-d'),
            'pembayaran' => $request->pembayaran,
            'total_harga' => $request->total_harga,
        ]);
        Temp::where('pelanggan_id',$request->pelanggan_id)
        ->where('user_id',auth()->user()->user_id)
        ->delete();
        
        return redirect()->route('invoice',$nota)->with('success','transaksi sukses');
    }


















//     public function penjualan(){
//         $penjualan = Penjualan::with('pelanggans')
//                     ->where('tanggal_penjualan',date('y-m-d'))
//                     ->get();
//         $pelanggan = Pelanggan::all();
//         return view('penjualan.index')->with(compact('pelanggan','penjualan'));
//     }
//     public function transaksi($pelanggan_id){
//         $pelanggan = Pelanggan::findOrFail($pelanggan_id);
//         $produk    = Produk::where('stok','>',0)->orderBy('nama_produk','ASC')->get();
//         $temp      = DB::table('temps as a')
//                     ->leftJoin('produks as b','a.produk_id','=','b.produk_id')
//                     ->where('a.user_id',auth()->user()->user_id)
//                     ->where('a.pelanggan_id',$pelanggan_id)
//                     ->get();
//         $data       = [
//             'pelanggan' => $pelanggan,
//             'pelanggan_id' => $pelanggan_id,
//             'produk' => $produk,
//             'temp' => $temp,
//         ];
//         return view('penjualan.transaksi',$data);
//     }
//     public function addtemp(Request $request,$pelanggan_id){
//         $produk = Produk::where('produk_id',$request->produk_id)->first();
//         $stoklama = $produk->stok;
//         $temp = Temp::where('user_id',auth()->user()->user_id)
//         ->where('pelanggan_id',$request->pelanggan_id)
//         ->where('produk_id',$request->produk_id)
//         ->first();
       
//         if ($stoklama > $request->jumlah_produk) {
//             # code...
//             return redirect()->back()->with('error', 'stok tidak mencukupi');
//         } elseif($temp!==NULL){
//             # code...
//             $temp->jumlah_produk+=$request->jumlah_produk;
//             $temp->save();
//             return redirect()->back()->with('success', 'jumlah produk berhasil di perbarui');
//         }else{
//             $data = [
//                 'pelanggan_id' => $request->pelanggan_id,
//                 'user_id' => auth()->user()->user_id,
//                 'produk_id' => $request->produk_id,
//                 'jumlah_produk' => $request->jumlah_produk,
//             ];
//             Temp::create($data);
//             return redirect()->back()->with('success', 'produk berhasil di tambahkeranajng');
//         }
//     }
//     public function deltemp($temp_id){
//         $temp = Temp::findOrFail($temp_id);
//         $temp->delete();
//         return redirect()->back()->with('error', 'Produk berhasil dihapus dari keranang');
//     }

//     public function bayar(Request $request,$pelanggan_id){
//         $jumlah = Penjualan::whereRaw("DATE_FORMAT(tanggal_penjualan,'%Y-%m')=?",date('y-m-d'))->count();
//         $nota   = date('ymd').($jumlah+1);
//         $temp   = DB::table('temps as a')
//             ->leftJoin('produks as b','a.produk_id','=','b.produk_id')
//             ->where('a.user_id',auth()->user()->user_id)
//             ->where('a.pelanggan_id',$pelanggan_id)
//             ->get();
//         foreach ($temp as $item) {
//             # code...
//             if ($item->stok > $item->jumlah_produk) {
//                 # code...
//                 return redirect()->back()->with('error', 'stok tidak mencukupi');
//             }
//             $data = [
//                 'kode_penjualan' => $nota,
//                 'produk_id' => $item->produk_id,
//                 'subtotal' => $item->jumlah_produk*$item->harga,
//                 'jumlah_produk' => $item->jumlah_produk,
//             ];
//             DetailPenjualan::create($data);
//             Produk::where('produk_id',$request->produk_id)
//                     ->update(['stok',$item->stok-$item->jumlah_produk]);
//         }
//         Penjualan::create([
//             'kode_penjualan' =>$nota,
//             'tanggal_penjualan' =>date('y-m-d'),
//             'pembayaran' =>$request->pembayaran,
//             'total_harga' =>$request->total_harga,
//             'pelanggan_id' =>$request->pelanggan_id,
//         ]);
//         Temp::where('user_id',auth()->user()->user_id)
//         ->where('pelanggan_id',$request->pelanggan_id)
//         ->delete();
//         return redirect()->route('invoice',$nota)->with('success','Transaksi success!'); 
//     }
}
