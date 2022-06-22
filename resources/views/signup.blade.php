<html lang="it">
  <head>
    <title>L'occhio tagliato</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href={{ asset("css/theme.css") }} rel="stylesheet">
    <link href={{ asset("css/login.css") }} rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Frijole&display=swap" rel="stylesheet">

    <script src={{ asset("js/signup.js") }} defer="true"> </script>
  </head>

  <body>
    <div id="signup-screen">
      <h1 id='site-name'>L'occhio tagliato</h1>
      <h2>Crea il tuo account</h2>
      <form name="signup" method="post" action={{asset('/register')}} enctype="multipart/form-data">
        <p><input type="text" name="username" placeholder="Username"></p>
        <p><input type="text" name="name" placeholder="Nome"></p>
        <p><input type="text" name="surname" placeholder="Cognome"></p>
        <p><input type="email" name="email" placeholder="E-mail"></p>
        <p><input type="password" name="password" placeholder="Password"></p>
        <div><input type="hidden" name="_token" value={{csrf_token()}}></div>
        <p><label>Seleziona imagine di profilo: <input type="file" name="profile-image"></p>
        <p><label>Seleziona imagine di copertina: <input type="file" name="cover-image"> </p>
        <p><label>&nbsp;</label><input type="submit"></p>
      </form>
      <div id="new-user">
        <p>Hai già un account?</p>
        <a href="/login">Accedi!</a>
      </div>
    </div>
  </body>
</html>
