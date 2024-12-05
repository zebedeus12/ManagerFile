@extends('layouts.media')

@section('title', 'Folder: ' . $folder->name)

@section('content')
<!-- Navbar -->
@include('layouts.navbar')

<div class="main-layout">
    @include('layouts.sidebar')

    <div class="container mt-4">
        <h2 class="mb-4">{{ $folder->name }}</h2>

        <div class="folder-content">
            <div class="subfolders">
                <ul>
                    @foreach($folder->subfolders as $subfolder)
                        <li>
                            <a href="{{ route('mediafolder.show', $subfolder->id) }}">
                                {{ $subfolder->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="media-items">
                <h3>Media</h3>
                @foreach($folder->mediaItems as $media)
                    <li>{{ $media->name }}</li>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection