<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Authentification') - SketchConvert</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    
    <style>
        /* ===== PALETTE SKETCHUP ===== */
        :root {
            --sketchup-primary: #3150BF;
            --sketchup-primary-dark: #17399eff;
            --sketchup-primary-light: #2763d1ff;
            --sketchup-secondary: #F0F4F5;
            --sketchup-accent: #224ea7ff;
            --sketchup-text: #3150BF;
            --sketchup-border: #d1dde3;
            --sketchup-shadow: #1b338dff(31, 142, 183, 0.15);
        }

        body {
            background: linear-gradient(135deg, #f8fbfc 0%, var(--sketchup-secondary) 100%);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: var(--sketchup-text);
        }
        
        /* ===== BUTTONS ===== */
        .btn-primary {
            background: linear-gradient(135deg, var(--sketchup-primary) 0%, var(--sketchup-primary-dark) 100%);
            border: none;
            font-weight: 500;
            letter-spacing: 0.3px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px var(--sketchup-shadow);
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background: linear-gradient(135deg, var(--sketchup-primary-dark) 0%, var(--sketchup-accent) 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px var(--sketchup-shadow);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-outline-secondary {
            border-color: var(--sketchup-border);
            color: var(--sketchup-text);
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background-color: var(--sketchup-secondary);
            border-color: var(--sketchup-primary);
            color: var(--sketchup-primary);
        }
        
        /* ===== LINKS ===== */
        .link-primary {
            color: var(--sketchup-primary) !important;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .link-primary:hover {
            color: var(--sketchup-primary-dark) !important;
            text-decoration: underline;
        }
        
        /* ===== FORMS ===== */
        .form-control {
            border: 2px solid var(--sketchup-border);
            border-radius: 8px;
            transition: all 0.3s ease;
            background-color: #fff;
        }

        .form-control:focus {
            border-color: var(--sketchup-primary);
            box-shadow: 0 0 0 3px var(--sketchup-shadow);
            background-color: #fff;
        }

        .form-control.is-valid {
            border-color: #28a745;
            background-image: none;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
            background-image: none;
        }

        .form-control-lg {
            padding: 0.875rem 1.125rem;
        }
        
        .form-outline {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        .form-outline .form-label {
            position: absolute;
            top: 0.875rem;
            left: 1.125rem;
            transition: all 0.2s ease;
            pointer-events: none;
            color: #6c757d;
            background: #fff;
            padding: 0 0.4rem;
            font-weight: 500;
        }
        
        .form-outline .form-control:focus ~ .form-label,
        .form-outline .form-control:not(:placeholder-shown) ~ .form-label {
            transform: translateY(-1.75rem) scale(0.85);
            color: var(--sketchup-primary);
        }

        .form-check-input:checked {
            background-color: var(--sketchup-primary);
            border-color: var(--sketchup-primary);
        }

        .form-check-input:focus {
            border-color: var(--sketchup-primary);
            box-shadow: 0 0 0 0.2rem var(--sketchup-shadow);
        }
        
        /* ===== ALERTS ===== */
        .alert {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .alert-info {
            background-color: #e7f5f9;
            color: var(--sketchup-accent);
            border-left: 4px solid var(--sketchup-primary);
        }

        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border-left: 4px solid #ffc107;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        /* ===== LAYOUT ===== */
        .h-custom {
           
            justify-content: center; /* centre horizontalement */
            align-items: center;     /* centre verticalement */
            height: 100vh; 
        }
        
        @media (max-width: 450px) {
            .h-custom {
                min-height: 100vh;
            }
        }


        .hero-img {
            filter: drop-shadow(0 10px 30px rgba(0,0,0,0.1));
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        
        /* ===== SPINNER ===== */
        .spinner-border {
            border-color: var(--sketchup-primary-light);
            border-right-color: transparent;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            border-width: 0.15em;
        }
        
        /* ===== TYPOGRAPHY ===== */
        h3 {
            color: var(--sketchup-text);
            font-weight: 700;
        }

        .text-muted {
            color: #6c757d !important;
            font-size: 0.875rem;
        }

        /* ===== INPUT GROUP ===== */
        .input-group .btn {
            border-radius: 0 8px 8px 0;
        }

        .input-group .form-control {
            border-radius: 8px 0 0 8px;
        }

        /* ===== UTILITIES ===== */
        .bg-primary {
            background-color: var(--sketchup-primary) !important;
        }

        .text-primary {
            color: var(--sketchup-primary) !important;
        }

        .border-primary {
            border-color: var(--sketchup-primary) !important;
        }

        /* ===== SMOOTH ANIMATIONS ===== */
        * {
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        button, .btn, input[type="submit"] {
            cursor: pointer;
        }

        /* ===== INVALID FEEDBACK ===== */
        .invalid-feedback {
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    @yield('contents')
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- CSRF Token Setup -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>