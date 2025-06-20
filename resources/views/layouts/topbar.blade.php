<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <ul class="navbar-nav ml-auto">

        @if(Auth::check())
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                    <img class="img-profile rounded-circle"
                         src="{{ Auth::user()->profile_photo ? asset('storage/profile_photos/' . Auth::user()->profile_photo) : asset('storage/profile_photos/def.jpg') }}"
                         style="width: 32px; height: 32px;">

                    <span class="ml-2 d-none d-lg-inline text-gray-600 small">
                        {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                    </span>
                </a>

                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                     aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Mój profil
                    </a>

                    <div class="dropdown-divider"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Wyloguj
                        </button>
                    </form>
                </div>
            </li>
        @else
            <!-- Jeśli użytkownik NIE jest zalogowany -->

            <li class="nav-item">
                <a class="nav-link btn btn-outline-primary mr-2" href="{{ route('login') }}">
                    <i class="fas fa-sign-in-alt"></i> Zaloguj się
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link btn btn-primary" href="{{ route('register') }}">
                    <i class="fas fa-user-plus"></i> Zarejestruj się
                </a>
            </li>

        @endif

    </ul>

</nav>
