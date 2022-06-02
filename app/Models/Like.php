<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
  protected $table = "LIKES";
  public $timestamps = false;

  public function post()
  {
    return $this->belongsTo(Post::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
?>
