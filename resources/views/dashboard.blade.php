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
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7f5;
            margin: 0;
            color: #17352a
        }

        .wrap {
            max-width: 1100px;
            margin: auto;
            padding: 28px
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
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 18px #0001;
            margin: 16px 0
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px
        }

        .scan {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 12px
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
            box-sizing: border-box
        }

        .datepicker-dropdown {
            position: absolute;
            width: 260px;
            box-sizing: border-box;
            padding: 8px;
            color: #17352a;
            background: #fff;
            border: 1px solid #ccd8d2;
            border-radius: 10px;
            box-shadow: 0 8px 24px #0002;
            z-index: 2000 !important
        }

        .datepicker-dropdown table {
            width: 100%;
            min-width: 0;
            table-layout: fixed;
            border-collapse: collapse;
            margin: 0;
            font-size: 13px
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

        .datepicker-dropdown table tr td.active,
        .datepicker-dropdown table tr td.active:hover,
        .datepicker-dropdown table tr td span.active {
            background: #1d6b4d !important;
            background-image: none !important
        }

        button {
            background: #1d6b4d;
            color: white;
            border: 0;
            cursor: pointer
        }

        .button-link {
            display: inline-block;
            box-sizing: border-box;
            padding: 13px;
            border-radius: 10px;
            background: #1d6b4d;
            color: white;
            text-decoration: none;
            cursor: pointer
        }

        .button-link:hover,
        button:hover {
            background: #15563d
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
            .page-loading {
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
            color: #65766d
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px
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
                    <h1>Sinergi Pool Member</h1>
                    <div class="hint">NFC / RFID USB Reader</div>
                </div>
            </div>
            <div class="form-actions"><button type="button" onclick="loadData(true)">Refresh</button>
                @if (session('admin_role') === 'superadmin')
                    <button type="button" class="password-button"
                        onclick="openPasswordModal()">Ubah Password</button>
                    <button type="button" class="password-button" onclick="openAdminModal()">Tambah User</button>
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

        <div class="card">
            <h2>Tambah Member dan ID Card</h2>
            <p class="hint">Isi data member, lalu tempelkan kartu pada kolom NFC UID jika menggunakan reader USB.</p>
            <form id="member-form" onsubmit="submitMember(event)">
                <div class="form-grid">
                    <div><label for="member-name">Nama member</label><input id="member-name" name="name" required
                            maxlength="100" placeholder="Nama member"></div>
                    <div><label for="created-date">Tanggal pembuatan</label><input id="created-date" name="created_date"
                            class="js-datepicker" required type="text" value="{{ now()->toDateString() }}"
                            placeholder="YYYY-MM-DD" autocomplete="off"></div>
                    <div><label for="member-nik">NIK</label><input id="member-nik" name="nik" required
                            maxlength="30" placeholder="NIK"></div>
                    <div><label for="member-phone">Nomor telepon</label><input id="member-phone" name="phone" required
                            maxlength="30" placeholder="Nomor telepon"></div>
                    <div><label for="member-address">Alamat</label><input id="member-address" name="address"
                            maxlength="500" placeholder="Alamat"></div>
                    <div><label for="room-number">Nomor kamar</label><input id="room-number" name="room_number"
                            maxlength="30" placeholder="Nomor kamar (opsional)"></div>
                    <div><label for="membership-type">Paket membership</label><select id="membership-type"
                            name="membership_type" required>
                            <option value="1 Bulan">1 Bulan</option>
                            <option value="2 Bulan">2 Bulan</option>
                        </select></div>
                    <div><label for="valid-until">Berlaku sampai</label><input id="valid-until" name="valid_until"
                            class="js-datepicker" required type="text" placeholder="YYYY-MM-DD"
                            autocomplete="off"></div>
                    <div><label for="member-nfc-uid">NFC UID / ID card</label><input id="member-nfc-uid" name="nfc_uid"
                            required maxlength="100" autocomplete="off" placeholder="NFC UID / ID card"></div>
                </div>
                <div class="form-actions"><button type="submit">Simpan Member + ID Card</button><button type="button"
                        class="secondary" onclick="resetMemberForm()">Reset</button></div>
            </form>
            <div id="member-result"></div>
        </div>

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
                <div class="form-actions">
                    <button type="button" onclick="closeRefreshModal()">Tutup</button>
                </div>
            </div>
        </div>
        <div id="reset-member-modal" class="modal" aria-hidden="true">
            <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="reset-member-title">
                <h2 id="reset-member-title">Semua data tambah member sudah dihapus</h2>
                <div class="form-actions">
                    <button type="button" onclick="closeResetMemberModal()">Tutup</button>
                </div>
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
        <div id="logs-card" class="card">
            <h2>Riwayat Scan</h2>
            <div class="logs-actions"><button type="button" onclick="openExportModal()">Export PDF</button><button
                    type="button" class="delete" onclick="openDeleteLogsModal()">Hapus Riwayat</button></div>
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
    </div>
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
    <script src="/js/app.js"></script>
    @if (session('admin_role') === 'superadmin' && (session('password_status') || $errors->password->any()))
        <script>
            openPasswordModal();
        </script>
    @endif
</body>

</html>
