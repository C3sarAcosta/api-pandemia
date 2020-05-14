<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    //
    protected $table = 'ciudades';
    protected $fillable = ['nombre_ciudad','habitantes_ciudad','id_pais'];
    protected $guarded = ['id'];
}
