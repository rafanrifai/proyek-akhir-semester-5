<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - SIM-BMN KEMENAG MINAHASA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
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
            --success: #059669;
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
            font-size: 13px;
            color: var(--text-secondary);
            line-height: 1.5;
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
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
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

        .alert-success {
            background-color: #DCFCE7;
            color: var(--success);
            padding: 12px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            border: 1px solid #A7F3D0;
        }

        .accent-line {
            height: 4px;
            width: 60px;
            background-color: var(--gold-accent);
            margin: 15px auto 0;
            border-radius: 4px;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
        }
        .back-link:hover {
            color: var(--primary);
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-header">
            <h1>SIM-BMN KEMENAG MINAHASA</h1>
            <div class="accent-line"></div>
            <p>Lupa Kata Sandi? Masukkan email Anda dan kami akan mengirimkan tautan untuk membuat kata sandi baru.</p>
        </div>

        @if(session('status'))
            <div class="alert-success">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group">
                <label for="email">Alamat Email Terdaftar</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="contoh: budi@simbmn.com">
            </div>

            <button type="submit" class="btn-login"><i class="ph ph-envelope-simple"></i> Kirim Tautan Reset</button>
        </form>
        
        <a href="{{ route('login') }}" class="back-link">&larr; Kembali ke halaman Login</a>
    </div>

</body>
</html>
