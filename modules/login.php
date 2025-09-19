<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            max-width: 420px;
            margin: 0 auto;
        }

        header.topbar {
            background: #ff8a92;
            padding: 14px;
            text-align: center;
            color: #fff;
            font-size: 18px;
            font-weight: bold;
        }

        .form {
            background: #fff;
            padding: 24px;
        }

        .form .helper {
            margin-bottom: 16px;
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }

        .input {
            width: 100%;
            padding: 12px;
            margin-bottom: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }

        .btn {
            display: block;
            text-align: center;
            padding: 12px;
            border-radius: 8px;
            font-weight: bold;
            text-decoration: none;
            font-size: 14px;
        }

        .btn.block {
            width: 100%;
        }

        .btn.mt12 {
            margin-top: 12px;
        }

        .btn.mt8 {
            margin-top: 8px;
        }

        .btn.primary {
            background: #ff8a92;
            color: #fff;
        }

        .btn.outline {
            border: 1px solid #ddd;
            background: #fff;
            color: #333;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-top: 12px;
            font-size: 13px;
        }

        .row a {
            color: #ff8a92;
            text-decoration: none;
        }

        .navbar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #fff;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-around;
            padding: 8px 0;
        }

        .nav-item {
            text-align: center;
            font-size: 12px;
            color: #444;
            text-decoration: none;
        }

        .nav-item.active {
            color: #ff8a92;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <header class="topbar">Login</header>

    <div class="form">
        <form action="/proses" method="post">
            <p class="helper">Silahkan Masukan Email dan Password Kamu disini</p>
            <input class="input" type="email" name="email" placeholder="Email">
            <input class="input" type="password" name="password" placeholder="Password">

            <button type="submit" class="btn block primary mt12">Masuk Sekarang</button>
        </form>
        <a class="btn block outline mt8" href="#">Sign With Google</a>

        <div class="row">
            <a href="lupa-sandi.html">Lupa Kata Sandi?</a>
            <a href="daftar.html">Daftar Disini</a>
        </div>
    </div>

</body>

</html>