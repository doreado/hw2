<?php
namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\Like;
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

  public function getPosts(int $offset)
  {
    if (!session()->has(['username', 'user_id'])) {
      return redirect('/login');
    }

    if (is_null($offset)) {
      return redirect('/login');
    }

    $user_id = session()->get('user_id');
    $posts = Post::where('user', $user_id)->orWhereIn('user', function($query) {
      $user_id = session()->get('user_id');
      $query->select('following')
          ->from(with(new Follow)->getTable())
          ->where('follower', $user_id);
    })->orderBy('time', 'desc')->limit('10')->offset($offset)->get();

    $end = count($posts) < 10;
    $msg = $end ? "Perché non pubblichi un nuovo post?" : "";
    $empty = count($posts) == 0;

    $response = ['content' => $posts, 'msg' => $msg, 'end' => $end, 'empty' => $empty];
    return json_encode($response);
  }

  public function removePost(int $post_id)
  {
    if (!session()->has(['username', 'user_id'])) {
      return redirect('/login');
    }

    $user_id = session()->get('user_id');

    $success = false;
    if (Post::find($post_id)->user == $user_id) {
      $success = true;
      Post::destroy($post_id);
    }

    return ['success' => true];
  }

  public function isLiked(int $post_id)
  {
    if (!session()->has(['username', 'user_id'])) {
      return redirect('/login');
    }

    if (is_null($post_id)) {
      return redirect('/home');
    }

    $user_id = session()->get('user_id');

    $liked = Like::where('post', $post_id)->where('user', $user_id)->exists();
    $success = true;
    $like_pic = $liked ? 'figures/ciak_dark.png' : 'figures/ciak_light.png';
    $response = ['liked' => $liked, 'like_pic' => $like_pic];

    return $response;
  }

  public function getLikeNumber(int $post_id)
  {
    if (!session()->has(['username', 'user_id'])) {
      return redirect('/login');
    }

    if (is_null($post_id)) {
      return redirect('/home');
    }

    $num_like = Like::where('post', $post_id)->groupBy('post')->count('user');
    $success = true;
    $response = ['success' => $success, 'num_like' => $num_like];

    return $response;
  }

  public function addLike(int $post_id)
  {
    if (!session()->has(['username', 'user_id'])) {
      return redirect('/login');
    }

    if (is_null($post_id)) {
      return redirect('/home');
    }

    $user_id = session()->get('user_id');

    $like = new Like;
    $like->post = $post_id;
    $like->user = $user_id;
    $like->save();

    return ['success' => true];
  }

  public function removeLike(int $post_id)
  {
    if (!session()->has(['username', 'user_id'])) {
      return redirect('/login');
    }

    if (is_null($post_id)) {
      return redirect('/home');
    }

    $user_id = session()->get('user_id');
    Like::where('post', $post_id)->where('user', $user_id)->delete();

    return ['success' => true];
  }
}
?>
