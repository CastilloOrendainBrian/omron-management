<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class SkinfoldProtocolSeeder extends Seeder
{
    public function run(): void
    {
        $protocols = [
            [
                'name' => 'Jackson-Pollock 3 sitios',
                'sites_count' => 3,
                'description' => 'Protocolo de plicometría de 3 sitios de Jackson & Pollock.',
            ],
            [
                'name' => 'Jackson-Pollock 7 sitios',
                'sites_count' => 7,
                'description' => 'Protocolo de plicometría de 7 sitios de Jackson & Pollock.',
            ],
            [
                'name' => 'Durnin-Womersley',
                'sites_count' => 4,
                'description' => 'Protocolo de plicometría de 4 sitios de Durnin & Womersley.',
            ],
        ];

        foreach ($protocols as $protocol) {
            DB::table('skinfold_protocols')->updateOrInsert(
                ['name' => $protocol['name']],
                $protocol,
            );
        }
    }
}
