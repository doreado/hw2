<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use function GuzzleHttp\json_encode;
use function redirect;
use function request;

class HomeController extends BaseController
{
  public function getHome()
  {
    if (!session()->has(['username', 'user_id'])) {
      return redirect('/login');
    }

    $user_id = session()->get('user_id');
    $profile_pic = UserPic::where('user', $user_id)->get('profile_pic')->first();
    return view('home')->with('profile_pic', base64_encode($profile_pic->profile_pic));
  }
}
?>
