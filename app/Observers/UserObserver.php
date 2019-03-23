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

        // 头像假数据
        $avatars = [
            'https://iocaffcdn.phphub.org/uploads/images/201710/14/1/s5ehp11z6s.png',
            'https://iocaffcdn.phphub.org/uploads/images/201710/14/1/Lhd1SHqu86.png',
            'https://iocaffcdn.phphub.org/uploads/images/201710/14/1/LOnMrqbHJn.png',
            'https://iocaffcdn.phphub.org/uploads/images/201710/14/1/xAuDMxteQy.png',
            'https://iocaffcdn.phphub.org/uploads/images/201710/14/1/ZqM7iaP4CR.png',
            'https://iocaffcdn.phphub.org/uploads/images/201710/14/1/NDnzMutoxX.png',
        ];

        if (!$user->avatar) {
            $user->avatar = $faker->randomElement($avatars);
        }
    }
}
