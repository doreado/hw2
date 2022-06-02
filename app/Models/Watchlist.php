<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{
  protected $table = "WATCHLIST";
  public $timestamps = false;

  public function user()
  {
    return $this->belongsTo("User");
  }
}
?>
