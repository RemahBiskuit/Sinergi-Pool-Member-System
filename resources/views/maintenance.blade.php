<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Under Maintenance - Sinergi Pool Member</title>
    <link rel="icon" type="image/png" href="{{ asset('images/sinergi-favicon.png') }}">
    <style>
        * {
            box-sizing: border-box
        }

        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            margin: 0;
            padding: 24px;
            background: #f4f7f5;
            color: #17352a;
            font-family: Arial, sans-serif
        }

        .site-footer {
            width: 100%;
            margin-top: auto;
            color: #65766d;
            font-size: 13px;
            text-align: center
        }

        .maintenance-card {
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

        .status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 13px;
            border-radius: 999px;
            background: #e8f2ed;
            color: #1d6b4d;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .08em
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #1d6b4d
        }

        h1 {
            margin: 20px 0 12px;
            font-size: clamp(26px, 6vw, 36px);
            line-height: 1.2
        }

        p {
            margin: 0;
            color: #65766d;
            font-size: 16px;
            line-height: 1.7
        }

        .divider {
            width: 48px;
            height: 3px;
            margin: 28px auto;
            border-radius: 999px;
            background: #1d6b4d
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
    <main class="maintenance-card">
        <img class="logo" src="{{ asset('images/sinergi-logo.png') }}" alt="Sinergi Hotel and Villa">
        <span class="status"><span class="status-dot" aria-hidden="true"></span>UNDER MAINTENANCE</span>
        <h1>Website Sedang Dalam Pemeliharaan</h1>
        <p>Kami sedang melakukan pemeliharaan untuk meningkatkan layanan. Silakan kembali lagi dalam beberapa saat.</p>
        <div class="divider" aria-hidden="true"></div>
        <p>Terima kasih atas pengertian Anda.</p>
        <a class="back-link" href="{{ route('login') }}">Kembali</a>
    </main>
    <footer class="site-footer">&copy; {{ now()->year }} by Sinergi Hotel &amp; Villa</footer>
</body>

</html>
