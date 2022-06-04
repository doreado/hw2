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
  public function show()
  {
    if (session()->has(['username', 'user_id'])) {
      return redirect('/home');
    }

    return view('signup');
  }
}
?>
