<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-light sidebar sidebar-light accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('welcome') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-car"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Wypożyczalnia</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    @if(auth()->check() && auth()->user()->role === 'admin')
        {{-- <!-- Linki tylko dla admina -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-fw fa-cogs"></i>
                <span>Panel Administratora</span>
            </a>
        </li> --}}
        
        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Zarządzanie
        </div>

        <!-- Nav Items -->
        <li class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('users.index') }}">
                <i class="fas fa-fw fa-users"></i>
                <span>Użytkownicy</span></a>
        </li>

        <li class="nav-item {{ request()->routeIs('cars.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('cars.index') }}">
                <i class="fas fa-fw fa-car-side"></i>
                <span>Samochody</span></a>
        </li>

        <li class="nav-item {{ request()->routeIs('reservations.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('reservations.index') }}">
                <i class="fas fa-fw fa-calendar-alt"></i>
                <span>Rezerwacje</span></a>
        </li>

        <li class="nav-item {{ request()->routeIs('rentals.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('rentals.index') }}">
                <i class="fas fa-fw fa-clipboard-list"></i>
                <span>Wypożyczenia</span></a>
        </li>

        <li class="nav-item {{ request()->routeIs('payments.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('payments.index') }}">
                <i class="fas fa-fw fa-money-bill-wave"></i>
                <span>Płatności</span></a>
        </li>

    @endif

    

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

</ul>
<!-- End of Sidebar -->
