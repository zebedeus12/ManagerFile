<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - My Laravel App</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- Link Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .background-half-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 50%;
            height: 100%;
            background-image: url('/img/Selulosa.png');
            background-size: cover;
            background-position: center;
        }

        .background-half-color {
            position: absolute;
            top: 0;
            right: 0;
            width: 50%;
            height: 100%;
            background: linear-gradient(135deg, #31511E, #91BC55, #A4C639);
        }

        .container {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: flex-end;
            width: 100%;
            padding-right: 50px;
        }

        .card {
            width: 400px;
            border-radius: 15px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
        }

        .card-header {
            background-color: transparent;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 600;
            color: #31511E;
            padding: 20px;
            border-bottom: none;
        }

        .form-control {
            border-radius: 10px;
            padding: 10px 15px;
        }

        .btn-primary {
            background-color: #31511E;
            border: none;
            border-radius: 10px;
            padding: 10px 15px;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #264011;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .card-body {
            padding: 30px;
        }

        .invalid-feedback {
            font-size: 0.875rem;
        }

        .text-center {
            margin-top: 20px;
        }
    </style>

    @stack('styles')
</head>

<body>

    <div class="background-half-image"></div>
    <div class="background-half-color"></div>

    <div class="container">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>