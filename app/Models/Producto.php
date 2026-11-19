<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    protected $fillable = ['categoria_id', 'nombre', 'precio', 'stock', 'stock_minimo'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class);
    }

    public function stockBajo(): bool
    {
        return $this->stock <= $this->stock_minimo;
    }
}