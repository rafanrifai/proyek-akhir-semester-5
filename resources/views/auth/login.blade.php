<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIM-BMN KEMENAG MINAHASA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563EB;
            --dark-blue: #1E40AF;
            --gold-accent: #D4A72C;
            --bg-color: #F8FAFC;
            --white: #FFFFFF;
            --text-main: #0F172A;
            --text-secondary: #64748B;
            --danger: #DC2626;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            color: var(--text-main);
        }

        .login-container {
            background-color: var(--white);
            width: 100%;
            max-width: 400px;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.05);
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: var(--dark-blue);
        }

        .login-header p {
            margin: 8px 0 0;
            font-size: 14px;
            color: var(--text-secondary);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: var(--primary);
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 10px;
        }

        .btn-login:hover {
            background-color: var(--dark-blue);
        }

        .alert-error {
            background-color: #FEF2F2;
            color: var(--danger);
            padding: 12px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            border: 1px solid #FECACA;
        }

        .accent-line {
            height: 4px;
            width: 60px;
            background-color: var(--gold-accent);
            margin: 15px auto 0;
            border-radius: 4px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-header">
            <h1>SIM-BMN KEMENAG MINAHASA</h1>
            <div class="accent-line"></div>
            <p>Sistem Informasi Manajemen BMN Minahasa</p>
        </div>

        @if($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('status'))
            <div style="background-color: #DCFCE7; color: #059669; padding: 12px; border-radius: 8px; font-size: 13px; margin-bottom: 20px; border: 1px solid #A7F3D0;">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="Masukkan email Anda">
            </div>

            <div class="form-group">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <label for="password" style="margin-bottom: 0;">Kata Sandi</label>
                    <a href="{{ route('password.request') }}" style="font-size: 12px; color: var(--primary); text-decoration: none; font-weight: 500;">Lupa Sandi?</a>
                </div>
                <div style="position: relative;">
                    <input type="password" id="password" name="password" class="form-control" required placeholder="Masukkan kata sandi" style="padding-right: 48px;">
                    <button type="button" data-password-toggle data-target="password" aria-label="Tampilkan kata sandi" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; padding: 0; border: 0; background: transparent; color: #64748B; cursor: pointer;">
                        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"></path>
                            <circle cx="12" cy="12" r="2.5"></circle>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-login">Masuk ke Dasbor</button>
        </form>
    </div>

    <script>
        document.querySelectorAll('[data-password-toggle]').forEach(function (button) {
            button.addEventListener('click', function () {
                const input = document.getElementById(button.dataset.target);
                const isVisible = input.type === 'text';
                input.type = isVisible ? 'password' : 'text';
                button.setAttribute('aria-label', isVisible ? 'Tampilkan kata sandi' : 'Sembunyikan kata sandi');
                button.setAttribute('aria-pressed', String(!isVisible));
            });
        });
    </script>

</body>
</html>
