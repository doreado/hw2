<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Routing\Controller as BaseController;

class ProfileController extends BaseController
{
  public function showProfile(int $user_id)
  {
    if (!session()->has(['user_id', 'username'])) {
      return redirect('/login');
    }

    $logged_id = session()->get('user_id');
    $am_i = $logged_id == $user_id;
    if (!$am_i) {
      session()->put('profile', $user_id);
    } else {
      session()->remove('profile');
    }

    $user = User::find($user_id);

    $has_cover_pic = $has_profile_pic = false;
    if (!is_null($user->UserPics)) {
      $has_cover_pic = !is_null($user->UserPics->cover_pic);
      $has_profile_pic = !is_null($user->UserPics->profile_pic);
    }

    $profile_pic = $has_profile_pic ? base64_encode($user->UserPics->profile_pic) : null;
    $cover_pic = $has_cover_pic ? base64_encode($user->UserPics->cover_pic): null;

    $followed = false;
    if (!$am_i && $user->following()) {
      $followed = $user->follower()->where('follower', $logged_id)->first() != null;
    }

    $par = [
      'username' => $user->username,
      'am_i' => $am_i,
      'followed' => $followed ? "true" : "false",

      'cover_pic' => $cover_pic,
      'profile_pic' => $profile_pic,
    ];

    return view('profile', $par);
  }
}
