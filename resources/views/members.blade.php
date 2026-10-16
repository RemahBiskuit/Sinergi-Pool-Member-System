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
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7f5;
            margin: 0;
            color: #17352a
        }

        .wrap {
            max-width: 1200px;
            margin: auto;
            padding: 28px
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
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 18px #0001;
            margin: 16px 0
        }

        .hint {
            font-size: 13px;
            color: #65766d
        }

        .search-input {
            width: 100%;
            margin: 12px 0 16px
        }

        .table-wrap {
            width: 100%;
            overflow-x: auto
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
            border-bottom: 1px solid #eee;
            vertical-align: top
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
            background: #1d6b4d;
            color: white;
            border: 0;
            cursor: pointer;
            text-decoration: none;
            display: inline-block
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
            width: min(650px, 100%);
            max-height: 90vh;
            overflow: auto;
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
                <h2>Data Member</h2><button type="button" onclick="loadMembers()">Refresh</button>
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
    <script src="/js/members.js"></script>
</body>

</html>
