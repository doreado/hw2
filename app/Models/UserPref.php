<?php
namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class UserPref extends Model
{
  protected $collection = 'user_prefs';
  protected $connection = 'mongodb';
}
?>
