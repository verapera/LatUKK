@extends('home')
@section('content')
@section('title','Produk')
  <!-- Button trigger modal -->
<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
    + Produk
  </button>
  
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Form tambah produk</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('addproduk') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col mt-2">
                    <label for="" class="form-input">Kode Produk</label>
                    <input type="text" name="kode_produk" class="form-control" value="{{ old('kode_produk',\App\Models\Produk::kodeproduk()) }}" readonly style="background-color: rgb(226, 240, 253)" required>
                </div>
                <div class="col mt-2">
                    <label for="" class="form-input">Nama Produk</label>
                    <input type="text" name="nama_produk" placeholder="Masukan nama produk..." class="form-control" required>
                </div>
            </div>
            <div class="col mt-2">
                <label for="" class="form-input">Harga Produk</label>
                <input type="number" name="harga" placeholder="Masukan harga produk..." class="form-control" required>
            </div>
            <div class="col mt-2">
                <label for="" class="form-input">Stok Produk</label>
                <input type="number" name="stok" placeholder="Masukan stok produk..." class="form-control" required>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
          </div>
          </form>
        </div>
    </div>
  </div>
  <div class="table-responsive text-nowrap">
    <table class="table table-bordered shadow">
      <thead style="background-color:rgb(226, 240, 253) ">
        <th>No</th>
        <th>Kode produk</th>
        <th>Nama produk</th>
        <th>Harga</th>
        <th>Stok</th>
        <th>Aksi</th>
      </thead>
      <tbody>
        <tr>
          @forelse ($produk as $item)
          <td>{{ $loop->iteration }}</td>
          <td>{{ $item->kode_produk }}</td>
          <td>{{ $item->nama_produk }}</td>
          <td>{{ $item->harga}}</td>
          <td>{{ $item->stok}}</td>
          <td>
            <form onsubmit="return confirm('Yakin ingin menghapus data ini?')" action="{{ route('deleteproduk',$item->produk_id) }}" method="POST"> 
              @csrf
              @method('DELETE')
              <button class="btn btn-danger" type="submit"> <i class="ti ti-trash"></i> </button>
              <a href="{{ route('showproduk',$item->produk_id) }}" class="btn btn-primary"> <i class="ti ti-pencil"></i> </a>
            </form>
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
@endsection