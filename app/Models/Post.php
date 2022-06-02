<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
  protected $table = "POST";
  public $timestamps = false;

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function likes()
  {
    return $this->belongsToMany(Post::class, 'LIKES', 'post', 'user');
  }
}
?>
