<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Marketplace Katering') · {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --ink:#0f2e2a; --brand:#0e6b5c; --brand-dark:#0a4f44; --accent:#f4a300; --paper:#f4f7f6; --line:#dde5e2; }
        body { font-family:'Plus Jakarta Sans',system-ui,sans-serif; background:var(--paper); color:var(--ink); }
        h1,h2,h3,h4,h5 { font-weight:700; letter-spacing:-.01em; }
        .navbar-app { background:var(--ink); }
        .navbar-app .nav-link { color:#cfe0db; }
        .navbar-app .nav-link:hover, .navbar-app .nav-link.active { color:#fff; }
        .brand-mark { color:var(--accent); }
        .btn-brand { background:var(--brand); border-color:var(--brand); color:#fff; font-weight:600; }
        .btn-brand:hover, .btn-brand:focus { background:var(--brand-dark); border-color:var(--brand-dark); color:#fff; }
        .btn-outline-brand { border-color:var(--brand); color:var(--brand); font-weight:600; }
        .btn-outline-brand:hover { background:var(--brand); color:#fff; }
        .card { border:1px solid var(--line); border-radius:.9rem; box-shadow:none; }
        .form-control:focus, .form-select:focus { border-color:var(--brand); box-shadow:0 0 0 .2rem rgba(14,107,92,.15); }
        .menu-photo { width:100%; aspect-ratio:4/3; object-fit:cover; border-radius:.9rem .9rem 0 0; background:#e8efec; }
        .menu-photo-empty { display:flex; align-items:center; justify-content:center; color:#8aa39c; font-size:2.2rem; }
        .stat { font-size:1.7rem; font-weight:800; }
        .table > :not(caption) > * > * { padding:.8rem .75rem; }
        .price { font-weight:700; color:var(--brand-dark); }
        .hero { background:var(--ink); color:#fff; border-radius:1.2rem; }
        .hero .lead { color:#cfe0db; }
        .sticky-summary { position:sticky; top:1rem; }
        @media print {
            .no-print { display:none !important; }
            body { background:#fff; }
            .card { border:0; }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark navbar-app no-print">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}"><i class="bi bi-basket2-fill brand-mark me-1"></i>Marketplace Katering</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-label="Menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav me-auto">
                @auth
                    @if(auth()->user()->isMerchant())
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('merchant.dashboard') ? 'active' : '' }}" href="{{ route('merchant.dashboard') }}">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('merchant.menus.*') ? 'active' : '' }}" href="{{ route('merchant.menus.index') }}">Menu</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('merchant.orders.*') ? 'active' : '' }}" href="{{ route('merchant.orders.index') }}">Pesanan</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('merchant.profile.*') ? 'active' : '' }}" href="{{ route('merchant.profile.edit') }}">Profil</a></li>
                    @else
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('customer.caterings.*') ? 'active' : '' }}" href="{{ route('customer.caterings.index') }}">Cari Katering</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('customer.orders.*') ? 'active' : '' }}" href="{{ route('customer.orders.index') }}">Pesanan Saya</a></li>
                    @endif
                @endauth
            </ul>
            <ul class="navbar-nav align-items-lg-center gap-lg-2">
                @auth
                    <li class="nav-item text-white-50 small me-lg-2">{{ auth()->user()->name }}</li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-sm btn-outline-light" type="submit"><i class="bi bi-box-arrow-right me-1"></i>Keluar</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Masuk</a></li>
                    <li class="nav-item"><a class="btn btn-sm btn-warning fw-semibold" href="{{ route('register.customer') }}">Daftar Kantor</a></li>
                    <li class="nav-item"><a class="btn btn-sm btn-outline-light" href="{{ route('register.merchant') }}">Daftar Katering</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4">
    @include('partials.alerts')
    @yield('content')
</main>

<footer class="container py-4 text-center text-muted small no-print">
    &copy; {{ date('Y') }} Marketplace Katering — makan siang kantor, langsung dari dapur katering.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
