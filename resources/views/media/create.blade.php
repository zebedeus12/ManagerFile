@extends('layouts.dashboard')

@section('title', 'Add Media')

@section('content')
<div class="dashboard-content">
    <h2>Add Media</h2>
    <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Media Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="file" class="form-label">Media File</label>
            <input type="file" name="file" class="form-control" accept="image/*,audio/*,video/*" required>
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Media Type</label>
            <select name="type" class="form-select" required>
                <option value="image">Image</option>
                <option value="audio">Audio</option>
                <option value="video">Video</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add Media</button>
    </form>
</div>
@endsection
