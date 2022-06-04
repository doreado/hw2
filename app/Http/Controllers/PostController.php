<?php
namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\UserPic;
use App\Models\Watchlist;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use function GuzzleHttp\json_encode;
use function redirect;
use function request;
use function urldecode;

class PostController extends BaseController
{
  public function addPost(string $type, string $type_id, string $text)
  {
    if (!session()->has(['username', 'user_id'])) {
      return redirect('/login');
    }

    if (is_null($text) || is_null($type) || is_null($type_id)) {
      return redirect('/home');
    }

    $post = new Post;
    $post->user = session()->get('user_id');
    $post->content = $text;
    $post->type = $type;
    $post->type_id = $type_id;
    $post->time = Carbon::now();
    $success = $post->save();

    $response = ['success' => $success, 'data' => $post];
    echo json_encode($response);
  }
}

