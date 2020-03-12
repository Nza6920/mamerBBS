<?php

namespace App\Models;

class Reply extends Model
{
    protected $fillable = ['content'];

    // 回复属于话题
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    // 回复属于用户
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 获取 @ 的人
    public function matchAt(){
        $content = $this->content;
        $reply_user_id=[];

        preg_match_all("/@.*?(?=( |$))/",$content,$userName);
        
        foreach($userName[0] as $key => $name) {
            $name=substr($name,1);
            $user_id  = User::where('name','like binary',$name)->value('id');

            if(!$user_id) {
                continue;
            }

            $reply_user_id[$key] = $user_id;
            $url = "<a style='color:#60a4be' href='" . config('app.url'). '/users/' . $reply_user_id[$key] . "' title='" . "$name'>@" . $name . "</a>";
            $content = str_replace('@'.$name, $url, $content);
        }

        return [
            'content'  => $content,
            'reply_user_id' =>  $reply_user_id
        ];
    }
}
