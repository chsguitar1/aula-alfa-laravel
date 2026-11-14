<div class="app-content-header">
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif
        <h3 class="mb-0">@yield('title', 'Painel')</h3>
    </div>
</div>
