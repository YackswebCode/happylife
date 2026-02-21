<?php

namespace Database\Seeders;

use App\Models\VtuProvider;
use Illuminate\Database\Seeder;

class VtuProviderSeeder extends Seeder
{
    public function run()
    {
        $providers = [
            [
                'name' => 'MTN Nigeria',
                'code' => 'MTN',
                'service_type' => 'airtime,data',
                'logo' => 'providers/mtn.png',
                'api_endpoint' => 'https://api.mtn.com/v1',
                'api_key' => 'mtn_api_key',
                'api_secret' => 'mtn_api_secret',
                'status' => true,
                'commission_rate' => 2.5
            ],
            [
                'name' => 'Airtel Nigeria',
                'code' => 'AIRTEL',
                'service_type' => 'airtime,data',
                'logo' => 'providers/airtel.png',
                'api_endpoint' => 'https://api.airtel.com/v1',
                'api_key' => 'airtel_api_key',
                'api_secret' => 'airtel_api_secret',
                'status' => true,
                'commission_rate' => 2.5
            ],
            [
                'name' => 'Glo Nigeria',
                'code' => 'GLO',
                'service_type' => 'airtime,data',
                'logo' => 'providers/glo.png',
                'api_endpoint' => 'https://api.glo.com/v1',
                'api_key' => 'glo_api_key',
                'api_secret' => 'glo_api_secret',
                'status' => true,
                'commission_rate' => 3.0
            ],
            [
                'name' => '9mobile',
                'code' => '9MOBILE',
                'service_type' => 'airtime,data',
                'logo' => 'providers/9mobile.png',
                'api_endpoint' => 'https://api.9mobile.com/v1',
                'api_key' => '9mobile_api_key',
                'api_secret' => '9mobile_api_secret',
                'status' => true,
                'commission_rate' => 3.0
            ],
            [
                'name' => 'DSTV',
                'code' => 'DSTV',
                'service_type' => 'cable',
                'logo' => 'providers/dstv.png',
                'api_endpoint' => 'https://api.dstv.com/v1',
                'api_key' => 'dstv_api_key',
                'api_secret' => 'dstv_api_secret',
                'status' => true,
                'commission_rate' => 4.0
            ],
            [
                'name' => 'GOTV',
                'code' => 'GOTV',
                'service_type' => 'cable',
                'logo' => 'providers/gotv.png',
                'api_endpoint' => 'https://api.gotv.com/v1',
                'api_key' => 'gotv_api_key',
                'api_secret' => 'gotv_api_secret',
                'status' => true,
                'commission_rate' => 4.0
            ],
            [
                'name' => 'IKEDC',
                'code' => 'IKEDC',
                'service_type' => 'electricity',
                'logo' => 'providers/ikedc.png',
                'api_endpoint' => 'https://api.ikedc.com/v1',
                'api_key' => 'ikedc_api_key',
                'api_secret' => 'ikedc_api_secret',
                'status' => true,
                'commission_rate' => 1.5
            ],
        ];

        foreach ($providers as $provider) {
            VtuProvider::create($provider);
        }
    }
}