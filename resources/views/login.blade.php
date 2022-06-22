<html lang="it">
  <head>
    <title>L'occhio tagliato</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href={{ asset("css/theme.css") }} rel="stylesheet">
    <link href={{ asset("css/login.css") }} rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Frijole&display=swap" rel="stylesheet">

    <script src={{ asset("js/login.js") }} defer="true"> </script>
  </head>

  <body>
    <div id="login-screen">
      <h1 id="site-name">L'occhio tagliato</h1>
      <form action= {{ asset('/login/check_credential') }} method="post" name="login">
        <div><input type="text" name="username" placeholder="Username"></div>
        <div><input type="password" name="password" placeholder="Password"></div>
        <div><input type="hidden" name="_token" value={{csrf_token()}}></div>
        <div><label>&nbsp;</label><input type="submit"></div>
      </form>
      @if (isset($wrong))
      <div><p>Nome utente o password non corretti!</p></div>
      @endif
      <div id="new-user">
        <p>Non sei registrato?</p>
        <a href="signup">Registrati!</a>
      </div>
    </div>
  </body>
</html>
