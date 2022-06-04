<?php
namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\UserPic;
use App\Models\Watchlist;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use function GuzzleHttp\json_encode;
use function redirect;
use function request;

class MovieController extends BaseController
{
  private function getKey()
  {
    return env('MOVIE_KEY');
  }

  public function searchMovie(string $movie)
  {
    if (!session()->has(['username', 'user_id'])) {
      return redirect('/login');
    }

    if (is_null($movie)) {
      return redirect('/home');
    }

    $user_id = session()->get('user_id');
    $api_key = $this->getKey() ;
    $movie = urlencode($movie);
    $endpoint = "https://api.themoviedb.org/3/search/movie?api_key=".$api_key."&query=".$movie."&total_results=10";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $endpoint);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);

    $success = $result ? true : false;

    $result = json_decode($result, true);
    $result = $result['results'];
    $data = array();
    $base_url = "https://www.themoviedb.org/t/p/original/";

    for ($i = 0; $i < count($result); $i++) {
      if (!isset($result[$i]['id'])) {
        $success = false;
        break;
      }

      $watched = Post::where('user', $user_id)->where('type_id', $result[$i]['id'])->exists();
      $watchlist = Watchlist::where('user', $user_id)->where('type_id', $result[$i]['id'])->exists();

      $data[] = [
          'id' => $result[$i]['id'],
          'title' => $result[$i]['title'],
          'release_date' => $result[$i]['release_date'],
          'poster' => $base_url.$result[$i]['poster_path'],
          'watched' => $watched,
          'watchlist' => $watchlist
      ];
    }

    return ['success' => $success, 'data' => $data];
  }

  public function getMoviePoster(string $movie_id)
  {
    if (!session()->has(['username', 'user_id'])) {
      return redirect('/login');
    }

    if (is_null($movie_id)) {
      return redirect('/home');
    }

    $user_id = session()->get('user_id');
    $api_key = $this->getKey() ;
    $base_url = "http://api.themoviedb.org/3";
    $endpoint = "/movie/".$movie_id."/images?api_key=".$api_key;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $base_url.$endpoint);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);

    $result_array = json_decode($result, true);
    // TODO check if exists
    $poster_id = $result_array['posters'][0]['file_path'];
    $base_src = "https://www.themoviedb.org/t/p/original/";
    $src = $base_src.$poster_id;
    $success = true;
    $response = ['success' => true, 'src' => $src];

    return $response;
  }

  public function toggleMovieInWatchlist(int $movie_id)
  {
    if (!session()->has(['username', 'user_id'])) {
      return redirect('/login');
    }

    if (is_null($movie_id)) {
      return redirect('/home');
    }

    $user_id = session()->get('user_id');
    $entry_id = Watchlist::where('user', $user_id)->where('type_id', $movie_id)->value('id');
    $in_watchlist = !is_null($entry_id);

    if ($in_watchlist) {
      Watchlist::destroy($entry_id);
    } else {
      $new_entry = new Watchlist;
      $new_entry->type = 'movie';
      $new_entry->type_id = $movie_id;
      $new_entry->user = $user_id;
      $new_entry->save();
    }

    return ['success' => true, 'in_watchlist' => !$in_watchlist];
  }
}
?>
