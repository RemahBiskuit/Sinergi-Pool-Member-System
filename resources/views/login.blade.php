<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login Admin - Sinergi Pool Member</title>
    <link rel="icon" type="image/png" href="{{ asset('images/sinergi-favicon.png') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7f5;
            color: #17352a;
            display: grid;
            place-items: center;
            min-height: 100vh;
            margin: 0
        }

        .login {
            background: white;
            width: min(380px, calc(100% - 32px));
            padding: 28px;
            border-radius: 16px;
            box-shadow: 0 4px 18px #0001
        }

        h1 {
            margin-top: 0
        }

        .login-heading {
            display: flex;
            align-items: center;
            gap: 12px
        }

        .login-heading img {
            display: block;
            width: 110px;
            max-width: 30vw;
            height: auto
        }

        .field {
            display: block;
            margin: 16px 0 6px;
            font-size: 14px;
            font-weight: bold
        }

        input,
        button {
            box-sizing: border-box;
            width: 100%;
            padding: 13px;
            border: 1px solid #ccd8d2;
            border-radius: 10px;
            font-size: 15px
        }

        button {
            margin-top: 20px;
            background: #1d6b4d;
            color: white;
            border: 0;
            cursor: pointer
        }

        button.forgot-password {
            margin-top: 10px;
            background: #65766d
        }

        .error {
            color: #a51f1f;
            background: #ffe4e4;
            padding: 12px;
            border-radius: 10px;
            font-size: 14px
        }

        .hint {
            font-size: 13px;
            color: #65766d
        }

        .modal {
            position: fixed;
            inset: 0;
            display: grid;
            place-items: center;
            padding: 16px;
            background: #17352a66;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity .2s ease, visibility 0s linear .2s
        }

        .modal.open {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transition-delay: 0s
        }

        .modal-content {
            background: white;
            width: min(420px, 100%);
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 8px 30px #0003;
            text-align: center;
            opacity: 0;
            transform: translateY(8px) scale(.98);
            transition: opacity .2s ease, transform .2s ease
        }

        .modal.open .modal-content {
            opacity: 1;
            transform: translateY(0) scale(1)
        }

        @media (prefers-reduced-motion: reduce) {
            .modal,
            .modal-content {
                transition-duration: 0s
            }
        }

        .modal-content button {
            margin-top: 16px
        }
    </style>
</head>

<body>
    <main class="login">
        <div class="login-heading">
            <img src="{{ asset('images/sinergi-logo.png') }}" alt="Sinergi Hotel and Villa">
            <h1>Login Admin</h1>
        </div>
        <p class="hint">Masuk untuk mengelola member swimming pool Sinergi Hotel.</p>
        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            <label class="field" for="username">Username</label>
            <input id="username" name="username" value="{{ old('username') }}" required autofocus
                autocomplete="username">
            <label class="field" for="password">Password</label>
            <input id="password" name="password" type="password" required autocomplete="current-password">
            <button type="submit">Masuk ke Dashboard</button>
            <button type="button" class="forgot-password" onclick="openForgotPasswordModal()">Lupa Password</button>
        </form>
    </main>
    <div id="forgot-password-modal" class="modal" aria-hidden="true">
        <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="forgot-password-title">
            <h2 id="forgot-password-title">Lupa Password</h2>
            <p>Silahkan hubungi Marcomm hotel untuk mengganti password</p>
            <button type="button" onclick="closeForgotPasswordModal()">Tutup</button>
        </div>
    </div>
    <script>
        function openForgotPasswordModal() {
            const modal = document.getElementById('forgot-password-modal');
            modal.classList.add('open');
            modal.setAttribute('aria-hidden', 'false');
        }

        function closeForgotPasswordModal() {
            const modal = document.getElementById('forgot-password-modal');
            modal.classList.remove('open');
            modal.setAttribute('aria-hidden', 'true');
        }
    </script>
</body>

</html>
