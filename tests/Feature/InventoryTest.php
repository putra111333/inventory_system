<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_inventory_pages_are_available_to_authenticated_users(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk();
        $this->get(route('products.index'))->assertOk();
        $this->get(route('products.create'))->assertOk();
        $this->get(route('stock.index'))->assertOk();
        $this->get(route('reports.index'))->assertOk();
    }

    public function test_database_seeder_adds_sample_products_and_stock_movements_once(): void
    {
        $this->seed(DatabaseSeeder::class);
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('products', 6);
        $this->assertDatabaseCount('stock_movements', 12);
        $this->assertDatabaseHas('products', ['code' => 'DEMO-005', 'stock' => 6]);

        $sampleProductId = Product::where('code', 'DEMO-005')->value('id');
        $this->assertDatabaseHas('stock_movements', ['product_id' => $sampleProductId, 'type' => 'in']);
        $this->assertDatabaseHas('stock_movements', ['product_id' => $sampleProductId, 'type' => 'out']);
    }

    public function test_admin_can_manage_products_but_gudang_cannot(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $gudang = User::factory()->create(['role' => 'gudang']);

        $this->actingAs($gudang)
            ->post(route('products.store'), $this->productData())
            ->assertForbidden();

        $this->actingAs($admin)
            ->post(route('products.store'), $this->productData())
            ->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', ['code' => 'BRG-001', 'stock' => 0]);
    }

    public function test_products_have_scannable_svg_codes_and_downloads(): void
    {
        $user = User::factory()->create(['role' => 'gudang']);
        $product = Product::create($this->productData());

        $this->actingAs($user)
            ->get(route('products.codes', $product))
            ->assertOk()
            ->assertSee('QR code')
            ->assertSee('Barcode Code 128');

        $qrCode = $this->get(route('products.codes.qr', $product));
        $qrCode->assertOk()->assertHeader('Content-Type', 'image/svg+xml; charset=UTF-8');
        $this->assertStringContainsString('<svg', $qrCode->getContent());

        $barcode = $this->get(route('products.codes.barcode', $product));
        $barcode->assertOk()->assertHeader('Content-Type', 'image/svg+xml; charset=UTF-8');
        $this->assertStringContainsString('<svg', $barcode->getContent());
        $this->assertStringContainsString(
            'attachment',
            $this->get(route('products.codes.qr', ['product' => $product, 'download' => 1]))
                ->headers->get('Content-Disposition'),
        );
    }

    public function test_only_admin_can_register_users_with_supported_roles(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $gudang = User::factory()->create(['role' => 'gudang']);
        $registration = [
            'name' => 'Kasir Baru',
            'email' => 'kasir@example.test',
            'role' => 'gudang',
            'password' => 'password-aman',
            'password_confirmation' => 'password-aman',
        ];

        $this->actingAs($gudang)
            ->get(route('users.register'))
            ->assertForbidden();
        $this->post(route('users.register.store'), $registration)
            ->assertForbidden();

        $this->actingAs($admin)
            ->get(route('users.register'))
            ->assertOk()
            ->assertSee('Daftarkan akun');
        $this->post(route('users.register.store'), $registration)
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('users', [
            'email' => 'kasir@example.test',
            'role' => 'gudang',
        ]);

        $registration['email'] = 'kasir-baru@example.test';
        $registration['role'] = 'kasir';
        $this->post(route('users.register.store'), $registration)
            ->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('users', [
            'email' => 'kasir-baru@example.test',
            'role' => 'kasir',
        ]);

        $registration['email'] = 'admin-baru@example.test';
        $registration['role'] = 'admin';
        $this->post(route('users.register.store'), $registration)
            ->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('users', [
            'email' => 'admin-baru@example.test',
            'role' => 'admin',
        ]);

        $registration['email'] = 'tidak-valid@example.test';
        $registration['role'] = 'superadmin';
        $this->from(route('users.register'))
            ->post(route('users.register.store'), $registration)
            ->assertSessionHasErrors('role');
    }

    public function test_stock_movements_update_stock_and_reject_insufficient_outgoing_stock(): void
    {
        $user = User::factory()->create(['role' => 'gudang']);
        $product = Product::create($this->productData(['stock' => 5]));

        $this->actingAs($user)->post(route('stock.store'), [
            'product_id' => $product->id,
            'type' => 'in',
            'quantity' => 3,
            'reference' => 'PO-001',
        ])->assertRedirect(route('stock.index'));

        $this->post(route('stock.store'), [
            'product_id' => $product->id,
            'type' => 'out',
            'quantity' => 4,
        ])->assertRedirect(route('stock.index'));

        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 4]);
        $this->assertDatabaseCount('stock_movements', 2);

        $this->from(route('stock.index'))->post(route('stock.store'), [
            'product_id' => $product->id,
            'type' => 'out',
            'quantity' => 5,
        ])->assertSessionHasErrors('quantity');

        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 4]);
        $this->assertDatabaseCount('stock_movements', 2);
    }

    public function test_report_summarizes_filtered_stock_movements(): void
    {
        $user = User::factory()->create(['role' => 'gudang']);
        $product = Product::create($this->productData(['stock' => 6]));
        StockMovement::create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'type' => 'in',
            'quantity' => 8,
            'created_at' => now(),
        ]);
        StockMovement::create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'type' => 'out',
            'quantity' => 2,
            'created_at' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('reports.index', ['type' => 'in']))
            ->assertOk()
            ->assertSee('Total kuantitas stok masuk')
            ->assertSee('8');
    }

    private function productData(array $overrides = []): array
    {
        return array_merge([
            'code' => 'BRG-001',
            'name' => 'Produk Contoh',
            'category' => 'Umum',
            'unit' => 'pcs',
            'minimum_stock' => 2,
            'price' => 15000,
        ], $overrides);
    }
}
