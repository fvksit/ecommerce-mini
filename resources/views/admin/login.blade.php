<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Laravel</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            text-align: center;
            background-color: #ffffff;
            padding: 50px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            font-size: 36px;
            color: #333;
        }

        p {
            font-size: 18px;
            color: #555;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .login-form {
            margin-top: 30px;
            text-align: left;
        }

        .login-form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .login-form button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .login-form button:hover {
            background-color: #218838;
        }

        .forgot-password {
            display: block;
            text-align: center;
            margin-top: 10px;
        }

        .forgot-password a {
            color: #007bff;
            text-decoration: none;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }

        .success-message {
            color: green;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Welcome to Laravel!</h1>
        <p>Your journey with Laravel begins here. Let's build something amazing together.</p>

        <!-- Login Form -->
        <div class="login-form">
            <form id="loginForm">
                @csrf
                <input type="email" name="email" id="email" placeholder="Email" required>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
            <div class="forgot-password">
                <a href="{{ route('admin.login') }}">Forgot your password?</a>
            </div>
            <div id="error-message" class="error-message"></div>
            <div id="success-message" class="success-message"></div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();

                $('#error-message').text('');
                $('#success-message').text('');

                var email = $('#email').val();
                var password = $('#password').val();
                var token = $('input[name="_token"]').val();

                $.ajax({
                    url: "{{ route('admin.login') }}",
                    type: "POST",
                    data: {
                        _token: token,
                        email: email,
                        password: password
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#success-message').text('Login successful! Redirecting...');
                            setTimeout(function() {
                                window.location.href = response
                                    .redirect_url;
                            }, 2000);
                        } else {
                            $('#error-message').text(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#error-message').text('An error occurred. Please try again later.');
                    }
                });
            });
        });
    </script>
</body>

</html>
