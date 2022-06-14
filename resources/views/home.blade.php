<!DOCTYPE html>
<html lang="it">
  <head>
    <title>Home</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href={{ asset("css/theme.css") }} rel="stylesheet">
    <link href={{ asset("css/home.css") }} rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Frijole&display=swap" rel="stylesheet">

    <script src="js/layouts.js" defer="true"></script>
    <script src="js/home.js" defer="true"></script>
  </head>
  <body>
  </body>

  <section>
    <div id="home-header">
      <div id="profile-pic">
        <img src=
          @if (!is_null($profile_pic))
          {{ "data:image/jpg;charset=utf8;base64,".$profile_pic }}
          @else {{ asset('figures/fallback_profile_icon.png') }}
          @endif>
      </div>
      <div id="home-header-right">
        <div class="tab-row">
          <div class="tab-row-option selected home-header-icon" data-view-type="movie">
            <img src= {{ asset('figures/movie.png') }}>
          </div>
          <div class="tab-row-option home-header-icon" data-view-type="people">
            <img src= {{ asset('figures/people_dark.png') }}>
          </div>
        </div>
      </div>
    </div>

    <div id="home-posts">
      <div id="home-posts-visible">
      </div>
    </div>
  </section>
</html>
