<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public $permissions = [
        [
            'code' => Permission::CODE_INFORMATION,
            'name' => '基本資料管理模組'
        ],
        [
            'code' => Permission::CODE_PRODUCT,
            'name' => '生產追蹤管理模組'
        ],
        [
            'code' => Permission::CODE_STOCK,
            'name' => '庫存管理模組'
        ],
        [
            'code' => Permission::CODE_REPORT,
            'name' => '報表匯出模組'
        ],
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 先看有沒有資料
        if (!Permission::first()) {
            //
        }
        Permission::create([

        ]);
    }
}
