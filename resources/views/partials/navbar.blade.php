<nav>
    <div class="nav-inner">
        <a class="logo" href="{{ url('/') }}">
            <img src="{{ asset('img/logo.png') }}" alt="Hero Image" class="logo-nav">
            <span class="logo-name">LENTERA</span>
        </a>

        <ul class="nav-links">
            <li>
                <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Beranda</a>
            </li>
            <li>
                <a href="{{ url('/tentang') }}" class="{{ request()->is('tentang') ? 'active' : '' }}">Tentang</a>
            </li>
        </ul>

        <div class="nav-actions">
            @guest
            <a href="{{ route('login') }}" class="btn-ghost">Masuk</a>
            <a href="{{ route('login') }}" class="btn-primary">Mulai Analisis</a>
            @else
            <a href="{{ route(Auth::user()->role->nama_role . '.dashboard') }}" class="btn-ghost">
                {{ Auth::user()->nama }}
            </a>

            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn-primary" style="cursor: pointer;">Keluar</button>
            </form>
            @endguest
        </div>
    </div>
</nav>