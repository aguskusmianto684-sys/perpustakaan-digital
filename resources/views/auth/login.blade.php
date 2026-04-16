<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/buku.png') }}" />

    {{-- FONT --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- ICON --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    {{-- SWEETALERT --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        * {
            box-sizing: border-box;
            -webkit-font-smoothing: antialiased;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #5d87ff, #7aa2ff);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            background: #fff;
            padding: 40px;
            width: 350px;
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .logo {
            width: 60px;
            margin-bottom: 10px;
        }

        .title {
            font-weight: 600;
            font-size: 18px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .input-box {
            position: relative;
        }

        .input-box i:first-child {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .input-box input {
            width: 100%;
            padding: 10px 40px 10px 35px;
            border-radius: 8px;
            border: 1px solid #ddd;
            outline: none;
            transition: 0.3s;
        }

        .input-box input:focus {
            border-color: #5d87ff;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
        }

        button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 8px;
            background: #5d87ff;
            color: white;
            font-weight: 500;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background: #4a6fe3;
        }

        .register {
            margin-top: 15px;
            font-size: 13px;
        }

        .register a {
            color: #5d87ff;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="login-box">

        <img src="{{ asset('assets/images/logos/buku.png') }}" class="logo">
        <div class="title">Perpustakaan Web</div>

        <form method="POST" action="/login">
            @csrf

            <div class="form-group">
                <div class="input-box">
                    <i class="fa fa-user"></i>
                    <input type="text" name="username" placeholder="Username" required>
                </div>
            </div>

            <div class="form-group">
                <div class="input-box">
                    <i class="fa fa-lock"></i>
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <i class="fa fa-eye toggle-password" id="toggleIcon" onclick="togglePassword()"></i>
                </div>
            </div>

            <button type="submit">Login</button>

            <div class="register">
                Belum punya akun anggota?
                <a href="/register">Daftar disini</a>
            </div>

        </form>

    </div>

    {{--ALERT berhasil --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Sukses',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    @endif

    {{--ALERT ERROR --}}
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}'
            });
        </script>
    @endif

    <script>
        function togglePassword() {
            var input = document.getElementById("password");
            var icon = document.getElementById("toggleIcon");

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>


    <script>
        let sudahMuncul = false;

        history.pushState(null, null, location.href);

        window.onpopstate = function() {

            if (!sudahMuncul) {
                sudahMuncul = true;

                Swal.fire({
                    icon: 'warning',
                    title: 'Akses ditolak',
                    text: 'Silakan login terlebih dahulu'
                }).then(() => {
                    window.location.href = "/login";
                });
            }
        };
    </script>
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Akses ditolak',
                text: '{{ session('error') }}'
            });
        </script>
    @endif
</body>

</html>
