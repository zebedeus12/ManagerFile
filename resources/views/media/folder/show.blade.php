@extends('layouts.media')

@section('title', 'Folder Details')

@section('content')
<h2>Folder: {{ $folder->name }}</h2>

<!-- Tampilkan subfolder jika ada -->
@if($folder->subfolders->isNotEmpty())
    <div class="subfolder-list">
        <h4>Subfolders:</h4>
        @foreach($folder->subfolders as $subfolder)
            <div>{{ $subfolder->name }}</div>
        @endforeach
    </div>
@endif

<!-- Tampilkan media di dalam folder -->
<div class="file-list">
    @if($folder->mediaItems->isEmpty())
        <p>No media available in this folder.</p>
    @else
        @foreach($folder->mediaItems as $media)
            <div>{{ $media->name }}</div>
        @endforeach
    @endif
</div>
@endsection