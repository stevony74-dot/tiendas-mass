<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reposicion extends Model
{
    protected $table = 'reposiciones';
    protected $fillable = ['producto_id', 'user_id', 'cantidad', 'estado'];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}