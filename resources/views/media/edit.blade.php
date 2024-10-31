@extends('layouts.dashboard')

@section('title', 'Edit Media')

@section('content')
<div class="dashboard-content">
    <h2>Edit Media</h2>
    <form action="{{ route('media.update', $media->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Media Name</label>
            <input type="text" name="name" class="form-control" value="{{ $media->name }}" required>
        </div>
        <div class="mb-3">
            <label for="file" class="form-label">Media File (optional)</label>
            <input type="file" name="file" class="form-control" accept="image/*,audio/*,video/*">
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Media Type</label>
            <select name="type" class="form-select" required>
                <option value="image" {{ $media->type === 'image' ? 'selected' : '' }}>Image</option>
                <option value="audio" {{ $media->type === 'audio' ? 'selected' : '' }}>Audio</option>
                <option value="video" {{ $media->type === 'video' ? 'selected' : '' }}>Video</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Media</button>
    </form>
</div>
@endsection
