<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('code') · @yield('heading')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800&display=swap" rel="stylesheet">
    <style>body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;background:#f4f7f6;color:#0f2e2a}.code{font-size:6rem;font-weight:800;color:#0e6b5c;line-height:1}</style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 text-center px-3">
    <div>
        <div class="code">@yield('code')</div>
        <h1 class="h3 mt-2">@yield('heading')</h1>
        <p class="text-muted">@yield('message')</p>
        <a href="{{ url('/') }}" class="btn btn-dark">Kembali ke beranda</a>
    </div>
</body>
</html>
