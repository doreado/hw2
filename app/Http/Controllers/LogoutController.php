<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class LogoutController extends BaseAppController {
  public function logout()
  {
    if ($this->isLogged()) {
      session()->flush();
      return redirect('/login');
    }
  }
}
?>
