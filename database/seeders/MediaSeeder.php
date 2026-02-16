<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class MediaSeeder extends Seeder
{
    public function run(): void
    {
        // Skip if media table does not exist (module not migrated)
        if (!Schema::hasTable('media')) {
            $this->command?->info('ℹ️  Skipping MediaSeeder — media table does not exist.');
            return;
        }

        // Skip if Media model is not available (module not loaded)
        if (!class_exists(\Modules\Media\Models\Media::class)) {
            $this->command?->info('ℹ️  Skipping MediaSeeder — Media model not found.');
            return;
        }

        try {
            $users = User::all();

            if ($users->isEmpty()) {
                \Modules\Media\Models\Media::factory(5)->create();
                return;
            }

            \Modules\Media\Models\Media::factory(15)
                ->state(fn() => ['user_id' => $users->random()->id])
                ->create();
        } catch (\Throwable $e) {
            // Factory may not be wired correctly for module models — skip gracefully
            $this->command?->warn("⚠️  MediaSeeder skipped: {$e->getMessage()}");
        }
    }
}
