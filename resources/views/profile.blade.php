@extends('layouts.base')

@section('head')
  @parent
  @section('title', 'Profile')
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
</div>
<div class="tab-view"></div>
@endsection
