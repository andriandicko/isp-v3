<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SPD LINK Quality Internet Solutions</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: #f9fafb;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .container {
            width: 100%;
            max-width: 448px;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            background-color: #4f46e5;
            border-radius: 1rem;
            margin-bottom: 1rem;
        }

        .logo-icon svg {
            width: 36px;
            height: 36px;
            color: white;
            stroke: currentColor;
            fill: none;
            stroke-width: 2.5;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.25rem;
        }

        .subtitle {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            padding: 2rem;
        }

        .card-header {
            margin-bottom: 1.5rem;
        }

        .card-header h2 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.25rem;
        }

        .card-header p {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .error-message {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        label {
            display: block;
            color: #374151;
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            border: 1px solid #d1d5db;
            padding: 0.625rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            color: #111827;
            transition: all 0.2s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        input::placeholder {
            color: #9ca3af;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 2.75rem;
        }

        .toggle-password {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            padding: 0;
            transition: color 0.2s;
        }

        .toggle-password:hover {
            color: #4b5563;
        }

        .toggle-password svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .hidden {
            display: none;
        }

        .remember-forgot {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
        }

        .remember-label {
            display: flex;
            align-items: center;
            cursor: pointer;
            color: #4b5563;
        }

        input[type="checkbox"] {
            width: 1rem;
            height: 1rem;
            margin-right: 0.5rem;
            cursor: pointer;
            accent-color: #4f46e5;
        }

        .forgot-link {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #4338ca;
        }

        .btn-primary {
            width: 100%;
            background-color: #4f46e5;
            color: white;
            font-weight: 500;
            padding: 0.625rem 1rem;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
            transition: background-color 0.2s;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .btn-primary:hover {
            background-color: #4338ca;
        }

        .divider {
            position: relative;
            margin: 1.5rem 0;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background-color: #e5e7eb;
        }

        .divider span {
            position: relative;
            display: inline-block;
            background: white;
            padding: 0 0.75rem;
            color: #6b7280;
            font-size: 0.75rem;
            left: 50%;
            transform: translateX(-50%);
        }

        .contact-buttons {
            display: flex;
            gap: 0.75rem;
        }

        .btn-contact {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.625rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            color: #374151;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .btn-contact:hover {
            background-color: #f9fafb;
        }

        .btn-contact svg {
            width: 20px;
            height: 20px;
            margin-right: 0.5rem;
        }

        .btn-contact.whatsapp svg {
            color: #25d366;
            fill: currentColor;
        }

        .btn-contact.email svg {
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #6b7280;
            font-size: 0.875rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Logo Section -->
        <div class="logo-section">
            <div class="logo-icon">
                <svg viewBox="0 0 24 24">
                    <path d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <h1>SPD-Link</h1>
            <p class="subtitle">Quality Internet Solutions</p>
        </div>

        <!-- Login Card -->
        <div class="card">
            <div class="card-header">
                <h2>Selamat Datang</h2>
                <p>Masuk ke akun Anda</p>
            </div>

            <!-- Error Message (shown if @errors exist) -->
            <!-- Uncomment this section in your Laravel blade file: -->
            @if ($errors->any())
                <div class="error-message">
                    {{ $errors->first() }}
                </div>
            @endif


            <form method="POST" action="{{ route('login.post') }}">
                <!-- CSRF Token (Laravel) -->
                @csrf

                <!-- Email/Phone/Billing Code Input -->
                <div class="form-group">
                    <label for="login">Email / Telepon / Kode Billing</label>
                    <input type="text" id="login" name="login"
                        placeholder="Masukkan email, telepon, atau kode billing" required>
                </div>

                <!-- Password Input -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <svg id="eye-icon" viewBox="0 0 24 24">
                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="eye-slash-icon" class="hidden" viewBox="0 0 24 24">
                                <path
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="remember-forgot">
                    <label class="remember-label">
                        <input type="checkbox" name="remember">
                        <span>Ingat saya</span>
                    </label>
                    <a href="#" class="forgot-link">Lupa password?</a>
                </div>

                <!-- Login Button -->
                <button type="submit" class="btn-primary">Masuk</button>
            </form>

            <!-- Divider -->
            <div class="divider">
                <span>Butuh bantuan?</span>
            </div>

            <!-- Contact Support -->
            <div class="contact-buttons">
                <a href="https://wa.me/6281234567890" target="_blank" class="btn-contact whatsapp">
                    <svg viewBox="0 0 24 24">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                    </svg>
                    WhatsApp
                </a>
                <a href="mailto:support@spd-link.com" class="btn-contact email">
                    <svg viewBox="0 0 24 24">
                        <path
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Email
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; 2024 SPD-Link. All rights reserved.</p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeSlashIcon = document.getElementById('eye-slash-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeSlashIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeSlashIcon.classList.add('hidden');
            }
        }
    </script>
</body>

</html>
