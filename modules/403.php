<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>403 Forbidden</title>

<style>
    body {
        margin: 0;
        padding: 0;
        font-family: "Courier New", monospace;
        background: #1a0000; /* tidak hitam total */
        color: #ff1e1e;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        overflow: hidden;
        text-shadow: 0 0 8px #ff0000;
    }

    @keyframes glitch {
        0% { transform: translate(0); }
        20% { transform: translate(-2px, 2px); color: #ff6868; }
        40% { transform: translate(2px, -2px); color: #ff1e1e; }
        60% { transform: translate(-1px, 1px); color: #ff9999; }
        80% { transform: translate(1px, -1px); color: #ff4d4d; }
        100% { transform: translate(0); color: #ff1e1e; }
    }

    h1 {
        font-size: 110px;
        margin: 0;
        font-weight: 900;
        animation: glitch 0.25s infinite;
    }

    p {
        font-size: 20px;
        max-width: 550px;
        text-align: center;
        margin-top: -10px;
        color: #ff8585;
    }

    a {
        display: inline-block;
        margin-top: 25px;
        color: #fff;
        background: #ff1e1e;
        padding: 12px 28px;
        border-radius: 6px;
        font-weight: bold;
        text-decoration: none;
        box-shadow: 0 0 15px #ff1e1e;
        transition: 0.2s;
    }

    a:hover {
        background: #ffffff;
        color: #8b0000;
        box-shadow: 0 0 20px #fff;
    }

    .blood-effect {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 140px;
        background: linear-gradient(180deg, #ff0000, transparent);
        opacity: 0.25;
        pointer-events: none;
    }
</style>
</head>
<body>

<div class="blood-effect"></div>

<div style="text-align:center;">
    <h1>403</h1>
    <p>
        Kamu tidak punya izin untuk masuk.<br>
        Namun… sesuatu sudah menyadari keberadaanmu.<br>
        Jangan lama-lama di sini.
    </p>
    <a href="/admin/dashboard">KEMBALI SEBELUM TERLAMBAT</a>
</div>

</body>
</html>
