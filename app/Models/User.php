<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
  protected $table = "USER";
  public $timestamps = false;

  public function posts()
  {
    return $this->hasMany(Post::class, 'user');
  }

  public function userPics()
  {
    return $this->hasOne(UserPic::class, 'user', 'id');
  }

  public function follower()
  {
    return $this->belongsToMany(User::class, 'FOLLOW', 'follower', 'following');
  }

  public function following()
  {
    return $this->belongsToMany(User::class, 'FOLLOW', 'following', 'follower');
  }

  public function watchlist()
  {
    return $this->hasOne("Watchlist");
  }

  public function likes()
  {
    return $this->belongsToMany(Post::class, 'LIKES', 'user', 'post');
  }
}
?>
