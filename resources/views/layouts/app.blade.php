<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>E-Commerce Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: #f8fafc;
            font-family: 'Poppins', sans-serif;
        }
        nav {
            background: #0d6efd;
        }
        .navbar-brand, .nav-link, .dropdown-item {
            color: white !important;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="{{ url('/') }}">E-Commerce</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        @auth
        <li class="nav-item"><a href="" class="nav-link">Cart</a></li>
        <li class="nav-item"><a href="" class="nav-link">My Orders</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">{{ Auth::user()->name }}</a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="dropdown-item w-100 text-start">
                        {{ __('Log Out') }}
                    </button>
                </form></li>
          </ul>
        </li>
        @else
        <li class="nav-item"><a href="{{ url('/login') }}" class="nav-link">Login</a></li>
        <li class="nav-item"><a href="{{ url('/register') }}" class="nav-link">Register</a></li>
        @endauth
      </ul>
    </div>
  </div>
</nav>

<div class="container py-4">
    @yield('content')
</div>

</body>
</html>
