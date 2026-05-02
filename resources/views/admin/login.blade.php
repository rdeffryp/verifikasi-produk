<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — Z&J Cookies</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;1,500&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: #FFF9F4;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border: 1px solid #EDD9BE;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05); /* Menambahkan bayangan halus agar kartu terlihat elegan */
        }

        /* PERUBAHAN DISINI: Mengubah background header menjadi putih dan menambahkan outline */
        .login-header {
            background: #fff; /* Mengubah background menjadi putih */
            padding: 2.2rem 2rem 2rem;
            text-align: center;
            
            /* Menambahkan Outline Elegan */
            border-bottom: 2px solid #EDD9BE; /* Garis tepi bawah berwarna coklat susu */
            border-radius: 20px 20px 0 0; /* Menyesuaikan radius sudut atas */
        }

        /* Menyesuaikan warna teks header agar kontras dengan background putih */
        .brand-name {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 500;
            color: #6B3E26; /* Mengubah warna teks Z&J menjadi coklat tua */
            margin-bottom: 3px;
        }
        .brand-name em { font-style: italic; color: #D7A86E; } /* Warna em tetap emas/coklat muda */

        .brand-sub {
            font-size: 12px;
            color: #9E7555; /* Mengubah warna teks Admin Panel menjadi coklat muda agar terbaca */
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        /* Style untuk logo (tidak berubah, hanya disesuaikan warnanya agar kontras) */
        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        /* GANTI URL_LOGO_KAMPUS DAN URL_LOGO_FAKULTAS DENGAN PATH LOGO ANDA */
        .university-logo {
            width: 60px;
            height: auto;
            object-fit: contain;
            /* Jika logo asli Anda gelap, Anda bisa menggunakan versi putih atau membiarkannya jika kontrasnya cukup */
        }

        /* Bagian login-body dan seterusnya TIDAK BERUBAH */
        .login-body { padding: 2rem; }
        .alert-err {
            background: #FEE2E2;
            border: 1px solid #FCA5A5;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 13px;
            color: #991B1B;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }
        .field { margin-bottom: 16px; }
        .field label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: #9E7555;
            text-transform: uppercase;
            letter-spacing: .6px;
            margin-bottom: 7px;
        }
        .input-wrap {
            display: flex;
            align-items: stretch;
            border: 1px solid #EDD9BE;
            border-radius: 10px;
            background: #FFF9F4;
            overflow: hidden;
            transition: border-color .15s;
            height: 42px;
        }
        .input-wrap:focus-within { border-color: #D7A86E; background: #fff; }
        .input-wrap.is-invalid { border-color: #FCA5A5; }
        .input-icon {
            width: 40px;
            display: flex; align-items: center; justify-content: center;
            color: #C8A47A; flex-shrink: 0; pointer-events: none;
        }
        .input-wrap input {
            flex: 1; border: none; background: transparent;
            font-size: 14px; color: #3D1F0A;
            padding: 0 10px 0 0; outline: none;
            font-family: 'DM Sans', sans-serif;
        }
        .input-wrap input::placeholder { color: #C8A47A; }
        .toggle-btn {
            width: 40px; border: none; background: transparent;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            color: #C8A47A; flex-shrink: 0; transition: color .15s;
        }
        .toggle-btn:hover { color: #6B3E26; }
        .check-row {
            display: flex; align-items: center; gap: 8px;
            margin: 4px 0 24px;
        }
        .check-row input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: #6B3E26; cursor: pointer;
        }
        .check-row label { font-size: 13px; color: #9E7555; cursor: pointer; }
        .btn-login {
            width: 100%;
            background: #6B3E26;
            color: #FFF3E0;
            border: none;
            border-radius: 10px;
            height: 44px;
            font-size: 14px;
            font-weight: 500;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: background .15s;
        }
        .btn-login:hover { background: #8B5236; }
        .btn-login:active { background: #5A3220; }
        .login-footer {
            border-top: 1px solid #EDD9BE;
            padding: 14px 2rem;
            text-align: center;
        }
        .login-footer p {
            font-size: 12px; color: #C8A47A;
            display: flex; align-items: center; justify-content: center; gap: 5px;
        }
    </style>
</head>
<body>

<div class="login-card">

    <div class="login-header">
        <div class="logo-container">
            <img src="{{ asset('logouniku.png') }}" alt="Logo Kampus" class="university-logo">
            <img src="{{ asset('logofkom.png') }}" alt="Logo Fakultas" class="university-logo">
        </div>
        
        <div class="brand-name">Z&amp;J <em>Cookies</em></div>
        <div class="brand-sub">Admin Panel</div>
    </div>

    <div class="login-body">
        @if ($errors->any())
            <div class="alert-err">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                {{ $errors->first() }}
                <button onclick="this.parentElement.remove()" style="margin-left:auto;background:none;border:none;color:#991B1B;cursor:pointer;font-size:16px;line-height:1;">×</button>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf

            <div class="field">
                <label for="username">Username</label>
                <div class="input-wrap {{ $errors->has('username') ? 'is-invalid' : '' }}">
                    <div class="input-icon">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                    <input type="text" id="username" name="username"
                        value="{{ old('username') }}"
                        placeholder="Masukkan username"
                        autofocus autocomplete="username">
                </div>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <div class="input-wrap {{ $errors->has('password') ? 'is-invalid' : '' }}">
                    <div class="input-icon">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>
                    <input type="password" id="password" name="password"
                        placeholder="Masukkan password"
                        autocomplete="current-password">
                    <button type="button" class="toggle-btn" onclick="togglePassword()" tabindex="-1">
                        <svg id="eye-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="check-row">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Ingat saya</label>
            </div>

            <button type="submit" class="btn-login">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                    <polyline points="10 17 15 12 10 7"/>
                    <line x1="15" y1="12" x2="3" y2="12"/>
                </svg>
                Masuk
            </button>

        </form>
    </div>

    <div class="login-footer">
        <p>
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            Sistem Verifikasi Produk · Z&amp;J Cookies
        </p>
    </div>

</div>

<script>
// Logic toggle password (tetap sama)
function togglePassword() {
    const input = document.getElementById('password');
    const icon  = document.getElementById('eye-icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>';
    } else {
        input.type = 'password';
        icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
    }
}
</script>

</body>
</html>