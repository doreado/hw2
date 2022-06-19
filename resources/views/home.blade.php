@extends('layouts.base')

@section('title', 'Home')

@section('head')
  @parent
  <link href={{ asset("css/home.css") }} rel="stylesheet">
  <script src="js/layouts.js" defer="true"></script>
  <script src="js/home.js" defer="true"></script>
@endsection

@section('link-desc', 'Benvenuto, ')
@section('nav-link', '/profile/'.$user_id )
@section('link-label', $username)

@section('section')
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
@endsection
