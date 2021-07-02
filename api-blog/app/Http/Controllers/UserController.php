<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use \Validator;

class UserController extends Controller
{
    public function test(Request $request)
    {
        return $request;
    }
		
		public function user(Request $request)
    {
			return response($this->returnTextRespons($request->user()));
    }

		private function returnTextRespons($user)
    {
        return ['success' => 'true',
            'data' =>
            [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ];
    }
}
