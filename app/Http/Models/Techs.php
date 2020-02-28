<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Techs extends Model
{
    protected $table = 'techs';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function devs()
    {
        return $this->belongsToMany(
            'App\Http\Models\Devs',
            'devs_techs',
            'id_tech',
            'id_dev'
        );
    }
}
