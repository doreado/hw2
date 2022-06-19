<!DOCTYPE html>
<html lang="it">
  <head>
    @section('head')
    <title>@yield('title')</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href={{ asset("css/theme.css") }} rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Frijole&display=swap" rel="stylesheet">
    @show
  </head>

  <body>
    <nav>
      <div id="title-link">
        <div id="site-name">L'occhio tagliato</div>
        <div>
          @yield('link-desc')
          <a href="@yield('nav-link', '/home')">@yield('link-label', 'HOME')</a>
        </div>
      </div>
      <div>
        <a href="/logout">LOGOUT</a>
      </div>
    </nav>
    <header>@yield('header')</header>
    <section>@yield('section')</section>
  </body>
</html>
