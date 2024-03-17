@extends('home')
@section('content')
@section('title','Penjualan')
<div class="row">
    <div class="col md-3">
        <div class="card shadow">
            <div class="card-body">
                <form method="POST" action="{{ route('addtemp',$pelanggan->pelanggan_id) }}" >
                    @csrf
                    <input type="hidden" name="user_id" value="{{auth()->user()->user_id}}">
                    <input type="hidden" name="pelanggan_id" value="{{$pelanggan->pelanggan_id}}">
                    <h5 class="fw-semibold mb-3">Pilih produk yang akan dijual</h5>
                    <div class="mt-2">
                        <label for="" class="form-input">Nama Pelanggan</label>
                        <input type="text" name="nama_pelanggan" value="{{ $pelanggan->nama_pelanggan }}" class="form-control" style="background-color: rgb(226, 240, 253)" readonly>
                    </div>
                    <div class="mt-2">
                        <label for="" class="form-input">Pilih Produk</label>
                        <select name="produk_id" id="" class="form-select">
                            @foreach ($produk as $item)
                                <option value="{{ $item->produk_id }}">{{ $item->nama_produk }} - Rp.{{ number_format( $item->harga)  }} ({{  $item->stok }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-2">
                        <label for="" class="form-input">Jumlah Produk</label>
                        <input type="number" name="jumlah_produk" placeholder="Masukan jumlah produk..." class="form-control" required>
                    </div>
                     <button class="btn btn-secondary mt-3" type="submit"> + Keranjang </button>
                 </form>
            </div>
        </div>
    </div>
    <div class="card shadow col-md-8">
      <div class="card-body">
        <div class="table-responsive text-nowrap ">
          <table class="table table-bordered">
              <h5 class="fw-semibold mb-3">Data keranjang</h5>
              <thead style="background-color:rgb(226, 240, 253) ">
                <th>No</th>
                <th>Kode produk</th>
                <th>Nama produk</th>
                <th>Quantity</th>
                <th>Harga</th>
                <th>Subtotal</th>
                <th>Aksi</th>
              </thead>
              <tbody>
                <tr>
                    <?php  $cek = 0; $total = 0; ?>
                    @forelse ($temp as $item)
                      <th>{{ $loop->iteration }}</th>
                      <td>{{ $item->kode_produk }}</td>
                      <td>{{ $item->nama_produk }}</td>
                      <td>{{ $item->jumlah_produk }}
                          <?php 
                          if ($item->jumlah_produk > $item->stok) {
                            # code...
                           echo "<span class='badge bg-danger'>Stok tidak mencukupi</span>";
                           $cek = 1;
                          }
                          ?>
                          
                          
                      <td>Rp.{{ number_format($item->harga) }}</td>
                      <td>Rp.{{ number_format($item->harga*$item->jumlah_produk)}}</td>
                      <td>
                        <form onsubmit="return confirm('Yakin ingin menghapus data ini?')" action="{{ route('deltemp',$item->temp_id) }}" method="POST"> 
                          @method('DELETE')
                          @csrf
                          <button class="btn btn-danger" type="submit"> <i class="ti ti-trash"></i> </button>
                        </form>
                      </td>
                </tr>
                <?php $total+=$item->harga*$item->jumlah_produk; ?>
                  @empty
                      <div class="alert alert-danger">
                        Data belum tersedia
                      </div>
                  @endforelse
                  @if (session('success'))
                      <div class="alert alert-success">
                        {{ (session('success'))}}
                      </div>
                  @elseif (session('error'))
                  <div class="alert alert-danger">
                    {{ (session('error'))}}
                  </div>
                  @endif
                  <tr>
                    <td colspan="5">Total harga</td>
                    <td colspan="5">Rp.{{number_format($total ) }}</td>
                  </tr>
              </tbody>
            </table>
            <form action="{{ route('bayarr',$pelanggan_id) }}" method="POST">
              @csrf
              @if (!empty($temp) && $cek == 0)
                  
              <div class="col-md-4">
               <label for="" class="form-input mb-2">Pembayaran</label>
               <input type="number" name="pembayaran" placeholder="Masukan nominal..." class="form-control" required>
               <input type="hidden" name="total_harga" value="{{ $total }}">
               <input type="hidden" name="pelanggan_id" value="{{ $pelanggan_id }}">
               <button class="btn btn-primary mt-3" type="submit"> Bayar</button>
              </div>
              @endif
            </form>
          </div>
      </div>
      </div>
</div>
@endsection