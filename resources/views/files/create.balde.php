<!-- resources/views/files/create.blade.php -->

<!DOCTYPE html>
<html>

<head>
    <title>Upload File</title>
</head>

<body>
    <h1>Upload File</h1>

    @if (session('success'))
    <p>{{ session('success') }}</p>
    @endif

    <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" required>
        <button type="submit">Upload</button>
    </form>
</body>

</html>