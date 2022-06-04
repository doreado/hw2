<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use function redirect;
use function request;

class LogoutController extends BaseController {
  public function logout()
  {
    if (session()->has(['username', 'user_id'])) {
      session()->flush();
      return redirect('/login');
    }
  }
}
?>
