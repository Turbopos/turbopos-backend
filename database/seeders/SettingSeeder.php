<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::updateOrCreate([
            'id' => 1,
        ], [
            'nama' => 'PT. Sejahtera Abadi',
            'alamat' => 'Jl. Jendral Sudirman No. 1, Jakarta Pusat',
            'telepon' => '021-1234567',
            'email' => 'YtOgA@example.com',
        ]);
    }
}
