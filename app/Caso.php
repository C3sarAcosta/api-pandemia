<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caso extends Model
{
    //
    protected $table = "casos";
    protected $fillable = ["activos","recuperados","muertos","fecha","id_ciudad"];
    protected $guarded = ["id"];
}
