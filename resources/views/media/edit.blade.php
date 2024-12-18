@extends('layouts.media')

@section('title', 'Edit Media')

@section('content')
@include('layouts.navbar')

<div class="main-layout">
    @include('layouts.sidebar')

    <div class="employee-content">
        <div class="header">
            <h2>Edit Media: {{ $media->name }}</h2>
        </div>

        <!-- Edit Media Form -->
        <div class="form-container">
            <form action="{{ route('media.update', $media->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="mediaName">Media Name</label>
                    <input type="text" class="form-control" id="mediaName" name="name"
                        value="{{ old('name', $media->name) }}" required>
                </div>

                <div class="form-group mt-3">
                    <label for="mediaType">Media Type</label>
                    <input type="text" class="form-control" id="mediaType" name="type"
                        value="{{ old('type', $media->type) }}" required>
                </div>

                <div class="form-group mt-3">
                    <label for="mediaFile">Upload New Media File (Optional)</label>
                    <input type="file" class="form-control" name="file" id="mediaFile" accept="image/*,audio/*,video/*">
                </div>

                <input type="hidden" name="folder_id" value="{{ $media->folder_id }}">

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="{{ route('media.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection