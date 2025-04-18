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
            background-image: url('{{ asset('img/login.jpeg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            margin: 0;
            display: flex;
            justify-content: flex-end;
            /* Memindahkan card ke sebelah kanan */
            align-items: center;
            padding-right: 25px;
            /* Memberikan sedikit jarak dari tepi kanan */
        }

        /* Card Styling */
        .card {
            display: flex;
            flex-direction: row;
            width: 600px;
            /* Lebar card diperkecil lagi */
            height: 450px;
            /* Tinggi card diperkecil lagi */
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
        }

        /* Left Side of Card (Welcome Message) */
        .card-left {
            flex: 1;
            background-color: #4caf50;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 15px;
            text-align: center;
        }

        .card-left h2 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .card-left p {
            font-size: 12px;
            margin-bottom: 15px;
        }

        .card-left .btn-outline-light {
            border: 2px solid white;
            border-radius: 20px;
            padding: 8px 18px;
            color: white;
            font-weight: 600;
            transition: background-color 0.3s;
            font-size: 13px;
        }

        .card-left .btn-outline-light:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* Right Side of Card (Login Form) */
        .card-right {
            flex: 1;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
        }

        .form-title {
            font-size: 20px;
            font-weight: 600;
            color: #4caf50;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 5px;
            padding: 8px 12px;
            margin-bottom: 12px;
        }

        .btn-primary {
            background-color: #4caf50;
            border: none;
            border-radius: 5px;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 600;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #4caf50;
        }
    </style>

    @stack('styles')
</head>

<body>

    <div class="card">
        <!-- Left Side - Welcome Message -->
        <div class="card-left">
            <h2>Welcome Back!</h2>
            <p>To keep connected with us please login with your personal info</p>
        </div>

        <!-- Right Side - Login Form -->
        <div class="card-right">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>