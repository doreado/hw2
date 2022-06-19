<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPic;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SignupController extends BaseAppController
{
  public function show()
  {
    if ($this->isLogged()) {
      return redirect('/home');
    }

    return view('signup');
  }
}
?>
