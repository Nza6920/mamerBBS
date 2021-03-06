<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api',
    'middleware' => ['serializer:array', 'bindings', 'change-locale']
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',
        'limit'      => config('api.rate_limits.sign.limit'),     // 默认10次
        'expires'    => config('api.rate_limits.sign.expires'),   // 默认1分钟
    ], function ($api) {
        /** 不需要token的接口 **/
        // 短信验证码
        $api->post('verificationCodes', 'VerificationCodesController@store')
            ->name('api.verificationCodes.store');
        // 用户注册
        $api->post('users', 'UsersController@store')
            ->name('api.users.store');
        // 图片验证码
        $api->post('captchas', 'CaptchasController@store')
            ->name('api.captchas.store');
        // 登陆
        $api->post('authorizations', 'AuthorizationsController@store')
            ->name('api.authorizations.store');
        // 短信登录
        $api->post('msg/authorizations', 'AuthorizationsController@msgStore')
            ->name('api.socials.authorizations.msg.store');
        // 刷新 token
        $api->put('authorizations/current', 'AuthorizationsController@update')
            ->name('api.authorizations.update');
        // 删除 token
        $api->delete('authorizations/current', 'AuthorizationsController@destroy')
            ->name('api.authorizations.destroy');
        // 分类接口
        $api->get('categories', 'CategoriesController@index')
            ->name('api.categories.index');
        // 话题列表
        $api->get('topics', 'TopicsController@index')
            ->name('api.topics.index');
        // 某个用户的话题
        $api->get('users/{user}/topics', 'TopicsController@userIndex')
            ->name('api.users.topics.index');
        // 话题详情
        $api->get('topics/{topic}', 'TopicsController@show')
            ->name('api.topics.show');
        // 话题回复列表
        $api->get('topics/{topic}/replies', 'RepliesController@index')
            ->name('api.topics.replies.index');
        // 某个用户的回复列表
        $api->get('users/{user}/replies', 'RepliesController@userIndex')
            ->name('api.users.replies.index');
        // 资源推荐
        $api->get('links', 'LinksController@index')
            ->name('api.links.index');
        // 活跃用户
        $api->get('actived/users', 'UsersController@activedIndex')
            ->name('api.actived.users.index');
        // 第三方登录
        $api->post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')
            ->name('api.socials.authorizations.store');
        // 用户的粉丝
        $api->get('users/{user}/followers', 'UsersController@followers')
            ->name('api.user.followers');
        // 当前登陆用户的关注
        $api->get('users/{user}/followings', 'UsersController@followings')
            ->name('api.user.followings');

        /** 测试专用接口 **/
        $api->post('test/destroy', 'TestController@destroy')
            ->name('api.test.destroy');
        $api->post('test/token', 'TestController@generateToken')
            ->name('api.test.token');
        $api->get('test/tokens', 'TestController@generateTokens')
            ->name('api.test.tokens');

        /** 需要token的接口 **/
        $api->group(['middleware' => 'api.auth'], function ($api) {
            // 当前登陆用户信息
            $api->get('user', 'UsersController@me')
                ->name('api.user.show');
            // 编辑登录用户信息
            $api->patch('user', 'UsersController@update')
                ->name('api.user.update');
            // 当前用户的点赞
            $api->get('user/votes', 'UsersController@myVotes')
                ->name('api.user.votes');
            // 图片资源
            $api->post('images', 'ImagesController@store')
                ->name('api.images.store');
            // 发布话题
            $api->post('topics', 'TopicsController@store')
                ->name('api.topics.store');
            // 编辑话题
            $api->patch('topics/{topic}', 'TopicsController@update')
                ->name('api.topics.update');
            // 删除话题
            $api->delete('topics/{topic}', 'TopicsController@destroy')
                ->name('api.topics.destroy');
            // 话题点赞
            $api->post('topics/{topic}/votes', 'TopicsController@upVote')
                ->name('api.topics.votes.up');
            // 取消点赞
            $api->delete('topics/{topic}/votes', 'TopicsController@cancelVote')
                ->name('api.topics.votes.cancel');
            // 判断文章是否点赞
            $api->get('topics/{topic}/voted', 'TopicsController@isVoted')
                ->name('api.topics.votes.isVoted');

            // 发布回复
            $api->post('topics/{topic}/replies', 'RepliesController@store')
                ->name('api.topics.replies.store');
            // 删除回复
            $api->delete('topics/{topic}/replies/{reply}', 'RepliesController@destroy')
                ->name('api.topics.replies.destroy');
            // 通知列表
            $api->get('user/notifications', 'NotificationsController@index')
                ->name('api.user.notifications.index');
            // 未读消息统计
            $api->get('user/notifications/stats', 'NotificationsController@stats')
                ->name('api.user.notifications.stats');
            // 通知消息通知已读
            $api->patch('user/read/notifications', 'NotificationsController@read')
                ->name('api.user.notifications.read');
            // 当前登陆用户权限
            $api->get('user/permission', 'PermissionsController@index')
                ->name('api.user.permission.index');
            // 关注某人
            $api->post('users/followers', 'UsersController@follow')
                ->name('api.user.follow');
            // 取关某人
            $api->delete('users/followers', 'UsersController@unFollow')
                ->name('api.user.unFollow');
            // 判断当前登陆用户是否关注某人
            $api->get('users/followers', 'UsersController@isFollowing')
                ->name('api.user.isFollowing');
        });
    });
});
