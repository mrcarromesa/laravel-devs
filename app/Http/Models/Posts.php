<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    protected $table = 'posts';
    protected $primaryKey = 'id';

    //protected $fillable = ['name'];

    protected $guarded = [];

    public function devs()
    {
        // 1 - Path\Model\Tabela_Pai
        // 2 - Nome do campo de referencia na tabela atual
        return $this->belongsTo('App\Http\Models\Devs', 'dev_id');
    }
}
