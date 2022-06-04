<?php
namespace App\Http\Controllers;

use App\Models\Follow;
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

  public function isFollowed(string $other_id)
  {
    if (!session()->has(['username', 'user_id'])) {
      redirect('/login');
    }

    $user_id = session()->get('user_id');
    $is_followed = Follow::where('follower', $user_id)->where('following', $other_id)->get()->first();
    $is_followed= !is_null($is_followed);
    return ['is_followed' => $is_followed];
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
    if (!session()->has(['username', 'user_id'])) {
      redirect('/login');
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

  public function getUsername(int $user_id)
  {
    if (!session()->has(['username', 'user_id'])) {
      redirect('/login');
    }

    // check if exists
    $username = User::find($user_id);
    if ($username != null) {
      $success = true;
      $username = $username->get('username')->first()['username'];
    } else {
      $success = false;
    }

    echo json_encode( ['success' => $success, 'username' => $username] );
  }

  public function getPics(int $user_id)
  {
    if (!session()->has(['username', 'user_id'])) {
      redirect('/login');
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

  public function searchUsers(string $user_query)
  {
    if (!session()->has(['username', 'user_id'])) {
      redirect('/login');
    }

    $user_id = session()->get('user_id');
    $users = User::where('username', 'like', '%' .$user_query. '%')
      ->where('id', '!=', $user_id)->limit('10')->get();
    $success = !is_null($users->first);
    $data = array();
    foreach ($users as $user) {
      $pic =  $user->userPics->toArray();
      $data[] = [
        'id' => $user->id,
        'username' => $user->username,
        'profile_pic' => base64_encode($pic['profile_pic']),
        'cover_pic' => base64_encode($pic['cover_pic']),
        // 'followed' => followed($db, $row->id)
      ];
    }
    return ['success' => $success, 'data' => $data];
  }

  public function toggleFollow(int $following_id, string $to_follow)
  {
    if (!session()->has(['username', 'user_id'])) {
      redirect('/login');
    }

    $followed = is_null($following_id) && session()->has('profile') ?
        session()->get('profile') : $following_id;

    $follower = session()->get('user_id');
    if ($to_follow == 'true') {
      $follow = new Follow;
      $follow->follower = $follower;
      $follow->following = $followed;
      $follow->save();
    } else {
      Follow::where('follower', $follower)->where('following', $followed)->delete();
    }

    echo json_encode(['success' => true]);
  }
}
?>
