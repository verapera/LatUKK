@extends('home')
@section('content')
@section('title','Penjualan')
{{-- @dd($penjualan) --}}
 <!-- Button trigger modal -->
 <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
    + Penjualan
  </button>
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title fs-5" id="exampleModalLabel">Silahkan pilih pelanggan</h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered shadow">
                  <thead style="background-color:rgb(226, 240, 253) ">
                    <th>No</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Nomor Telepon</th>
                    <th>Pilih</th>
                  </thead>
                  <tbody>
                    <tr>
                      @forelse ($pelanggan as $item)
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $item->nama_pelanggan }}</td>
                      <td>{{ $item->alamat}}</td>
                      <td>{{ $item->nomor_telepon}}</td>
                      <td>
                          <a class="btn btn-primary" href="{{ route('transaksi',$item->pelanggan_id) }}"> <i class="ti ti-shopping-cart-plus"></i> </a>
                      </td>
                    </tr>
                      @empty
                          <div class="alert alert-danger">
                            Data tidak tersedia
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
                  </tbody>
                </table>
              </div>
        </div>
    </div>
  </div>
    </div>



    <div class="card shadow">
      <div class="card-body">
        <div class="table-responsive text-nowrap ">
          <table class="table table-bordered">
              <thead>
                <th>No</th>
                <th>Nota</th>
                <th>Nama pelanggan</th>
                <th>Nominal</th>
                <th>Aksi</th>
              </thead>
              <tbody>
                <tr>
                    @forelse ($penjualan as $item)
                      <th>{{ $loop->iteration }}</th>
                      <td>{{ $item->kode_penjualan }}</td>
                      <td>{{ $item->pelanggans->nama_pelanggan }}</td>
                      <td>Rp.{{ number_format($item->total_harga)}}</td>
                      <td>
                        <a href="{{ route('invoice',$item->kode_penjualan) }}" class="btn btn-danger">Cek</a>
                      </td>
                </tr>
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
                
              </tbody>
            </table>
          </div>
      </div>
@endsection