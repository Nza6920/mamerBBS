<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // 获取 Faker 实例
        $faker = app(Faker\Generator::class);

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

        // 生成数据集合
        $users = factory(User::class)
            ->times(10)
            ->make()
            ->each(function ($user, $index)
            use ($faker, $avatars, $url)
            {
                // 从头像数组中随机取出一个并赋值
                $user->avatar = $url . $faker->randomElement($avatars);
            });

        // 让隐藏字段可见，并将数据集合转换为数组
        $user_array = $users->makeVisible(['password', 'remember_token'])->toArray();

        // 插入到数据库中
        User::insert($user_array);

        // 单独处理第一个用户的数据
        $user = User::find(1);
        $user->name = 'admin';
        $user->email = '2388426660@qq.com';
        $user->password = bcrypt('qwerty');
        $user->avatar = 'https://iocaffcdn.phphub.org/uploads/images/201710/14/1/ZqM7iaP4CR.png';
        $user->save();
        $user->assignRole('Founder');        // 一号默认站长

        // 2号用户指派为 [管理员]
        $user = User::find(2);
        $user->assignRole('Maintainer');



    }
}
