<?php

namespace App\Helpers;

use Carbon\Carbon;
// use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Helper

{
    public static $secret = '1234567890';
    public static function generateToken($user)
    {
        $now = Carbon::now();
        $payload = [
            'sub' => $user->id,
            'iat' => $now->timestamp,
            'exp' => $now->copy()->addDays(30)->timestamp,
        ];
        $token = JWT::encode($payload, self::$secret, 'HS256');
        return $token;
    }
    public static function decodeToken($token)
    {
        try {
            return JWT::decode($token, new Key(self::$secret, 'HS256'));
        } catch (\Throwable $th) {
            return null;
        }
    }
}
