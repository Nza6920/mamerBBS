<?php

namespace App\Observers;

use App\Models\User;
use Faker\Generator;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class UserObserver
{

    public function creating(User $user)
    {
        $faker = app(Generator::class);
        $url = env('APP_URL') . '/uploads/images/system/default';
        // 头像假数据
        $avatars = [
            '/s5ehp11z6s.png',
            '/Lhd1SHqu86.png',
            '/LOnMrqbHJn.png',
            '/xAuDMxteQy.png',
            '/ZqM7iaP4CR.png',
            '/NDnzMutoxX.png',
        ];

        if (empty($user->avatar)) {
            $user->avatar = $url . $faker->randomElement($avatars);
        }
    }
}
