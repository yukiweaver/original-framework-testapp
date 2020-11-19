<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    @if (isset($title))
    {{ $title }}
    @else
    {{ 'MiniBlog' }}
    @endif
  </title>
</head>
<body>
  <div id="header">
    <h1><a href="{{ $base_url }}">Mini Blog</a></h1>
  </div>
  <div id="main">
    @yield('content')
  </div>
</body>
</html>