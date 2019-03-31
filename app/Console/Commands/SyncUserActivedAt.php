<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SyncUserActivedAt extends Command
{
    protected $signature = 'mamerbbs:sync-user-actived-at';

    protected $description = '将用户最后登陆时间从 Redis 同步到数据库中';

    public function handle(User $user)
    {
        $user->syncUserActivedAt();
        $this->info("同步成功!");
    }
}
