<?php
namespace App\Http\Controllers;

use App\Models\User;
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
}
?>
