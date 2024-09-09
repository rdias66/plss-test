<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['Novo', 'Pendente', 'Resolvido'];

        foreach ($statuses as $status) {
            Status::updateOrCreate(
                ['name' => $status],
                ['name' => $status]
            );
        }
    }
}
