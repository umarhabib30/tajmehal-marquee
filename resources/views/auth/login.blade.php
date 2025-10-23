<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
    <link href="../assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/libs/css/style.css">
    <link rel="stylesheet" href="../assets/vendor/fonts/fontawesome/css/fontawesome-all.css">

    <style>
        html, body {
            height: 100%;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f4f4f9;
            padding-top: 40px;
            padding-bottom: 40px;
        }

        /* ✅ Card Styling */
        .card {
            background-color: #29166f; /* your custom background */
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            color: #fff; /* make text white for contrast */
        }

        /* ✅ Logo styling */
        .logo-img {
            width: 120px;
            height: auto;
            object-fit: contain;
            margin-bottom: 10px;
        }

        /* ✅ Splash container */
        .splash-container {
            width: 100%;
            max-width: 400px;
            margin: auto;
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }

        .splash-description {
            display: block;
            margin-top: 8px;
            font-size: 14px;
            color: #ddd;
        }

        /* ✅ Inputs */
        .form-control {
            background-color: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.3);
            color: #fff;
        }

        .form-control::placeholder {
            color: #ccc;
        }

        .form-control:focus {
            background-color: rgba(255,255,255,0.2);
            border-color: #fff;
            color: #fff;
        }

        /* ✅ Button */
        .btn-primary {
            background-color: #fff;
            color: #311464;
            font-weight: 600;
            border: none;
        }

        .btn-primary:hover {
            background-color: #eaeaea;
            color: #311464;
        }

        /* ✅ Checkbox label color */
        .custom-control-label {
            color: #ddd;
        }
    </style>
</head>

<body>
    <div class="splash-container">
        <div class="card">
            <div class="card-header text-center">
                <a href="{{ url('/') }}">
                    <img class="logo-img" src="{{ asset('assets/images/logo.jpg') }}" alt="logo">
                </a>
                <span class="splash-description">Please enter your user information.</span>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <input id="email" type="email"
                               class="form-control form-control-lg @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                               placeholder="Email Address">

                        @error('email')
                            <span class="invalid-feedback d-block text-white" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <input id="password" type="password"
                               class="form-control form-control-lg @error('password') is-invalid @enderror"
                               name="password" required autocomplete="current-password"
                               placeholder="Password">

                        @error('password')
                            <span class="invalid-feedback d-block text-white" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox"
                                   name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span class="custom-control-label">Remember Me</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg btn-block">Sign in</button>
                </form>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="../assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
</body>
</html>
