<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(['email' => 'admin@inventory.test'], [
            'name' => 'Administrator',
            'password' => 'password',
            'role' => 'admin',
        ]);

        User::firstOrCreate(['email' => 'gudang@inventory.test'], [
            'name' => 'Petugas Gudang',
            'password' => 'password',
            'role' => 'gudang',
        ]);

        User::firstOrCreate(['email' => 'kasir@inventory.test'], [
            'name' => 'Kasir',
            'password' => 'password',
            'role' => 'kasir',
        ]);

        DB::transaction(function (): void {
            $warehouseUser = User::where('email', 'gudang@inventory.test')->firstOrFail();
            $samples = [
                ['DEMO-001', 'Mouse Wireless', 'Aksesori komputer', 'pcs', 50, 8, 10, 175000],
                ['DEMO-002', 'Keyboard Mekanis', 'Aksesori komputer', 'pcs', 30, 12, 5, 425000],
                ['DEMO-003', 'Kabel HDMI 2m', 'Kabel dan adaptor', 'pcs', 25, 5, 8, 65000],
                ['DEMO-004', 'Flashdisk 32GB', 'Media penyimpanan', 'pcs', 40, 9, 10, 85000],
                ['DEMO-005', 'Headset USB', 'Aksesori komputer', 'pcs', 20, 14, 8, 235000],
                ['DEMO-006', 'Webcam HD', 'Aksesori komputer', 'pcs', 16, 4, 5, 310000],
            ];

            foreach ($samples as [$code, $name, $category, $unit, $incoming, $outgoing, $minimum, $price]) {
                $product = Product::firstOrCreate(
                    ['code' => $code],
                    [
                        'name' => $name,
                        'category' => $category,
                        'unit' => $unit,
                        'stock' => 0,
                        'minimum_stock' => $minimum,
                        'price' => $price,
                    ],
                );

                if (! $product->wasRecentlyCreated) {
                    continue;
                }

                $product->increment('stock', $incoming);
                $product->stockMovements()->create([
                    'user_id' => $warehouseUser->id,
                    'type' => 'in',
                    'quantity' => $incoming,
                    'reference' => "PO-DEMO-{$code}",
                    'note' => 'Penerimaan stok contoh.',
                    'created_at' => now()->subDays(7)->setTime(9, 0),
                ]);

                $product->decrement('stock', $outgoing);
                $product->stockMovements()->create([
                    'user_id' => $warehouseUser->id,
                    'type' => 'out',
                    'quantity' => $outgoing,
                    'reference' => "OUT-DEMO-{$code}",
                    'note' => 'Barang keluar contoh.',
                    'created_at' => now()->subDays(3)->setTime(14, 0),
                ]);
            }
        });
    }
}
