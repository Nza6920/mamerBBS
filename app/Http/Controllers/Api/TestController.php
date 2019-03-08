<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function destroy(Request $request)
    {
        $phone = $request->phone;

        $user = User::where('phone', $phone)->first();

        if (!$user) {
            return $this->response->error('没有找到该用户', 404);
        }

        $user->delete();
        return $this->response->noContent();
    }

    public function generateToken(Request $request)
    {
        if ($request->has('id')) {
            $user = User::where('id', $request->id)->first();
        } elseif($request->has('phone')) {
            $user = User::where('phone', $request->id)->first();
        } else {
            $user = User::where('email', $request->email)->first();
        }

        // 一年后过期
        $ttl = 365 *24 * 60;
        $token = \Auth::guard('api')->setTTL($ttl)->fromUser($user);
        return $this->response->array(['token' => $token])->setStatusCode(201);
    }
}
