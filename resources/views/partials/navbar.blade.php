<nav>
    <div class="nav-inner">
        <a class="logo" href="{{ url('/') }}">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2L15 8.5L22 9.5L17 14.5L18.5 21.5L12 18L5.5 21.5L7 14.5L2 9.5L9 8.5L12 2Z" stroke="currentColor" class="nav-logo-img" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
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