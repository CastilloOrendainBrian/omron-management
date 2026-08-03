<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class SkinfoldSiteSeeder extends Seeder
{
    public function run(): void
    {
        $sites = [
            ['code' => 'triceps', 'name' => 'Tríceps'],
            ['code' => 'subescapular', 'name' => 'Subescapular'],
            ['code' => 'suprailiaco', 'name' => 'Suprailiaco'],
            ['code' => 'abdominal', 'name' => 'Abdominal'],
            ['code' => 'muslo', 'name' => 'Muslo'],
            ['code' => 'pecho', 'name' => 'Pectoral'],
            ['code' => 'axilar_medio', 'name' => 'Axilar medio'],
            ['code' => 'biceps', 'name' => 'Bíceps'],
            ['code' => 'pantorrilla', 'name' => 'Pantorrilla'],
        ];

        foreach ($sites as $site) {
            DB::table('skinfold_sites')->updateOrInsert(
                ['code' => $site['code']],
                $site,
            );
        }
    }
}
