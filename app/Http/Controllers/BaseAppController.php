<?php
namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\UserPic;
use App\Models\Watchlist;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class BaseAppController extends BaseController
{
  protected function isLogged(): bool
  {
    return session()->has(['username', 'user_id']);
  }
}
?>
