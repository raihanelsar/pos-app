@extends('layouts.app')
@section('title', 'Ganti Password')

@section('content')
<div class="container">
  <div class="card text-white">
    <div class="card-header bg-warning">Ganti Password</div>
    <div class="card-body">
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <form method="POST" action="{{ route('password.update') }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label class="form-label">Password Lama</label>
          <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
          @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Password Baru</label>
          <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
          @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Konfirmasi Password Baru</label>
          <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-warning">Update Password</button>
      </form>
    </div>
  </div>
</div>
@endsection
