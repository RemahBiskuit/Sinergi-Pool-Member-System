<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Under Maintenance - Sinergi Pool Member</title>
    <link rel="icon" type="image/png" href="{{ asset('images/sinergi-favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/button-glow.css') }}">
    <style>
        * {
            box-sizing: border-box
        }

        body {
            position: relative;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            align-items: center;
            isolation: isolate;
            margin: 0;
            padding: 24px;
            background: #e9f0f3;
            color: #17352a;
            font-family: -apple-system, BlinkMacSystemFont, "SF Pro Display", "SF Pro Text", system-ui, sans-serif
        }

        body::before {
            position: fixed;
            inset: 0;
            z-index: -1;
            background: url("{{ asset('images/sinergi-pool-background.png') }}") center / max(105vw, 140vh) auto no-repeat;
            content: "";
            opacity: .1
        }

        .site-footer {
            box-sizing: border-box;
            width: 100%;
            padding-top: 24px;
            color: #314b55;
            font-size: 13px;
            text-align: center;
            text-shadow: 0 1px 8px rgba(255, 255, 255, .8)
        }

        .maintenance-card {
            margin-block: auto;
            width: min(560px, 100%);
            padding: clamp(28px, 7vw, 48px);
            border: 1px solid rgba(255, 255, 255, .68);
            border-radius: 28px;
            background: rgba(239, 248, 250, .66);
            box-shadow: 0 24px 70px rgba(8, 28, 38, .18), inset 0 1px 0 rgba(255, 255, 255, .8);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            backdrop-filter: blur(24px) saturate(180%);
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
            border: 1px solid rgba(255, 255, 255, .7);
            background: rgba(255, 255, 255, .48);
            color: #1d6b4d;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .08em;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .8)
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
            color: #405a61;
            font-size: 16px;
            line-height: 1.7
        }

        .divider {
            width: 48px;
            height: 3px;
            margin: 28px auto;
            border-radius: 999px;
            background: linear-gradient(90deg, rgba(29, 107, 77, .45), #1d6b4d, rgba(29, 107, 77, .45))
        }

        .back-link {
            display: inline-block;
            margin-top: 28px;
            padding: 12px 18px;
            border: 1px solid rgba(255, 255, 255, .34);
            border-radius: 13px;
            background: linear-gradient(135deg, #247c61, #155b48);
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 8px 18px rgba(21, 91, 72, .22);
            transition: transform .18s ease, box-shadow .18s ease
        }

        .back-link:hover {
            transform: translateY(-1px);
            box-shadow: 0 11px 22px rgba(21, 91, 72, .28)
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

        @media (max-width: 480px) {
            body {
                padding: 16px
            }

            .maintenance-card {
                border-radius: 24px
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
    <script src="{{ asset('js/button-glow.js') }}"></script>
</body>

</html>
