<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RenDetBeli extends Model
{
  protected $table = 'r_det_beli';
  protected $primaryKey = 'kd';
  public $timestamps = false;
}
