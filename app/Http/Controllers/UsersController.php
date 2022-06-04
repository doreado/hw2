<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPic;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Routing\Controller as BaseController;

class UsersController extends BaseController
{
  public function isRegistered(string $key, string $value)
  {
    $exists = User::where($key, $value)->exists();
    return ['is_registered' => $exists];
  }

  public function whoami()
  {
    if (!session()->has(['username', 'user_id'])) {
      redirect('/login');
    }

    return ['id' => session()->get('user_id'),
        'username' => session()->get('username')];
  }

  public function addUser()
  {
    if (session()->has(['username', 'user_id'])) {
      redirect('/home');
    }

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

  public function getPics(string $user_id)
  {
    if (session()->has(['username', 'user_id'])) {
      redirect('/home');
    }

    $pics = UserPic::where('user', $user_id)->get()->first();

    $res = [ 'profile_pic' => [
                              'empty' => isset($pics->profile_pic) ? false : true,
                              'src' => base64_encode($pics->profile_pic)
                              ],
               'cover_pic' => [
                              'empty' => isset($pics->profile_pic) ? false : true,
                              'src' => base64_encode($pics->profile_pic)
                              ]
    ];

    return $res;
  }
}
?>
