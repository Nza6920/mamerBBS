<?php

use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Reply;

class ReplysTableSeeder extends Seeder
{
    public function run()
    {
        // 所有用户的 ID 数组
        $user_ids = User::all()->pluck('id')->toArray();

        // 所有话题 ID 数组
        $topic_ids = Topic::all()->pluck('id')->toArray();

        // 获取 faker 实例
        $faker = app(Faker\Generator::class);

        $replys = factory(Reply::class)
            ->times(1000)
            ->make()
            ->each(function ($reply, $index) use ($user_ids, $topic_ids, $faker) {
                // 从用户 ID 数组中随机取出一个并赋值
                $reply->user_id = $faker->randomElement($user_ids);

                // 从话题 ID 数组中随机取出一个并赋值
                $topic_id = $faker->randomElement($topic_ids);
                $reply->topic_id = $topic_id;

                \DB::table('topics')->where('id', $topic_id)->increment('reply_count', 1);
            });

        Reply::insert($replys->toArray());
    }

}

