<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Devs extends Model
{
    protected $table = 'devs';
    protected $primaryKey = 'id';

    protected $hidden = ['created_at'];

    //protected $fillable = ['name'];

    protected $guarded = [];


    public function posts()
    {
        // 1 - Path\Model\Tabela_Filha
        // 2 - Nome do campo de referencia na tabela filha
        // 3 - Nome do campo de referencia na tabela pai
        return $this->hasMany('App\Http\Models\Posts', 'dev_id', 'id');
    }

    public function techs()
    {
        return $this->belongsToMany(
            'App\Http\Models\Techs',
            'devs_techs',
            'id_dev',
            'id_tech'
        );
    }
}
