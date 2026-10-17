<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Semua Member - Sinergi Pool Member</title>
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
            max-width: 1200px;
            width: 100%;
            flex: 1 0 auto;
            margin: 0 auto;
            padding: 28px
        }

        .site-footer {
            box-sizing: border-box;
            padding: 16px 24px;
            color: #314b55;
            font-size: 13px;
            text-align: center;
            text-shadow: 0 1px 8px rgba(255, 255, 255, .8)
        }

        .top,
        .section-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px
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
            margin: 0
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

        .hint {
            font-size: 13px;
            color: #405a61
        }

        .search-input {
            width: 100%;
            margin: 12px 0 16px
        }

        .table-wrap {
            width: 100%;
            overflow-x: auto
        }

        .table-wrap table {
            border-collapse: separate;
            border-spacing: 0 8px
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1050px
        }

        th,
        td {
            text-align: left;
            padding: 10px;
            vertical-align: top
        }

        .table-wrap thead th {
            border-bottom: 1px solid rgba(255, 255, 255, .58)
        }

        .table-wrap tbody td {
            padding: 12px 10px;
            background: rgba(255, 255, 255, .32);
            vertical-align: middle
        }

        .table-wrap tbody td:first-child {
            border-radius: 10px 0 0 10px
        }

        .table-wrap tbody td:last-child {
            border-radius: 0 10px 10px 0
        }

        button,
        .button-link,
        input,
        select {
            box-sizing: border-box;
            padding: 10px;
            border: 1px solid #ccd8d2;
            border-radius: 10px;
            font-size: 14px
        }

        button,
        .button-link {
            background: linear-gradient(135deg, #247c61, #155b48);
            color: white;
            border: 0;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 8px 18px rgba(21, 91, 72, .2);
            transition: transform .18s ease, box-shadow .18s ease
        }

        button:hover,
        .button-link:hover {
            transform: translateY(-1px);
            box-shadow: 0 11px 22px rgba(21, 91, 72, .26)
        }

        input,
        select {
            background: rgba(255, 255, 255, .58);
            color: #17352a;
            border-color: rgba(255, 255, 255, .76);
            box-shadow: inset 0 1px 3px rgba(20, 50, 65, .06)
        }

        input:focus-visible,
        select:focus-visible {
            border-color: rgba(29, 107, 77, .65);
            outline: 3px solid rgba(29, 107, 77, .18)
        }

        button.delete {
            background: #a51f1f
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

        button.extend {
            background: #246b8f
        }

        .actions button {
            display: block;
            width: 100%;
            margin: 4px 0;
            white-space: nowrap
        }

        .inactive {
            color: #a51f1f;
            font-weight: bold
        }

        .active {
            color: #14643d;
            font-weight: bold
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
            width: min(650px, 100%);
            max-height: 90vh;
            overflow: auto;
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
            .modal-content,
            button,
            .button-link {
                transition-duration: 0s
            }
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px
        }

        .form-grid label {
            display: block;
            margin-bottom: 5px;
            font-size: 13px;
            font-weight: bold
        }

        .form-grid input,
        .form-grid select {
            width: 100%
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 16px
        }

        .form-actions button {
            flex: 1
        }

        @media(max-width:700px) {
            .wrap {
                padding: 16px
            }

            .top,
            .section-heading {
                align-items: flex-start;
                flex-direction: column
            }

            .form-grid {
                grid-template-columns: 1fr
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
                    <h1>Semua Member</h1>
                    <div class="hint">Daftar member aktif dan inactive</div>
                </div>
            </div><a class="button-link" href="{{ route('dashboard') }}">Kembali ke Dashboard</a>
        </div>
        <div class="card">
            <div class="section-heading">
                <h2>Data Member</h2><button type="button" onclick="loadMembers(true)">Refresh</button>
            </div>
            <input id="member-search" class="search-input" type="search"
                placeholder="Cari nama, kode, NIK, telepon, alamat, UID, paket, atau status..." autocomplete="off">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Tanggal Dibuat</th>
                            <th>NIK</th>
                            <th>Telepon</th>
                            <th>Alamat</th>
                            <th>Kamar</th>
                            <th>NFC UID</th>
                            <th>Paket</th>
                            <th>Berlaku</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="members"></tbody>
                </table>
            </div>
        </div>
    </div>
    <footer class="site-footer">&copy; {{ now()->year }} by Sinergi Hotel &amp; Villa</footer>
    <div id="edit-modal" class="modal" aria-hidden="true">
        <div class="modal-content">
            <h2>Update Member</h2>
            <form id="edit-form" onsubmit="submitEdit(event)">
                <div class="form-grid">
                    <div><label for="edit-name">Nama member</label><input id="edit-name" name="name" required></div>
                    <div><label for="edit-created-date">Tanggal pembuatan</label><input id="edit-created-date"
                            name="created_date" class="js-datepicker" type="text" required
                            placeholder="YYYY-MM-DD" autocomplete="off"></div>
                    <div><label for="edit-nik">NIK</label><input id="edit-nik" name="nik" required></div>
                    <div><label for="edit-phone">Nomor telepon</label><input id="edit-phone" name="phone" required>
                    </div>
                    <div><label for="edit-address">Alamat</label><input id="edit-address" name="address"></div>
                    <div><label for="edit-room-number">Nomor kamar</label><input id="edit-room-number"
                            name="room_number"></div>
                    <div><label for="edit-nfc-uid">NFC UID / ID card</label><input id="edit-nfc-uid" name="nfc_uid">
                    </div>
                    <div><label for="edit-membership-type">Paket membership</label><select id="edit-membership-type"
                            name="membership_type" required>
                            <option>1 Bulan</option>
                            <option>2 Bulan</option>
                        </select></div>
                    <div><label for="edit-valid-until">Berlaku sampai</label><input id="edit-valid-until"
                            name="valid_until" class="js-datepicker" type="text" required
                            placeholder="YYYY-MM-DD" autocomplete="off"></div>
                    <div><label for="edit-status">Status</label><select id="edit-status" name="status" required>
                            <option value="active">Aktif</option>
                            <option value="inactive">Inactive</option>
                        </select></div>
                </div>
                <div class="form-actions"><button type="submit">Update</button><button type="button"
                        onclick="closeEdit()">Batal</button></div>
            </form>
        </div>
    </div>
    <div id="extend-modal" class="modal" aria-hidden="true">
        <div class="modal-content">
            <h2>Perpanjang Paket</h2>
            <form id="extend-form" onsubmit="submitExtend(event)">
                <div class="form-grid">
                    <div><label for="extend-membership-type">Paket membership</label><select id="extend-membership-type"
                            name="membership_type" required>
                            <option value="1 Bulan">1 Bulan</option>
                            <option value="2 Bulan">2 Bulan</option>
                        </select></div>
                    <div><label for="extend-valid-until">Berlaku sampai</label><input id="extend-valid-until"
                            name="valid_until" class="js-datepicker" type="text" required
                            placeholder="YYYY-MM-DD" autocomplete="off"></div>
                    <div><label for="extend-status">Status</label><select id="extend-status" name="status" required>
                            <option value="active">Aktif</option>
                            <option value="inactive">Inactive</option>
                        </select></div>
                </div>
                <div class="form-actions"><button type="submit">Perpanjang</button><button type="button"
                        onclick="closeExtend()">Batal</button></div>
            </form>
        </div>
    </div>
    @if (session('admin_role') === 'superadmin')
        <div id="delete-confirmation-modal" class="modal" aria-hidden="true">
            <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="delete-confirmation-title">
                <h2 id="delete-confirmation-title">Konfirmasi Hapus Member</h2>
                <p id="delete-confirmation-message"></p>
                <div class="form-actions">
                    <button type="button" onclick="closeDeleteConfirmation()">Batal</button>
                    <button id="confirm-delete-member" type="button" class="delete"
                        onclick="confirmDeleteMember()">Ya, Hapus</button>
                </div>
            </div>
        </div>
    @endif
    <div id="refresh-modal" class="modal" aria-hidden="true">
        <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="refresh-title">
            <h2 id="refresh-title">Data sudah di-refresh</h2>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/locales/bootstrap-datepicker.id.min.js"></script>
    <script>
        $('.js-datepicker').each(function() {
            const modalContent = this.closest('.modal-content');
            $(this).datepicker({
                format: 'yyyy-mm-dd',
                language: 'id',
                autoclose: true,
                todayHighlight: true,
                orientation: 'auto',
                container: modalContent || 'body'
            });
        });
    </script>
    <script src="{{ asset('js/liquid-select.js') }}"></script>
    <script>
        window.canDeleteMembers = @json(session('admin_role') === 'superadmin');
    </script>
    <script src="/js/members.js"></script>
    <script src="{{ asset('js/button-glow.js') }}"></script>
</body>

</html>
