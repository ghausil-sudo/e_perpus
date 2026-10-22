<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login') - E-Perpus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <style>
        :root {
            --color-primary: #2563eb;
            --color-danger: #ef4444;
            --color-text: #1f2937;
            --color-muted: #6b7280;
            --color-border: #eef0f6;
            --color-background: #f4f5fa;
            --color-surface: #ffffff;
            --radius-card: 18px;
            --radius-control: 10px;
            --shadow-card: 0 1px 2px rgba(16, 24, 40, .04), 0 12px 32px rgba(16, 24, 40, .08);
        }
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; color: var(--color-text); background: var(--color-background); -webkit-font-smoothing: antialiased; }
        body > div.min-h-screen {
            min-height: 100vh;
            padding: 24px 16px;
            background:
                radial-gradient(ellipse at 12% 8%, rgba(219, 234, 254, .78), transparent 35%),
                radial-gradient(ellipse at 92% 90%, rgba(224, 231, 255, .45), transparent 32%),
                var(--color-background);
        }
        body > div.min-h-screen > div {
            width: 100%;
            max-width: 440px;
            padding: 40px;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-card);
            background: var(--color-surface);
            box-shadow: var(--shadow-card);
            animation: login-card-enter 300ms cubic-bezier(.2, .8, .2, 1) both;
        }
        @keyframes login-card-enter {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .input {
            width: 100%;
            min-height: 44px;
            padding: 0.65rem 0.875rem;
            font-size: 0.9rem;
            color: #344054;
            background-color: #fff;
            border: 1px solid #e5e7eb;
            border-radius: var(--radius-control);
            transition: border-color 150ms ease, box-shadow 150ms ease;
        }
        .input::placeholder { color: #98a2b3; }
        .input:focus { outline: none; border-color: var(--color-primary); box-shadow: 0 0 0 4px rgba(37, 99, 235, .14); }
        .select2-container { width: 100% !important; }
        .select2-container .select2-selection--single { height: 44px; border: 1px solid #e5e7eb; border-radius: var(--radius-control); }
        .select2-container--default .select2-selection--single .select2-selection__rendered { padding: 7px 14px; color: #344054; line-height: 28px; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 42px; right: 8px; }
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single { border-color: var(--color-primary); box-shadow: 0 0 0 4px rgba(37, 99, 235, .14); }
        .input:-webkit-autofill,
        .input:-webkit-autofill:hover,
        .input:-webkit-autofill:focus {
            -webkit-text-fill-color: #344054;
            box-shadow: 0 0 0 1000px #fff inset, 0 0 0 4px rgba(37, 99, 235, .12);
            transition: background-color 9999s ease-out;
        }
        .label { display: block; font-size: 0.8125rem; font-weight: 500; color: #374151; margin-bottom: 0.4rem; }
        input[type="checkbox"] { width: 18px; height: 18px; accent-color: var(--color-primary); border-radius: 5px; }
        .btn { display: inline-flex; min-height: 46px; align-items: center; justify-content: center; padding: 0.7rem 1.25rem; font-size: 0.9375rem; font-weight: 600; border-radius: var(--radius-control); transition: background 150ms ease, box-shadow 150ms ease, transform 150ms ease; cursor: pointer; border: none; text-decoration: none; }
        .btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #ffffff; box-shadow: 0 5px 12px rgba(37, 99, 235, .2); }
        .btn-primary:hover { background: linear-gradient(135deg, #1d4ed8, #1e40af); box-shadow: 0 7px 16px rgba(37, 99, 235, .25); transform: translateY(-1px); }
        .btn-primary:active { transform: translateY(0); }
        .btn-primary:focus-visible,
        input[type="checkbox"]:focus-visible { outline: 3px solid rgba(37, 99, 235, .35); outline-offset: 3px; }
        .login-brand { margin-bottom: 28px !important; }
        .login-brand img,
        .login-brand > div {
            width: 72px !important;
            height: 72px !important;
            margin-bottom: 18px !important;
            border: 1px solid var(--color-border) !important;
            border-radius: 50% !important;
            box-shadow: 0 4px 12px rgba(16, 24, 40, .08) !important;
        }
        .login-brand img { padding: 8px !important; }
        .login-brand > div { background: var(--color-primary) !important; }
        .login-brand h1 { color: var(--color-text); font-size: 1.75rem; font-weight: 700; letter-spacing: -.045em; }
        .login-brand p { margin-top: 8px; color: var(--color-muted); font-size: .875rem; }
        .login-error { border: 1px solid #fecaca; border-radius: var(--radius-control); background: #fef2f2; color: #b91c1c; font-size: .8125rem; }
        .login-form { gap: 20px; }
        .login-form > div { min-width: 0; }
        @media (max-width: 480px) {
            body > div.min-h-screen { padding: 16px; }
            body > div.min-h-screen > div { padding: 24px; border-radius: 16px; box-shadow: 0 1px 2px rgba(16, 24, 40, .04), 0 8px 22px rgba(16, 24, 40, .06); }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col justify-center items-center px-4">
        <div class="w-full max-w-sm">
            @yield('content')
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        (function () {
            const options = {
                width: '100%',
                minimumResultsForSearch: 0,
                placeholder: 'Ketik untuk mencari',
            };

            function initializeSelects(root) {
                const selects = root.matches && root.matches('select')
                    ? $(root)
                    : $(root).find('select');

                selects.filter(function () {
                    return !$(this).hasClass('select2-hidden-accessible')
                        && !$(this).is('[data-select2="false"]');
                }).select2(options);
            }

            initializeSelects(document);
        })();
    </script>
    @yield('page_scripts')
</body>
</html>
