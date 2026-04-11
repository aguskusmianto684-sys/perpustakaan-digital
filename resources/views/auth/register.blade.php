<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register Anggota</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/buku.png') }}" />

    {{-- FONT --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- ICON --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            -webkit-font-smoothing: antialiased;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #5d87ff, #7aa2ff);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .register-box {
            background: #fff;
            padding: 30px;
            width: 400px;
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .logo {
            width: 60px;
            display: block;
            margin: 0 auto 10px;
        }

        .title {
            text-align: center;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 12px;
        }

        .input-box {
            position: relative;
        }

        /* ICON KIRI */
        .input-box i:first-child {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        /* INPUT */
        .input-box input,
        .input-box select,
        .input-box textarea {
            width: 100%;
            padding: 10px 40px 10px 35px;
            /* 🔥 FIX */
            border-radius: 8px;
            border: 1px solid #ddd;
            outline: none;
            transition: 0.3s;
        }

        .input-box input:focus,
        .input-box select:focus,
        .input-box textarea:focus {
            border-color: #5d87ff;
        }

        textarea {
            resize: none;
        }

        /* ICON MATA */
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

        .login {
            text-align: center;
            font-size: 13px;
            margin-top: 10px;
        }

        .login a {
            color: #5d87ff;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="register-box">

        <img src="{{ asset('assets/images/logos/buku.png') }}" class="logo">
        <div class="title">Registrasi Anggota</div>

        <form method="POST" action="/register">
            @csrf

            <div class="form-group">
                <div class="input-box">
                    <i class="fa fa-user"></i>
                    <input type="text" name="nama" placeholder="Nama Lengkap" required>
                </div>
            </div>

            <div class="form-group">
                <div class="input-box">
                    <i class="fa fa-user-circle"></i>
                    <input type="text" name="username" placeholder="Username" required>
                </div>
            </div>

            <div class="form-group">
                <div class="input-box">
                    <i class="fa fa-lock"></i>
                    <input type="password" name="password" id="password" placeholder="Password" required>

                    {{-- ICON MATA --}}
                    <i class="fa fa-eye toggle-password" id="toggleIcon" onclick="togglePassword()"></i>
                </div>
            </div>

            <div class="form-group">
                <div class="input-box">
                    <i class="fa fa-envelope"></i>
                    <input type="email" name="email" placeholder="Email">
                </div>
            </div>

            <div class="form-group">
                <div class="input-box">
                    <i class="fa fa-phone"></i>
                    <input type="text" name="no_hp" placeholder="No HP">
                </div>
            </div>

            <div class="form-group">
                <div class="input-box">
                    <i class="fa fa-venus-mars"></i>
                    <select name="jenis_kel">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="input-box">
                    <i class="fa fa-calendar"></i>
                    <input type="date" name="tgl_lahir">
                </div>
            </div>

            <div class="form-group">
                <div class="input-box">
                    <i class="fa fa-map-marker-alt"></i>
                    <textarea name="alamat" placeholder="Alamat"></textarea>
                </div>
            </div>

            <button type="submit">Daftar</button>

            <div class="login">
                Sudah punya akun?
                <a href="/login">Login disini</a>
            </div>

        </form>

    </div>

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

</body>

</html>
