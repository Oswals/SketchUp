<?php

namespace Database\Seeders;

use App\Models\CoinPackage;
use Illuminate\Database\Seeder;

class CoinPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => '1 FILE',
                'coins' => 1,
                'price' => 0.90,
                'stripe_price_id' => ' price_1SmBZXHPdPM2NxgxWePhjPHj', // ⚠️ À remplacer par le nouveau prix Stripe $0.99
                'description' => 'For 1 conversion',
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'UNLIMITED',
                'coins' => 99999,
                'price' => 19.90,
                'stripe_price_id' => 'price_1SmBb4HPdPM2NxgxMvPTPBYG', // ⚠️ À remplacer par le nouveau prix Stripe $19.99
                'description' => 'Unlimited conversions — one-time payment',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
        ];

        // Désactiver l'ancien package 5 FILES et 20 FILES s'ils existent
        CoinPackage::whereIn('coins', [5, 20])->update(['is_active' => false]);

        foreach ($packages as $package) {
            CoinPackage::updateOrCreate(
                ['coins' => $package['coins']],
                $package
            );
        }

        $this->command->info('✅ File packages updated successfully!');
        $this->command->info('');
        $this->command->info('💡 Don\'t forget to:');
        $this->command->info('   1. Create 2 new products in Stripe Dashboard');
        $this->command->info('   2. Update stripe_price_id for both packages');
        $this->command->info('');
        $this->command->info('Configured prices:');
        $this->command->info('   • 1 FILE      = $0.90');
        $this->command->info('   • UNLIMITED   = $19.90 (99,999 conversions)');
        $this->command->info('');
        $this->command->info('⚠️  Old packages (5 FILES, 20 FILES) have been deactivated.');
    }
}