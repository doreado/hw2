<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPic extends Model
{
  protected $table = "USER_PICS";
  public $timestamps = false;
  protected $autoincrement = false;

  public function user()
  {
    return $this->belongsTo(User::class, 'user', 'id');
  }
}
?>
