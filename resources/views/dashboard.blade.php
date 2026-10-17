<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sinergi Pool Member</title>
    <link rel="icon" type="image/png" href="{{ asset('images/sinergi-favicon.png') }}">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/css/bootstrap-datepicker3.min.css">
    <link rel="stylesheet" href="{{ asset('css/button-glow.css') }}">
    <link rel="stylesheet" href="{{ asset('css/liquid-select.css') }}">
    <style>
        body {
            position: relative;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            isolation: isolate;
            font-family: -apple-system, BlinkMacSystemFont, "SF Pro Display", "SF Pro Text", system-ui, sans-serif;
            background: #e9f0f3;
            margin: 0;
            color: #17352a
        }

        body::before {
            position: fixed;
            inset: 0;
            z-index: -1;
            background: url("{{ asset('images/sinergi-pool-background.png') }}") center / max(105vw, 140vh) auto no-repeat;
            content: "";
            opacity: .1
        }

        .wrap {
            max-width: 1100px;
            width: 100%;
            flex: 1 0 auto;
            margin: 0 auto;
            padding: 28px
        }

        .site-footer {
            padding: 16px 24px;
            color: #314b55;
            font-size: 13px;
            text-align: center;
            text-shadow: 0 1px 8px rgba(255, 255, 255, .8)
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px
        }

        .brand img {
            display: block;
            width: 160px;
            max-width: 30vw;
            height: auto
        }

        .brand h1 {
            margin: 0 0 6px
        }

        .card {
            border: 1px solid rgba(255, 255, 255, .68);
            background: rgba(239, 248, 250, .66);
            border-radius: 24px;
            padding: 20px;
            box-shadow: 0 18px 48px rgba(8, 28, 38, .14), inset 0 1px 0 rgba(255, 255, 255, .72);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            backdrop-filter: blur(24px) saturate(180%);
            margin: 16px 0
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px
        }

        .form-grid .full-width {
            grid-column: 1 / -1
        }

        .scan {
            display: grid;
            grid-template-columns: 1fr auto;
            align-items: end;
            gap: 12px
        }

        .scan button {
            height: 48px;
            white-space: nowrap
        }

        input,
        select,
        button {
            box-sizing: border-box;
            padding: 13px;
            border: 1px solid #ccd8d2;
            border-radius: 10px;
            font-size: 15px
        }

        input,
        select {
            width: 100%;
            box-sizing: border-box;
            border-color: rgba(255, 255, 255, .76);
            background: rgba(255, 255, 255, .58);
            color: #17352a;
            box-shadow: inset 0 1px 3px rgba(20, 50, 65, .06)
        }

        input:focus-visible,
        select:focus-visible {
            border-color: rgba(29, 107, 77, .65);
            outline: 3px solid rgba(29, 107, 77, .18)
        }

        .datepicker-dropdown {
            position: absolute;
            width: 260px;
            box-sizing: border-box;
            padding: 8px;
            color: #17352a;
            border: 1px solid rgba(255, 255, 255, .78);
            border-radius: 16px;
            background: rgba(239, 248, 250, .78);
            box-shadow: 0 18px 48px rgba(8, 28, 38, .22), inset 0 1px 0 rgba(255, 255, 255, .86);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            backdrop-filter: blur(24px) saturate(180%);
            transform-origin: top center;
            animation: datepicker-appear .32s cubic-bezier(.2, .8, .2, 1) both;
            z-index: 4000 !important
        }

        .datepicker-dropdown.datepicker-orient-top {
            transform-origin: bottom center
        }

        .datepicker-dropdown table {
            width: 100%;
            min-width: 0;
            table-layout: fixed;
            border-collapse: collapse;
            margin: 0;
            font-size: 13px
        }

        .datepicker-dropdown table,
        .datepicker-dropdown table tr,
        .datepicker-dropdown table tr td,
        .datepicker-dropdown table tr th {
            background: transparent
        }

        .datepicker-dropdown table tr td,
        .datepicker-dropdown table tr th {
            width: auto;
            height: 32px;
            padding: 0 !important;
            border: 0 !important;
            text-align: center;
            vertical-align: middle;
            line-height: 32px
        }

        .datepicker-dropdown table tr td.day,
        .datepicker-dropdown table tr th.datepicker-switch,
        .datepicker-dropdown table tr th.prev,
        .datepicker-dropdown table tr th.next {
            border-radius: 8px;
            transition: background-color .16s ease, color .16s ease, transform .16s ease
        }

        .datepicker-dropdown table tr td.day:hover,
        .datepicker-dropdown table tr th.datepicker-switch:hover,
        .datepicker-dropdown table tr th.prev:hover,
        .datepicker-dropdown table tr th.next:hover {
            background: rgba(255, 255, 255, .62) !important;
            color: #155b48;
            transform: scale(1.06)
        }

        .datepicker-dropdown table tr td.today,
        .datepicker-dropdown table tr td.today:hover {
            border-radius: 8px;
            background: rgba(29, 107, 77, .16) !important;
            background-image: none !important;
            color: #155b48
        }

        .datepicker-dropdown table tr td.active,
        .datepicker-dropdown table tr td.active:hover,
        .datepicker-dropdown table tr td span.active {
            border-radius: 8px;
            background: linear-gradient(135deg, #247c61, #155b48) !important;
            background-image: none !important
        }

        @keyframes datepicker-appear {
            from {
                opacity: 0;
                transform: translateY(-7px) scale(.94)
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1)
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .datepicker-dropdown {
                animation: none
            }

            .datepicker-dropdown table tr td.day,
            .datepicker-dropdown table tr th.datepicker-switch,
            .datepicker-dropdown table tr th.prev,
            .datepicker-dropdown table tr th.next {
                transition: none
            }
        }

        button {
            background: linear-gradient(135deg, #247c61, #155b48);
            color: white;
            border: 1px solid rgba(255, 255, 255, .28);
            cursor: pointer;
            box-shadow: 0 8px 18px rgba(21, 91, 72, .2);
            transition: transform .18s ease, box-shadow .18s ease
        }

        .button-link {
            display: inline-block;
            box-sizing: border-box;
            padding: 13px;
            border-radius: 10px;
            background: linear-gradient(135deg, #247c61, #155b48);
            color: white;
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 8px 18px rgba(21, 91, 72, .2)
        }

        .button-link:hover,
        button:hover {
            background: linear-gradient(135deg, #1d6b4d, #104b3b);
            transform: translateY(-1px);
            box-shadow: 0 11px 22px rgba(21, 91, 72, .26)
        }

        button.password-button {
            background: #246b8f
        }

        button.secondary {
            background: #65766d
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 12px
        }

        .admin-menu-actions {
            display: grid;
            gap: 10px;
            margin-top: 16px
        }

        .admin-menu-actions button {
            width: 100%
        }

        .admin-menu-actions button.delete {
            background: #a51f1f
        }

        .form-grid label,
        .scan label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: bold
        }

        .result {
            padding: 18px;
            border-radius: 12px;
            margin-top: 14px;
            font-weight: bold
        }

        .ok {
            background: #dff5e8;
            color: #14643d
        }

        .bad {
            background: #ffe4e4;
            color: #a51f1f
        }

        table {
            width: 100%;
            border-collapse: collapse
        }

        .table-wrap {
            width: 100%;
            overflow-x: auto
        }

        th,
        td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #eee
        }

        td.actions {
            white-space: normal
        }

        td.actions button {
            display: block;
            width: 100%;
            max-width: 100%;
            margin: 4px 0
        }

        td.actions {
            width: 96px;
            min-width: 96px
        }

        td.actions select,
        td.actions button {
            padding: 8px;
            font-size: 13px
        }

        td.actions button.delete {
            background: #a51f1f
        }

        td.actions button.update {
            background: #1d6b4d
        }

        .logs-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 12px
        }

        .logs-actions button.delete {
            background: #a51f1f
        }

        .modal-content button.delete {
            background: #a51f1f
        }

        .form-actions button.delete,
        .top .form-actions button.delete {
            background: #a51f1f
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

        .modal-content label {
            display: block;
            margin: 12px 0 6px;
            font-size: 13px;
            font-weight: bold
        }

        .scan-details {
            line-height: 1.8;
            margin: 16px 0
        }

        .pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-top: 16px
        }

        .pagination button:disabled {
            background: #b9c5bf;
            cursor: not-allowed
        }

        .print-only {
            display: none
        }

        .page-loading {
            position: fixed;
            inset: 0;
            z-index: 5000;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(244, 247, 245, .96);
            opacity: 0;
            visibility: hidden;
            transition: opacity .2s ease, visibility .2s ease
        }

        .page-loading.is-visible {
            opacity: 1;
            visibility: visible
        }

        .page-loading-content {
            display: flex;
            width: min(280px, calc(100vw - 48px));
            flex-direction: column;
            align-items: center;
            gap: 28px
        }

        .page-loading-logo {
            display: block;
            width: min(220px, 70vw);
            height: auto
        }

        .page-loading-bar {
            position: relative;
            width: 100%;
            height: 28px;
            overflow: hidden;
            border: 1px solid #cbd9d1;
            border-radius: 999px;
            background: #e4ebe7
        }

        .page-loading-progress {
            width: 0;
            height: 100%;
            border-radius: inherit;
            background: #1d6b4d;
            transition: width .05s linear
        }

        .page-loading-percentage {
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

        @media(prefers-reduced-motion:reduce) {
            .page-loading,
            button,
            .button-link {
                transition: none
            }
        }

        @media print {
            body {
                background: white
            }

            .wrap>*:not(#logs-card),
            #logs-card>.logs-actions,
            #logs-pagination {
                display: none !important
            }

            #logs-card {
                display: block !important;
                box-shadow: none;
                margin: 0;
                padding: 0
            }

            #logs-card .print-only {
                display: block
            }
        }

        .hint {
            font-size: 13px;
            color: #405a61
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px
        }

        .add-member-trigger {
            width: 100%;
            margin: 16px 0;
            font-weight: bold
        }

        .member-modal-content {
            width: min(720px, 100%);
            max-height: calc(100vh - 32px);
            overflow-y: auto
        }

        @media(max-width:700px) {

            .grid,
            .form-grid {
                grid-template-columns: 1fr
            }

            .scan {
                grid-template-columns: 1fr
            }

            .wrap {
                padding: 16px
            }

            .top {
                align-items: flex-start;
                gap: 12px
            }
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="top">
            <div class="brand">
                <img src="{{ asset('images/sinergi-logo.png') }}" alt="Sinergi Hotel and Villa">
                <div>
                    <h1>Swimming Pool Member System</h1>
                </div>
            </div>
            <div class="form-actions"><button type="button" onclick="loadData(true)">Refresh</button>
                @if (session('admin_role') === 'superadmin')
                    <button id="open-admin-menu-modal" type="button" class="password-button"
                        onclick="openAdminMenuModal()">Menu Admin</button>
                @endif
                <button type="button" class="secondary" onclick="openLogoutModal()">Logout</button>
            </div>
        </div>

        <div class="grid">
            <div class="card">
                <div class="hint">Total Member</div>
                <h2 id="total">0</h2>
            </div>
            <div class="card">
                <div class="hint">Member Aktif</div>
                <h2 id="active">0</h2>
            </div>
            <div class="card">
                <div class="hint">Scan Hari Ini</div>
                <h2 id="scans">0</h2>
            </div>
        </div>
        <button id="open-add-member-modal" class="add-member-trigger" type="button"
            onclick="openAddMemberModal()">Tambah Member dan ID Card</button>

        <div class="card">
            <h2>Scan Kartu</h2>
            <p class="hint">Klik kotak di bawah, lalu tempelkan kartu. Reader USB tipe keyboard-emulation akan
                mengetik UID otomatis.</p>
            <div class="scan">
                <div><label for="uid">NFC UID kartu</label><input id="uid" autocomplete="off" autofocus
                        placeholder="Tempel kartu NFC / ketik UID"></div><button type="button"
                    onclick="scanCardInfo()">Scan Kartu</button>
            </div>
            <div id="result"></div>
        </div>
        <div id="add-member-modal" class="modal" aria-hidden="true">
            <div class="modal-content member-modal-content" role="dialog" aria-modal="true"
                aria-labelledby="add-member-title">
                <h2 id="add-member-title">Tambah Member dan ID Card</h2>
                <p class="hint">Isi data member, lalu tempelkan kartu pada kolom NFC UID jika menggunakan reader USB.</p>
                <form id="member-form" onsubmit="submitMember(event)">
                    <div class="form-grid">
                        <div><label for="member-name">Nama member</label><input id="member-name" name="name" required
                                maxlength="100" placeholder="Nama member"></div>
                        <div><label for="created-date">Tanggal pembuatan</label><input id="created-date"
                                name="created_date" class="js-datepicker" required type="text"
                                value="{{ now()->toDateString() }}" placeholder="YYYY-MM-DD" autocomplete="off"></div>
                        <div><label for="member-nik">NIK</label><input id="member-nik" name="nik" required
                                maxlength="30" placeholder="NIK"></div>
                        <div><label for="member-phone">Nomor telepon</label><input id="member-phone" name="phone"
                                required maxlength="30" placeholder="Nomor telepon"></div>
                        <div><label for="member-address">Alamat</label><input id="member-address" name="address"
                                maxlength="500" placeholder="Alamat"></div>
                        <div><label for="room-number">Nomor kamar</label><input id="room-number" name="room_number"
                                maxlength="30" placeholder="Nomor kamar (opsional)"></div>
                        <div><label for="membership-type">Paket membership</label><select id="membership-type"
                                name="membership_type" required>
                                <option value="1 Bulan">1 Bulan</option>
                                <option value="2 Bulan">2 Bulan</option>
                            </select></div>
                        <div><label for="valid-until">Berlaku sampai</label><input id="valid-until"
                                name="valid_until" class="js-datepicker" required type="text"
                                placeholder="YYYY-MM-DD" autocomplete="off"></div>
                        <div class="full-width"><label for="member-nfc-uid">NFC UID / ID card</label><input id="member-nfc-uid"
                                name="nfc_uid" required maxlength="100" autocomplete="off"
                                placeholder="NFC UID / ID card"></div>
                    </div>
                    <div class="form-actions">
                        <button type="submit">Simpan Member + ID Card</button>
                        <button type="button" class="secondary" onclick="resetMemberForm()">Reset</button>
                    </div>
                </form>
                <div id="member-result"></div>
            </div>
        </div>
        <div id="scan-modal" class="modal" aria-hidden="true">
            <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="scan-title">
                <h2 id="scan-title">Konfirmasi Akses</h2>
                <div id="scan-details" class="scan-details">
                    <div>Nama: <strong id="scan-member-name"></strong></div>
                    <div>Jenis paket: <strong id="scan-membership-type"></strong></div>
                    <div>Masa berlaku: <strong id="scan-valid-until"></strong></div>
                    <div>Sisa scan paket: <strong id="scan-remaining"></strong></div>
                </div>
                <div class="form-actions"><button id="scan-enter" type="button"
                        onclick="confirmCardEntry()">Masuk</button><button type="button" class="secondary"
                        onclick="closeScanModal()">Batal</button></div>
            </div>
        </div>
        <div id="refresh-modal" class="modal" aria-hidden="true">
            <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="refresh-title">
                <h2 id="refresh-title">Data sudah di-refresh</h2>
            </div>
        </div>
        <div id="reset-member-modal" class="modal" aria-hidden="true">
            <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="reset-member-title">
                <h2 id="reset-member-title">Semua data tambah member sudah dihapus</h2>
            </div>
        </div>
        <div id="logout-modal" class="modal" aria-hidden="true">
            <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="logout-title">
                <h2 id="logout-title">Apakah Anda yakin untuk logout?</h2>
                <form method="POST" action="{{ route('logout') }}" class="form-actions">
                    @csrf
                    <button type="submit">Ya</button>
                    <button type="button" class="secondary" onclick="closeLogoutModal()">Tidak</button>
                </form>
            </div>
        </div>
        @if (session('admin_role') === 'superadmin')
            <div id="admin-menu-modal" class="modal" aria-hidden="true">
                <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="admin-menu-title">
                    <h2 id="admin-menu-title">Menu Admin</h2>
                    <div class="admin-menu-actions">
                        <button type="button" class="password-button"
                            onclick="openAdminMenuAction(openPasswordModal)">Ubah Password</button>
                        <button type="button" class="password-button"
                            onclick="openAdminMenuAction(openAdminModal)">Tambah User</button>
                        <button type="button" class="delete"
                            onclick="openAdminMenuAction(openPurgeModal)">Purge Database</button>
                    </div>
                </div>
            </div>
            <div id="purge-modal" class="modal" aria-hidden="true">
                <div class="modal-content" role="alertdialog" aria-modal="true"
                    aria-labelledby="purge-title" aria-describedby="purge-description">
                    <h2 id="purge-title">Purge Database</h2>
                    <p id="purge-description" class="hint">Tindakan ini akan menghapus semua data member dan riwayat scan secara permanen. Data akun admin tidak akan dihapus.</p>
                    <form id="purge-form" onsubmit="purgeDatabase(event)">
                        <label for="purge-password">Password superadmin</label>
                        <input id="purge-password" name="password" type="password" required
                            autocomplete="current-password">
                        <div class="form-actions">
                            <button id="purge-submit" type="submit" class="delete">Hapus Semua Data</button>
                            <button type="button" class="secondary" onclick="closePurgeModal()">Batal</button>
                        </div>
                    </form>
                    <div id="purge-result" aria-live="polite"></div>
                </div>
            </div>
            <div id="password-modal" class="modal" aria-hidden="true">
                <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="password-title">
                    <h2 id="password-title">Ubah Password Admin</h2>
                    @if (session('password_status'))
                        <div class="result ok">{{ session('password_status') }}</div>
                    @endif
                    @if ($errors->password->any())
                        <div class="result bad">{{ $errors->password->first() }}</div>
                    @endif
                    <form method="POST" action="{{ route('password.change') }}">
                        @csrf
                        <label for="password-admin">Admin</label>
                        <select id="password-admin" name="admin_id" required>
                            @foreach ($admins as $admin)
                                <option value="{{ $admin->id }}"
                                    @selected((string) old('admin_id', session('admin_id')) === (string) $admin->id)>
                                    {{ $admin->username }}
                                </option>
                            @endforeach
                        </select>
                        <label for="new-password">Password baru</label>
                        <input id="new-password" name="password" type="password" required minlength="8"
                            autocomplete="new-password">
                        <label for="password-confirmation">Konfirmasi password baru</label>
                        <input id="password-confirmation" name="password_confirmation" type="password" required
                            minlength="8" autocomplete="new-password">
                        <div class="form-actions"><button type="submit">Simpan Password</button><button type="button"
                                class="secondary" onclick="closePasswordModal()">Batal</button></div>
                    </form>
                </div>
            </div>
            <div id="admin-modal" class="modal" aria-hidden="true">
                <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="admin-title">
                    <h2 id="admin-title">Tambah User Admin</h2>
                    @if (session('admin_status'))
                        <div class="result ok">{{ session('admin_status') }}</div>
                    @endif
                    <form method="POST" action="{{ route('admins.create') }}">
                        @csrf
                        <label for="admin-username">Username</label>
                        <input id="admin-username" name="username" required maxlength="100" autocomplete="username">
                        <label for="admin-password">Password</label>
                        <input id="admin-password" name="password" type="password" required minlength="8"
                            autocomplete="new-password">
                        <label for="admin-password-confirmation">Konfirmasi password</label>
                        <input id="admin-password-confirmation" name="password_confirmation" type="password" required
                            minlength="8" autocomplete="new-password">
                        <label for="admin-role">Role</label>
                        <select id="admin-role" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="superadmin">Superadmin</option>
                        </select>
                        <div class="form-actions"><button type="submit">Simpan User</button><button type="button"
                                class="secondary" onclick="closeAdminModal()">Batal</button></div>
                    </form>
                </div>
            </div>
        @endif
        <div class="card">
            <div class="section-heading">
                <h2>Member Aktif</h2><a id="all-members-link" class="button-link"
                    href="{{ route('members.all') }}">Lihat Semua Member</a>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Tanggal Dibuat</th>
                            <th>Telepon</th>
                            <th>Alamat</th>
                            <th>Kamar</th>
                            <th>NFC UID</th>
                            <th>Berlaku</th>
                            <th>Status</th>
                            <th>Scan Paket</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="members"></tbody>
                </table>
            </div>
            <div id="members-pagination" class="pagination"></div>
        </div>
        <div id="update-modal" class="modal" aria-hidden="true">
            <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="update-title">
                <h2 id="update-title">Update Membership</h2>
                <form id="update-form" onsubmit="submitMemberUpdate(event)">
                    <div id="update-member-fields" class="form-grid">
                        <div><label for="update-name">Nama member</label><input id="update-name" name="name"
                                required maxlength="100"></div>
                        <div><label for="update-created-date">Tanggal pembuatan</label><input id="update-created-date"
                                name="created_date" class="js-datepicker" type="text" required
                                placeholder="YYYY-MM-DD" autocomplete="off"></div>
                        <div><label for="update-nik">NIK</label><input id="update-nik" name="nik" required
                                maxlength="30"></div>
                        <div><label for="update-phone">Nomor telepon</label><input id="update-phone" name="phone"
                                required maxlength="30"></div>
                        <div><label for="update-address">Alamat</label><input id="update-address" name="address"
                                maxlength="500"></div>
                        <div><label for="update-room-number">Nomor kamar</label><input id="update-room-number"
                                name="room_number" maxlength="30"></div>
                        <div><label for="update-nfc-uid">NFC UID / ID card</label><input id="update-nfc-uid"
                                name="nfc_uid" required maxlength="100"></div>
                    </div>
                    <label for="update-membership-type">Membership</label>
                    <select id="update-membership-type" name="membership_type" required>
                        <option value="1 Bulan">1 Bulan</option>
                        <option value="2 Bulan">2 Bulan</option>
                    </select>
                    <label for="update-valid-until">Berlaku sampai</label>
                    <input id="update-valid-until" name="valid_until" class="js-datepicker" type="text" required
                        placeholder="YYYY-MM-DD" autocomplete="off">
                    <label for="update-status">Status</label>
                    <select id="update-status" name="status" required>
                        <option value="active">Aktif</option>
                        <option value="inactive">Nonaktif</option>
                    </select>
                    <div class="form-actions"><button id="update-submit" type="submit"
                            class="update">Update</button><button type="button" class="secondary"
                            onclick="closeUpdateModal()">Batal</button></div>
                </form>
            </div>
        </div>
        <div id="confirm-delete-member-modal" class="modal" aria-hidden="true">
            <div class="modal-content" role="alertdialog" aria-modal="true"
                aria-labelledby="confirm-delete-member-title" aria-describedby="confirm-delete-member-message">
                <h2 id="confirm-delete-member-title">Yakin ingin menghapus member?</h2>
                <p id="confirm-delete-member-message" class="hint"></p>
                <div class="form-actions">
                    <button id="confirm-delete-member-button" type="button" class="delete"
                        onclick="confirmDeleteMember()">Ya, Hapus Member</button>
                    <button type="button" class="secondary" onclick="closeDeleteMemberModal()">Batal</button>
                </div>
            </div>
        </div>
        <div id="logs-card" class="card">
            <h2>Riwayat Scan</h2>
            <div class="logs-actions"><button type="button" onclick="openExportModal()">Export PDF</button>
                @if (session('admin_role') === 'superadmin')
                    <button id="delete-logs-open" type="button" class="delete"
                        onclick="openDeleteLogsModal()">Hapus Riwayat</button>
                @endif
            </div>
            <div class="print-only">
                <p>Sinergi Pool Member - Riwayat Scan</p>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>UID</th>
                            <th>Member</th>
                            <th>Hasil</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody id="logs"></tbody>
                </table>
            </div>
            <div id="logs-pagination" class="pagination"></div>
        </div>
        <div id="export-modal" class="modal" aria-hidden="true">
            <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="export-title">
                <h2 id="export-title">Export Riwayat Scan</h2>
                <p class="hint">Pilih rentang tanggal data yang akan diexport.</p>
                <form id="export-form" onsubmit="submitExport(event)">
                    <label for="export-from">Dari tanggal</label>
                    <input id="export-from" name="from" class="js-datepicker" type="text"
                        placeholder="YYYY-MM-DD" autocomplete="off">
                    <label for="export-to">Sampai tanggal</label>
                    <input id="export-to" name="to" class="js-datepicker" type="text"
                        placeholder="YYYY-MM-DD" autocomplete="off">
                    <div class="form-actions"><button type="submit">Tampilkan PDF</button><button type="button"
                            class="secondary" onclick="closeExportModal()">Batal</button></div>
                </form>
                <div id="export-result"></div>
            </div>
        </div>
        @if (session('admin_role') === 'superadmin')
            <div id="delete-logs-modal" class="modal" aria-hidden="true">
                <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="delete-logs-title">
                    <h2 id="delete-logs-title">Hapus Riwayat Scan</h2>
                    <p class="hint">Pilih rentang tanggal riwayat yang akan dihapus.</p>
                    <form id="delete-logs-form" onsubmit="submitDeleteLogs(event)">
                        <label for="delete-logs-from">Dari tanggal</label>
                        <input id="delete-logs-from" name="from" class="js-datepicker" type="text" required
                            placeholder="YYYY-MM-DD" autocomplete="off">
                        <label for="delete-logs-to">Sampai tanggal</label>
                        <input id="delete-logs-to" name="to" class="js-datepicker" type="text" required
                            placeholder="YYYY-MM-DD" autocomplete="off">
                        <div class="form-actions"><button type="submit" class="delete">Hapus Riwayat</button><button
                                type="button" class="secondary" onclick="closeDeleteLogsModal()">Batal</button></div>
                    </form>
                    <div id="delete-logs-result"></div>
                </div>
            </div>
            <div id="confirm-delete-logs-modal" class="modal" aria-hidden="true">
                <div class="modal-content" role="alertdialog" aria-modal="true"
                    aria-labelledby="confirm-delete-logs-title" aria-describedby="confirm-delete-logs-range">
                    <h2 id="confirm-delete-logs-title">Yakin ingin menghapus riwayat?</h2>
                    <p id="confirm-delete-logs-range" class="hint"></p>
                    <div class="form-actions">
                        <button type="button" class="delete" onclick="confirmDeleteLogs()">Ya, Hapus Riwayat</button>
                        <button type="button" class="secondary" onclick="closeConfirmDeleteLogsModal()">Batal</button>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <footer class="site-footer">&copy; {{ now()->year }} by Sinergi Hotel &amp; Villa</footer>
    <div id="page-loading" class="page-loading" aria-hidden="true">
        <div class="page-loading-content">
            <img class="page-loading-logo" src="{{ asset('images/sinergi-logo.png') }}"
                alt="Sinergi Hotel and Villa">
            <div class="page-loading-bar" role="progressbar" aria-label="Memuat daftar member" aria-valuemin="0"
                aria-valuemax="100" aria-valuenow="0">
                <div class="page-loading-progress"></div>
                <span class="page-loading-percentage">0%</span>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/locales/bootstrap-datepicker.id.min.js"></script>
    <script>
        $('.js-datepicker').datepicker({
            format: 'yyyy-mm-dd',
            language: 'id',
            autoclose: true,
            todayHighlight: true,
            orientation: 'auto',
            container: 'body'
        });
    </script>
    <script src="{{ asset('js/liquid-select.js') }}"></script>
    <script src="/js/app.js"></script>
    @if (session('admin_role') === 'superadmin' && (session('password_status') || $errors->password->any()))
        <script>
            openPasswordModal();
        </script>
    @endif
    <script src="{{ asset('js/button-glow.js') }}"></script>
</body>

</html>
