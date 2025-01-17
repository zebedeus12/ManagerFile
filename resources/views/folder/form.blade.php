@extends('layouts.manager')

@section('title', 'Tambah Folder')

@section('content')
<!-- Navbar -->
@include('layouts.navbar')

<div class="main-layout">
    @include('layouts.sidebar')
    <div class="container">
        <h2>Buat Folder Baru</h2>
        @if (isset($parentFolder))
            <p>Menambahkan sub-folder di dalam: <strong>{{ $parentFolder->name }}</strong></p>
        @endif
        <form action="{{ route('folder.store', ['parentId' => $parentFolder->id ?? null]) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="folder_name" class="form-label">Nama Folder</label>
                <input type="text" class="form-control" id="folder_name" name="folder_name" required>
            </div>
            <div class="mb-3">
                <label for="accessibility" class="form-label">Aksesibilitas</label>
                <select class="form-select" id="accessibility" name="accessibility" required>
                    <option value="public">Publik</option>
                    <option value="private">Rahasia</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="hak-akses" class="form-label">Hak Akses</label>
                <select class="form-select" id="hak-akses" name="hak-akses" required>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id_user }}">{{ $employee->nama_user }} - {{ ucfirst($employee->role) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Folder</button>
        </form>
    </div>
</div>
@endsection