<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPic;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Routing\Controller as BaseController;

class SignupController extends BaseController
{
  private function isLogged() {
    if (session()->has(['username', 'user_id'])) {
      return redirect('/home');
    }
  }

  public function show()
  {
    $this->isLogged();

    return view('signup');
  }

  public function add_user() {
    $this->isLogged();

    $request = request()->all();

    // TODO VALIDATION

    $user = new User;
    $user->username = $request['username'];
    $user->name = $request['name'];
    $user->surname = $request['surname'];
    $user->email = $request['email'];
    $user->password = $request['password'];
    $user->time = Carbon::now();
    $user->save();

    $user_pics = new UserPic;
    $user_pics->user = $user->id;
    $user_pics->profile_pic = file_get_contents($request['profile-image']);
    $user_pics->cover_pic = file_get_contents($request['cover-image']);
    $user_pics->save();

    session(['user_id' => $user->id, 'username' => $user->username]);
    return redirect('/home');
  }
}
?>
