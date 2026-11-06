<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>AdminLTE v4 | Dashboard</title>
    @vite('resources/scss/app.scss')
    @stack('styles')
</head>
<body class="@yield('classe-body')">
@vite('resources/js/app.js')    
@yield('content')
</body>
</html>
