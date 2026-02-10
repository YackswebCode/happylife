<?php

namespace Database\Seeders;

use App\Models\VtuPlan;
use App\Models\VtuProvider;
use Illuminate\Database\Seeder;

class VtuPlanSeeder extends Seeder
{
    public function run()
    {
        $providers = VtuProvider::all();

        $plans = [
            // MTN Data Plans
            [
                'provider_id' => $providers->where('code', 'MTN')->first()->id,
                'name' => 'MTN 1GB - 30 Days',
                'code' => 'MTN-1GB-30D',
                'service_type' => 'data',
                'amount' => 500,
                'validity' => '30 days',
                'description' => '1GB data bundle valid for 30 days',
                'is_active' => true
            ],
            [
                'provider_id' => $providers->where('code', 'MTN')->first()->id,
                'name' => 'MTN 2GB - 30 Days',
                'code' => 'MTN-2GB-30D',
                'service_type' => 'data',
                'amount' => 1000,
                'validity' => '30 days',
                'description' => '2GB data bundle valid for 30 days',
                'is_active' => true
            ],
            [
                'provider_id' => $providers->where('code', 'MTN')->first()->id,
                'name' => 'MTN 5GB - 30 Days',
                'code' => 'MTN-5GB-30D',
                'service_type' => 'data',
                'amount' => 2000,
                'validity' => '30 days',
                'description' => '5GB data bundle valid for 30 days',
                'is_active' => true
            ],

            // Airtel Data Plans
            [
                'provider_id' => $providers->where('code', 'AIRTEL')->first()->id,
                'name' => 'Airtel 1GB - 30 Days',
                'code' => 'AIRTEL-1GB-30D',
                'service_type' => 'data',
                'amount' => 500,
                'validity' => '30 days',
                'description' => '1GB data bundle valid for 30 days',
                'is_active' => true
            ],

            // Glo Data Plans
            [
                'provider_id' => $providers->where('code', 'GLO')->first()->id,
                'name' => 'Glo 1GB - 30 Days',
                'code' => 'GLO-1GB-30D',
                'service_type' => 'data',
                'amount' => 450,
                'validity' => '30 days',
                'description' => '1GB data bundle valid for 30 days',
                'is_active' => true
            ],

            // DSTV Plans
            [
                'provider_id' => $providers->where('code', 'DSTV')->first()->id,
                'name' => 'DSTV Compact',
                'code' => 'DSTV-COMPACT',
                'service_type' => 'cable',
                'amount' => 7900,
                'validity' => '1 month',
                'description' => 'DSTV Compact monthly subscription',
                'is_active' => true
            ],
            [
                'provider_id' => $providers->where('code', 'DSTV')->first()->id,
                'name' => 'DSTV Compact Plus',
                'code' => 'DSTV-COMPACT-PLUS',
                'service_type' => 'cable',
                'amount' => 12400,
                'validity' => '1 month',
                'description' => 'DSTV Compact Plus monthly subscription',
                'is_active' => true
            ],

            // GOTV Plans
            [
                'provider_id' => $providers->where('code', 'GOTV')->first()->id,
                'name' => 'GOtv Max',
                'code' => 'GOTV-MAX',
                'service_type' => 'cable',
                'amount' => 3650,
                'validity' => '1 month',
                'description' => 'GOtv Max monthly subscription',
                'is_active' => true
            ],

            // Electricity Plans
            [
                'provider_id' => $providers->where('code', 'IKEDC')->first()->id,
                'name' => 'IKEDC Prepaid',
                'code' => 'IKEDC-PREPAID',
                'service_type' => 'electricity',
                'amount' => 1000,
                'validity' => 'Instant',
                'description' => 'IKEDC prepaid electricity bill payment',
                'is_active' => true
            ],
        ];

        // Add airtime plans (variable amounts)
        foreach (['MTN', 'AIRTEL', 'GLO', '9MOBILE'] as $network) {
            foreach ([100, 200, 500, 1000, 2000, 5000] as $amount) {
                $plans[] = [
                    'provider_id' => $providers->where('code', $network)->first()->id,
                    'name' => $network . ' Airtime ₦' . $amount,
                    'code' => $network . '-AIRTIME-' . $amount,
                    'service_type' => 'airtime',
                    'amount' => $amount,
                    'validity' => 'Instant',
                    'description' => $network . ' airtime recharge of ₦' . $amount,
                    'is_active' => true
                ];
            }
        }

        foreach ($plans as $plan) {
            VtuPlan::create($plan);
        }
    }
}