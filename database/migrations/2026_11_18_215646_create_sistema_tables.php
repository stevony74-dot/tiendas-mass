<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->timestamps();
        });

        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->string('nombre');
            $table->decimal('precio', 10, 2);
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedInteger('stock_minimo')->default(5);
            $table->timestamps();
        });

        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos');
            $table->foreignId('user_id')->constrained('users');
            $table->string('tipo'); // entrada | salida
            $table->unsignedInteger('cantidad');
            $table->string('motivo')->nullable();
            $table->timestamps();
        });

        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('cliente_nombre');
            $table->string('cliente_documento')->nullable();
            $table->string('tipo_comprobante')->default('boleta'); // boleta | factura
            $table->string('numero_comprobante')->nullable();
            $table->decimal('total', 10, 2)->default(0);
            $table->string('estado')->default('confirmado');
            $table->timestamps();
        });

        Schema::create('detalle_pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos');
            $table->unsignedInteger('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });

        Schema::create('reposiciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos');
            $table->foreignId('user_id')->constrained('users');
            $table->unsignedInteger('cantidad');
            $table->string('estado')->default('pendiente'); // pendiente | atendida
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reposiciones');
        Schema::dropIfExists('detalle_pedidos');
        Schema::dropIfExists('pedidos');
        Schema::dropIfExists('movimientos');
        Schema::dropIfExists('productos');
        Schema::dropIfExists('categorias');
    }
};