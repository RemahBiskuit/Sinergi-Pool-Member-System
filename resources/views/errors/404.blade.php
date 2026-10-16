<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Halaman Tidak Ditemukan - Sinergi Pool Member</title>
    <link rel="icon" type="image/png" href="{{ asset('images/sinergi-favicon.png') }}">
    <style>
        * {
            box-sizing: border-box
        }

        body {
            display: grid;
            min-height: 100vh;
            place-items: center;
            margin: 0;
            padding: 24px;
            background: #f4f7f5;
            color: #17352a;
            font-family: Arial, sans-serif
        }

        .error-card {
            width: min(560px, 100%);
            padding: clamp(28px, 7vw, 48px);
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 4px 24px #00000012;
            text-align: center
        }

        .logo {
            display: block;
            width: min(240px, 72%);
            height: auto;
            margin: 0 auto 32px
        }

        .error-code {
            margin: 0;
            color: #1d6b4d;
            font-size: clamp(72px, 18vw, 112px);
            font-weight: 700;
            letter-spacing: -.06em;
            line-height: 1
        }

        h1 {
            margin: 20px 0 12px;
            font-size: clamp(24px, 5vw, 32px);
            line-height: 1.25
        }

        p {
            margin: 0;
            color: #65766d;
            font-size: 16px;
            line-height: 1.7
        }

        .back-link {
            display: inline-block;
            margin-top: 28px;
            padding: 12px 18px;
            border-radius: 10px;
            background: #1d6b4d;
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: background .2s ease
        }

        .back-link:hover {
            background: #17583f
        }

        .back-link:focus-visible {
            outline: 3px solid #92c5ad;
            outline-offset: 3px
        }

        @media (prefers-reduced-motion: reduce) {
            .back-link {
                transition: none
            }
        }
    </style>
</head>

<body>
    <main class="error-card">
        <img class="logo" src="{{ asset('images/sinergi-logo.png') }}" alt="Sinergi Hotel and Villa">
        <p class="error-code" aria-hidden="true">404</p>
        <h1>Halaman Tidak Ditemukan</h1>
        <p>Maaf, halaman yang Anda cari tidak tersedia atau alamatnya sudah berubah.</p>
        <a class="back-link" href="{{ route('dashboard') }}">Kembali ke halaman utama</a>
    </main>
</body>

</html>
