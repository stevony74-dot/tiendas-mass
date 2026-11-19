<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SistemaMassTest extends TestCase
{
    use RefreshDatabase;

    private function crearProducto(int $stock = 10): Producto
    {
        $cat = Categoria::create(['nombre' => 'Prueba']);
        return Producto::create([
            'categoria_id' => $cat->id,
            'nombre' => 'Producto test',
            'precio' => 5,
            'stock' => $stock,
            'stock_minimo' => 5,
        ]);
    }

    public function test_usuario_no_entra_al_panel_admin(): void
    {
        $user = User::factory()->create(['role' => 'usuario']);
        $this->actingAs($user)->get('/admin')->assertStatus(403);
    }

    public function test_usuario_no_ve_reporte_de_ventas(): void
    {
        $user = User::factory()->create(['role' => 'usuario']);
        $this->actingAs($user)->get('/reportes/ventas')->assertStatus(403);
    }

    public function test_admin_entra_al_panel(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->get('/admin')->assertOk();
    }

    public function test_pedido_con_stock_descuenta_inventario(): void
    {
        $user = User::factory()->create(['role' => 'usuario']);
        $p = $this->crearProducto(10);

        $respuesta = $this->actingAs($user)->post('/pedidos', [
            'cliente_nombre' => 'Cliente Test',
            'tipo_comprobante' => 'boleta',
            'items' => [$p->id => 3],
        ]);

        $this->assertEquals(7, $p->fresh()->stock);
        $this->assertDatabaseHas('pedidos', ['cliente_nombre' => 'Cliente Test', 'total' => 15]);
    }

    public function test_pedido_sin_stock_no_se_confirma(): void
    {
        $user = User::factory()->create(['role' => 'usuario']);
        $p = $this->crearProducto(2);

        $this->actingAs($user)->post('/pedidos', [
            'cliente_nombre' => 'Cliente Test',
            'tipo_comprobante' => 'boleta',
            'items' => [$p->id => 10],
        ]);

        $this->assertEquals(2, $p->fresh()->stock);
        $this->assertDatabaseCount('pedidos', 0);
    }

    public function test_salida_mayor_al_stock_se_rechaza(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $p = $this->crearProducto(5);

        $this->actingAs($admin)->post('/movimientos', [
            'producto_id' => $p->id,
            'tipo' => 'salida',
            'cantidad' => 50,
        ]);

        $this->assertEquals(5, $p->fresh()->stock);
    }
}