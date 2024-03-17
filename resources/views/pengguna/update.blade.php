@extends('home')
@section('content')
@section('title','Update pengguna')
<form action="{{ route('addpengguna') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col mt-2">
            <label for="" class="form-input">Name</label>
            <input type="text" name="name" placeholder="Masukan nama pengguna..." class="form-control" required>
        </div>
        <div class="col mt-2">
            <label for="" class="form-input">Username</label>
            <input type="text" name="username" placeholder="Masukan username pengguna..." class="form-control" required>
        </div>
    </div>
    <div class="col mt-2">
        <label for="" class="form-input">Password</label>
        <input type="password" name="password" placeholder="Masukan password pengguna..." class="form-control" required>
    </div>
    <div class="col mt-2">
        <label for="" class="form-input">Level</label>
        <select name="level" id="" class="form-select">
            <option value="Admin">Admin</option>
            <option value="Petugas">Petugas</option>
        </select>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      <button type="submit" class="btn btn-primary">Save changes</button>
    </div>
  </div>
  </form>
@endsection