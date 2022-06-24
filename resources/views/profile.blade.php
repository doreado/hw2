@extends('layouts.base')

@section('head')
  @parent
  @section('title', 'Profile')
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href={{ asset("css/profile.css") }} rel="stylesheet">
  <script src={{ asset("js/utils.js") }} defer="true"></script>
  <script src={{ asset("js/layouts.js") }} defer="true"></script>
  <script src={{ asset("js/profile.js") }} defer="true"></script>
@endsection

@section('header')
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
@endsection

@section('section')
<div class="tab-row">
  <div class="tab-row-option selected" data-view-type="summary">Summary</div>
  <div class="tab-row-option" data-view-type="recently">Attività Recente</div>
  <div class="tab-row-option" data-view-type="preference">Preferenze</div>
</div>

<div class="tab-view">
  <div class="view hidden" data-view-type="preference">
    <div class="preference-container description">
      <h1>Chi sono</h1>
      @if (isset($prefs[0]->description) && (count($prefs[0]->directors) > 0))
      @foreach($prefs[0]->description as $description)
      <div class='preference-box'>
        <p class='preference' data-preference-type="description">{{ $description }}</p>
        @if ($am_i)
        <div class="x-icon-box" data-preference-type="description" data-preference="{{ $description}}">
          <img class="x-icon" src={{ asset("figures/x_icon_dark.png") }}>
        </div>
        @endif
      </div>
      @endforeach
      @elseif (!$am_i)
      <p>Non c'è nessuna descrizione</p>
      @endif
      @if ($am_i)
      <div class="hidden plus-icon-box">
        <img class="plus-icon" src={{ asset("figures/plus_icon_dark.png") }}>
      </div>
      <form class="hidden">
        <input type="text" name="pref" placeholder="Descriviti un po'">
        <input type="hidden" name="type" value="description">
        <input type="submit" value="Salva">
        <input type="hidden" name="_token" value={{csrf_token()}}>
      </form>
      @endif
    </div>

    <div class="preference-container directors">
    <h1>Registi preferiti</h1>
    @if (isset($prefs[0]->directors) && (count($prefs[0]->directors) > 0))
    @foreach($prefs[0]->directors as $director)
    <div class='preference-box'>
        <p class='preference'>{{ $director }}</p>
      @if ($am_i)
      <div class="x-icon-box" data-preference-type="directors" data-preference="{{ $director }}">
        <img class="x-icon" src={{ asset("figures/x_icon_dark.png") }}>
      </div>
      @endif
    </div>
    @endforeach
    @elseif (!$am_i)
    <p>Non ci sono registi preferiti da mostrare</p>
    @endif
    @if ($am_i)
    <div class="hidden plus-icon-box">
      <img class="plus-icon" src={{ asset("figures/plus_icon_dark.png") }}>
    </div>
    <form class="hidden">
      <input type="text" name="pref" placeholder="Nome di un regista">
      <input type="hidden" name="type" value="directors">
      <input type="submit" value="Salva">
      <input type="hidden" name="_token" value={{csrf_token()}}>
    </form>
    @endif
    </div>

    <div class="preference-container movies">
    <h1>Film preferiti</h1>
    @if (isset($prefs[0]->movies) && (count($prefs[0]->movies) > 0))
    @foreach($prefs[0]->movies as $movie)
    <div class='preference-box'>
      <p class='preference'>{{ $movie }}</p>
      @if ($am_i)
      <div class="x-icon-box" data-preference-type="movies" data-preference="{{ $movie }}">
        <img class="x-icon" src={{ asset("figures/x_icon_dark.png") }}>
      </div>
      @endif
    </div>
    @endforeach
    @elseif (!$am_i)
    <p>Non ci sono film preferiti da mostrare</p>
    @endif
    @if ($am_i)
    <div class="hidden plus-icon-box">
      <img class="plus-icon" src={{ asset("figures/plus_icon_dark.png") }}>
    </div>
    <form class="hidden">
      <input type="text" name="pref" placeholder="Movie name">
      <input type="hidden" name="type" value="movies">
      <input type="submit" value="Salva">
      <input type="hidden" name="_token" value={{csrf_token()}}>
    </form>
    @endif
    </div>

    <div class="preference-container genres">
    <h1>Genere favorito</h1>
    @if (isset($prefs[0]->genres) && (count($prefs[0]->genres) > 0))
    @foreach($prefs[0]->genres as $genre)
    <div class='preference-box'>
      <p class='preference'>{{ $genre }}</p>
      @if ($am_i)
      <div class="x-icon-box" data-preference-type="genres" data-preference="{{ $genre }}" >
        <img class="x-icon" src={{ asset("figures/x_icon_dark.png") }}>
      </div>
      @endif
    </div>
    @endforeach
    @elseif (!$am_i)
    <p>Non ci sono generi preferiti da mostrare</p>
    @endif
    @if ($am_i)
    <div class="hidden plus-icon-box">
      <img class="plus-icon" src={{ asset("figures/plus_icon_dark.png") }}>
    </div>
    <form class="hidden">
      <input type="text" name="pref" placeholder="Genere">
      <input type="hidden" name="type" value="genres">
      <input type="submit" value="Salva">
      <input type="hidden" name="_token" value={{csrf_token()}}>
    </form>
    @endif
    </div>

  </div>
</div>
@endsection
