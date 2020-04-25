<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function destroy(Request $request)
    {
        if ($request->has('phone')) {
            $user = User::where('phone', $request->phone)->first();
        } else {
            $user = User::where('email', $request->email)->first();
        }
        $user->delete();

        return $this->response->noContent();
    }

    public function generateToken(Request $request)
    {
        if ($request->has('id')) {
            $user = User::where('id', $request->id)->first();
        } elseif ($request->has('phone')) {
            $user = User::where('phone', $request->id)->first();
        } else {
            $user = User::where('email', $request->email)->first();
        }

        // 一年后过期
        $ttl = 365 * 24 * 60;
        $token = \Auth::guard('api')->setTTL($ttl)->fromUser($user);
        return $this->response->array(['token' => $token])->setStatusCode(201);
    }

    public function generateTokens()
    {
        // 查出 50 个用户
        $users = User::take(50)->get();

        // 一年后过期
        $ttl = 365 * 24 * 60;
        $tokenInfos = collect();

        foreach ($users as $user) {
            $token = \Auth::guard('api')->setTTL($ttl)->fromUser($user);
            $tokenInfos->push(['name' => $user->name, 'email' => $user->email, 'token' => $token]);
        }

        return $this->response->array($tokenInfos->toArray())->setStatusCode(201);
    }
}
