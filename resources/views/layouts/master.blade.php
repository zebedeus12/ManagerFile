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
            background-image: url('{{ asset('img/Selulosa.png') }}');
            /* Set your background image */
            background-size: cover;
            /* Cover the entire area */
            background-position: center;
            /* Center the image */
            background-repeat: no-repeat;
            /* Prevent repeating the image */
            min-height: 100vh;
            /* Ensure the body takes full height */
            margin: 0;
            display: flex;
            /* Center the container */
            justify-content: center;
            align-items: center;
        }

        .container {
            display: flex;
            justify-content: flex-end;
            /* Move the card to the right */
            width: 100%;
            padding-right: 50px;
            /* Add space on the right side */
            position: relative;
            /* For positioning the card */
            z-index: 1;
            /* Ensure the container is above the image */
        }

        .card {
            width: 400px;
            border-radius: 15px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
            position: relative;
            /* For positioning the card */
            z-index: 2;
            /* Ensure the card is above the container */
        }

        .card-header {
            background-color: transparent;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 600;
            color: #31511E;
            /* Dark green color */
            padding: 20px;
            border-bottom: none;
        }

        .form-control {
            border-radius: 10px;
            padding: 10px 15px;
        }

        .btn-primary {
            background-color: #31511E;
            /* Dark green color */
            border: none;
            border-radius: 10px;
            padding: 10px 15px;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #264011;
            /* Darker color on hover */
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

    <div class="container">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>