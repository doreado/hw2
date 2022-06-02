<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use function redirect;
use function request;

class LoginController extends BaseController
{
  private function isLogged() {
    if (session()->has(['username', 'user_id'])) {
      return redirect('/home');
    }
  }

  public function show()
  {
    $this->isLogged();

    return view('login');
  }

  public function checkCredential() {
    $this->isLogged();

    $request = request()->post();
    if (request()->missing(['username', 'password'])) {
        return redirect('login');
    }

    $id = User::where('username', $request['username'])
        ->where('password', $request['password'])->get('id')->first();
    if ($id) {
      session(['user_id' => $id['id'], 'username' => $request['username']]);
      return ['success' => true, 'route' => 'http://localhost:8000/home'];
    }

    return ['success' => false];
  }
}
