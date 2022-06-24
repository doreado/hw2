<?php

namespace App\Http\Controllers;

use App\Models\UserPref;
use Illuminate\Http\Request;

class UserPrefController extends BaseAppController
{
  public function addPreference()
  {
    if ($this->isLogged()) {
      // return redirect('/home');
    }

    $request = request()->post();
    if (request()->missing(['type', 'pref'])) {
        // return redirect('login');
    }

    $user_id = session('user_id');
    UserPref::where('user', strval($user_id))->push($request['type'], $request['pref']);
    // return redirect('/profile' + $user_id);
  }

  public function removePreference(string $type, string $preference)
  {
    if (!$this->isLogged()) {
      redirect('/login');
    }

    $user_id = session('user_id');
    UserPref::where('user', strval($user_id))->pull($type, $preference);
  }
}
