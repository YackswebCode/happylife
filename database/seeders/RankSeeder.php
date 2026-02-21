<?php

namespace Database\Seeders;

use App\Models\Rank;
use Illuminate\Database\Seeder;

class RankSeeder extends Seeder
{
    public function run()
    {
        $ranks = [
            [
                'name' => 'WINNER LEADER',
                'level' => 1,
                'required_pv' => 1000,
                'cash_reward' => 100000,
                'other_reward' => '₦100,000 Cash',
                'description' => 'First major achievement rank',
                'is_active' => true
            ],
            [
                'name' => 'ACHIEVER LEADER',
                'level' => 2,
                'required_pv' => 5000,
                'cash_reward' => 250000,
                'other_reward' => '₦250,000 Cash / Refrigerator',
                'description' => 'Mid-level achievement rank',
                'is_active' => true
            ],
            [
                'name' => 'GOLDEN LEADER',
                'level' => 3,
                'required_pv' => 15000,
                'cash_reward' => 750000,
                'other_reward' => '₦750,000 + 3-Night 5-Star Retreat',
                'description' => 'Premium achievement rank',
                'is_active' => true
            ],
            [
                'name' => 'EXECUTIVE LEADER',
                'level' => 4,
                'required_pv' => 30000,
                'cash_reward' => 900000,
                'other_reward' => '₦900,000 Cash / iPhone 12 Pro Max',
                'description' => 'Executive level rank',
                'is_active' => true
            ],
            [
                'name' => 'DIAMOND LEADER',
                'level' => 5,
                'required_pv' => 75000,
                'cash_reward' => 2500000,
                'other_reward' => '₦2,500,000 / International Trip',
                'description' => 'Diamond level achievement',
                'is_active' => true
            ],
            [
                'name' => 'AMBASSADOR',
                'level' => 6,
                'required_pv' => 150000,
                'cash_reward' => 12500000,
                'other_reward' => 'Brand New Car ₦12,500,000',
                'description' => 'Ambassador level - Car award',
                'is_active' => true
            ],
            [
                'name' => 'KING PALACE',
                'level' => 7,
                'required_pv' => 300000,
                'cash_reward' => 60000000,
                'other_reward' => '₦60,000,000 House Fund',
                'description' => 'King Palace - House fund award',
                'is_active' => true
            ],
            [
                'name' => 'PACESETTERS DIRECTOR',
                'level' => 8,
                'required_pv' => 500000,
                'cash_reward' => 100000000,
                'other_reward' => '₦100,000,000 + Bonuses',
                'description' => 'Highest rank - Pacesetters Director',
                'is_active' => true
            ],
        ];

        foreach ($ranks as $rank) {
            Rank::create($rank);
        }
    }
}