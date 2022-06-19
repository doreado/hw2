<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class HomeController extends BaseAppController
{
  public function getHome()
  {
    if (!$this->isLogged()) {
      return redirect('/login');
    }

    $user_id = session('user_id');
    $profile_pic = UserPic::where('user', $user_id)->get('profile_pic')->first();
    if ($profile_pic) $profile_pic = base64_encode($profile_pic->profile_pic);
    $view_param = [
      'profile_pic' => $profile_pic,
      'user_id' => $user_id,
      'username' => session('username'),
    ];
    return view('home', $view_param);
  }
}
?>
