<!DOCTYPE html>
<html lang="it">
  <head>
    <title>Home</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href={{ asset("css/theme.css") }} }} rel="stylesheet">
    <!-- <link href={{ asset("css/home.css") }} }} rel="stylesheet"> -->
    <link href={{ asset("css/profile.css") }} }} rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Frijole&display=swap" rel="stylesheet">

    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <script src={{ asset("js/utils.js") }} defer="true"></script>
    <script src={{ asset("js/layouts.js") }} defer="true"></script>
    <script src={{ asset("js/profile.js") }} defer="true"></script>
  </head>
  <body>
  <header>
    @if ($cover_pic)
    <div id="cover" style="background-image:url('{{ 'data:image/jpg;charset=utf8;base64,'.$cover_pic }}')"></div>
    @endif

    <div id="profile-pic"><img src=
    @if ($profile_pic) {{ 'data:image/jpg;charset=utf8;base64,'.$profile_pic }}
    @else {{ asset('figures/fallback_profile_icon.png') }}
    @endif>
</div>

    <div id="username">{{ $username }}
      @if (!$am_i)
      <div class='icon-box'><img class='icon' data-followed='{{ $followed }}'
        src=
        @if($followed == "true")
        {{ asset('figures/followed_dark.png') }}
        @else {{ asset('figures/not_followed_dark.png') }}
        @endif>
      </div>
      @endif
    </div>
  </header>

  <section>
    <div class="tab-row">
      <div class="tab-row-option selected" data-view-type="summary">Summary</div>
      <div class="tab-row-option" data-view-type="recently">Attività Recente</div>
    </div>
    <div class="tab-view"></div>
  </section
  </body>
</html>
