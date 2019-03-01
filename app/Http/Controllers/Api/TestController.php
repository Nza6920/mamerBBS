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
}
