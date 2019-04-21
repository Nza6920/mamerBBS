<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class Policy
{
    use HandlesAuthorization;

    // App\Policies\Policy 基类中的 before 方法会优先执行
    public function before($user, $ability)
	{
	    // 如果用户有管理内容的权限, 则通过
        if ($user->can('manage_contents')) {
            return true;
        }
	}
}
