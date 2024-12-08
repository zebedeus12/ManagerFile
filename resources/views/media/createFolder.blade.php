@extends('layouts.media')

@section('title', 'Create Folder')

@section('content')
<h2>Create Folder</h2>

<form action="{{ route('media.folder.store', ['parentId' => $parentId]) }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="folderName">Folder Name</label>
        <input type="text" class="form-control" name="name" id="folderName" required>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Create Folder</button>
</form>
@endsection