<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use \Validator;

class AuthController extends Controller
{

    public function test(Request $request)
    {
        return $request;
    }
    public function register(Request $request)
    {
        if ($this->failValidateEmail($request)) {
            return $this->returnErrorTextRespons('1001', 'Ваш емаил говно');
        }
        if ($this->validatePassword($request)) {
            return $this->returnErrorTextRespons('1002', 'Ваш пасс говно');
        }
        if (!$this->validatePassword($request)) {
            $user = User::create($request->all());
            $token = $user->createToken('myapptoken')->plainTextToken;
            return response($this->returnTextRespons($user))->header('X-Auth-Token', $token);
						// $response = [
						// 	'user' => $user,
						// 	'token' => $token
						// ];
						// return 	$response;
        } else {
            return $this->returnErrorTextRespons('666', 'Все в жопе');
        }
    }

		public function login(Request $request)
    {
				$dataUser = $this->auntifTypeToken($request);
				$user = User::where('email', $request['login'])->first();

        if ($this->failValidateEmail($request)) {
            return $this->returnErrorTextRespons('1001', 'Ваш емаил говно1');
        }
        if ($this->validatePassword($request)) {
            return $this->returnErrorTextRespons('1002', 'Ваш пасс говно');
        }
        if (!$this->validatePassword($request) || $this->checkPassword($request, $user)) {
            $token = $user->createToken('myapptoken')->plainTextToken;
            return response($this->returnTextRespons($user))->header('X-Auth-Token', $token);
        } else {
            return $this->returnErrorTextRespons('666', 'Все в жопе');
        }
    }

    // public function login(Request $request)
    // {
    //     $user = User::where('email', $request['email'])->first();

    //     if ($this->failValidateEmail($request)) {
    //         return $this->returnErrorTextRespons('1001', 'Ваш емаил говно1');
    //     }
    //     if ($this->validatePassword($request)) {
    //         return $this->returnErrorTextRespons('1002', 'Ваш пасс говно');
    //     }
    //     if (!$this->validatePassword($request) || $this->checkPassword($request, $user)) {
    //         $token = $user->createToken('myapptoken')->plainTextToken;
    //         return response($this->returnTextRespons($user))->header('X-Auth-Token', $token);
    //     } else {
    //         return $this->returnErrorTextRespons('666', 'Все в жопе');
    //     }
    // }

		public function logout(Request $request)
		{
			$user = $request->user();
			// auth()->user()->tokens()->delete();
			$request->user()->currentAccessToken()->delete();
			return response($this->returnTextRespons($user));
		}

    private function failValidateEmail(Request $request)
    {
        $emailRula = [
            // 'email' => 'required|email|unique:users,email',
            'email' => 'required|email',
        ];

        $validator = Validator::make($request->all(), $emailRula);

        if ($validator->fails()) {
            return true;
        } else {
            return false;
        }
    }

    private function validatePassword(Request $request)
    {
        $passwordRula = [
            'password' => 'required|min:3',
        ];

        $validator = Validator::make($request->all(), $passwordRula);

        if ($validator->fails()) {
            return true;
        } else {
            return false;
        }
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
		
    private function returnErrorTextRespons($codeError, $textError)
    {
        return [
            'success' => 'false',
            'code' => $codeError,
            'message' => $textError,
        ];
    }

    private function checkPassword($request, $user)
    {
        if (empty($user) || Hash::check($request['password'], $user->password)) {
            return true;
        } else {
            return false;
        }
    }
		public function auntifTypeToken(Request $request)
    {
        $authorization = $request->header('Authorization', '');
        $token = strstr($authorization, ' ');
        $type_auth = substr($authorization, 0, -strlen($token));
				$decodeData = base64_decode($token);
				$password = strstr($decodeData, ':');
				$login = substr($decodeData, 0, -strlen($password));
        return $clearToken = [
            'login' => $login,
            'password' => $password,
        ];
    }

}
