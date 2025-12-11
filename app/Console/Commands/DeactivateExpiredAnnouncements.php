<?php

namespace App\Console\Commands;

use App\Models\Announcement;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class DeactivateExpiredAnnouncements extends Command
{
    protected $signature = 'announcements:deactivate-expired';

    protected $description = 'Marca como inactivos los anuncios de reuniones cuyo end_time ya expiró';

    public function handle(): int
    {
        $count = Announcement::where('type', 'reunion')
            ->where('status', 'active')
            ->where('end_time', '<', now())
            ->update(['status' => 'inactive']);

        if ($count > 0) {
            Cache::forget('active_announcements');
            Cache::forget('active_announcements_reunion');
            Cache::forget('active_announcements_multimedia');
        }

        $this->info("Anuncios marcados inactivos: {$count}");
        return Command::SUCCESS;
    }
}
