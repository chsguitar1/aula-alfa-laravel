<header style="display:flex; justify-content:space-between; align-items:center; padding:10px 20px; background:#f1f1f1;">
    <span>{{ auth()->user()->name ?? '' }}</span>
    <form action="{{ route('logout') }}" method="post" style="margin:0;">
        @csrf
        <button type="submit" class="btn btn-danger btn-sm">Sair</button>
    </form>
</header>
