<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login Admin - SINERGI POOL MEMBER SYSTEM</title>
    <link rel="icon" type="image/png" href="{{ asset('images/sinergi-favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/button-glow.css') }}">
    <style>
        body {
            position: relative;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            isolation: isolate;
            font-family: -apple-system, BlinkMacSystemFont, "SF Pro Display", "SF Pro Text", system-ui, sans-serif;
            background: #e9f0f3;
            color: #17352a;
            margin: 0
        }

        body::before {
            position: fixed;
            inset: 0;
            z-index: -1;
            background: url("{{ asset('images/sinergi-pool-background.png') }}") center / max(105vw, 140vh) auto no-repeat;
            content: "";
            opacity: .2
        }

        .login {
            margin: auto;
            box-sizing: border-box;
            width: min(440px, calc(100% - 32px));
            padding: 32px;
            border: 1px solid rgba(255, 255, 255, .62);
            border-radius: 28px;
            background: rgba(255, 255, 255, .38);
            box-shadow: 0 24px 70px rgba(15, 40, 55, .22), inset 0 1px 0 rgba(255, 255, 255, .8);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            backdrop-filter: blur(24px) saturate(180%)
        }

        .site-footer {
            width: 100%;
            padding: 16px 24px;
            color: #65766d;
            font-size: 13px;
            text-align: center
        }

        h1 {
            margin: 0;
            font-size: clamp(20px, 5vw, 25px);
            text-align: center
        }

        .login-heading {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px
        }

        .login-heading img {
            display: block;
            width: min(220px, 75%);
            height: auto
        }

        .field {
            display: block;
            margin: 16px 0 6px;
            font-size: 14px;
            font-weight: bold;
            color: #193b4a
        }

        input,
        button {
            box-sizing: border-box;
            width: 100%;
            padding: 13px;
            border: 1px solid rgba(255, 255, 255, .74);
            border-radius: 13px;
            font-size: 15px
        }

        input {
            background: rgba(255, 255, 255, .62);
            color: #17352a;
            box-shadow: inset 0 1px 3px rgba(20, 50, 65, .06)
        }

        input:focus-visible {
            border-color: rgba(29, 107, 77, .65);
            outline: 3px solid rgba(29, 107, 77, .18)
        }

        button {
            margin-top: 20px;
            background: linear-gradient(135deg, #247c61, #155b48);
            color: white;
            border: 0;
            cursor: pointer;
            font-weight: 700;
            box-shadow: 0 8px 18px rgba(21, 91, 72, .22);
            transition: transform .18s ease, box-shadow .18s ease
        }

        button:hover {
            transform: translateY(-1px);
            box-shadow: 0 11px 22px rgba(21, 91, 72, .28)
        }

        button.forgot-password {
            margin-top: 10px;
            border: 1px solid rgba(255, 255, 255, .42);
            background: rgba(42, 67, 72, .56);
            -webkit-backdrop-filter: blur(12px);
            backdrop-filter: blur(12px)
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
            color: #405a61
        }

        .site-footer {
            box-sizing: border-box;
            color: #314b55;
            text-shadow: 0 1px 8px rgba(255, 255, 255, .8)
        }

        @media (max-width: 480px) {
            .login {
                padding: 26px 22px;
                border-radius: 24px
            }
        }

        @media (prefers-reduced-motion: reduce) {
            button {
                transition: none
            }
        }

        .login-loading {
            position: fixed;
            inset: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(244, 247, 245, .96);
            opacity: 0;
            visibility: hidden;
            transition: opacity .2s ease, visibility .2s ease
        }

        .login-loading.is-visible {
            opacity: 1;
            visibility: visible
        }

        .login-loading-content {
            display: flex;
            width: min(280px, calc(100vw - 48px));
            flex-direction: column;
            align-items: center;
            gap: 28px
        }

        .login-loading-logo {
            display: block;
            width: min(220px, 70vw);
            height: auto
        }

        .login-loading-bar {
            position: relative;
            width: 100%;
            height: 28px;
            overflow: hidden;
            border: 1px solid #cbd9d1;
            border-radius: 999px;
            background: #e4ebe7
        }

        .login-loading-progress {
            width: 0;
            height: 100%;
            border-radius: inherit;
            background: #1d6b4d
        }

        .login-loading-percentage {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: rgba(255, 255, 255, .88);
            color: #17352a;
            font-size: 13px;
            font-weight: 700
        }

        .modal {
            position: fixed;
            inset: 0;
            z-index: 3000;
            display: grid;
            place-items: center;
            padding: 16px;
            background: rgba(15, 35, 45, .3);
            -webkit-backdrop-filter: blur(10px) saturate(140%);
            backdrop-filter: blur(10px) saturate(140%);
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
            position: relative;
            border: 1px solid rgba(255, 255, 255, .68);
            background: rgba(239, 248, 250, .66);
            width: min(420px, 100%);
            padding: 56px 24px 24px;
            border-radius: 24px;
            box-shadow: 0 24px 70px rgba(8, 28, 38, .28), inset 0 1px 0 rgba(255, 255, 255, .8);
            -webkit-backdrop-filter: blur(28px) saturate(180%);
            backdrop-filter: blur(28px) saturate(180%);
            text-align: center;
            opacity: 0;
            transform: translateY(8px) scale(.98);
            transition: opacity .2s ease, transform .2s ease
        }

        .modal-content .modal-close {
            position: absolute;
            top: 12px;
            right: 12px;
            display: grid;
            width: 36px;
            height: 36px;
            place-items: center;
            padding: 0;
            border: 0;
            border-radius: 50%;
            background: #f4f7f5;
            color: #17352a;
            font-size: 24px;
            line-height: 1;
            cursor: pointer
        }

        .modal-content .modal-close:hover {
            background: #e4ebe7;
            color: #a51f1f
        }

        .modal-content .modal-close:focus-visible {
            outline: 3px solid #92c5ad;
            outline-offset: 2px
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
            <h1>SWIMMING POOL MEMBER SYSTEM</h1>
        </div>
        <p class="hint">Masuk untuk mengelola member swimming pool Sinergi Hotel.</p>
        <form id="login-form" method="POST" action="{{ route('login.submit') }}">
            @csrf
            <label class="field" for="username">Username</label>
            <input id="username" name="username" value="{{ old('username') }}" required autofocus
                autocomplete="username">
            <label class="field" for="password">Password</label>
            <input id="password" name="password" type="password" required autocomplete="current-password">
            <button id="login-submit" type="submit">Login</button>
            <button type="button" class="forgot-password" onclick="openForgotPasswordModal()">Lupa Password</button>
        </form>
    </main>
    <footer class="site-footer">&copy; {{ now()->year }} by Sinergi Hotel &amp; Villa</footer>
    <div id="login-loading" class="login-loading" aria-hidden="true">
        <div class="login-loading-content">
            <img class="login-loading-logo" src="{{ asset('images/sinergi-logo.png') }}" alt="Sinergi Hotel and Villa">
            <div class="login-loading-bar" role="progressbar" aria-label="Sedang login" aria-valuemin="0"
                aria-valuemax="100" aria-valuenow="0">
                <div class="login-loading-progress"></div>
                <span class="login-loading-percentage">0%</span>
            </div>
        </div>
    </div>
    <div id="forgot-password-modal" class="modal" aria-hidden="true">
        <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="forgot-password-title">
            <h2 id="forgot-password-title">Lupa Password</h2>
            <p>Silahkan hubungi Marcomm hotel untuk mengganti password</p>
        </div>
    </div>
    @if ($errors->any())
        <div id="login-error-modal" class="modal open" aria-hidden="false">
            <div class="modal-content" role="alertdialog" aria-modal="true" aria-labelledby="login-error-title"
                aria-describedby="login-error-message">
                <h2 id="login-error-title">Login Gagal</h2>
                <p id="login-error-message" class="error">{{ $errors->first() }}</p>
            </div>
        </div>
    @endif
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

        const forgotPasswordModal = document.getElementById('forgot-password-modal');
        const forgotPasswordClose = document.createElement('button');
        forgotPasswordClose.type = 'button';
        forgotPasswordClose.className = 'modal-close';
        forgotPasswordClose.setAttribute('aria-label', 'Tutup modal');
        forgotPasswordClose.textContent = '×';
        forgotPasswordClose.addEventListener('click', () => {
            forgotPasswordClose.blur();
            closeForgotPasswordModal();
        });
        forgotPasswordModal.querySelector('.modal-content').prepend(forgotPasswordClose);

        const loginErrorModal = document.getElementById('login-error-modal');
        if (loginErrorModal) {
            const loginErrorClose = document.createElement('button');
            loginErrorClose.type = 'button';
            loginErrorClose.className = 'modal-close';
            loginErrorClose.setAttribute('aria-label', 'Tutup modal');
            loginErrorClose.textContent = '×';
            loginErrorClose.addEventListener('click', () => {
                loginErrorClose.blur();
                loginErrorModal.classList.remove('open');
                loginErrorModal.setAttribute('aria-hidden', 'true');
                document.getElementById('username').focus();
            });
            loginErrorModal.querySelector('.modal-content').prepend(loginErrorClose);
        }

        const loginForm = document.getElementById('login-form');
        const loginLoading = document.getElementById('login-loading');
        let loginLoadingStarted = false;

        loginForm.addEventListener('submit', (event) => {
            if (loginForm.dataset.loading === 'true') return;

            event.preventDefault();
            if (loginLoadingStarted) return;
            loginLoadingStarted = true;

            loginLoading.classList.add('is-visible');
            loginLoading.setAttribute('aria-hidden', 'false');

            const progressBar = loginLoading.querySelector('[role="progressbar"]');
            const progress = loginLoading.querySelector('.login-loading-progress');
            const percentage = loginLoading.querySelector('.login-loading-percentage');
            const startedAt = performance.now();
            const duration = 1500;

            function updateProgress(now) {
                const value = Math.min(Math.floor(((now - startedAt) / duration) * 100), 100);
                progress.style.width = `${value}%`;
                percentage.textContent = `${value}%`;
                progressBar.setAttribute('aria-valuenow', String(value));

                if (value === 100) {
                    loginForm.dataset.loading = 'true';
                    loginForm.requestSubmit(document.getElementById('login-submit'));
                    return;
                }

                requestAnimationFrame(updateProgress);
            }

            requestAnimationFrame(updateProgress);
        });
    </script>
    <script src="{{ asset('js/button-glow.js') }}"></script>
</body>

</html>
