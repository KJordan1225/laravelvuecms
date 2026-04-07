<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'CMS' }}</title>
    @vite(['resources/css/app.css'])
    <style>
        .public-shell {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px;
        }

        .public-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            padding: 16px 0 28px;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 28px;
        }

        .public-brand a {
            color: #111827;
            font-size: 28px;
            font-weight: 800;
        }

        .public-tagline {
            color: #6b7280;
            margin-top: 6px;
        }

        .public-nav {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .public-nav a {
            color: #1f2937;
            font-weight: 600;
        }

        .hero {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 24px;
            padding: 28px;
            margin-bottom: 28px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        }

        .hero h1 {
            margin: 0 0 12px;
            font-size: 40px;
        }

        .public-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .public-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        }

        .public-card-image img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            display: block;
        }

        .public-card-body {
            padding: 18px;
        }

        .public-card-body h3 {
            margin: 0 0 10px;
        }

        .public-meta {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .public-content {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 24px;
            padding: 28px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        }

        .public-content img {
            max-width: 100%;
            border-radius: 16px;
        }

        .public-footer {
            margin-top: 40px;
            padding: 20px 0;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
        }

        .tag-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 12px;
        }

        .tag-pill {
            display: inline-flex;
            padding: 6px 10px;
            border-radius: 999px;
            background: #e0e7ff;
            color: #3730a3;
            font-size: 12px;
            font-weight: 700;
        }

        @media (max-width: 900px) {
            .public-grid {
                grid-template-columns: 1fr;
            }

            .public-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .hero h1 {
                font-size: 32px;
            }
        }
    </style>
</head>
<body>
    <div class="public-shell">
        <header class="public-header">
            <div class="public-brand">
                <a href="{{ route('home') }}">{{ \App\Models\Setting::getValue('site_name', 'My Laravel CMS') }}</a>
                <div class="public-tagline">
                    {{ \App\Models\Setting::getValue('site_tagline', 'A modern CMS') }}
                </div>
            </div>

            <nav class="public-nav">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('posts.index') }}">Posts</a>
            </nav>
        </header>

        @yield('content')

        <footer class="public-footer">
            &copy; {{ now()->year }} {{ \App\Models\Setting::getValue('site_name', 'My Laravel CMS') }}
        </footer>
    </div>
</body>
</html>
