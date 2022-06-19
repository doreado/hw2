<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class LoginController extends BaseAppController
{
  public function show()
  {
    if ($this->isLogged()) {
      return redirect('/home');
    }

    return view('login');
  }

  public function checkCredential() {
    if ($this->isLogged()) {
      return redirect('/home');
    }

    $request = request()->post();
    if (request()->missing(['username', 'password'])) {
        return redirect('login');
    }

    $id = User::where('username', $request['username'])
        ->where('password', $request['password'])->get('id')->first();

    if ($id) {
      session(['user_id' => $id['id'], 'username' => $request['username']]);
      return redirect('/home');
    }

    return redirect('/login');
  }
}
