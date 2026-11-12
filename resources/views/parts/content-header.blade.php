<div class="app-content-header">
    <div class="container-fluid py-3">
        <h3>@yield('title', 'Sistema')</h3>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
    </div>
</div>
