<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Rijschool Management')</title>
    <!-- Fonts and Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('styles')
</head>
<body>

    <header>
        <div class="nav-container">
            <a href="{{ route('instructeur.index') }}" class="logo">
                <i class="fa-solid fa-car-side"></i>
                <span>Rijschool <strong>El Yassidi</strong></span>
            </a>
            <nav>
                <ul>
                    <li>
                        <a href="{{ route('instructeur.index') }}" class="{{ request()->routeIs('instructeur.index') || request()->routeIs('instructeur.edit') ? 'active' : '' }}">
                            <i class="fa-solid fa-user-tie"></i> Instructeurs
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('voertuigen.beschikbaar') }}" class="{{ request()->routeIs('voertuigen.beschikbaar') ? 'active' : '' }}">
                            <i class="fa-solid fa-car"></i> Beschikbare Voertuigen
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div>
                    <strong style="display: block; margin-bottom: 0.25rem;">Er zijn fouten opgetreden:</strong>
                    <ul style="margin-left: 1.25rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer>
        <div class="footer-container">
            <p>&copy; {{ date('Y') }} Rijschool Management System. Ontwikkeld door <a href="#">Andrei Gwn</a>.</p>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
