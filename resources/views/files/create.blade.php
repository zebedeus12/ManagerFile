@extends('layouts.manager')

@section('title', 'Upload File')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Upload File</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('files.store', $folder->id ?? null) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if ($folder)
            <input type="hidden" name="folder_id" value="{{ $folder->id }}">
            <p class="text-muted">Mengunggah file ke folder: <strong>{{ $folder->name }}</strong></p>
        @else
            <p class="text-muted">Mengunggah file ke root directory.</p>
        @endif
        <div class="mb-3">
            <label for="file" class="form-label">Pilih File</label>
            <input type="file" class="form-control" id="file" name="file" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</div>
@endsection